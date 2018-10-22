<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__) . "\..\Historico_Prazo.php";

class Historico_Prazo_test extends CI_Controller {
	private $dep;
	private $CI;

	function __construct() {
		$this->dep = new Historico_Prazo();
		$this->CI =& get_instance();

		$this->CI->load->library('unit_test');
		$this->CI->load->model('historico_prazo_model');

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
				'prioridades_pks' => [
					0 => 97,
					1 => 98,
					2 => 99,
					3 => 100
				]
			],
			[
				'test_name' => 'criação inccorreta / prioridades_pks inexistentes',
				'expected' => 503,
				'prioridades_pks' => [
					0 => 2,
					1 => 8,
					2 => 9,
					3 => 10
				]
			],	
		];


		foreach($test_case as $c):

			ob_start();
			$this->dep->$method($c['prioridades_pks']);
			$output = ob_get_contents();
			$var = json_decode($output);
			ob_end_clean();

			if($var->code == 200)
			{
				foreach ($c['prioridades_pks'] as $p) 
				{
					$this->CI->historico_prazo_model->delete([
					'prioridade_fk' => $p
					]);
				}
				
			}

			$this->CI->unit->run($var->code,$c['expected'], $c['test_name'], $output);

		endforeach;

		header("Content-Type: text/html; charset=UTF-8",true);
		echo "<a href=".base_url('test/'.$this->class_name.'/index').">Voltar</a>";
		echo $this->CI->unit->report();
	}

}
?>