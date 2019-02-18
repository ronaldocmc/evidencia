<?php

//Definindo as constantes PATH que será utilizada na inserção. 
define('PATH_OS','./assets/uploads/imagens_situacoes/');
define('PATH_FUNC','./assets/uploads/perfil_images/');

//Função que executa o upload das imagens. 
//Params possui 'id' (os ou func), 'path' (PATH_OS ou PATH_FUNC),'is_os' (TRUE OR FALSE) e 'situation' (nome da situacao)
function upload_img($params , $base64_images){	
    
    //Crio um array que será setado com os caminhos das imagens que foram salvas (banco)
    $array[] = $images_was_uploaded; 

    //Se uma ou mais imagens foram enviadas, percorreremos: 
    foreach ($base64_images as $image){
    
        //Gerando o nome da imagem hasheado (segurança)
        $image_name = hash(ALGORITHM_HASH, $params['id'] . uniqid(rand(), true)).".jpg";
                
        //Recebemos uma imagem em base 64, portanto e necessário remover o cabeçalho dela. 
        list($type, $image) = explode(';', $image);
        list(, $image)     = explode(',', $image);

        //Decodificando o texto na base 64
        $image_file = base64_decode($image);
        
        $final_path = constant($params['path']).$image_name;

        //Realizamos um tratamento específico caso as imagens sejam de uma ordem de serviço 
        if($params['is_os']){
            $final_path = tratament_for_os($params).$image_name;
        }

        //Criando e gravando o arquivo da imagem
        if(!file_put_contents($final_path, $image_file)){
            throw new Exception([
                'Erro ao fazer upload da imagem:', 
                Response::SERVER_FAIL
                ]);
        }else{

           //Adicionando o caminho da última imagem armazenada
            array_push($images_was_uploaded, $final_path);
        }
    }

    //Retorno o array de caminhos
    return $images_was_uploaded;
}

 function tratament_for_os($params){

    //A pasta para a OS será criada ou já existirá 
    $os_path = mkdir(constant($params['path']).hash(ALGORITHM_HASH, $params['id'])); 

    //Verificando se realmente foi criada ou já existia $os_path == TRUE
    if($os_path){

        //Caso sim, então criamos a pasta situacao
        $situation_path = mkdir(constant($params['path']).hash(ALGORITHM_HASH, $params['id']).$params['situation']);

        //Se a pasta foi criada normalmente
        if($situation_path){
            $path_for_os = constant($params['path']).hash(ALGORITHM_HASH, $params['id']).$params['situation'];
            return $path_for_os; 
        }
    } 
    
}

//Função que cria uma pasta caso ela não exista. 
function makeDir($path)
{
     return is_dir($path) || mkdir($path);
}

?>