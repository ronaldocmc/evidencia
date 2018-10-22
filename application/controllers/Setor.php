<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . "/Response.php";

require_once APPPATH."core/CRUD_Controller.php";

class Setor extends CRUD_Controller 
{

	function __construct() 
	{
		parent::__construct();
		$this->load->model('Setor_model', 'setor_model');
	}


    /**
     * Função responsável pela leitura dos setores existentes e o carregamento da view
     * 
     * @param null
     * @return null 
     */
    function index() 
    {
    	// Leitura dos setores no banco
    	$setores = $this->setor_model->get([
    		'organizacao_fk' => $this->session->user['id_organizacao']
    	]);

        //CSS para crud setores
    	$this->session->set_flashdata('css',[
    		0 => base_url('assets/css/modal_desativar.css'),
    		1 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.css'),
    		2 => base_url('assets/css/loading_input.css'),
    		3 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css')
    	]);

        //Scripts para crud setores
    	$this->session->set_flashdata('scripts',[
    		0 => base_url('assets/vendor/masks/jquery.mask.min.js'),
    		1 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.js'),
    		2 => base_url('assets/js/masks.js'),
    		3 => base_url('assets/vendor/bootstrap-multistep-form/jquery.easing.min.js'),
    		4 => base_url('assets/vendor/datatables/datatables.min.js'),
    		5 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js'),
    		6 => base_url('assets/js/utils.js'),
    		7 => base_url('assets/js/constants.js'),
    		8 => base_url('assets/js/jquery.noty.packaged.min.js'),
    		9 => base_url('assets/js/dashboard/setor/index.js'),
    		10 => base_url('assets/vendor/select-input/select-input.js')
    	]);

    	load_view([
    		0 => [
    			'src' => 'dashboard/administrador/setor/home',
    			'params' => ['setores' => $setores],
    		],
    	],'administrador');
    }


    /**
     * Função responsável pelo insert ou update de setor
     * 
     * @param setor_pk (para realizar update), setor_nome e senha (caso for superusuário)
     * @return Response 
     */
    public function insert_update()
    {
    	$this->load->library('form_validation');
    	$response = new Response();

        // Regras para o nome do setor
    	$this->form_validation->set_rules(
    		'setor_nome', 
    		'setor_nome',
    		'trim|required|min_length[3]|max_length[50]'
    	);

    	if($this->input->post('setor_pk') != '')
    	{
    		$this->form_validation->set_rules(
    			'setor_pk', 
    			'setor_pk', 
    			'trim|required|numeric|max_length[11]'
    		);
    	}

    	if($this->form_validation->run())
    	{
            // Pegando os dados da requisição POST
    		$dados['setor_nome'] = $this->input->post('setor_nome');

            // Se for passada a pk do setor é feito o update
    		if($this->input->post('setor_pk'))
    		{
    			$query = $this->setor_model->update($dados, 
    				$this->input->post('setor_pk'));

                // Caso houve um erro no update
    			if(!$query)
    			{
    				$response->set_code(Response::DB_ERROR_UPDATE);
    			}
    		}
    		else
    		{
                // Caso contrário, é feito o insert
    			$dados['organizacao_fk'] = $this->session->user['id_organizacao'];
    			$query = $this->setor_model->insert($dados);
    			$response->set_data(['id'=> $query]);

                // Caso houve um erro no insert
    			if(!$query)
    			{
    				$response->set_code(Response::DB_ERROR_INSERT);
    			}
    		}

            // Caso a query tenha tido sucesso
    		if($query)
    		{
    			$response->set_code(Response::SUCCESS);
    		}

    	}
    	else
    	{
            // Caso o form_validation->run falhe
    		$response->set_code(Response::BAD_REQUEST);
    		$response->set_data($this->form_validation->error_array());
    	}

    	$response->send();
    }

    /**
     * Função responsável por desativar um setor
     * 
     * @param setor_pk
     * @return Response 
     */
    public function deactivate()
    {
        $response = new Response();

        // Novo status da flag
        $dados['setor_status'] = 0;

        // Update da tabela, no departamento informado
        $query = $this->setor_model->update($dados, 
            $this->input->post('setor_pk'));

        if($query)
        {
            // Caso a query tenha sucesso
            $response->set_code(Response::SUCCESS);
            $response->set_data(['msg' => 'linhas afetadas: '.$query]);
        }
        else
        {
            // Caso falhe
            $response->set_code(Response::DB_ERROR_UPDATE);
        }

        $response->send();
    }


    public function activate()
    {
        $response = new Response();

        // Novo status da flag
        $dados['setor_status'] = 1;

        // Update da tabela, no departamento informado
        $query = $this->setor_model->update($dados, 
            $this->input->post('setor_pk'));

        if($query)
        {
            // Caso a query tenha sucesso
            $response->set_code(Response::SUCCESS);
        }
        else
        {
            // Caso falhe
            $response->set_code(Response::DB_ERROR_UPDATE);
        }

        $response->send();
    }
}


?>