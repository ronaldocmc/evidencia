<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__) . "\..\Access.php";

class Access_test extends CI_Controller {
	private $dep;
	private $CI;

	function __construct() {
		$this->dep = new Access();
		$this->CI =& get_instance();

		$this->CI->load->library('form_validation');
		$this->CI->load->library('unit_test');
		$this->CI->load->model('acesso_model');

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


	/*
	* Para testar o método de login, deve-se comentar o método na classe Access, para que
	* o Recaptcha não acuse falha e proíba o fluxo do método
	*/ 
	public function login() {
		$method = 'login';
		$test_case = [
			// login com funcionario
			[
				'login' => 'ronaldo@prudenco',
				'password' => '12345678',
				'expected' => 200,
				'test_name' => 'login correto funcionario'
			],
			[
				'login' => 'ronaldoprudenco',
				'password' => '12345678',
				'expected' => 400,
				'test_name' => 'login incorreto funcionario'
			],
			[
				'login' => 'ronaldo@prudenco',
				'password' => '12348',
				'expected' => 400,
				'test_name' => 'senha incorreta funcionario'
			],
			[
				'login' => 'ronaldoprudenco',
				'password' => '12348',
				'expected' => 400,
				'test_name' => 'login e senha incorreta funcionario'
			],
			[
				'login' => 'rogerio@prudenco',
				'password' => '12345678',
				'expected' => 404,
				'test_name' => 'funcionario inexistente'
			],
			[
				'login' => 'rogerio@lala',
				'password' => '12345678',
				'expected' => 404,
				'test_name' => 'organizacao inexistente'
			],

			// login com superusuario
			[
				'login' => 'ronaldo@admin',
				'password' => '12345678',
				'expected' => 200,
				'test_name' => 'login correto SU'
			],
			[
				'login' => 'ronaldoadmin',
				'password' => '12345678',
				'expected' => 400,
				'test_name' => 'login incorreto SU'
			],
			[
				'login' => 'ronaldo@admin',
				'password' => '12348',
				'expected' => 400,
				'test_name' => 'senha incorreta SU'
			],
			[
				'login' => 'ronaldoadmin',
				'password' => '12348',
				'expected' => 400,
				'test_name' => 'login e senha incorreta SU'
			],
			[
				'login' => 'rogerio@admin',
				'password' => '12345678',
				'expected' => 404,
				'test_name' => 'superusuario inexistente'
			]
		];


		foreach($test_case as $c):

			$_POST['login'] = $c['login'];
			$_POST['password'] = $c['password'];
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

}
?>