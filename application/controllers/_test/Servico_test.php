<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__) . "\..\Servico.php";

class Servico_test extends CI_Controller 
{
	private $dep;
	private $CI;

	function __construct() 
	{
		$this->dep = new Servico();
		$this->CI =& get_instance();

		$this->CI->load->library('form_validation');
		$this->CI->load->library('unit_test');
		$this->CI->load->model('servico_model');

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

	// public function read()
	// {
	// 	$method = 'index';

	// 	$test_case = [
	// 		[
	// 			'test_name' => 'leitura correta de tipos de serviços',
	// 			'expected' => 200,
	// 			'session' => [
	// 				'is_superusuario' => false,
	// 				'id_organizacao' => 'teste'
	// 			]
	// 		],
	// 		[
	// 			'test_name' => 'organizacao inexistente',
	// 			'expected' => 404,
	// 			'session' => [
	// 				'is_superusuario' => false,
	// 				'id_organizacao' => 'abga'
	// 			]
	// 		]
	// 	];


	// 	foreach($test_case as $c):

	// 		$this->CI->session->set_userdata('user',$c['session']);

	// 		ob_start();
	// 		$this->dep->$method();
	// 		$output = ob_get_contents();
	// 		$var = json_decode($output);
	// 		ob_end_clean();

	// 		$this->CI->unit->run($var->code,$c['expected'], $c['test_name'], $output);

	// 		$this->CI->form_validation->reset_validation();

	// 	endforeach;

	// 	header("Content-Type: text/html; charset=UTF-8",true);
	// 	echo "<a href=".base_url('test/'.$this->class_name.'/index').">Voltar</a>";
	// 	echo $this->CI->unit->report();
	// }


	public function insert() 
	{
		$method = 'insert_update';

		$test_case = [
			[
				'test_name' => 'inserção correta de serviço',
				'expected' => 200,
				'servico_pk' => '',
				'servico_nome' => 'servico_teste',
				'servico_desc' => 'Este é um serviço para testes.',
				'situacao_padrao_pk' => '15',
				'tipo_servico_pk' => '1',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			],
			[
				'test_name' => 'dados incorretos para a inserção de serviço',
				'expected' => 400,
				'servico_pk' => '',
				'servico_nome' => 'servico_testcccccccccccccccccccccccccccccccccccccccccccccccccccccccccce',
				'servico_desc' => '',
				'situacao_padrao_pk' => 'sacc',
				'tipo_servico_pk' => '456',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			],
			[
				'test_name' => 'situacao inexistente',
				'expected' => 404,
				'servico_pk' => '',
				'servico_nome' => 'servico_teste',
				'servico_desc' => 'Este é um serviço para testes.',
				'situacao_padrao_pk' => '5',
				'tipo_servico_pk' => '1',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			],
			[
				'test_name' => 'tipo de serviço inexistente',
				'expected' => 404,
				'servico_pk' => '',
				'servico_nome' => 'servico_teste',
				'servico_desc' => 'Este é um serviço para testes.',
				'situacao_padrao_pk' => '15',
				'tipo_servico_pk' => '15',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			]
		];


		foreach($test_case as $c):

			$this->CI->session->set_userdata('user',$c['session']);

			$_POST['servico_pk'] = $c['servico_pk'];
			$_POST['servico_nome'] = $c['servico_nome'];
			$_POST['servico_desc'] = $c['servico_desc'];
			$_POST['situacao_padrao_pk'] = $c['situacao_padrao_pk'];
			$_POST['tipo_servico_pk'] = $c['tipo_servico_pk'];
			$this->CI->form_validation->set_data($_POST);

			ob_start();
			$this->dep->$method();
			$output = ob_get_contents();
			$var = json_decode($output);
			ob_end_clean();

			// if (isset($var->code) && $var->code == 200)
			// {
			// 	$this->CI->servico_model->delete($var->data->servico_pk);
			// }

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
				'test_name' => 'inserção correta de serviço',
				'expected' => 200,
				'servico_pk' => '5',
				'servico_nome' => 'servico_teste_update',
				'servico_desc' => 'Este é um serviço para testes.',
				'situacao_padrao_pk' => '15',
				'tipo_servico_pk' => '1',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			],
			[
				'test_name' => 'dados incorretos para a inserção de serviço',
				'expected' => 400,
				'servico_pk' => '5',
				'servico_nome' => 'servico_testcccccccccccccccccccccccccccccccccccccccccccccccccccccccccce',
				'servico_desc' => '',
				'situacao_padrao_pk' => 'sacc',
				'tipo_servico_pk' => '456',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			],
			[
				'test_name' => 'situacao inexistente',
				'expected' => 404,
				'servico_pk' => '5',
				'servico_nome' => 'servico_teste',
				'servico_desc' => 'Este é um serviço para testes.',
				'situacao_padrao_pk' => '5',
				'tipo_servico_pk' => '1',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			],
			[
				'test_name' => 'tipo de serviço inexistente',
				'expected' => 404,
				'servico_pk' => '5',
				'servico_nome' => 'servico_teste',
				'servico_desc' => 'Este é um serviço para testes.',
				'situacao_padrao_pk' => '15',
				'tipo_servico_pk' => '15',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			],
			[
				'test_name' => 'serviço inexistente',
				'expected' => 501,
				'servico_pk' => '10',
				'servico_nome' => 'servico_teste',
				'servico_desc' => 'Este é um serviço para testes.',
				'situacao_padrao_pk' => '15',
				'tipo_servico_pk' => '1',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			]
		];


