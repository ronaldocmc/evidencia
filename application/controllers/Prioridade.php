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
