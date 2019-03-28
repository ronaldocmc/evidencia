<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once dirname(__FILE__) . "\Response.php";

require_once APPPATH . "core\CRUD_Controller.php";

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

    public function index()
    {

        $situacoes = $this->situacao_model->get_all(
            '*',
            ['situacoes.organizacao_fk' => $this->session->user['id_organizacao']],
            -1,
            -1
        );

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
            9 => base_url('assets/js/dashboard/situacao/index.js'),
            10 => base_url('assets/vendor/select-input/select-input.js'),
        ]);

        load_view([
            0 => [
                'src' => 'dashboard/administrador/situacao/home',
                'params' => ['situacoes' => $situacoes],
            ],
        ], 'administrador');
    }

    /**
     * Função responsável por criar ou editar uma situação
     *
     * @param Requisição POST com situacao_nome, situacao_descricao, situacao_foto_obrigatoria (bool)
     *           e situacao_pk (opcional)
     * @return Objeto Response
     */

    public function insert_update()
    {
        try {

            // if ($this->is_superuser()) {
            //     $this->add_password_to_form_validation();
            // }

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

    public function get_dependents()
    {
        $this->load->model('servico_model');

        $servicos = $this->servico_model->get_all(
            'servicos.servico_nome',
            ['servicos.situacao_padrao_fk' => $this->input->post('situacao_pk')],
            - 1,
            -1
        );
        
        if (!empty($servicos)) {
            
            $mensagem = "";

            foreach($servicos as $s){
                $mensagem = $mensagem. $s->servico_nome .", ";
            }

            throw new MyException($mensagem, Response::UNAUTHORIZED);
        }
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

            $this->situacao_model->__set('situacao_pk', $this->input->post('situacao_pk'));
            $this->get_dependents(); 
        
            $this->situacao_model->deactivate(); 

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
