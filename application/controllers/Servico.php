<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "core\Response.php";

require_once APPPATH."core\CRUD_Controller.php";

require_once APPPATH."core\MyException.php";

class Servico extends CRUD_Controller {
	
	private $response; 

	function __construct() 
	{	
		parent::__construct();

		date_default_timezone_set('America/Sao_Paulo');

		$this->load->model('situacao_model');
		$this->load->model('tipo_servico_model');
		$this->load->model('servico_model');
		$this->load->helper('exception');
		$this->load->library('form_validation');
		$this->response = new Response();
		
	}

	private function padroniza_situacoes($situacoes_aux){
		
		$situacoes = null;

		if ($situacoes_aux !== false) 
		{
			foreach ($situacoes_aux as $s) 
			{
				$situacoes[$s->situacao_pk] = $s->situacao_nome;

				// foreach($servicos as $servico){
					
				// 	if($servico->situacao_padrao_fk == $s->situacao_pk){
				// 		$servico->situacao_nome = $s->situacao_nome;
				// 	}	
				// }
				 
			}
		}
		return $situacoes;
	}

	private function padroniza_tipos_servicos($tipos_servicos_aux){
		
		$tipos_servicos = null; 

		if ($tipos_servicos_aux !== false) 
		{
			foreach ($tipos_servicos_aux as $t) 
			{
				$tipos_servicos[$t->tipo_servico_pk] = $t->tipo_servico_nome;

				// foreach($servicos as $servico){
				// 	if($servico->tipo_servico_fk == $t->tipo_servico_pk){
				// 		$servico->tipo_servico_nome = $t->tipo_servico_nome;
				// 	}	
				// }
			}
		}

		return $tipos_servicos;
	}

	private function update(){
		$this->servico_model->__set('servico_pk', $this->input->post('servico_pk'));
		$this->servico_model->update();
		$this->response->set_code(Response::SUCCESS);

	}

	private function insert(){

		$servico_pk = $this->servico_model->insert(); 
			
		$this->response->set_code(Response::SUCCESS);
		$this->response->set_data([
			'servico_pk' => $servico_pk
		]);
	}

	private function save(){
		if($this->input->post('servico_pk') != ''){
			$this->update();
		}else{
			$this->insert(); 
		}
	}

	public function insert_update()
	{
	
		try{
			$this->servico_model->config_form_validation(); 
			$this->servico_model->run_form_validation();

			$this->servico_model->fill();

			$this->begin_transaction(); 
			$this->save();
			$this->end_transaction();
			
			$this->response->send(); 
		
		} catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
		}
	}

	public function deactivate()
	{
		$this->form_validation->set_rules(
			'servico_pk',
			'servico_pk',
			'trim|required|numeric'
		);

		try{

			$this->servico_model->run_form_validation();

			$this->servico_model->__set('servico_pk', $this->input->post('servico_pk'));

			$this->begin_transaction();
			$this->servico_model->deactivate(); 
			$this->end_transaction();
			
			$this->response->set_code(Response::SUCCESS);
			$this->response->send();

		} catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
		}	
	} 

	public function activate()
	{
		$this->form_validation->set_rules(
			'servico_pk',
			'servico_pk',
			'trim|required|numeric'
		);

		try{
			$this->servico_model->run_form_validation();
			$this->servico_model->__set('servico_pk', $this->input->post('servico_pk'));

			$this->begin_transaction();
			$this->servico_model->activate();
			$this->end_transaction(); 

			$this->response->set_code(Response::SUCCESS);
			$this->response->send(); 

		} catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
		}	
	} 
}

?>