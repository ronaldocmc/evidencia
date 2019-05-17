<?php

function verify_token($token, $response)
{
    log_message('MONITORING', 'tentando verificar o token:['.$token.'] Resp: '.$response);
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

function get($atribute, $token)
{
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

function token_encrypt($string)
{
    $output = false;

    $encrypt_method = 'AES-256-CBC';
    $secret_key = '2925612f39ae98f805b86ce5400b70df';
    $secret_iv = 'e3317304c70e3f69a9ef8895c69de64b';

    // hash
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
    $output = base64_encode($output);

    return $output;
}

function token_decrypt($string)
{
    $output = false;

    $encrypt_method = 'AES-256-CBC';
    $secret_key = '2925612f39ae98f805b86ce5400b70df';
    $secret_iv = 'e3317304c70e3f69a9ef8895c69de64b';

    // hash
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);

    return $output;
}
