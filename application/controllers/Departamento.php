<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once(dirname(__FILE__)."/Response.php");    
require_once APPPATH."core/CRUD_Controller.php";

class Departamento extends CRUD_Controller {

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

    function index() 
    {
        $departamentos = $this->departamento->get_all(
            '*',
            ['organizacao_fk' => $this->session->user['id_organizacao']],
            -1,
            -1
        );

        //CSS para departamentos
        $this->session->set_flashdata('css',[
            0 => base_url('assets/css/modal_desativar.css'),
            1 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.css'),
            2 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css'),
            3 => base_url('assets/css/user_guide.css')
        ]);

        //CSS para departamentos
        $this->session->set_flashdata('scripts',[
            0 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.js'),
            1 => base_url('assets/vendor/bootstrap-multistep-form/jquery.easing.min.js'),
            2 => base_url('assets/vendor/datatables/datatables.min.js'),
            3 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js'),
            4 => base_url('assets/js/utils.js'),
            5 => base_url('assets/js/constants.js'),
            6 => base_url('assets/js/jquery.noty.packaged.min.js'),
            7 => base_url('assets/js/dashboard/departamento/index.js'),
            8 => base_url('assets/js/response_messages.js')
        ]);

        load_view([
            0 => [
                'src' => 'dashboard/administrador/departamento/home',
                'params' => ['departamentos' => $departamentos],
            ],
        ],'administrador');
        
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
            $this->departamento->fill();

            if($this->input->post('departamento_pk') !== '')
            {
                $this->departamento->config_form_validation_primary_key();
            }
            $this->departamento->config_form_validation();
            $this->departamento->run_form_validation();

            $this->begin_transaction();

            if($this->input->post('departamento_pk') !== '')
            {
                $this->update();
            } 
            else 
            {
                $response->set_data(['id' => $this->departamento->insert()]);
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

    // TODO: voltar para o CRUD_Controller
    public function begin_transaction()
    {
        $this->db->trans_start();
    }

    // TODO voltar para o CRUD CONTOLLER
    public function end_transaction()
    {
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            if(is_array($this->db->error())){
                throw new MyException('Erro ao realizar operação.<br>'.implode('<br>',$this->db->error()), Response::SERVER_FAIL);
            } else {
                throw new MyException('Erro ao realizar operação.<br>'.$this->db->error(), Response::SERVER_FAIL);
            }
        }
        else
        {
            $this->db->trans_commit();
        }
    }

    private function add_password_to_form_validation()
    {
        $this->form_validation->set_rules(
            'senha', 
            'senha', 
            'trim|required|min_length[8]'
        );
    }

    private function is_superuser()
    {
        return $this->session->user['is_superusuario'];
    }

    public function get_dependents()
    {
        // $this->load->model('Tipo_Servico_model', 'tipo_servico_model');
        // date_default_timezone_set('America/Sao_Paulo');

        // $response = new Response();
        // $response->set_use_success(false);

        // $departamento = $this->departamento_model->get($this->input->post('departamento_pk'));

        // if ($departamento === false) 
        // {
        //     $response->set_code(Response::NOT_FOUND);
        //     $response->set_message('Departamento não encontrado.');
        // } 
        // else 
        // { //se existe departamento:
        //     //se a departamento já foi desativada:
        //     if ($departamento[0]->ativo == 0) 
        //     {
        //         $response->set_code(Response::BAD_REQUEST);
        //         $response->set_message('Departamento desativado.');
        //     }
        //     else
        //     { // se a departamento está ativa: 
        //         $departamento_pk =  $this->input->post('departamento_pk');
                
        //         $tipos_servicos = $this->tipo_servico_model->get(['tipos_servicos.departamento_fk' => $departamento_pk]);                
                
        //         $response->set_code(Response::SUCCESS);
        //         $response->set_data($tipos_servicos);
        //     }
        // }

        $response = new Response();
        $response->set_code(Response::SUCCESS);
        $response->set_data([]);
        $response->send();
        return;
    }

    private function update()
    {
        $this->departamento->__set('departamento_pk', $this->input->post('departamento_pk'));
        $this->departamento->update();
    }

    public function deactivate()
    {
        try{
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

        } catch(MyException $e) {
            handle_my_exception($e);
        } catch(Exception $e) {
            handle_exception($e);
        }
    }
}

?>