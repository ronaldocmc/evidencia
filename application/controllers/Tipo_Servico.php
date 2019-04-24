<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH . "core/CRUD_Controller.php";
require_once APPPATH . "core/Response.php";

class Tipo_Servico extends CRUD_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Tipo_Servico_model', 'tipo_servico');
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
            9 => base_url('assets/js/dashboard/tipo-servico/index.js'),
            10 => base_url('assets/vendor/select-input/select-input.js'),
        ]);

        $this->load->helper('form');

        load_view([
            0 => [
                'src' => 'dashboard/administrador/tipo-servico/home',
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
        $this->load->model('Prioridade_model', 'prioridade');
        $this->load->model('Departamento_model', 'departamento');

        $tipos_servicos = $this->tipo_servico->get(
            '*,tipos_servicos.ativo as ativo',
            [
                'departamentos.organizacao_fk' => $this->session->user['id_organizacao'],
            ]
        );

        $departamentos = $this->departamento->get_all(
            '*',
            [
                'organizacao_fk' => $this->session->user['id_organizacao'],
                'ativo' => 1,
            ],
            -1,
            -1
        );

        $prioridades = $this->prioridade->get_all(
            '*',
            [
                'organizacao_fk' => $this->session->user['id_organizacao'],
                'ativo' => 1,
            ],
            -1,
            -1
        );

        $response->add_data('self', $tipos_servicos);
        $response->add_data('prioridades', $prioridades);
        $response->add_data('departamentos', $departamentos);

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

            $this->tipo_servico->fill();

            if ($this->input->post('tipo_servico_pk') !== '') {
                $this->tipo_servico->config_form_validation_primary_key();
            }
            $this->tipo_servico->config_form_validation();
            $this->tipo_servico->run_form_validation();

            $this->begin_transaction();

            if ($this->input->post('tipo_servico_pk') !== '') {
                $this->tipo_servico->update();
            } else {
                $id = $this->tipo_servico->insert();

                $new = $this->tipo_servico->get(
                    '*,tipos_servicos.ativo as ativo',
                    [
                        'departamentos.organizacao_fk' => $this->session->user['id_organizacao'],
                        'tipos_servicos.tipo_servico_pk' => $id
                    ]
                );

                $response->add_data('id', $id);
                $response->add_data('new', $new[0]);
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
        $response = new Response();

        $this->load->model('Servico_model', 'servico');

        $servicos = $this->servico->get_all(
            'servicos.servico_nome as name',
            ['servicos.tipo_servico_fk' => $this->input->post('tipo_servico_pk')],
            -1,
            -1
        );

        $response->add_data('dependences', $servicos);
        $response->add_data('dependence_type', 'serviço');

        $response->send();
    }

    public function deactivate()
    {
        try {
            $this->load();
            $this->load->model('Servico_model', 'servico');

            $servico = $this->servico->get_all(
                'servicos.servico_nome',
                [
                    'servicos.tipo_servico_fk' => $this->input->post('tipo_servico_pk'),
                    'servicos.ativo' => 1,
                ],
                -1,
                -1
            );

            if (count($servico) > 0) {
                throw new MyException("Ainda há serviços ativos com esse tipo de serviço", Response::BAD_REQUEST);
            }

            $this->tipo_servico->config_form_validation_primary_key();
            $this->tipo_servico->run_form_validation();
            $this->tipo_servico->fill();

            $this->begin_transaction();
            $this->tipo_servico->deactivate();
            $this->end_transaction();

            $response = new Response();
            $response->set_code(Response::SUCCESS);
            $response->set_message('Tipo de Serviço desativado com sucesso!');
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
            $this->tipo_servico->config_form_validation_primary_key();
            $this->tipo_servico->run_form_validation();
            $this->tipo_servico->fill();

            $this->begin_transaction();
            $this->tipo_servico->activate();
            $this->end_transaction();

            $response = new Response();
            $response->set_code(Response::SUCCESS);
            $response->set_message('Tipo de Servio ativado com sucesso!');
            $response->send();

        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        }
    }
}
