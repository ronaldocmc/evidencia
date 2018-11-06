<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__)."/Response.php");	
/**
 * Acess Class
 *
 * @package     Evidencia
 * @category    Controller
 * @author      Pedro Cerdeirinha & Matheus Palmeira 
 */
class Cidadao extends CI_Controller {
	/**
	 * Construtor da Classe
	 * 
	 * Chama o construtor da classe pai
	 *
	 * @return void
	 */
	function __construct() 
	{
		parent::__construct();
		date_default_timezone_set('America/Sao_Paulo');
		$this->response = new Response();
        $this->load->model('Log_model', 'log_model');
	}
    //--------------------------------------------------------------------------------

    /**
	 * Método padrão da classe Access
	 * 
	 * @return void
	 */
    public function index()
    {
    	$this->load->view("cidadao/consulta");
    }

	//--------------------------------------------------------------------------------


    public function getOs(){
        $this->load->model('Ordem_Servico_model', 'ordem_servico_model');

        $json['os'] = $this->ordem_servico_model->getCidadao(
            array(
                'ordem_servico_cod' => $_GET['protocol']
            )
        );

        // var_dump($json['os']->ordem_servico_pk);die();
        
        if($json['os'] != false){
        // if($json['os'] != false || ($json['os']->situacao_atual == 1 || $json['os']->situacao_atual == 2)){
            $json['code'] = 200;

            $json['historico'] = $this->ordem_servico_model->getHistorico(
                array(
                    'ordem_servico_fk' => $json['os']->ordem_servico_pk
                )
            );
            

        }else{
            $json['historico'] = [];
            $json['code'] = 404;
        }

		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($json,JSON_UNESCAPED_UNICODE);
    }
}
