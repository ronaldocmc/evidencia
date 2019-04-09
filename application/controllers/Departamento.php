<?php


if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH."core/Response.php";    
require_once APPPATH."core/CRUD_Controller.php";

class Departamento extends CRUD_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('departamento_model', 'departamento');
    }

    public function load()
    {
        $this->load->library('form_validation');

        $this->load->helper('exception');
    }
    
    public function save()
    {
        $response = new Response();

        try {
            $this->load();

            if ($this->is_superuser()) {
                $this->add_password_to_form_validation();
            }

            $_POST['organizacao_fk'] = $this->session->user['id_organizacao'];
            $this->departamento->fill();

            if ($this->input->post('departamento_pk') !== '') {
                $this->departamento->config_form_validation_primary_key();
            }
            $this->departamento->config_form_validation();
            $this->departamento->run_form_validation();

            $this->begin_transaction();

            if ($this->input->post('departamento_pk') !== '') {
                $this->update();
            } else {
                $response->set_data(['id' => $this->departamento->insert()]);
            }

            $this->end_transaction();

            $response->set_code(Response::SUCCESS);
            $response->send();

        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        }
    }

    public function get(){
        $response = new Response();

        $departamentos = $this->departamento->get_all(
            '*, departamentos.ativo as ativo',
            ['organizacao_fk' => $this->session->user['id_organizacao']],
            -1,
            -1
        );

        $response->add_data('self', $departamentos);

        $response->send();
    }

    public function get_dependents()
    {
        $response = new Response();

        $this->load->model('Tipo_Servico_model', 'tipo_servico');

        $response->add_data('dependences', $this->tipo_servico->get_dependents($this->input->post('departamento_pk')));
        $response->add_data('dependence_type', 'tipos de serviço');

        $response->send();
    }

    private function update()
    {
        $this->departamento->__set('departamento_pk', $this->input->post('departamento_pk'));
        $this->departamento->update();
    }

    public function deactivate()
    {
        try {
            $this->load();
            $this->departamento->config_form_validation_primary_key();
            $this->departamento->run_form_validation();
            $this->departamento->fill();

            $this->begin_transaction();
            $this->departamento->deactivate();
            $this->end_transaction();

            $response = new Response();
            $response->set_code(Response::SUCCESS);
            $response->set_message('Departamento desativado com sucesso!');
            $response->send();

        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        }
    }

    public function activate()
    {
        try {
            $this->load();
            $this->departamento->config_form_validation_primary_key();
            $this->departamento->run_form_validation();
            $this->departamento->fill();

            $this->begin_transaction();
            $this->departamento->activate();
            $this->end_transaction();

            $response = new Response();
            $response->set_code(Response::SUCCESS);
            $response->set_message('Departamento ativado com sucesso!');
            $response->send();

        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        }
    }
}
