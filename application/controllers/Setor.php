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

    public function get(){
        $response = new Response();

        $setores = $this->setor->get_all(
            '*, setores.ativo as ativo',
            ['organizacao_fk' => $this->session->user['id_organizacao']],
            -1,
            -1
        );

        $response->add_data('self', $setores);

        $response->send();
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