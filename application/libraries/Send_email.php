<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require 'vendor/autoload.php'; // If you're using Composer (recommended)
// Comment out the above line if not using Composer
// require("<PATH TO>/sendgrid-php.php");
// If not using Composer, uncomment the above line and
// download sendgrid-php.zip from the latest release here,
// replacing <PATH TO> with the path to the sendgrid-php.php file,
// which is included in the download:
// https://github.com/sendgrid/sendgrid-php/releases

class Send_email
{
	protected $ci;

	public function __construct()
	{
        $this->ci =& get_instance();
        $this->ci->load->library('email');
	}

	public function send_email($view, $subject, $message, $to)
	{

		if (ENABLE_EMAIL)
		{
			log_message('monitoring', 'Sending mail to ['.$to.']');
			$data['url'] = $message;
			$body = $this->ci->load->view($view, $data, TRUE);

			$email_data['from'] = "noreply@prudenco.com.br";
			$email_data['name'] = "Evidência";
			$email_data['to'] = $to;
			$email_data['subject'] = $subject;
			//$email_data['TID'] = 'd-15b009ed3996470c985834d9d0933353'; //template_id

			$email = new \SendGrid\Mail\Mail(); 
			$email->setFrom($email_data['from'], $email_data['name']);
			$email->setSubject($email_data['subject']);
			$email->addTo($email_data['to'], $email_data['to']);
			$email->addContent(
			    "text/html", $body
			);
			//$email->setTemplateId($email_data['TID']);


			$sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
			// var_dump(getenv('SENDGRID_API_KEY')); die();
			try {
			    $response = $sendgrid->send($email);
			    // var_dump($response->statusCode());
			    // var_dump($response->headers());
				// var_dump($response->body());
				// die();
			    return $response->statusCode();
			} catch (Exception $e) {
			    echo 'Caught exception: '. $e->getMessage() ."\n";
			}

		}
		else
		{
			return TRUE;
		}
	}
	

}

/* End of file MY_Send_email.php */
/* Location: ./application/libraries/MY_Send_email.php */
