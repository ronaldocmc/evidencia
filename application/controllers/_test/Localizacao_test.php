<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__) . "\..\Localizacao.php";

class Localizacao_test extends CI_Controller {
	private $dep;
	private $CI;

	function __construct() {
		$this->dep = new Localizacao();
		$this->CI =& get_instance();

		$this->CI->load->library('unit_test');

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


	public function bairros() {
		$method = 'bairros';
		$test_case = [
			[
				'id_cidade' => '3541406',
				'test_name' => 'Cidade existe com bairros vinculados',
				'expected' => 200
			],
			[
				'id_cidade' => '3541208',
				'test_name' => 'Cidade existe sem bairros vinculados',
				'expected' => 404
			],
			[
				'id_cidade' => '3541456',
				'test_name' => 'Cidade inexistente',
				'expected' => 404
			]			
		];


		foreach($test_case as $c):

			ob_start();
			$this->dep->$method($c['id_cidade']);
			$output = ob_get_contents();
			$var = json_decode($output);
			ob_end_clean();

			$this->CI->unit->run($var->code,$c['expected'], $c['test_name'], $output);

		endforeach;

		header("Content-Type: text/html; charset=UTF-8",true);
		echo "<a href=".base_url('test/'.$this->class_name.'/index').">Voltar</a>";
		echo $this->CI->unit->report();
	}


	public function logradouros() {
		$method = 'logradouros';
		$test_case = [
			[
				'municipio_pk' => '3541406',
				'logradouro_nome' => 'rua',
				'test_name' => 'Logradouro com prefixo comum',
				'expected' => 200
			],
			[
				'municipio_pk' => '3541406',
				'logradouro_nome' => 'rua aurora',
				'test_name' => 'Logradouro mais específico',
				'expected' => 200
			],
			[
				'municipio_pk' => '3541428',
				'logradouro_nome' => 'rua aurora lisboa',
				'test_name' => 'Logradouro existente com cidade inexistente',
				'expected' => 404
			]			
		];


		foreach($test_case as $c):

			$_POST['logradouro_nome'] = $c['logradouro_nome'];
			$_POST['municipio_pk'] = $c['municipio_pk'];

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


	public function get_estados() {
		$method = 'get_estados';
		$test_case = [
			[
				'test_name' => 'teste único',
				'expected' => 200
			]		
		];


		foreach($test_case as $c):

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


	public function get_municipios() {
		$method = 'get_municipios';
		$test_case = [
			[
				'estado' => 'SP',
				'test_name' => 'estado existente',
				'expected' => 200
			],
			[
				'estado' => 'MP',
				'test_name' => 'estado inexistente',
				'expected' => 404
			]		
		];


		foreach($test_case as $c):

			ob_start();
			$this->dep->$method($c['estado']);
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