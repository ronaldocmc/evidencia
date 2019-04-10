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

                if ($this->input->post('permissions') !== null) 
                {
                    $this->load->model('Permissao_model', 'permissao');
                    $this->insert_permissions($response->data['id']);
                }
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

        $this->load->model('Permissao_model', 'permissao');
        $this->permissao->delete_permissions($this->input->post('funcao_pk'));

        if ($this->input->post('permissions') !== null) 
        {
            $this->insert_permissions($this->input->post('funcao_pk'));
        }
    }

    public function get(){
        $response = new Response();

        $funcoes = $this->funcao->get_all(
            '*, funcoes.ativo as ativo',
            ['organizacao_fk' => $this->session->user['id_organizacao']],
            -1,
            -1
        );

        $response->add_data('self', $funcoes);

        $response->send();
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
            log_message('monitoring', 'USER '.$this->get_current_user().' Atempt to activate ' . $this->get_current_controller());
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
            log_message('monitoring', $this->get_current_controller().' activation failed');
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        }
    }

    public function get_all_permissions()
    {
        $response = new Response();

        try {
            if ($this->input->post('funcao') !== null) 
            {
                $permissions = $this->authorization->return_permissions($this->input->post('funcao'));
            }
            else
            {
                $permissions = $this->authorization->get_all_permissions();             
            }

            $response->add_data('permissions', $permissions);
            $response->send();
        
        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        }
    }

    private function insert_permissions($funcao_pk)
    {
        $permission_data = $this->create_permission_batch($funcao_pk, $this->input->post('permissions'));

        $this->permissao->insert_many($permission_data);
    }

    private function create_permission_batch($funcao_fk, $permissions)
    {
        $batch = [];

        foreach ($permissions as $p) 
        {
            $batch[] = [
                'funcao_fk' => $funcao_fk,
                'permissao_fk' => $p
            ];
        }

        return $batch;
    }

    public function remove_permissions($funcao_fk)
    {
        $this->permissao->delete_permissions($funcao_fk);
    }
}
