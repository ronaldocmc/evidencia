<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__) . "\..\Organizacao.php";

class Organizacao_test extends CI_Controller {
	private $dep;
	private $CI;

	function __construct() {
		$this->dep = new Organizacao();
		$this->CI =& get_instance();

		$this->CI->load->library('form_validation');
		$this->CI->load->library('unit_test');
		$this->CI->load->model('organizacao_model');

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
			[
				'test_name' => 'inserção correta / criando prioridades padrões',
				'expected' => 200,
				'dominio' => 'orga',
				'organizacao_nome' => 'Organizacao',
				'organizacao_cnpj' => '97.850.763/0001-63',
				'logradouro_nome' => 'RUA AURORA LISBOA',
				'local_num' => '96',
				'local_complemento' => '',
				'bairro' => 'jardim maracanã',
				'municipio_pk' => '3541406',
				'estado_pk' => 'SP',
				'senha' => '12345678',
				'session' => [
					'is_superusuario' => true,
					'password_user' => hash(ALGORITHM_HASH, '12345678'.SALT)
				]
			],
			[
				'test_name' => 'campos do formulários incorretos',
				'expected' => 400,
				'dominio' => 'orgaaaaaaaaaaaaaa',
				'organizacao_nome' => 'Org',
				'organizacao_cnpj' => '631651616',
				'logradouro_nome' => '',
				'local_num' => '1234567891011',
				'local_complemento' => '',
				'bairro' => '',
				'municipio_pk' => '',
				'estado_pk' => 'SPC',
				'senha' => '123456',
				'session' => [
					'is_superusuario' => true,
					'password_user' => hash(ALGORITHM_HASH, '12345678'.SALT)
				]
			],
			[
				'test_name' => 'senha incorreta do SU',
				'expected' => 401,
				'dominio' => 'orga',
				'organizacao_nome' => 'Organizacao',
				'organizacao_cnpj' => '97.850.763/0001-63',
				'logradouro_nome' => 'RUA AURORA LISBOA',
				'local_num' => '96',
				'local_complemento' => '',
				'bairro' => 'jardim maracanã',
				'municipio_pk' => '3541406',
				'estado_pk' => 'SP',
				'senha' => '1234551641648',
				'session' => [
					'is_superusuario' => true,
					'password_user' => hash(ALGORITHM_HASH, '12345678'.SALT)
				]
			],
			[
				'test_name' => 'estado incorreto',
				'expected' => 400,
				'dominio' => 'orga',
				'organizacao_nome' => 'Organizacao',
				'organizacao_cnpj' => '97.850.763/0001-63',
				'logradouro_nome' => 'RUA AURORA LISBOA',
				'local_num' => '96',
				'local_complemento' => '',
				'bairro' => 'jardim maracanã',
				'municipio_pk' => '3541406',
				'estado_pk' => 'MP',
				'senha' => '12345678',
				'session' => [
					'is_superusuario' => true,
					'password_user' => hash(ALGORITHM_HASH, '12345678'.SALT)
				]
			]
		];


		foreach($test_case as $c):

			$this->CI->session->set_userdata('user',$c['session']);

			$_POST['organizacao_nome'] = $c['organizacao_nome'];
			$_POST['senha'] = $c['senha'];
			$_POST['dominio'] = $c['dominio'];
			$_POST['organizacao_cnpj'] = $c['organizacao_cnpj'];
			$_POST['logradouro_nome'] = $c['logradouro_nome'];
			$_POST['local_num'] = $c['local_num'];
			$_POST['local_complemento'] = $c['local_complemento'];
			$_POST['bairro'] = $c['bairro'];
			$_POST['municipio_pk'] = $c['municipio_pk'];
			$_POST['estado_pk'] = $c['estado_pk'];
			$this->CI->form_validation->set_data($_POST);

			ob_start();
			$this->dep->$method();
			$output = ob_get_contents();
			$var = json_decode($output);
			ob_end_clean();

