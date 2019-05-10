<?php

require_once 'storage_helper.php';

/**
 * Utility function to format current data.
 *
 * @return string YYYY-mm-dd formated string
 */
function get_current_date()
{
    date_default_timezone_set('America/Sao_Paulo');

    return date('Y-m-d');
}

/**
 * Função que executa o upload das imagens.
 *
 * Params possui
 *     'id' (os ou func),
 *     'path' (PATH_OS ou PATH_FUNC),
 *     'is_os' (TRUE OR FALSE) e
 *     'situation' (nome da situacao)
 *
 * @param array $params
 * @param array $base64_images
 */
function upload_img($params, array $base64_images = null)
{
    //Crio um array que será setado com os caminhos das imagens que foram salvas (banco)
    $images_uploaded = [];
    
    if ($base64_images != 'null' && $base64_images != null) {
        //Se uma ou mais imagens foram enviadas, percorreremos:
        foreach ($base64_images as $image) {
            //Gerando o nome da imagem hasheado (segurança)
            $blob_name = get_current_date().'/'.(hash(ALGORITHM_HASH, $params['id'].uniqid(rand(), true)).'.jpg');
            
            //Recebemos uma imagem em base64, portanto e necessário remover o cabeçalho dela.
            list(, $image) = explode(';', $image);
            list(, $image) = explode(',', $image);
            //Decodificando o texto na base64
            $image_content = base64_decode($image);

            //Realizamos um tratamento específico caso as imagens sejam de uma ordem de serviço
            if ($params['is_os']) {
                $blob_name = 'ordens_servico/'.$blob_name;
            } else {
                $blob_name = 'perfil_images/'.$blob_name;
            }

            try {
                $blob_url = upload_to_storage($image_content, $blob_name);
                //Adicionando o caminho da última imagem armazenada
                array_push($images_uploaded, $blob_url);
            } catch (Exception $e) {
                throw new MyException('ERROR: Falha no upload da imagem: ['.$e->getMessage().']', Response::SERVER_FAIL);
            }
        }
    } else {
        $images_uploaded = null;
    }

    //Retorno o array de caminhos
    return $images_uploaded;
}
