<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__) . "\..\Tipo_Servico.php";

class Tipo_Servico_test extends CI_Controller 
{
	private $dep;
	private $CI;

	function __construct() 
	{
		$this->dep = new Tipo_Servico();
		$this->CI =& get_instance();

		$this->CI->load->library('form_validation');
		$this->CI->load->library('unit_test');
		$this->CI->load->model('tipo_servico_model');

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
				'test_name' => 'leitura correta de tipos de serviços',
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
					'id_organizacao' => 'abga'
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
				'test_name' => 'inserção correta de tipo de serviço',
				'expected' => 200,
				'tipo_servico_pk' => '',
				'tipo_servico_nome' => 'tipos_servico_teste',
				'tipo_servico_desc' => 'Este é um tipo de serviço para testes.',
				'prioridade_pk' => '8',
				'departamento_pk' => '10',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			],
			[
				'test_name' => 'dados incorretos na inserção de tipo de serviço',
				'expected' => 400,
				'tipo_servico_pk' => '',
				'tipo_servico_nome' => '',
				'tipo_servico_desc' => '',
				'prioridade_pk' => 'caas',
				'departamento_pk' => 'casc',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			]
		];


		foreach($test_case as $c):

			$this->CI->session->set_userdata('user',$c['session']);

			$_POST['tipo_servico_pk'] = $c['tipo_servico_pk'];
			$_POST['tipo_servico_nome'] = $c['tipo_servico_nome'];
			$_POST['tipo_servico_desc'] = $c['tipo_servico_desc'];
			$_POST['prioridade_pk'] = $c['prioridade_pk'];
			$_POST['departamento_pk'] = $c['departamento_pk'];
			$this->CI->form_validation->set_data($_POST);

			ob_start();
			$this->dep->$method();
			$output = ob_get_contents();
			$var = json_decode($output);
			ob_end_clean();

			if (isset($var->code) && $var->code == 200)
			{
				$this->CI->tipo_servico_model->delete($var->data->tipo_servico_pk);
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
				'test_name' => 'update correto de tipo de serviço',
				'expected' => 200,
				'tipo_servico_pk' => '1',
				'tipo_servico_nome' => 'tipos_servico_teste_udpate',
				'tipo_servico_desc' => 'Este é um tipo de serviço para testes.',
				'prioridade_pk' => '8',
				'departamento_pk' => '10',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			],
			[
				'test_name' => 'inserção correta de tipo de serviço',
				'expected' => 400,
				'tipo_servico_pk' => '1',
				'tipo_servico_nome' => 'jjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjj',
				'tipo_servico_desc' => '',
				'prioridade_pk' => 'caas',
				'departamento_pk' => 'casc',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			],
			[
				'test_name' => 'departamento inexistente',
				'expected' => 404,
				'tipo_servico_pk' => '1',
				'tipo_servico_nome' => 'tipos_servico_teste_udpate',
				'tipo_servico_desc' => 'Este é um tipo de serviço para testes.',
				'prioridade_pk' => '8',
				'departamento_pk' => '66',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			],
			[
				'test_name' => 'prioridade inexistente',
				'expected' => 404,
				'tipo_servico_pk' => '1',
				'tipo_servico_nome' => 'tipos_servico_teste_udpate',
				'tipo_servico_desc' => 'Este é um tipo de serviço para testes.',
				'prioridade_pk' => '84',
				'departamento_pk' => '10',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			],
		];


		foreach($test_case as $c):

			$this->CI->session->set_userdata('user',$c['session']);

			$_POST['tipo_servico_pk'] = $c['tipo_servico_pk'];
			$_POST['tipo_servico_nome'] = $c['tipo_servico_nome'];
			$_POST['tipo_servico_desc'] = $c['tipo_servico_desc'];
			$_POST['prioridade_pk'] = $c['prioridade_pk'];
			$_POST['departamento_pk'] = $c['departamento_pk'];
			$this->CI->form_validation->set_data($_POST);

			ob_start();
			$this->dep->$method();
			$output = ob_get_contents();
			$var = json_decode($output);
			ob_end_clean();

			if (isset($var->code) && $var->code == 200)
			{
				$this->CI->tipo_servico_model->update([
					'tipo_servico_nome' => 'tipos_teste'
				],'1');
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
			// 	'test_name' => 'desativação correta',
			// 	'expected' => 200,
			// 	'tipo_servico_pk' => '1',
			// 	'session' => [
			// 		'is_superusuario' => false,
			// 		'id_organizacao' => 'teste'
			// 	]
			// ],
			// [
			// 	'test_name' => 'desativação de tipo de servico inexistente',
			// 	'expected' => 404,
			// 	'tipo_servico_pk' => '564',
			// 	'session' => [
			// 		'is_superusuario' => false,
			// 		'id_organizacao' => 'teste'
			// 	]
			// ],


			// Mudar a flag no banco para false para executar o próximo teste
			[
				'test_name' => 'desativação de tipo de serviço já desativado',
				'expected' => 501,
				'tipo_servico_pk' => '1',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			]
		];


		foreach($test_case as $c):

			$this->CI->session->set_userdata('user',$c['session']);

			$_POST['tipo_servico_pk'] = $c['tipo_servico_pk'];
			$this->CI->form_validation->set_data($_POST);

			ob_start();
			$this->dep->$method();
			$output = ob_get_contents();
			$var = json_decode($output);
			ob_end_clean();

			if (isset($var->code) && $var->code == 200)
			{
				$this->CI->tipo_servico_model->update([
					'tipo_servico_status' => '1'
				],'1');
			}

			$this->CI->unit->run($var->code,$c['expected'], $c['test_name'], $output);
			
			$this->CI->form_validation->reset_validation();

		endforeach;

		header("Content-Type: text/html; charset=UTF-8",true);
		echo "<a href=".base_url('test/'.$this->class_name.'/index').">Voltar</a>";
		echo $this->CI->unit->report();
	}


	public function activate() 
	{
		$method = 'activate';

		$test_case = [
			[
				'test_name' => 'ativação correta',
				'expected' => 200,
				'tipo_servico_pk' => '1',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			],
			[
				'test_name' => 'ativação de tipo de servico inexistente',
				'expected' => 404,
				'tipo_servico_pk' => '564',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			],


			// Mudar a flag no banco para false para executar o próximo teste
			// [
			// 	'test_name' => 'desativação de tipo de serviço já desativado',
			// 	'expected' => 501,
			// 	'tipo_servico_pk' => '1',
			// 	'session' => [
			// 		'is_superusuario' => false,
			// 		'id_organizacao' => 'teste'
			// 	]
			// ]
		];


		foreach($test_case as $c):

			$this->CI->session->set_userdata('user',$c['session']);

			$_POST['tipo_servico_pk'] = $c['tipo_servico_pk'];
			$this->CI->form_validation->set_data($_POST);

			ob_start();
			$this->dep->$method();
			$output = ob_get_contents();
			$var = json_decode($output);
			ob_end_clean();

			if (isset($var->code) && $var->code == 200)
			{
				$this->CI->tipo_servico_model->update([
					'tipo_servico_status' => '0'
				],'1');
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