			if($var->code == 200)
			{
				$org = $this->CI->organizacao_model->get($c['dominio']);

				if ($org != false)
				{
					$this->CI->organizacao_model->delete($c['dominio']);
				}
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
			// edição SU
			[
				'test_name' => 'edição correta SU',
				'expected' => 200,
				'organizacao_pk' => 'teste',
				'dominio' => 'teste',
				'organizacao_nome' => 'Organizacao',
				'organizacao_cnpj' => '97.850.763/0001-63',
				'logradouro_nome' => 'RUA AURORA LISBOA',
				'local_num' => '96',
				'local_complemento' => '',
				'bairro' => 'jardim maracanã',
				'municipio_pk' => '3541406',
				'estado_pk' => 'SP',
				'senha' => '12345678',
				'session' => [
					'is_superusuario' => true,
					'password_user' => hash(ALGORITHM_HASH, '12345678'.SALT)
				]
			],
			[
				'test_name' => 'campos do formulário incorretos SU',
				'expected' => 400,
				'organizacao_pk' => 'tee',
				'dominio' => 'testeeeeeeeeeeeeeeeeeee',
				'organizacao_nome' => 'Org',
				'organizacao_cnpj' => '631651616',
				'logradouro_nome' => '',
				'local_num' => '1234567891011',
				'local_complemento' => '',
				'bairro' => '',
				'municipio_pk' => '',
				'estado_pk' => 'SPC',
				'senha' => '123456',
				'session' => [
					'is_superusuario' => true,
					'password_user' => hash(ALGORITHM_HASH, '12345678'.SALT)
				]
			],
			[
				'test_name' => 'senha incorreta do SU',
				'expected' => 401,
				'organizacao_pk' => 'teste',
				'dominio' => 'teste',
				'organizacao_nome' => 'Organizacao',
				'organizacao_cnpj' => '97.850.763/0001-63',
				'logradouro_nome' => 'RUA AURORA LISBOA',
				'local_num' => '96',
				'local_complemento' => '',
				'bairro' => 'jardim maracanã',
				'municipio_pk' => '3541406',
				'estado_pk' => 'SP',
				'senha' => '1234551641648',
				'session' => [
					'is_superusuario' => true,
					'password_user' => hash(ALGORITHM_HASH, '12345678'.SALT)
				]
			],
			[
				'test_name' => 'organizacao_pk inexistente SU',
				'expected' => 404,
				'organizacao_pk' => 'cascas',
				'dominio' => 'teste',
				'organizacao_nome' => 'Organizacao',
				'organizacao_cnpj' => '97.850.763/0001-63',
				'logradouro_nome' => 'RUA AURORA LISBOA',
				'local_num' => '96',
				'local_complemento' => '',
				'bairro' => 'jardim maracanã',
				'municipio_pk' => '3541406',
				'estado_pk' => 'SP',
				'senha' => '12345678',
				'session' => [
					'is_superusuario' => true,
					'password_user' => hash(ALGORITHM_HASH, '12345678'.SALT)
				]
			],

			// edição funcionario
			[
				'test_name' => 'edição correta FUNC',
				'expected' => 200,
				'organizacao_pk' => 'teste',
				'dominio' => 'teste',
				'organizacao_nome' => 'Organizacao',
				'organizacao_cnpj' => '97.850.763/0001-63',
				'logradouro_nome' => 'RUA AURORA LISBOA',
				'local_num' => '96',
				'local_complemento' => '',
				'bairro' => 'jardim maracanã',
				'municipio_pk' => '3541406',
				'estado_pk' => 'SP',
				'senha' => '12345678',
				'session' => [
					'is_superusuario' => false
				]
			],
			[
				'test_name' => 'campos do formulário incorretos FUNC',
				'expected' => 400,
				'organizacao_pk' => 'tee',
				'dominio' => 'testeeeeeeeeeeeeeeeeeee',
				'organizacao_nome' => 'Org',
				'organizacao_cnpj' => '631651616',
				'logradouro_nome' => '',
				'local_num' => '1234567891011',
				'local_complemento' => '',
				'bairro' => '',
				'municipio_pk' => '',
				'estado_pk' => 'SPC',
				'senha' => '123456',
				'session' => [
					'is_superusuario' => false
				]
			],
			[
				'test_name' => 'organizacao_pk inexistente FUNC',
				'expected' => 404,
				'organizacao_pk' => 'cascas',
				'dominio' => 'teste',
				'organizacao_nome' => 'Organizacao',
				'organizacao_cnpj' => '97.850.763/0001-63',
				'logradouro_nome' => 'RUA AURORA LISBOA',
				'local_num' => '96',
				'local_complemento' => '',
				'bairro' => 'jardim maracanã',
				'municipio_pk' => '3541406',
				'estado_pk' => 'SP',
				'senha' => '12345678',
				'session' => [
					'is_superusuario' => false
				]
			]
		];


		foreach($test_case as $c):

			$this->CI->session->set_userdata('user',$c['session']);

