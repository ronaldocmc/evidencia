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
            7 => base_url('assets/js/dashboard/departamento/index.js')
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

        if($this->session->user['is_superusuario'])
        {
            $this->form_validation->set_rules(
                'senha', 
                'senha', 
                'trim|required|min_length[8]'
            );
        }

        if($this->form_validation->run())
        {
            // Se for um superusuário
            if($this->session->user['is_superusuario'])
            {
                // Validação da sua senha
                if(!authenticate_operation($this->input->post('senha'),$this->session->user['password_user']))
                {
                    // Caso a senha esteja incorreta
                    $response->set_code(Response::UNAUTHORIZED);
                    $response->set_data(['senha' => 'Senha informada incorreta']);
                    $response->send();
                    return;
                }
            }

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
                }
            }

            // Caso a query tenha tido sucesso
            if($query)
            {
                $response->set_code(Response::SUCCESS);
            }

        }
        else
        {
            // Caso o form_validation->run falhe
            $response->set_code(Response::BAD_REQUEST);
            $response->set_data($this->form_validation->error_array());
        }

        $response->send();
    }


    public function get_dependents()
    {
        $this->load->model('Tipo_Servico_model', 'tipo_servico_model');
        date_default_timezone_set('America/Sao_Paulo');
        $response = new Response();

        $departamento = $this->departamento_model->get($this->input->post('departamento_pk'));

        if ($departamento === false) 
        {
            $response->set_code(Response::NOT_FOUND);
            $response->set_data(['erro' => 'Departamento não encontrado.']);
        } 
        else 
        { //se existe departamento:
            //se a departamento já foi desativada:
            if ($departamento[0]->ativo == 0) 
            {
                $response->set_code(Response::BAD_REQUEST);
                $response->set_data(['erro' => 'Departamento desativado.']);
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

        // Se for um superusuário
        if($this->session->user['is_superusuario'])
        {
            // Validação da sua senha
            if(!authenticate_operation($this->input->post('senha'),$this->session->user['password_user']))
            {
                // Caso a senha esteja incorreta
                $response->set_code(Response::UNAUTHORIZED);
                $response->set_data(['password_user' => 'Senha informada incorreta']);
                $response->send();
                return;
            }
        }

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
                $response->set_data(['msg' => 'linhas afetadas: '.$query]);
            }
            else
            {
            // Caso falhe
                $response->set_code(Response::DB_ERROR_UPDATE);
            }
        }
        else
        {
            $response->set_code(Response::BAD_REQUEST);
            $response->set_data(['erro' => 'Departamento ainda possui tipos de serviço vinculados.']);
        }


        $response->send();
    }


    public function activate()
    {
        $response = new Response();

        // Se for um superusuário
        if($this->session->user['is_superusuario'])
        {
            // Validação da sua senha
            if(!authenticate_operation($this->input->post('senha'),$this->session->user['password_user']))
            {
                // Caso a senha esteja incorreta
                $response->set_code(Response::UNAUTHORIZED);
                $response->set_data(['password_user' => 'Senha informada incorreta']);
                $response->send();
                return;
            }
        }

        // Novo status da flag
        $dados['ativo'] = 1;

        // Update da tabela, no departamento informado
        $query = $this->departamento_model->update($dados, 
            $this->input->post('departamento_pk'));

        if($query)
        {
            // Caso a query tenha sucesso
            $response->set_code(Response::SUCCESS);
        }
        else
        {
            // Caso falhe
            $response->set_code(Response::DB_ERROR_UPDATE);
        }

        $response->send();
    }
}

?>