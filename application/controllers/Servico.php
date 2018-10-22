<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__)."\Response.php"); 

require_once APPPATH."core\CRUD_Controller.php";

class Servico extends CRUD_Controller {

	function __construct() 
	{
		date_default_timezone_set('America/Sao_Paulo');
		parent::__construct();
		$this->load->model('servico_model');
	}

	function index() 
	{
		//Para testes
		// $response = new Response();
		// ---

		$this->load->model('situacao_model');
		$this->load->model('tipo_servico_model');

		$servicos = $this->servico_model->get([
			'situacoes.organizacao_fk' => $this->session->user['id_organizacao']
		]);

		$situacoes_aux = $this->situacao_model->get([
			'situacoes.organizacao_fk' => $this->session->user['id_organizacao'],
			'situacoes.situacao_ativo' => '1'
		]);

		$tipos_servicos_aux = $this->tipo_servico_model->get([
			'departamentos.organizacao_fk' => $this->session->user['id_organizacao'],
			'departamentos.ativo' => '1', 
			'tipos_servicos.tipo_servico_status' => '1'
		]);


		$tipos_servicos = null;

		if ($situacoes_aux !== false) 
		{
			foreach ($situacoes_aux as $s) 
			{
				$situacoes[$s->situacao_pk] = $s->situacao_nome;
			}
		}

		if ($tipos_servicos_aux !== false) 
		{
			foreach ($tipos_servicos_aux as $t) 
			{
				$tipos_servicos[$t->tipo_servico_pk] = $t->tipo_servico_nome;
			}
		}
		

		// Para testes
		// if(!$depts_aux)
		// {
		// 	$response->set_code(Response::NOT_FOUND);
		// }
		// else
		// {
		// 	$response->set_code(Response::SUCCESS);
		// 	$response->set_data($depts_aux);
		// }
		// $response->send();
		// ----

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

	/**
     * Função responsável por validar os dados vindos da requisição de insert_update
     *
     * @param Requisição POST com servico_nome, servico_desc, situacao_padrao_pk e
     *		  tipo_servico_fk, e, se setada, a servico_pk
     * @return Objeto Response caso falhe, ou então, TRUE, caso esteja correto
     */
	private function form_validation_insert_update()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules(
			'servico_nome', 
			'servico_nome', 
			'trim|required|max_length[30]'
		);

		$this->form_validation->set_rules(
			'servico_abreviacao', 
			'servico_abreviacao', 
			'trim|required|max_length[10]'
		);

		$this->form_validation->set_rules(
			'servico_desc', 
			'servico_desc', 
			'trim|required|max_length[200]'
		);

		$this->form_validation->set_rules(
			'situacao_padrao_pk', 
			'situacao_padrao_pk', 
			'trim|numeric|required'
		);

		$this->form_validation->set_rules(
			'tipo_servico_pk', 
			'tipo_servico_pk', 
			'trim|required|numeric'
		);

		if($this->input->post('servico_pk') != '')
		{
			$this->form_validation->set_rules(
				'servico_pk', 
				'servico_pk', 
				'trim|required|numeric'
			);
		}

		if($this->form_validation->run())
		{
			return true;
		}
		else
		{
			$response = new Response();
			$response->set_code(Response::BAD_REQUEST);
			$response->set_data($this->form_validation->errors_array());
			return $response;
		}
	}

