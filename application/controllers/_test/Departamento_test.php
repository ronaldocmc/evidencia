<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__) . "\..\Departamento.php";

class Departamento_test extends CI_Controller {
	private $dep;
	private $CI;

	function __construct() {
		$this->dep = new Departamento();
		$this->CI =& get_instance();

		$this->CI->load->library('form_validation');
		$this->CI->load->library('unit_test');
		$this->CI->load->model('departamento_model');

		$this->class_methods = get_class_methods($this);
		$this->class_name = get_class($this);
		unset($this->class_methods[0]);
		unset($this->class_methods[count($this->class_methods)]);
	}

	public function index(){

		header("Content-Type: text/html; charset=UTF-8", true);
		foreach ($this->class_methods as $method_name) {
			echo "<a href='".base_url('test/'.$this->class_name.'/'.$method_name)."'>".$method_name."</a><br>";
		}
	}

	public function insert() {
		$method = 'insert_update';
		$test_case = [
			// insert sem superusuario
			[
				'nome' => 'te',
				'senha' => '',
				'expected' => 400,
				'test_name' => 'nome muito pequeno',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'prudenco'
				]
			],
			[
				'nome' => 'teste',
				'senha' => '',
				'expected' => 200,
				'test_name' => 'inserção correta administrador',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'prudenco'
				]
			],

