<?php 

/**
 * Departamento
 *
 * @package     application
 * @subpackage  controllers
 * @author      Gustavo, Pedro
 */


if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once(dirname(__FILE__)."/Response.php");    
require_once APPPATH."core/CRUD_Controller.php";

class Departamento extends CRUD_Controller {

    function __construct() 
    {
        parent::__construct();
        $this->load->model('departamento_model');
    }

    function index() 
    {
        $departamentos = $this->departamento_model->get([
            'organizacao_fk' => $this->session->user['id_organizacao']
        ]);

        //CSS para departamentos
        $this->session->set_flashdata('css',[
            0 => base_url('assets/css/modal_desativar.css'),
            1 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.css'),
            2 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css')
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


    public function insert_update()
    {
        $this->load->library('form_validation');
        $response = new Response();

        // Regras para o nome do departamento
        $this->form_validation->set_rules(
            'nome', 
            'nome',
            'trim|required|min_length[3]|max_length[50]'
        );

        if($this->form_validation->run())
        {
            // Pegando os dados da requisição POST
            $dados['departamento_nome'] = $this->input->post('nome');

            // Se for passada a fk do departamento é feito o update
            if($this->input->post('departamento_pk'))
            {
                $query = $this->departamento_model->update($dados, 
                    $this->input->post('departamento_pk'));

                // Caso houve um erro no update
                if(!$query)
                {
                    $response->set_code(Response::DB_ERROR_UPDATE);
                    $response->set_message('Erro ao atualizar dados do departamento');
                }
            }
            else
            {
                // Caso contrário, é feito o insert
                $dados['organizacao_fk'] = $this->session->user['id_organizacao'];
                $query = $this->departamento_model->insert($dados);

                $response->set_data(['id'=> $query]);

                // Caso houve um erro no insert
                if(!$query)
                {
                    $response->set_code(Response::DB_ERROR_INSERT);
                    $response->set_data('Erro ao inserir dados do departamento');
                }
            }

            // Caso a query tenha tido sucesso
            if($query)
            {
                $response->set_code(Response::SUCCESS);
                $response->set_message('Operação realizada com sucesso');
            }

        }
        else
        {
            // Caso o form_validation->run falhe
            $response->set_code(Response::BAD_REQUEST);
            $response->set_message(implode('<br>', $this->form_validation->error_array()));
        }

        $response->send();
    }


    public function get_dependents()
    {
        $this->load->model('Tipo_Servico_model', 'tipo_servico_model');
        date_default_timezone_set('America/Sao_Paulo');

        $response = new Response();
        $response->set_use_success(false);

        $departamento = $this->departamento_model->get($this->input->post('departamento_pk'));

        if ($departamento === false) 
        {
            $response->set_code(Response::NOT_FOUND);
            $response->set_message('Departamento não encontrado.');
        } 
        else 
        { //se existe departamento:
            //se a departamento já foi desativada:
            if ($departamento[0]->ativo == 0) 
            {
                $response->set_code(Response::BAD_REQUEST);
                $response->set_message('Departamento desativado.');
            }
            else
            { // se a departamento está ativa: 
                $departamento_pk =  $this->input->post('departamento_pk');
                
                $tipos_servicos = $this->tipo_servico_model->get(['tipos_servicos.departamento_fk' => $departamento_pk]);                
                
                $response->set_code(Response::SUCCESS);
                $response->set_data($tipos_servicos);
            }
        }

        $response->send();
        return;
    }


    public function deactivate()
    {   
        $this->load->model('Tipo_Servico_model', 'tipo_servico_model');
        $response = new Response();

        $departamento_pk =  $this->input->post('departamento_pk');

        $tipos_servicos = $this->tipo_servico_model->get(['tipos_servicos.departamento_fk' => $departamento_pk]);

        if($tipos_servicos == false)
        {
             // Novo status da flag
            $dados['ativo'] = 0;

            // Update da tabela, no departamento informado
            $query = $this->departamento_model->update($dados, 
                $this->input->post('departamento_pk'));

            if($query)
            {
            // Caso a query tenha sucesso
                $response->set_code(Response::SUCCESS);
                $response->set_message('Departamento desativado com sucesso');
            }
            else
            {
            // Caso falhe
                $response->set_code(Response::DB_ERROR_UPDATE);
                $response->set_message('Erro ao desativar departamento');
            }
        }
        else
        {
            $response->set_code(Response::BAD_REQUEST);
            $response->set_message('Departamento ainda possui tipos de serviço vinculados.');
        }


        $response->send();
    }


    public function activate()
    {
        $response = new Response();

        // Novo status da flag
        $dados['ativo'] = 1;

        // Update da tabela, no departamento informado
        $query = $this->departamento_model->update($dados, 
            $this->input->post('departamento_pk'));

        if($query)
        {
            // Caso a query tenha sucesso
            $response->set_code(Response::SUCCESS);
            $response->set_message('Departamento ativado com sucesso');
        }
        else
        {
            // Caso falhe
            $response->set_code(Response::DB_ERROR_UPDATE);
            $response->set_message('Erro ao desativar departamento');
        }

        $response->send();
    }
}

?>