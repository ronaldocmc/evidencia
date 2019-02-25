<?php

//Definindo as constantes PATH que será utilizada na inserção.
define('PATH_OS', 'assets/uploads/imagens_situacoes/');
define('PATH_FUNC', 'assets/uploads/perfil_images/');

//Função que executa o upload das imagens.
//Params possui 'id' (os ou func), 'path' (PATH_OS ou PATH_FUNC),'is_os' (TRUE OR FALSE) e 'situation' (nome da situacao)
function upload_img($params, array $base64_images)
{

    date_default_timezone_set('America/Sao_Paulo');
    $date = date('m_Y');

    //Crio um array que será setado com os caminhos das imagens que foram salvas (banco)
    $images_uploaded = [];



    if ($base64_images[0] != null) {

        //Se uma ou mais imagens foram enviadas, percorreremos:
        foreach ($base64_images as $image) {

            //Gerando o nome da imagem hasheado (segurança)
            $image_name = hash(ALGORITHM_HASH, $params['id'] . uniqid(rand(), true)) . ".jpg";

            //Recebemos uma imagem em base 64, portanto e necessário remover o cabeçalho dela.
            list($type, $image) = explode(';', $image);
            list(, $image) = explode(',', $image);

            //Decodificando o texto na base 64
            $image_file = base64_decode($image);

            //Realizamos um tratamento específico caso as imagens sejam de uma ordem de serviço
            if ($params['is_os']) {
                $final_path = tratament_for_os($params, $date) . '/' . $image_name;
                // var_dump($final_path);
            } else {
                $final_path = constant($params['path']) . $image_name;
            }

            //Criando e gravando o arquivo da imagem
            if (!file_put_contents($final_path, $image_file)) {
                // roll_back($images_uploaded);
                throw new MyException([
                    'Erro ao fazer upload da imagem:',
                    Response::SERVER_FAIL,
                ]);
            } else {
                //Adicionando o caminho da última imagem armazenada
                array_push($images_uploaded, $final_path);
            }
        }
    }

    //Retorno o array de caminhos
    return $images_uploaded;
}

function tratament_for_os($params, $date)
{

    //A pasta para a OS será criada ou já existirá
    $os_path = PATH_OS . $date;

    //Verificando se realmente foi criada ou já existia $os_path == TRUE
    if (!is_dir($os_path)) {
        //Caso não, então criamos a pasta situacao
        if (!mkdir($os_path, 0755, true)) {
            throw new MyException("Erro ao criar diretório para imagem(ns){$os_path}", 500);
        }

    }

    return $os_path;
}

//Função que cria uma pasta caso ela não exista.
function makeDir($path)
{
    return is_dir($path) || mkdir($path);
}

function roll_back(array $image_paths)
{
    foreach ($image_paths as $path) {
        unlink($path);
    }
}