			// Insert com superusuario
			[
				'nome' => 'teste',
				'senha' => '12345678',
				'expected' => 200,
				'test_name' => 'inserção correta superusuario',
				'session' => [
					'is_superusuario' => true,
					'password_user' => hash(ALGORITHM_HASH, '12345678'.SALT),
					'id_organizacao' => 'prudenco'
				]
			],
			[
				'nome' => 'teste2',
				'senha' => '123456789',
				'expected' => 401,
				'test_name' => 'senha inválida',
				'session' => [
					'is_superusuario' => true,
					'password_user' => hash(ALGORITHM_HASH, '12345678'.SALT),
					'id_organizacao' => 'prudenco'
				]
			]
		];


		foreach($test_case as $c):

			$this->CI->session->set_userdata('user',$c['session']);

			$_POST['nome'] = $c['nome'];
			$_POST['senha'] = $c['senha'];
			$this->CI->form_validation->set_data($_POST);

			ob_start();
			$this->dep->$method();
			$output = ob_get_contents();
			$var = json_decode($output);
			ob_end_clean();

			if (isset($var->data) && isset($var->data->id))
			{
				$id = $var->data->id;
				$this->CI->departamento_model->delete($id);
			}

			$this->CI->unit->run($var->code,$c['expected'], $c['test_name'], $output);
			
			$this->CI->form_validation->reset_validation();

		endforeach;

		header("Content-Type: text/html; charset=UTF-8",true);
		echo "<a href=".base_url('test/'.$this->class_name.'/index').">Voltar</a>";
		echo $this->CI->unit->report();
	}


	public function update() {
		$method = 'insert_update';
		$test_case = [
			// update sem superusuario
			[
				'nome' => 'te',
				'senha' => '',
				'departamento_pk' => '42',
				'expected' => 400,
				'test_name' => 'nome muito pequeno update',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'prudenco'
				]
			],
			[
				'nome' => 'Departamento de teste',
				'senha' => '',
				'departamento_pk' => '42',
				'expected' => 200,
				'test_name' => 'edição correta administrador',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'prudenco'
				]
			],

			// update com superusuario
			[
				'nome' => 'teste',
				'senha' => '12345678',
				'departamento_pk' => '42',
				'expected' => 200,
				'test_name' => 'edição correta superusuario',
				'session' => [
					'is_superusuario' => true,
					'password_user' => hash(ALGORITHM_HASH, '12345678'.SALT),
					'id_organizacao' => 'prudenco'
				]
			],
			[
				'nome' => 'teste2',
				'senha' => '123456789',
				'departamento_pk' => '42',
				'expected' => 401,
				'test_name' => 'senha inválida update',
				'session' => [
					'is_superusuario' => true,
					'password_user' => hash(ALGORITHM_HASH, '12345678'.SALT),
					'id_organizacao' => 'prudenco'
				]
			],

			// Departamento inexistente
			[
				'nome' => 'teste2',
				'senha' => '12345678',
				'departamento_pk' => '10',
				'expected' => 501,
				'test_name' => 'update em dpto inexistente',
				'session' => [
					'is_superusuario' => true,
					'password_user' => hash(ALGORITHM_HASH, '12345678'.SALT),
					'id_organizacao' => 'prudenco'
				]
			]
		];


		foreach($test_case as $c):

			$this->CI->session->set_userdata('user',$c['session']);

			$_POST['nome'] = $c['nome'];
			$_POST['senha'] = $c['senha'];
			$_POST['departamento_pk'] = $c['departamento_pk'];
			$this->CI->form_validation->set_data($_POST);

			ob_start();
			$this->dep->$method();
			$output = ob_get_contents();
			$var = json_decode($output);
			ob_end_clean();

			$this->CI->unit->run($var->code,$c['expected'], $c['test_name'], $output);
			
			$this->CI->form_validation->reset_validation();

		endforeach;

		header("Content-Type: text/html; charset=UTF-8",true);
		echo "<a href=".base_url('test/'.$this->class_name.'/index').">Voltar</a>";
		echo $this->CI->unit->report();
	}


	public function deactivate() {
		$method = 'deactivate';
		$test_case = [
			// update sem superusuario
			[
				'senha' => '',
				'departamento_pk' => '42',
				'expected' => 200,
				'test_name' => 'desativação correta',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'prudenco'
				]
			],
			[
				'senha' => '',
				'departamento_pk' => '50',
				'expected' => 501,
				'test_name' => 'departamento inexistente com adm',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'prudenco'
				]
			],

			// desativando com superusuario
			[
				'senha' => '12345678',
				'departamento_pk' => '42',
				'expected' => 200,
				'test_name' => 'desativando com superusuario',
				'session' => [
					'is_superusuario' => true,
					'password_user' => hash(ALGORITHM_HASH, '12345678'.SALT),
					'id_organizacao' => 'prudenco'
				]
			],
			[
				'senha' => '123456789',
				'departamento_pk' => '42',
				'expected' => 401,
				'test_name' => 'senha inválida desativar',
				'session' => [
					'is_superusuario' => true,
					'password_user' => hash(ALGORITHM_HASH, '12345678'.SALT),
					'id_organizacao' => 'prudenco'
				]
			],
			[
				'senha' => '12345678',
				'departamento_pk' => '50',
				'expected' => 501,
				'test_name' => 'departamento inexistente com superusuario',
				'session' => [
					'is_superusuario' => true,
					'password_user' => hash(ALGORITHM_HASH, '12345678'.SALT),
					'id_organizacao' => 'prudenco'
				]
			]
		];


		foreach($test_case as $c):

			$this->CI->session->set_userdata('user',$c['session']);

			$_POST['senha'] = $c['senha'];
			$_POST['departamento_pk'] = $c['departamento_pk'];
			$this->CI->form_validation->set_data($_POST);

			ob_start();
			$this->dep->$method();
			$output = ob_get_contents();
			$var = json_decode($output);
			ob_end_clean();

			$dados['ativo'] = 1;
			$this->CI->departamento_model->update($dados, 
				$c['departamento_pk']);

			$this->CI->unit->run($var->code,$c['expected'], $c['test_name'], $output);
			
			$this->CI->form_validation->reset_validation();

		endforeach;

		header("Content-Type: text/html; charset=UTF-8",true);
		echo "<a href=".base_url('test/'.$this->class_name.'/index').">Voltar</a>";
		echo $this->CI->unit->report();
	}


	public function activate() {
		$method = 'activate';
		$test_case = [
			// update sem superusuario
			[
				'senha' => '',
				'departamento_pk' => '42',
				'expected' => 200,
				'test_name' => 'ativação correta',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'prudenco'
				]
			],
			[
				'senha' => '',
				'departamento_pk' => '50',
				'expected' => 501,
				'test_name' => 'departamento inexistente com adm',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'prudenco'
				]
			],

			// desativando com superusuario
			[
				'senha' => '12345678',
				'departamento_pk' => '42',
				'expected' => 200,
				'test_name' => 'ativação com superusuario',
				'session' => [
					'is_superusuario' => true,
					'password_user' => hash(ALGORITHM_HASH, '12345678'.SALT),
					'id_organizacao' => 'prudenco'
				]
			],
			[
				'senha' => '123456789',
				'departamento_pk' => '42',
				'expected' => 401,
				'test_name' => 'senha inválida desativar',
				'session' => [
					'is_superusuario' => true,
					'password_user' => hash(ALGORITHM_HASH, '12345678'.SALT),
					'id_organizacao' => 'prudenco'
				]
			],
			[
				'senha' => '12345678',
				'departamento_pk' => '50',
				'expected' => 501,
				'test_name' => 'departamento inexistente com superusuario',
				'session' => [
					'is_superusuario' => true,
					'password_user' => hash(ALGORITHM_HASH, '12345678'.SALT),
					'id_organizacao' => 'prudenco'
				]
			]
		];


		foreach($test_case as $c):

			$this->CI->session->set_userdata('user',$c['session']);

			$_POST['senha'] = $c['senha'];
			$_POST['departamento_pk'] = $c['departamento_pk'];
			$this->CI->form_validation->set_data($_POST);

			ob_start();
			$this->dep->$method();
			$output = ob_get_contents();
			$var = json_decode($output);
			ob_end_clean();

			$dados['ativo'] = 0;
			$this->CI->departamento_model->update($dados, 
				$c['departamento_pk']);

			$this->CI->unit->run($var->code,$c['expected'], $c['test_name'], $output);
			
			$this->CI->form_validation->reset_validation();

		endforeach;

		header("Content-Type: text/html; charset=UTF-8",true);
		echo "<a href=".base_url('test/'.$this->class_name.'/index').">Voltar</a>";
		echo $this->CI->unit->report();
	}
}
?>