<?php

/**
 * FeedBackWS
 *
 * @package     application
 * @subpackage  controllers
 * @author      Gustavo
 */

defined('BASEPATH') or exit('No direct script access allowed');

require_once dirname(__FILE__) . "/Response.php";
require_once APPPATH . "core/MY_Controller.php";

class FeedBackWS extends MY_Controller
{

    private $response;

    public function __construct()
    {
      $this->response = new Response();
      parent::__construct($this->response);

		  date_default_timezone_set('America/Sao_Paulo');
      exit();
    }

   public function novo_feedback()
   {
   		$this->load->model('Mensagens_model', 'mensagens_model');
   		$this->load->helper('token_helper');

   		$this->response = new Response();

   		$obj = $obj = json_decode(file_get_contents('php://input'));
		  $headers = apache_request_headers();

  		$retorno = $this->mensagens_model->insert([
  			'funcionario_fk' => get('id_funcionario', $headers['Token']),
  			'mensagem_texto' => $obj->mensagem_texto
  		]);

  		if ($retorno === false) 
  		{
  			$this->response->set_code(Response::DB_ERROR_INSERT);
  			$this->response->set_message('Não foi possível inserir a mensagem');
  		}

		  $this->response->send();
   }
}
