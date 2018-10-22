<?php 
	/**
	* Coeficiente de espera em minutos.
	* Exemplo: Usuário tentou 3 vezes sem sucesso, ele deverá esperar 2 minutos
	* para a próxima tentativa, pois: (3-2) * 2 = 2
	*/	
	define('WAIT_TIME',2);
	/**
	* Quantidade máxima de tentativas anteriores aceitas sem nenhuma restrição
	*/ 
	define('ACCEPTED_ATTEMPTS',2);

	/**
	* Quantidade máxima de tentativas de recuperação para emails diferentes
	* anteriores aceitas sem nenhuma restrição
	*/ 
	define('ACCEPTED_IP_RECOVER_ATTEMPTS',30);

	 /**
	 * Faz a verificação de quantidade de tentativas de acesso do usuário
	 *
	 * @param       string  $ip_address
	 * @return      mixed TRUE se usuário obedece as restrições de tentativas e array caso contrário
	 */
	function verify_attempt($ip_address)
	{
		$CI =& get_instance();
		$CI->load->model('tentativa_model');
		$attempts = $CI->tentativa_model->get($ip_address);

		//Caso tenha mais tentativas que a esperada
		if ($attempts && count($attempts)>ACCEPTED_ATTEMPTS)
		{
			//calcula-se o tempo de espera
			$wait_time = (count($attempts)-ACCEPTED_ATTEMPTS) * WAIT_TIME;
			//obtem-se o instante da ultima tentativa.	
			$last_attempt_time = end($attempts)->tentativa_tempo;
			$next_attempt_time = strtotime("+{$wait_time} minutes", strtotime($last_attempt_time));	
			if (strtotime(date('Y/m/d H:i:s'))<$next_attempt_time)
			{
				$err[0] = 'Aguardar '.date('i:s', $next_attempt_time - strtotime(date('Y/m/d H:i:s'))).' minutos';
				return $err;
			}
			else
			{
				return TRUE;
			}
		}
		else
		{
			return TRUE;
		}
	}

/**
	 * Faz a verificação de quantidade de tentativas de acesso do usuário para reccuperação de senha
	 *
	 * @param       string  $ip_address
	 * @return      mixed TRUE se usuário obedece as restrições de tentativas e array caso contrário
	 */
	function verify_attempt_restore($ip_address, $email)
	{
		$CI =& get_instance();
		$CI->load->model('tentativa_recuperacao_model','tentativa_model');
		$attempts = $CI->tentativa_model->get($ip_address);

		//Caso tenha mais tentativas que a esperada
		if ($attempts && count($attempts)>ACCEPTED_IP_RECOVER_ATTEMPTS)
		{
			//obtem-se o instante da ultima tentativa.	
			$last_attempt_time = end($attempts)->tentativa_tempo;
			$next_attempt_time = strtotime("+1 day", strtotime($last_attempt_time));	
			if (strtotime(date('Y/m/d H:i:s'))<$next_attempt_time)
			{
				$err[0] = 'Novas recuperações de senha bloqueada hoje';
				return $err;
			}
			else
			{
				return TRUE;
			}
		}
		else
		{
			$attempts = $CI->tentativa_model->get(['tentativa_email' => $email]);
			if ($attempts && count($attempts)>ACCEPTED_ATTEMPTS)
			{
				$last_attempt_time = end($attempts)->tentativa_tempo;
				$next_attempt_time = strtotime("+1 day", strtotime($last_attempt_time));	
				if (strtotime(date('Y/m/d H:i:s'))<$next_attempt_time)
				{
					$err[0] = 'Novas recuperações de senha bloqueada hoje';
					return $err;
				}
				else
				{
					return TRUE;
				}	
			}
			else
			{
				return TRUE;
			}
		}
	}



 ?>