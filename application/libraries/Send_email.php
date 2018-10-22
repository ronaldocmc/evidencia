<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Send_email
{
	protected $ci;

	public function __construct()
	{
        $this->ci =& get_instance();
        $this->ci->load->library('email');
	}

	public function send_email($view, $subject, $message, $email)
	{
		if (ENABLE_EMAIL)
		{
			$data['url'] = $message;
			$body = $this->ci->load->view($view, $data, TRUE);

			$result = $this->ci->email
			    ->from('no.reply.teste.evidencia@gmail.com')
			    ->to($email)
			    ->subject($subject)
			    ->message($body)
			    ->send();
			return $result;
		}
		else
		{
			return TRUE;
		}
	}
	

}

/* End of file MY_Send_email.php */
/* Location: ./application/libraries/MY_Send_email.php */
