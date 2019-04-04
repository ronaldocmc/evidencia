<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once dirname(__FILE__) . "/Historico_Prazo.php";
require_once APPPATH . "core\Response.php";
require_once APPPATH . "core/CRUD_Controller.php";
require_once APPPATH . "core/MyException.php";

class Prioridade extends CRUD_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Prioridade_model', 'prioridade');
    }

    public function index()
    {

        $this->session->set_flashdata('css', [
            0 => base_url('assets/css/modal_desativar.css'),
            1 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.css'),
            2 => base_url('assets/css/loading_input.css'),
            3 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css'),
        ]);

        $this->session->set_flashdata('scripts', [
            0 => base_url('assets/vendor/masks/jquery.mask.min.js'),
            1 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.js'),
            2 => base_url('assets/js/masks.js'),
            3 => base_url('assets/vendor/bootstrap-multistep-form/jquery.easing.min.js'),
            4 => base_url('assets/vendor/datatables/datatables.min.js'),
            5 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js'),
            6 => base_url('assets/js/utils.js'),
            7 => base_url('assets/js/constants.js'),
            8 => base_url('assets/js/jquery.noty.packaged.min.js'),
            9 => base_url('assets/js/dashboard/prioridade/index.js'),
        ]);

        load_view([
            0 => [
                'src' => 'dashboard/administrador/prioridade/home',
                'params' => null,
            ],
        ], 'administrador');
    }

    private function load()
    {
        $this->load->library('form_validation');
        $this->load->helper('exception_helper');
    }

    public function get()
    {
        $response = new Response();

        $prioridades = $this->prioridade->get_all(
            '*, prioridades.ativo as ativo',
            [
                'organizacao_fk' => $this->session->user['id_organizacao'],
            ],
            -1,
            -1
        );

        $response->add_data('self', $prioridades);

        $response->send();
    }

    public function save()
    {
        $this->load();
        $response = new Response();

        try {
            if ($this->is_superuser()) {
                $this->add_password_to_form_validation();
            }

            $_POST['organizacao_fk'] = $this->session->user['id_organizacao'];
            $this->prioridade->fill();

            if ($this->input->post('prioridade_pk') !== '') {
                $this->prioridade->config_form_validation_primary_key();
            }
            $this->prioridade->config_form_validation();
            $this->prioridade->run_form_validation();

            $this->begin_transaction();

            if ($this->input->post('prioridade_pk') !== '') {
                $this->prioridade->update();
            } else {
                $response->set_data(['id' => $this->prioridade->insert()]);
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

    public function get_dependents()
    {
        $this->load->model('Tipo_Servico_model', 'tipo_servico');
        $response = new Response();

        try {
            $tipos_servicos = $this->tipo_servico->get_all(
                'tipos_servicos.tipo_servico_nome as name',
                ['prioridade_padrao_fk' => $this->input->post('prioridade_pk')],
                -1,
                -1
            );

            $response->add_data('dependences', $tipos_servicos);
            $response->add_data('dependence_type', 'tipos de serviço');

            $response->send();
        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        }
    }

    public function deactivate()
    {
        try {
            $this->load();
            $this->load->model('Tipo_Servico_model', 'tipo_servico');

            $tipos_servicos = $this->tipo_servico->get_all(
                'tipos_servicos.tipo_servico_nome',
                ['prioridade_padrao_fk' => $this->input->post('prioridade_pk')],
                -1,
                -1
            );

            if (count($tipos_servicos) > 0) {
                throw new MyException("Ainda há tipos de serviços com essa prioridade como padrão", Response::BAD_REQUEST);

            }

            $this->prioridade->config_form_validation_primary_key();
            $this->prioridade->run_form_validation();
            $this->prioridade->fill();

            $this->begin_transaction();
            $this->prioridade->deactivate();
            $this->end_transaction();

            $response = new Response();
            $response->set_code(Response::SUCCESS);
            $response->set_message('Prioridade desativada com sucesso!');
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
            $this->prioridade->config_form_validation_primary_key();
            $this->prioridade->run_form_validation();
            $this->prioridade->fill();

            $this->begin_transaction();
            $this->prioridade->activate();
            $this->end_transaction();

            $response = new Response();
            $response->set_code(Response::SUCCESS);
            $response->set_message('Prioridade ativada com sucesso!');
            $response->send();

        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        }
    }
}
