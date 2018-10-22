<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__) . "\..\Prioridade.php";

class Prioridade_test extends CI_Controller {
	private $dep;
	private $CI;

	function __construct() {
		$this->dep = new Prioridade();
		$this->CI =& get_instance();

		$this->CI->load->library('unit_test');
		$this->CI->load->model('prioridade_model');

		$this->class_methods = get_class_methods($this);
		$this->class_name = get_class($this);
		unset($this->class_methods[0]);
		unset($this->class_methods[count($this->class_methods)]);
	}

	public function index(){

		header("Content-Type: text/html; charset=UTF-8", true);
		foreach ($this->class_methods as $method_name) 
		{
			echo "<a href='".base_url('test/'.$this->class_name.'/'.$method_name)."'>".$method_name."</a><br>";
		}
	}

	/*
	* Para testar esse método, deve-se criar um objeto Response na classe testada, para retornar 
	* código da função.
	*/
	public function create_standart() {
		$method = 'create_standart';
		$test_case = [
			[
				'test_name' => 'criação correta',
				'expected' => 200,
				'organizacao_pk' => 'teste'
			],
			[
				'test_name' => 'criação com organização inexistente',
				'expected' => 404,
				'organizacao_pk' => 'cascas'
			]		
		];


		foreach($test_case as $c):

			ob_start();
			$this->dep->$method($c['organizacao_pk']);
			$output = ob_get_contents();
			$var = json_decode($output);
			ob_end_clean();

			if($var->code == 200)
			{
				$this->CI->prioridade_model->delete([
					'organizacao_fk' => $c['organizacao_pk']
				]);
			}

			$this->CI->unit->run($var->code,$c['expected'], $c['test_name'], $output);

		endforeach;

		header("Content-Type: text/html; charset=UTF-8",true);
		echo "<a href=".base_url('test/'.$this->class_name.'/index').">Voltar</a>";
		echo $this->CI->unit->report();
	}


	public function insert() 
	{
		$this->CI->load->library('form_validation');
		$this->CI->load->model('historico_prazo_model');

		$method = 'insert_update';
		$test_case = [
			[
				'test_name' => 'criação correta com FUNC',
				'expected' => 200,
				'prioridade_nome' => 'prioridade_teste',
				'prioridade_duracao' => '50',
				'prioridade_pk' => '',
				'senha' => '',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			],
			[
				'test_name' => 'campos incorretos com FUNC',
				'expected' => 400,
				'prioridade_nome' => '',
				'prioridade_duracao' => 'cascas',
				'prioridade_pk' => '',
				'senha' => '',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			],
			[
				'test_name' => 'criação correta com SU',
				'expected' => 200,
				'prioridade_nome' => 'prioridade_teste',
				'prioridade_duracao' => '50',
				'prioridade_pk' => '',
				'senha' => '12345678',
				'session' => [
					'is_superusuario' => true,
					'password_user' => hash(ALGORITHM_HASH, '12345678'.SALT),
					'id_organizacao' => 'teste'
				]
			],
			[
				'test_name' => 'criação incorreta com SU',
				'expected' => 400,
				'prioridade_nome' => '',
				'prioridade_duracao' => '50vda',
				'prioridade_pk' => '',
				'senha' => '123478',
				'session' => [
					'is_superusuario' => true,
					'password_user' => hash(ALGORITHM_HASH, '12345678'.SALT),
					'id_organizacao' => 'teste'
				]
			],
			[
				'test_name' => 'criação correta / senha incorreta SU',
				'expected' => 401,
				'prioridade_nome' => 'prioridade_teste',
				'prioridade_duracao' => '50',
				'prioridade_pk' => '',
				'senha' => '12cas345csac78',
				'session' => [
					'is_superusuario' => true,
					'password_user' => hash(ALGORITHM_HASH, '12345678'.SALT),
					'id_organizacao' => 'teste'
				]
			]
		];


		foreach($test_case as $c):

			$this->CI->session->set_userdata('user',$c['session']);

			$_POST['prioridade_nome'] = $c['prioridade_nome'];
			$_POST['prioridade_duracao'] = $c['prioridade_duracao'];
			$_POST['prioridade_pk'] = $c['prioridade_pk'];
			$_POST['senha'] = $c['senha'];
			$this->CI->form_validation->set_data($_POST);

			ob_start();
			$this->dep->$method();
			$output = ob_get_contents();
			$var = json_decode($output);
			ob_end_clean();

			if($var->code == 200)
			{
				$this->CI->prioridade_model->delete([
					'organizacao_fk' => $c['session']['id_organizacao']
				]);

				$this->CI->historico_prazo_model->delete([
					'prioridade_fk' => $var->data->prioridade_pk
				]);
			}

			$this->CI->unit->run($var->code,$c['expected'], $c['test_name'], $output);

			$this->CI->form_validation->reset_validation();

		endforeach;

		header("Content-Type: text/html; charset=UTF-8",true);
		echo "<a href=".base_url('test/'.$this->class_name.'/index').">Voltar</a>";
		echo $this->CI->unit->report();
	}

