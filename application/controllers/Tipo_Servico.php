<?php


if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH . "core/CRUD_Controller.php";
require_once APPPATH . "core/Response.php";

class Tipo_Servico extends CRUD_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Tipo_Servico_model', 'tipo_servico');
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
            ['ativo' => 1],
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
