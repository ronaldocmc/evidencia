<?php 

if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "core\Response.php";

class Historico_Prazo extends CI_Controller {
	private $CI;

	function __construct()
	{
		date_default_timezone_set('America/Sao_Paulo');
		if ($this->CI = & get_instance() === NULL)
		{
			parent::__construct();
			$this->CI = & get_instance();
		}
		$this->CI->load->model('historico_prazo_model');
	}

	function index() 
	{

	}

	/**
    * Método responsável por criar os historicos de prioridades padrões de uma nova organização
    *
    * @param chave primária das prioridades criadas
    * @return 
    */
	public function create_standart($prioridades_fks)
	{
		// Para teste
        	// $response = new Response();
        // ---

		$historicos = [
			[
				'prazo_duracao' => '72 hours',
				'prioridade_fk' => $prioridades_fks[0]
			],
			[
				'prazo_duracao' => '48 hours',
				'prioridade_fk' => $prioridades_fks[1]
			],
			[
				'prazo_duracao' => '24 hours',
				'prioridade_fk' => $prioridades_fks[2]
			],
			[
				'prazo_duracao' => '12 hours',
				'prioridade_fk' => $prioridades_fks[3]
			]
		];

		foreach ($historicos as $h) 
		{
			$retorno = $this->CI->historico_prazo_model->insert($h);

			if($retorno === false)
			{
				// Para teste
                // $response->set_code(Response::DB_ERROR_INSERT);
                // $response->set_data([
                //     'erro' => 'Erro na inserção'
                // ]);
                // $response->send();
                // ---

                return;
			}
		}

		// Para teste
        // $response->set_code(Response::SUCCESS);
        // $response->send();

        return;
        // ---
	}
}


?>