<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__) . "\..\Situacao.php";

class Situacao_test extends CI_Controller 
{
	private $dep;
	private $CI;

	function __construct() 
	{
		$this->dep = new Situacao();
		$this->CI =& get_instance();

		$this->CI->load->library('form_validation');
		$this->CI->load->library('unit_test');
		$this->CI->load->model('situacao_model');

		$this->class_methods = get_class_methods($this);
		$this->class_name = get_class($this);
		unset($this->class_methods[0]);
		unset($this->class_methods[count($this->class_methods)]);
	}

	public function index()
	{

		header("Content-Type: text/html; charset=UTF-8", true);
		foreach ($this->class_methods as $method_name) {
			echo "<a href='".base_url('test/'.$this->class_name.'/'.$method_name)."'>".$method_name."</a><br>";
		}
	}

	public function read()
	{
		$method = 'index';

		$test_case = [
			[
				'test_name' => 'leitura correta de situações',
				'expected' => 200,
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			],
			[
				'test_name' => 'organizacao inexistente',
				'expected' => 404,
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'prudenco'
				]
			]
		];


		foreach($test_case as $c):

			$this->CI->session->set_userdata('user',$c['session']);

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

	public function insert() 
	{
		$method = 'insert_update';

		$test_case = [
			[
				'test_name' => 'inserção correta de situação',
				'expected' => 200,
				'situacao_nome' => 'situacao_teste',
				'situacao_descricao' => 'Esta é uma situação para testes.',
				'situacao_foto_obrigatoria' => true,
				'senha' => '',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			],
			[
				'test_name' => 'dados incorretos para inserção situação',
				'expected' => 400,
				'situacao_nome' => '',
				'situacao_descricao' => '',
				'situacao_foto_obrigatoria' => '',
				'senha' => '',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			],
		];


		foreach($test_case as $c):

			$this->CI->session->set_userdata('user',$c['session']);

			$_POST['situacao_nome'] = $c['situacao_nome'];
			$_POST['situacao_descricao'] = $c['situacao_descricao'];
			$_POST['situacao_foto_obrigatoria'] = $c['situacao_foto_obrigatoria'];
			$_POST['senha'] = $c['senha'];
			$this->CI->form_validation->set_data($_POST);

			ob_start();
			$this->dep->$method();
			$output = ob_get_contents();
			$var = json_decode($output);
			ob_end_clean();

			if (isset($var->code) && $var->code == 200)
			{
				// $this->CI->situacao_model->delete($var->data->situacao_pk);
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
				'test_name' => 'update correto de situação',
				'expected' => 200,
				'situacao_nome' => 'situacao_para_teste',
				'situacao_pk' => '5',
				'situacao_descricao' => 'Esta é uma situação para testes.',
				'situacao_foto_obrigatoria' => true,
				'senha' => '',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			],
			[
				'test_name' => 'dados incorretos para update de situação',
				'expected' => 400,
				'situacao_nome' => '',
				'situacao_pk' => 'cas5',
				'situacao_descricao' => '',
				'situacao_foto_obrigatoria' => '',
				'senha' => '',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			],
		];


		foreach($test_case as $c):

			$this->CI->session->set_userdata('user',$c['session']);

			$_POST['situacao_nome'] = $c['situacao_nome'];
			$_POST['situacao_pk'] = $c['situacao_pk'];
			$_POST['situacao_descricao'] = $c['situacao_descricao'];
			$_POST['situacao_foto_obrigatoria'] = $c['situacao_foto_obrigatoria'];
			$_POST['senha'] = $c['senha'];
			$this->CI->form_validation->set_data($_POST);

			ob_start();
			$this->dep->$method();
			$output = ob_get_contents();
			$var = json_decode($output);
			ob_end_clean();

			if (isset($var->code) && $var->code == 200)
			{
				$this->CI->situacao_model->update([
					'situacao_nome' => 'situacao_teste'
				], '5');
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
				'senha' => '',
				'situacao_pk' => '7',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			],
			[
				'test_name' => 'desativação de situacao inexistente',
				'expected' => 501,
				'senha' => '',
				'situacao_pk' => '9',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			],
			[
				'test_name' => 'situacao_pk inexiste',
				'expected' => 400,
				'senha' => '',
				'situacao_pk' => '',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			],
			// Mudar a flag no banco para false para executar o próximo teste
			// [
			// 	'test_name' => 'desativação de situacao já desativada',
			// 	'expected' => 501,
			// 	'senha' => '',
			// 	'situacao_pk' => '7',
			// 	'session' => [
			// 		'is_superusuario' => false,
			// 		'id_organizacao' => 'teste'
			// 	]
			// ]
		];


		foreach($test_case as $c):

			$this->CI->session->set_userdata('user',$c['session']);

			$_POST['senha'] = $c['senha'];
			$_POST['situacao_pk'] = $c['situacao_pk'];
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


	public function activate() {
		$method = 'activate';

		$test_case = [
			[
				'test_name' => 'ativação correta',
				'expected' => 200,
				'senha' => '',
				'situacao_pk' => '7',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			],
			[
				'test_name' => 'ativação de situacao inexistente',
				'expected' => 501,
				'senha' => '',
				'situacao_pk' => '9',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			],
			[
				'test_name' => 'situacao_pk inexiste',
				'expected' => 400,
				'senha' => '',
				'situacao_pk' => '',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			],
			[
				'test_name' => 'ativação de situacao já ativada',
				'expected' => 501,
				'senha' => '',
				'situacao_pk' => '7',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			]
		];


		foreach($test_case as $c):

			$this->CI->session->set_userdata('user',$c['session']);

			$_POST['senha'] = $c['senha'];
			$_POST['situacao_pk'] = $c['situacao_pk'];
			$this->CI->form_validation->set_data($_POST);

			ob_start();
			$this->dep->$method();
			$output = ob_get_contents();
			$var = json_decode($output);
			ob_end_clean();

			$this->CI->unit->run($var->code,$c['expected'], $c['test_name'], $output);
			
			$this->CI->form_validation->reset_validation();

		endforeach;

		$this->CI->situacao_model->update(
			['situacao_ativo' => 0], '7'
		);

		header("Content-Type: text/html; charset=UTF-8",true);
		echo "<a href=".base_url('test/'.$this->class_name.'/index').">Voltar</a>";
		echo $this->CI->unit->report();
	}
}
?>