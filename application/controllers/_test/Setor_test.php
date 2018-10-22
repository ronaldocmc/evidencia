<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__) . "\..\Setor.php";

class Setor_test extends CI_Controller {
	private $dep;
	private $CI;

	function __construct() {
		$this->dep = new Setor();
		$this->CI =& get_instance();

		$this->CI->load->library('form_validation');
		$this->CI->load->library('unit_test');
		$this->CI->load->model('setor_model');

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

	public function insert() 
	{
		$method = 'insert_update';
		$test_case = [
			[
				'test_name' => 'inserção correta de setor',
				'expected' => 200,
				'nome' => 'setor_teste2',
				'senha' => '',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'unesp'
				]
			],
			[
				'test_name' => 'Insert com nome muito pequeno',
				'expected' => 400,
				'nome' => 'te',
				'senha' => '',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'unesp'
				]
			],
			[
				'test_name' => 'Insert com organização inexistente',
				'expected' => 503,
				'nome' => 'teste_setor_da_hr',
				'senha' => '',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'xamalaia'
				]
			]
		];


		foreach($test_case as $c):

			$this->CI->session->set_userdata('user',$c['session']);

			$_POST['setor_nome'] = $c['nome'];
			$_POST['setor_pk'] = '';
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
				$this->CI->setor_model->delete($id);
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
		$method = 'insert_update';
		$test_case = [
			[
				'test_name' => 'nome muito pequeno',
				'expected' => 400,
				'nome' => 'te',
				'setor_pk' => '20',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'unesp'
				]
			],
			[
				'test_name' => 'edição correta',
				'expected' => 200,
				'nome' => 'Setor de teste',
				'setor_pk' => '20',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'unesp'
				]
			],

			// Departamento inexistente
			[
				'test_name' => 'update em setor inexistente',
				'expected' => 501,
				'nome' => 'teste2',
				'setor_pk' => '55',
				'session' => [
					'is_superusuario' => true,
					'id_organizacao' => 'unesp'
				]
			]
		];


		foreach($test_case as $c):

			$this->CI->session->set_userdata('user',$c['session']);

			$_POST['setor_nome'] = $c['nome'];
			$_POST['setor_pk'] = $c['setor_pk'];
			$this->CI->form_validation->set_data($_POST);

			ob_start();
			$this->dep->$method();
			$output = ob_get_contents();
			$var = json_decode($output);
			ob_end_clean();

			if ($var->code == 200)
			{
				$this->CI->setor_model->update([
					'setor_nome' => 'Setor Testes'
				], $c['setor_pk']);
			}

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
			[
				'test_name' => 'desativação correta',
				'expected' => 200,
				'setor_pk' => '20',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'unesp'
				]
			],
			[
				'test_name' => 'setor inexistente',
				'expected' => 501,
				'setor_pk' => '55',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'unesp'
				]
			],
			// Alterar no banco para testar
			[
				'test_name' => 'setor já desativado',
				'expected' => 501,
				'setor_pk' => '21',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'unesp'
				]
			]
		];


		foreach($test_case as $c):

			$this->CI->session->set_userdata('user',$c['session']);

			$_POST['setor_pk'] = $c['setor_pk'];
			$this->CI->form_validation->set_data($_POST);

			ob_start();
			$this->dep->$method();
			$output = ob_get_contents();
			$var = json_decode($output);
			ob_end_clean();

			if($var->code == 200){
				$dados['setor_status'] = 1;
				$this->CI->setor_model->update($dados, 
					$c['setor_pk']);
			}

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
			[
				'test_name' => 'ativação correta',
				'expected' => 200,
				'setor_pk' => '20',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'unesp'
				]
			],
			[
				'test_name' => 'setor inexistente',
				'expected' => 501,
				'setor_pk' => '55',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'unesp'
				]
			],
			// Alterar no banco para testar
			[
				'test_name' => 'setor já ativado',
				'expected' => 501,
				'setor_pk' => '21',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'unesp'
				]
			]
		];


		foreach($test_case as $c):

			$this->CI->session->set_userdata('user',$c['session']);

			$_POST['setor_pk'] = $c['setor_pk'];
			$this->CI->form_validation->set_data($_POST);

			ob_start();
			$this->dep->$method();
			$output = ob_get_contents();
			$var = json_decode($output);
			ob_end_clean();

			if($var->code == 200){
				$dados['setor_status'] = 0;
				$this->CI->setor_model->update($dados, 
					$c['setor_pk']);
			}

			$this->CI->unit->run($var->code,$c['expected'], $c['test_name'], $output);
			
			$this->CI->form_validation->reset_validation();

		endforeach;

		header("Content-Type: text/html; charset=UTF-8",true);
		echo "<a href=".base_url('test/'.$this->class_name.'/index').">Voltar</a>";
		echo $this->CI->unit->report();
	}
}
?>