	public function update() 
	{
		$this->CI->load->library('form_validation');
		$this->CI->load->model('historico_prazo_model');

		$method = 'insert_update';
		$test_case = [
			[
				'test_name' => 'edição do nome da prioridade com FUNC',
				'expected' => 200,
				'prioridade_nome' => 'prioridade_teste',
				'prioridade_duracao' => '12',
				'prioridade_pk' => '151',
				'senha' => '',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			],
			[
				'test_name' => 'edição do prazo da prioridade com FUNC',
				'expected' => 200,
				'prioridade_nome' => 'para_testes',
				'prioridade_duracao' => '14',
				'prioridade_pk' => '151',
				'senha' => '',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			],
			[
				'test_name' => 'edição do nome e do prazo da prioridade com FUNC',
				'expected' => 200,
				'prioridade_nome' => 'prioridade_teste_',
				'prioridade_duracao' => '14',
				'prioridade_pk' => '151',
				'senha' => '',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			]
		];


		foreach($test_case as $c):

			$this->CI->session->set_userdata('user',$c['session']);

			$_POST['prioridade_nome'] = $c['prioridade_nome'];
			$_POST['prioridade_duracao'] = $c['prioridade_duracao'];
			$_POST['prioridade_pk'] = $c['prioridade_pk'];
			$_POST['senha'] = $c['senha'];
			$this->CI->form_validation->set_data($_POST);

			ob_start();
			$this->dep->$method($c);
			$output = ob_get_contents();
			$var = json_decode($output);
			ob_end_clean();

			if($var->code == 200)
			{
				$this->CI->prioridade_model->update([
					'prioridade_pk' => $c['prioridade_pk'],
					'prioridade_nome' => 'para_testes'
				]);

				$this->CI->historico_prazo_model->update([
					'prioridade_fk' => $c['prioridade_pk'],
					'prazo_duracao' => '12 hours'
				]);
			}

			$this->CI->unit->run($var->code,$c['expected'], $c['test_name'], $output);

			$this->CI->form_validation->reset_validation();

		endforeach;

		header("Content-Type: text/html; charset=UTF-8",true);
		echo "<a href=".base_url('test/'.$this->class_name.'/index').">Voltar</a>";
		echo $this->CI->unit->report();
	}

	public function deactivate() 
	{
		$method = 'deactivate';
		$test_case = [
			// [
			// 	'test_name' => 'desativação da prioridade com FUNC',
			// 	'expected' => 200,
			// 	'prioridade_pk' => '151',
			// 	'senha' => '',
			// 	'session' => [
			// 		'is_superusuario' => false,
			// 		'id_organizacao' => 'teste'
			// 	]
			// ],
			// [
			// 	'test_name' => 'prioridade inexistente com FUNC',
			// 	'expected' => 404,
			// 	'prioridade_pk' => '155',
			// 	'senha' => '',
			// 	'session' => [
			// 		'is_superusuario' => false,
			// 		'id_organizacao' => 'teste'
			// 	]
			// ],
			// o banco para testar
			[
				'test_name' => 'prioridade já desativada com FUNC',
				'expected' => 400,
				'prioridade_pk' => '151',
				'senha' => '',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			],
		];


		foreach($test_case as $c):

			$this->CI->session->set_userdata('user',$c['session']);

			$_POST['prioridade_pk'] = $c['prioridade_pk'];
			$_POST['senha'] = $c['senha'];

			ob_start();
			$this->dep->$method();
			$output = ob_get_contents();
			$var = json_decode($output);
			ob_end_clean();

			if($var->code == 200)
			{
				$this->CI->prioridade_model->update([
					'prioridade_pk' => $c['prioridade_pk'],
					'prioridade_desativar_tempo' => null
				]);
			}

			$this->CI->unit->run($var->code,$c['expected'], $c['test_name'], $output);

		endforeach;

		header("Content-Type: text/html; charset=UTF-8",true);
		echo "<a href=".base_url('test/'.$this->class_name.'/index').">Voltar</a>";
		echo $this->CI->unit->report();
	}
}
?>