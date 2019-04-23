<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH . "core/Response.php";

require_once APPPATH . "core/CRUD_Controller.php";

class Situacao extends CRUD_Controller
{
    private $response;

    public function __construct()
    {
        date_default_timezone_set('America/Sao_Paulo');

        parent::__construct();

        $this->load->model('situacao_model');
        $this->load->library('form_validation');
        $this->load->helper('exception');
        $this->response = new Response();

    }

    
    /**
     * Função responsável por criar ou editar uma situação
     *
     * @param Requisição POST com situacao_nome, situacao_descricao, situacao_foto_obrigatoria (bool)
     *           e situacao_pk (opcional)
     * @return Objeto Response
     */

    public function save()
    {
        try {
            
            $this->situacao_model->config_form_validation();
            $this->situacao_model->run_form_validation();

            $this->situacao_model->fill();
            $this->situacao_model->__set('organizacao_fk', $this->session->user['id_organizacao']);

            $this->begin_transaction();

            if ($this->input->post('situacao_pk') == "") {
                $situacao_pk = $this->situacao_model->insert();
                $this->response->set_data($situacao_pk);
            } else {
                $this->situacao_model->update();
            }

            $this->end_transaction();

            $this->response->set_code(Response::SUCCESS);
            $this->response->send();

        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        }
    }

    public function get(){
        $response = new Response();

        $data = $this->situacao_model->get_all(
            '*, situacoes.ativo as ativo',
            ['organizacao_fk' => $this->session->user['id_organizacao']],
            -1,
            -1
        );

        $response->add_data('self', $data);

        $response->send();
    }

    public function get_dependents()
    {
        $response = new Response();

        $this->load->model('Servico_model', 'servico');
        
        $response->add_data('dependences', $this->servico->get_dependents($this->input->post('situacao_pk')));
        $response->add_data('dependence_type', 'serviço');

        $response->send();
    }

    /**
     * Função responsável por desativar uma situação
     *
     * @param Requisição POST com a situacao_pk
     * @return Objeto Response
     */
    public function deactivate()
    {
        try{
            if ($this->is_superuser()) {
                $this->add_password_to_form_validation();
            }

            $this->situacao_model->run_form_validation(); 
            $this->situacao_model->fill();

            $this->begin_transaction();
            $this->load->model('Servico_model', 'servico');
            $this->situacao_model->deactivate($this->servico, 'get_dependents'); 
            $this->end_transaction();
            $this->response->set_code(Response :: SUCCESS);
            $this->response->send();

        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        }
        
    }

    /**
     * Função responsável por ativar uma situação
     *
     * @param Requisição POST com a situacao_pk
     * @return Objeto Response
     */
    public function activate()
    {
        try{

            if ($this->is_superuser()) {
                $this->add_password_to_form_validation();
            }

            $this->situacao_model->run_form_validation(); 

            $this->situacao_model->__set('situacao_pk', $this->input->post('situacao_pk'));
        
            $this->situacao_model->activate(); 

            $this->response->set_code(Response :: SUCCESS);
            $this->response->send();

        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        }
    }
}

