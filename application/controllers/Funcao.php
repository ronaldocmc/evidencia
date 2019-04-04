<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH."core/Response.php";       
require_once APPPATH."core/CRUD_Controller.php";

class Funcao extends CRUD_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('funcao_model', 'funcao');
    }

    public function load()
    {
        $this->load->library('form_validation');

        $this->load->helper('exception');
    }

    public function index()
    {

        //CSS para funcaos
        $this->session->set_flashdata('css', [
            0 => base_url('assets/css/modal_desativar.css'),
            1 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.css'),
            2 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css'),
            3 => base_url('assets/css/user_guide.css'),
        ]);

        //CSS para funcaos
        $this->session->set_flashdata('scripts', [
            0 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.js'),
            1 => base_url('assets/vendor/bootstrap-multistep-form/jquery.easing.min.js'),
            2 => base_url('assets/vendor/datatables/datatables.min.js'),
            3 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js'),
            4 => base_url('assets/js/utils.js'),
            5 => base_url('assets/js/constants.js'),
            6 => base_url('assets/js/jquery.noty.packaged.min.js'),
            7 => base_url('assets/js/dashboard/funcao/index.js'),
            8 => base_url('assets/js/response_messages.js'),
        ]);

        load_view([
            0 => [
                'src' => 'dashboard/administrador/funcoes/home',
                'params' => null,
            ],
        ], 'administrador');

    }

    public function get()
    {
        $response = new Response();

        $funcoes = $this->funcao->get_all(
            '*',
            [
                'organizacao_fk' => $this->session->user['id_organizacao'],
            ],
            -1,
            -1
        );

        $response->add_data('self', $funcoes);

        $response->send();
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
            
            $this->funcao->fill();
            $this->funcao->__set('organizacao_fk', $this->session->user['id_organizacao']);

            if ($this->input->post('funcao_pk') !== '') {
                $this->funcao->config_form_validation_primary_key();
            }
            $this->funcao->config_form_validation();
            $this->funcao->run_form_validation();

            $this->begin_transaction();

            if ($this->input->post('funcao_pk') !== '') {
                $this->update();
            } else {
                $response->set_data(['id' => $this->funcao->insert()]);
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

    private function update()
    {
        $this->funcao->__set('funcao_pk', $this->input->post('funcao_pk'));
        $this->funcao->update();
    }

    public function get_dependents()
    {
        $response = new Response();

        $this->load->model('funcionario_model', 'funcionario');

        $funcionarios = $this->funcionario->get_all(
            'funcionario_nome as name',
            ['funcao_fk' => $this->input->post('funcao_pk')],
            -1,
            -1
        );

        $response->add_data('dependences', $funcionarios);
        $response->add_data('dependence_type', 'funcionario');

        $response->send();
    }

    private function check_if_has_dependents()
    {
        $this->load->model('funcionario_model', 'funcionario');

        $funcionarios = $this->funcionario->get_all(
            'funcionario_pk',
            ['funcao_fk' => $this->input->post('funcao_pk')],
            -1,
            -1
        );

        if (count($funcionarios) > 0) {
            throw new MyException('Há funcionários com esta função', Response::FORBIDDEN);
        }
    }

    public function deactivate()
    {
        try {
            $this->load();
            $this->funcao->config_form_validation_primary_key();
            $this->funcao->run_form_validation();
            $this->funcao->fill();

            $this->check_if_has_dependents();

            $this->begin_transaction();
            $this->funcao->deactivate();
            $this->end_transaction();

            $response = new Response();
            $response->set_code(Response::SUCCESS);
            $response->set_message('Função desativado com sucesso!');
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
            $this->funcao->config_form_validation_primary_key();
            $this->funcao->run_form_validation();
            $this->funcao->fill();

            $this->begin_transaction();
            $this->funcao->activate();
            $this->end_transaction();

            $response = new Response();
            $response->set_code(Response::SUCCESS);
            $response->set_message('Função ativado com sucesso!');
            $response->send();

        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        }
    }
}
