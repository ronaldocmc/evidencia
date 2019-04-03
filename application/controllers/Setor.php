<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "core/Response.php";

require_once APPPATH."core/CRUD_Controller.php";

class Setor extends CRUD_Controller 
{

	function __construct() 
	{
		parent::__construct();
		$this->load->model('Setor_model', 'setor');
	}


    /**
     * Função responsável pela leitura dos setores existentes e o carregamento da view
     * 
     * @param null
     * @return null 
     */
    public function index() 
    {
    	// Leitura dos setores no banco
    	$setores = $this->setor->get_all(
            '*',
            ['organizacao_fk' => $this->session->user['id_organizacao']],
            -1,
            -1
        );

        //CSS para crud setores
    	$this->session->set_flashdata('css',[
    		0 => base_url('assets/css/modal_desativar.css'),
    		1 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.css'),
    		2 => base_url('assets/css/loading_input.css'),
    		3 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css'),
            4 => base_url('assets/css/user_guide.css')
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
    		10 => base_url('assets/vendor/select-input/select-input.js'),
            11 => base_url('assets/js/response_messages.js')
    	]);

    	load_view([
    		0 => [
    			'src' => 'dashboard/administrador/setor/home',
    			'params' => ['setores' => $setores],
    		],
    	],'administrador');
    }

    private function update()
    {
        $this->setor->__set('setor_pk', $this->input->post('setor_pk'));
        $this->setor->update();
    }

    public function deactivate()
    {
        try{
            $this->load();
            $this->setor->config_form_validation_primary_key();
            $this->setor->run_form_validation();
            $this->setor->fill();

            $this->begin_transaction();
            $this->setor->deactivate();
            $this->end_transaction();

            $response = new Response();
            $response->set_code(Response::SUCCESS);
            $response->set_message('Setor desativado com sucesso!');
            $response->send();

        } catch(MyException $e) {
            handle_my_exception($e);
        } catch(Exception $e) {
            handle_exception($e);
        }
    }

    public function activate()
    {
        try{
            $this->load();
            $this->setor->config_form_validation_primary_key();
            $this->setor->run_form_validation();
            $this->setor->fill();

            $this->begin_transaction();
            $this->setor->activate();
            $this->end_transaction();
            
            $response = new Response();
            $response->set_code(Response::SUCCESS);
            $response->set_message('Setor ativado com sucesso!');
            $response->send();

        } catch(MyException $e) {
            handle_my_exception($e);
        } catch(Exception $e) {
            handle_exception($e);
        }
    }

    public function save()
    {
        $response = new Response();

        try {
            $this->load();

            if ($this->is_superuser()) 
            {
                $this->add_password_to_form_validation();
            }

            $_POST['organizacao_fk'] = $this->session->user['id_organizacao'];
            $this->setor->fill();

            if($this->input->post('setor_pk') !== '')
            {
                $this->setor->config_form_validation_primary_key();
            }
            $this->setor->config_form_validation();
            $this->setor->run_form_validation();

            $this->begin_transaction();

            if($this->input->post('setor_pk') !== '')
            {
                $this->update();
            } 
            else 
            {
                $response->set_data(['id' => $this->setor->insert()]);
            }

            $this->end_transaction();

            $response->set_code(Response::SUCCESS);
            $response->send();

        } catch(MyException $e) {
            handle_my_exception($e);
        } catch(Exception $e) {
            handle_exception($e);
        }
    }

    public function load()
    {
        $this->load->library('form_validation');

        $this->load->helper('exception');        
    }
}
?>