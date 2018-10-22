<?php

function verify_token($token, $response)
{
    $token_decodificado = json_decode(token_decrypt($token));

    if (is_object($token_decodificado)) {
        if (!verify_token_timestamp($token_decodificado->timestamp)) {
            $response->set_code(Response::TOKEN_TIMEOUT);
            return false;
        } else {
            $data['id_pessoa'] = $token_decodificado->id_pessoa;
            $data['id_empresa'] = $token_decodificado->id_empresa;
            $data['last_update'] = $token_decodificado->last_update;
            $data['id_funcionario'] = $token_decodificado->id_funcionario;
            // $data['setor'] = $token_decodificado->setor;
            return generate_token($data);
        }
    } else {
        $response->set_code(Response::FORBIDDEN);
        return false;
    }
}

function generate_token($data)
{
    $data['timestamp'] = generate_token_timestamp('+5 days');
    $token = token_encrypt(json_encode($data));

    return $token;
}

function get($atribute, $token){
    $token_decodificado = json_decode(token_decrypt($token));
	return $token_decodificado->$atribute;
}

function generate_token_timestamp($duracao = '+5 days')
{
    $today = date('Y-m-d H:i:s');
    return date('Y-m-d H:i:s', strtotime($duracao, strtotime($today)));
}

function verify_token_timestamp($timestamp)
{
    $today = date('Y-m-d H:i:s');

    if (strtotime($timestamp) > strtotime($today)) {
        return true;
    }

    return false;
}

// function token_encrypt($Buffer)
// {
//     $BlockSize = mcrypt_get_block_size(
//         MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
//     $PadSize = $BlockSize - (strlen($Buffer) % $BlockSize);
//     $Buffer .= str_repeat(chr($PadSize), $PadSize);
//     $Buffer = mcrypt_encrypt(MCRYPT_RIJNDAEL_128,
//         KEY, $Buffer, MCRYPT_MODE_ECB);
//     return base64_encode($Buffer);
// }

// function token_decrypt($Buffer)
// {
//     $Buffer = base64_decode($Buffer);
//     $Buffer = mcrypt_decrypt(MCRYPT_RIJNDAEL_128,
//         KEY, $Buffer, MCRYPT_MODE_ECB);
//     $Length = strlen($Buffer);
//     $PadSize = ord($Buffer[$Length - 1]);
//     $Buffer = substr($Buffer, 0, strlen($Buffer) - $PadSize);
//     return $Buffer;
// }

function token_encrypt($string){
    $output = false;
 
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'This is my secret key';
    $secret_iv = 'This is my secret iv';
 
    // hash
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
    $output = base64_encode($output);

    return $output;
}

function token_decrypt($string){
    $output = false;
 
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'This is my secret key';
    $secret_iv = 'This is my secret iv';
 
    // hash
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    
    $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    
    return $output;
}

