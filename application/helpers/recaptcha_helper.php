<?php 

	define('SCORE',0.6);

    /**
     * Faz a verificação de humanidade do usuário
     *
     * @param       string  $secret_key
     * @return      mixed TRUE se usuário certamente é humado e array caso contrário
     */
    function get_captcha($secret_key)
    {
        $answer=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".RECAPTCHA_SECRET_KEY."&response={$secret_key}");
        $return=json_decode($answer);

        if ($return->success === TRUE && $return->score > SCORE)
        {
        	return TRUE;
        }
        else
        {
        	$err[0] = 'Bot Detectado';
			//return $err;
            return TRUE;
    	}
    }



 ?>