			$_POST['organizacao_pk'] = $c['organizacao_pk'];
			$_POST['organizacao_nome'] = $c['organizacao_nome'];
			$_POST['senha'] = $c['senha'];
			$_POST['dominio'] = $c['dominio'];
			$_POST['organizacao_cnpj'] = $c['organizacao_cnpj'];
			$_POST['logradouro_nome'] = $c['logradouro_nome'];
			$_POST['local_num'] = $c['local_num'];
			$_POST['local_complemento'] = $c['local_complemento'];
			$_POST['bairro'] = $c['bairro'];
			$_POST['municipio_pk'] = $c['municipio_pk'];
			$_POST['estado_pk'] = $c['estado_pk'];
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
				'organizacao_pk' => 'teste',
				'expected' => 200,
				'test_name' => 'desativação correta FUNC',
				'session' => [
					'is_superusuario' => false
				]
			],
			[
				'senha' => '',
				'organizacao_pk' => 'gasc',
				'expected' => 501,
				'test_name' => 'departamento inexistente FUNC',
				'session' => [
					'is_superusuario' => false
				]
			],

			// // desativando com superusuario
			[
				'senha' => '12345678',
				'organizacao_pk' => 'orgsu',
				'expected' => 200,
				'test_name' => 'desativação correta SU',
				'session' => [
					'is_superusuario' => true,
					'password_user' => hash(ALGORITHM_HASH, '12345678'.SALT)
				]
			],
			[
				'senha' => '123456789',
				'organizacao_pk' => 'orgsu',
				'expected' => 401,
				'test_name' => 'senha inválida desativar SU',
				'session' => [
					'is_superusuario' => true,
					'password_user' => hash(ALGORITHM_HASH, '12345678'.SALT)
				]
			],
			[
				'senha' => '12345678',
				'organizacao_pk' => 'cacasc',
				'expected' => 501,
				'test_name' => 'organizacao inexistente SU',
				'session' => [
					'is_superusuario' => true,
					'password_user' => hash(ALGORITHM_HASH, '12345678'.SALT)
				]
			]
		];


		foreach($test_case as $c):

			$this->CI->session->set_userdata('user',$c['session']);

			$_POST['senha'] = $c['senha'];
			$_POST['organizacao_pk'] = $c['organizacao_pk'];

			ob_start();
			$this->dep->$method();
			$output = ob_get_contents();
			$var = json_decode($output);
			ob_end_clean();

			$this->CI->unit->run($var->code,$c['expected'], $c['test_name'], $output);

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
				'organizacao_pk' => 'teste',
				'expected' => 200,
				'test_name' => 'ativação correta',
				'session' => [
					'is_superusuario' => false
				]
			],
			[
				'senha' => '',
				'organizacao_pk' => 'gasc',
				'expected' => 501,
				'test_name' => 'departamento inexistente FUNC',
				'session' => [
					'is_superusuario' => false
				]
			],

			// // desativando com superusuario
			[
				'senha' => '12345678',
				'organizacao_pk' => 'orgsu',
				'expected' => 200,
				'test_name' => 'ativação correta SU',
				'session' => [
					'is_superusuario' => true,
					'password_user' => hash(ALGORITHM_HASH, '12345678'.SALT)
				]
			],
			[
				'senha' => '123456789',
				'organizacao_pk' => 'orgsu',
				'expected' => 401,
				'test_name' => 'senha inválida ativar SU',
				'session' => [
					'is_superusuario' => true,
					'password_user' => hash(ALGORITHM_HASH, '12345678'.SALT)
				]
			],
			[
				'senha' => '12345678',
				'organizacao_pk' => 'cacasc',
				'expected' => 501,
				'test_name' => 'organizacao inexistente SU',
				'session' => [
					'is_superusuario' => true,
					'password_user' => hash(ALGORITHM_HASH, '12345678'.SALT)
				]
			]
		];


		foreach($test_case as $c):

			$this->CI->session->set_userdata('user',$c['session']);

			$_POST['senha'] = $c['senha'];
			$_POST['organizacao_pk'] = $c['organizacao_pk'];

			ob_start();
			$this->dep->$method();
			$output = ob_get_contents();
			$var = json_decode($output);
			ob_end_clean();

			$this->CI->unit->run($var->code,$c['expected'], $c['test_name'], $output);

		endforeach;

		header("Content-Type: text/html; charset=UTF-8",true);
		echo "<a href=".base_url('test/'.$this->class_name.'/index').">Voltar</a>";
		echo $this->CI->unit->report();
	}
}
?>