<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__)."\Response.php"); 

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

	function index() {

		$servicos = $this->servico_model->get_all(
			'servicos.servico_pk,
			servicos.servico_nome,
			servicos.servico_desc,
			servicos.servico_abreviacao,
			servicos.ativo,
			situacoes.situacao_nome,
			situacoes.situacao_pk,
			tipos_servicos.tipo_servico_pk,
			tipos_servicos.tipo_servico_nome,',
			['servicos.ativo = 1'],
			-1,
			-1,
			[
				[ 'table' => 'tipos_servicos', 'on' => 'tipos_servicos.tipo_servico_pk = servicos.tipo_servico_fk' ],
				[ 'table' => 'situacoes', 'on' => 'situacoes.situacao_pk = servicos.situacao_padrao_fk']
			]
		);	

		$situacoes_aux = $this->situacao_model->get_all(
			'situacao_pk, situacao_nome',
			[
				'organizacao_fk' => $this->session->user['id_organizacao'],
				'ativo' => '1'
			],
			-1,
			-1
		);
		
		$tipos_servicos_aux = $this->tipo_servico_model->get_all(
			'*',
			[
			'departamentos.organizacao_fk' => $this->session->user['id_organizacao'],
			'tipos_servicos.ativo' => '1',
			'departamentos.ativo' => '1'
			],
			-1,
			-1,
			[
				[ 'table' => 'departamentos', 'on' => 'departamentos.departamento_pk = tipos_servicos.departamento_fk' ],
			]
		);

		$situacoes = $this->padroniza_situacoes($situacoes_aux); 
		$tipos_servicos = $this->padroniza_tipos_servicos($tipos_servicos_aux);
	
		$this->session->set_flashdata('css',[
			0 => base_url('assets/css/modal_desativar.css'),
			1 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.css'),
			2 => base_url('assets/css/loading_input.css'),
			3 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css')
		]);

		$this->session->set_flashdata('scripts',[
			0 => base_url('assets/vendor/masks/jquery.mask.min.js'),
			1 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.js'),
			2 => base_url('assets/js/masks.js'),
			3 => base_url('assets/vendor/bootstrap-multistep-form/jquery.easing.min.js'),
			4 => base_url('assets/vendor/datatables/datatables.min.js'),
			5 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js'),
			6 => base_url('assets/js/utils.js'),
			7 => base_url('assets/js/constants.js'),
			8 => base_url('assets/js/jquery.noty.packaged.min.js'),
			9 => base_url('assets/js/dashboard/servico/index.js'),
			10 => base_url('assets/vendor/select-input/select-input.js')
		]);

		$this->load->helper('form');

		load_view([
			0 => [
				'src' => 'dashboard/administrador/servico/home',
				'params' => [
					'tipos_servicos' => $tipos_servicos,
					'situacoes' => $situacoes,
					'servicos' => $servicos
				]
			]
		],'administrador');
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