		foreach($test_case as $c):

			$this->CI->session->set_userdata('user',$c['session']);

			$_POST['servico_pk'] = $c['servico_pk'];
			$_POST['servico_nome'] = $c['servico_nome'];
			$_POST['servico_desc'] = $c['servico_desc'];
			$_POST['situacao_padrao_pk'] = $c['situacao_padrao_pk'];
			$_POST['tipo_servico_pk'] = $c['tipo_servico_pk'];
			$this->CI->form_validation->set_data($_POST);

			ob_start();
			$this->dep->$method();
			$output = ob_get_contents();
			$var = json_decode($output);
			ob_end_clean();

			if (isset($var->code) && $var->code == 200)
			{
				$this->CI->servico_model->update([
					'servico_nome' => 'servico_teste'
				], '5');
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
			// 	'servico_pk' => '5',
			// 	'session' => [
			// 		'is_superusuario' => false,
			// 		'id_organizacao' => 'teste'
			// 	]
			// ],
			// [
			// 	'test_name' => 'desativação de servico inexistente',
			// 	'expected' => 404,
			// 	'servico_pk' => '564',
			// 	'session' => [
			// 		'is_superusuario' => false,
			// 		'id_organizacao' => 'teste'
			// 	]
			// ],
			// [
			// 	'test_name' => 'dado incorreto',
			// 	'expected' => 400,
			// 	'servico_pk' => 'csac',
			// 	'session' => [
			// 		'is_superusuario' => false,
			// 		'id_organizacao' => 'teste'
			// 	]
			// ]


			// Mudar a flag no banco para false para executar o próximo teste
			[
				'test_name' => 'desativação de serviço já desativado',
				'expected' => 501,
				'servico_pk' => '5',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			]
		];


		foreach($test_case as $c):

			$this->CI->session->set_userdata('user',$c['session']);

			$_POST['servico_pk'] = $c['servico_pk'];
			$this->CI->form_validation->set_data($_POST);

			ob_start();
			$this->dep->$method();
			$output = ob_get_contents();
			$var = json_decode($output);
			ob_end_clean();

			if (isset($var->code) && $var->code == 200)
			{
				$this->CI->servico_model->update([
					'servico_status' => '1'
				],'5');
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
				'servico_pk' => '5',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			],
			[
				'test_name' => 'ativação de servico inexistente',
				'expected' => 404,
				'servico_pk' => '564',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			],
			[
				'test_name' => 'dado incorreto',
				'expected' => 400,
				'servico_pk' => 'csac',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			],


			// Mudar a flag no banco para false para executar o próximo teste
			[
				'test_name' => 'ativação de serviço já desativado',
				'expected' => 501,
				'servico_pk' => '5',
				'session' => [
					'is_superusuario' => false,
					'id_organizacao' => 'teste'
				]
			]
		];


		foreach($test_case as $c):

			$this->CI->session->set_userdata('user',$c['session']);

			$_POST['servico_pk'] = $c['servico_pk'];
			$this->CI->form_validation->set_data($_POST);

			ob_start();
			$this->dep->$method();
			$output = ob_get_contents();
			$var = json_decode($output);
			ob_end_clean();

			if (isset($var->code) && $var->code == 200)
			{
				// $this->CI->servico_model->update([
				// 	'servico_status' => '0'
				// ],'5');
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