	/**
     * Função responsável por criar ou editar uma situação
     *
     * @param Requisição POST com servico_nome, servico_desc, situacao_padrao_pk e
     *		  tipo_servico_fk, e, se setada, a servico_pk
     * @return Objeto Response
     */
	public function insert_update()
	{
		// Validação dos dados da requisição
		$result_form_validation = $this->form_validation_insert_update();

		if($result_form_validation === true)
		{
			$response = new Response();

			// Leitura dos dados do serviço
			$servico['servico_nome'] = $this->input->post('servico_nome');
			$servico['servico_abreviacao'] = $this->input->post('servico_abreviacao');
			$servico['servico_desc'] = $this->input->post('servico_desc');

			// Verificando se a situacao passada existe no banco
			$this->load->model('situacao_model');

			$situacao = $this->situacao_model->get($this->input->post('situacao_padrao_pk'));

			if(!$situacao)
			{
				$response->set_code(Response::NOT_FOUND);
				$response->set_data([
					'erro' => 'Situacao passada não encontrada'
				]);
				$response->send();
				return;
			}
			else
			{
				$servico['situacao_padrao_fk'] = $situacao[0]->situacao_pk;
			}

			// Verificando se o tipo de serviço passado existe no banco
			$this->load->model('tipo_servico_model');

			$tipo_servico = $this->tipo_servico_model->get($this->input->post('tipo_servico_pk'));

			if(!$tipo_servico)
			{
				$response->set_code(Response::NOT_FOUND);
				$response->set_data([
					'erro' => 'Tipo de Serviço passado não encontrado'
				]);
				$response->send();
				return;
			}
			else
			{
				$servico['tipo_servico_fk'] = $tipo_servico[0]->tipo_servico_pk;
			}

			// Update
			if($this->input->post('servico_pk') != '')
			{
				$servico['servico_pk'] = $this->input->post('servico_pk');

				// Se houver a servico_pk, trata-se de um update
				$resultado = $this->servico_model->update($servico, 
					$this->input->post('servico_pk'));

				if(!$resultado)
				{
    				// Caso o update falhe
					$response->set_code(Response::DB_ERROR_UPDATE);
					$response->set_data([
						'erro' => 'Erro no update do tipo de serviço:' . $resultado
					]);
				}
				else
				{
    				// Caso o update obteve sucesso
					$response->set_code(Response::SUCCESS);
				}
			}
			// Insert
			else
			{
				$resultado = $this->servico_model->insert($servico);

				if(!$resultado)
				{
					$response->set_code(Response::DB_ERROR_INSERT);
					$response->set_data([
						'erro' => 'Erro na inserção do serviço'
					]);
				}
				else
				{
					$response->set_code(Response::SUCCESS);
					$response->set_data([
						'servico_pk' => $resultado
					]);
				}
			}

			$response->send();
		}
		else //se o form_validation acusar erro: 
    	{   
      		//enviamos os erros do form validation 
			$result_form_validation->send();
		}
	}

	/**
     * Método responsável por desativar um tipo de serviço
     *
     * @param pk do tipo de servico
     * @return objeto Response contendo sucesso ou erros
     */
	public function deactivate()
	{
		$this->load->library('form_validation');

		$response = new Response();

		$this->form_validation->set_rules(
			'servico_pk',
			'servico_pk',
			'trim|required|numeric'
		);

		if ($this->form_validation->run()) 
		{
			$servico = $this->servico_model->get($this->input->post('servico_pk'));

			if($servico !== false)
			{
				$resultado = $this->servico_model->update([
					'servico_status' => '0'
				], $this->input->post('servico_pk'));

				if($resultado === 0)
				{
					$response = new Response();
					$response->set_code(Response::DB_ERROR_UPDATE);
					$response->set_data(['erro' => 'Erro na desativação do serviço']);
				}
				else
				{
					$response->set_code(Response::SUCCESS);
				}
			}
			else
			{
				$response = new Response();
				$response->set_code(Response::NOT_FOUND);
				$response->set_data(['erro' => 'Serviço não encontrado']);
			}
		}
		else
		{
			$response = new Response();
			$response->set_code(Response::BAD_REQUEST);
			$response->set_data($this->form_validation->errors_array());
		}

		$response->send();
	} 


	/**
     * Método responsável por desativar um tipo de serviço
     *
     * @param pk do tipo de servico
     * @return objeto Response contendo sucesso ou erros
     */
	public function activate()
	{
		$this->load->library('form_validation');

		$response = new Response();

		$this->form_validation->set_rules(
			'servico_pk',
			'servico_pk',
			'trim|required|numeric'
		);

		if ($this->form_validation->run()) 
		{
			$servico = $this->servico_model->get($this->input->post('servico_pk'));

			if($servico !== false)
			{
				$resultado = $this->servico_model->update([
					'servico_status' => '1'
				], $this->input->post('servico_pk'));

				if($resultado === 0)
				{
					$response = new Response();
					$response->set_code(Response::DB_ERROR_UPDATE);
					$response->set_data(['erro' => 'Erro na ativação do serviço']);
				}
				else
				{
					$response->set_code(Response::SUCCESS);
				}
			}
			else
			{
				$response = new Response();
				$response->set_code(Response::NOT_FOUND);
				$response->set_data(['erro' => 'Serviço não encontrado']);
			}
		}
		else
		{
			$response = new Response();
			$response->set_code(Response::BAD_REQUEST);
			$response->set_data($this->form_validation->errors_array());
		}

		$response->send();
	} 


}


?>