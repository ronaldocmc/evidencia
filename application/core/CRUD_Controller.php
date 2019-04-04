
<?php

/**
 * CRUD_Controller
 *
 * @package     application
 * @subpackage  core
 * @author      Pietro
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH."core/Response.php";
require_once APPPATH."core/MyException.php";

class CRUD_Controller extends CI_Controller
{
    private $ci;
    private $pseudo_session;
    private $is_web = false;
    private $authorization;

    public function __construct()
    {
        $this->ci = &get_instance();

        if($this->ci === NULL)
        {
            $this->is_web = true;
            parent::__construct();

            $this->check_if_has_user();
        }
    }

    private function check_if_has_user()
    {
        if ($this->session->has_userdata('user'))
        {
            $this->set_pseudo_session();
            $this->verify_password_superuser();
            if(!$this->is_superuser()){
                $this->check_permissions();
            }
        }        
        else
        {
            redirect(base_url());
        }
    }

    private function check_permissions()
    {
        $this->load->library('Authorization');

        $this->authorization = new Authorization();

        $authorized = $this->authorization->check_permission( 
            $this->get_current_controller(), 
            $this->get_current_method()
        );
        
        if(!$authorized)
        {
            $this->return_unauthorized_response();
        }
    }

    private function return_unauthorized_response()
    {
        $response = new Response();

        $response->set_code(Response::UNAUTHORIZED);
        $response->set_data(['password_user' => 'Senha informada incorreta']);
        $response->send();
        die();
    }

    private function set_pseudo_session()
    {
        $this->pseudo_session['id_organizacao'] = $this->session->user['id_organizacao'];
        $this->pseudo_session['id_user'] = $this->session->user['id_user'];
    }

    private function get_current_controller()
    {
        return strtolower($this->uri->segment(1));
    }

    private function get_current_method()
    {
        return strtolower($this->uri->segment(2));
    }
    
    // private function check_if_current_controller_is_allowed()
    // {
    //     $function = $this->session->user['func_funcao'];

    //     $current_controller = $this->get_current_controller();

    //     $controller_exceptions = $this->load_controller_exceptions($function);

    //     if(array_key_exists($current_controller, $controller_exceptions))
    //     {
    //         $this->load_view_unauthorized();
    //         return;
    //     }
    // }

    // private function check_if_current_method_is_allowed()
    // {
    //     $function = $this->session->user['func_funcao'];

    //     $current_controller = $this->get_current_controller();
    //     $current_method = $this->get_current_method();

    //     $controller_exceptions = $this->load_controller_exceptions($function);

    //     $method_exceptions = $this->load_method_exceptions($function);

    //     if(array_key_exists($current_controller, $controller_exceptions))
    //     {
    //         if(array_key_exists($current_method, $method_exceptions))
    //         {
    //             $this->load_view_unauthorized();
    //             return;
    //         }
    //     }

    // }

    // private function load_method_exceptions($function)
    // {
    //     if($function == 'Administrador'){
    //         return array(
    //             0 => [
    //             'controller' => 'organizacao',
    //             'methods'    => ['deactivate', 'activate', 'index']
    //             ]
    //         );
    //     }
        
    //     if($function == 'Atendente'){
    //         return array(
    //             0 => [
    //                 'controller' => 'dashboard',
    //                 'methods'    => ['superusuario']
    //             ],
    //             1 => [
    //                 'controller' => 'ordem_servico',
    //                 'methods'    => 'deactivate'
    //             ]
    //         );
    //     }
    // }


    // private function load_controller_exceptions($function)
    // {
    //     if($function == 'Administrador'){
    //         return array(
    //             0 => 'superusuario',
    //             // 0 => 'organizacao',
    //         );
    //     }

    //     if($function == 'Atendente'){
    //         return array(
    //             0 => 'organizacao',
    //             1 => 'superusuario',
    //             2 => 'departamento',
    //             3 => 'funcionario',
    //             4 => 'prioridade',
    //             5 => 'servico',
    //             6 => 'setor',
    //             7 => 'situacao',
    //             8 => 'tipo_servico'
    //         );
    //     }
    // }

    /**
     * Este método verifica se o usuário tem permissão para acessar aquela view.
     */
    // private function verify_permission()
    // {

    //     if($this->session->permissions)
    //     {
    //         $this->verify_controller_exceptions($this->session->permissions['controller_exceptions']);

    //         $this->verify_method_exceptions($this->session->permissions['method_exceptions']);
    //     }else
    //     {
    //         $this->load_view_unauthorized();
    //     }

    // }

    // private function verify_controller_exceptions($controller_exceptions)
    // {
    //     $this->load->model('Log_model','log_model');
    //     //este é o controller que ele está acessando atualmente 
    //     $current_controller = $this->uri->segment(1);
    //     foreach($controller_exceptions as $exception)
    //     {
    //         if($current_controller == $exception)
    //         {
    //             // Salva no log que o usuário tentou acessar um controlador que não tem permissão
    //             $this->log_model->insert([
    //                 'log_pessoa_fk' => $this->session->user['id_user'],
    //                 'log_descricao' => 'Tentou acessar controlador' . $exception
    //             ]);
    //             $this->load_view_unauthorized();
    //         }
    //     }
    // }

    // private function verify_method_exceptions($method_exceptions)
    // {
    //     $this->load->model('Log_model','log_model');
    //     $current_controller = $this->uri->segment(1);

    //     $current_method = '';
    //     if (null !== $this->uri->segment(2)) 
    //     {
    //         $current_method = $this->uri->segment(2);
    //     }
       
    //     foreach($method_exceptions as $exception)
    //     {   
    //         if($current_controller == $exception['controller'] && $current_method == $exception['method'])
    //         {
    //             // Salva no log que o usuário tentou acessar um método que não tem permissão
    //             $this->log_model->insert([
    //                 'log_pessoa_fk' => $this->session->user['id_user'],
    //                 'log_descricao' => 'Tentou acessar ' . $exception['method'] . ' do controlador ' . $current_controller
    //             ]);
    //             $this->load_view_unauthorized();   
    //         }
    //     }
    // }

    private function load_view_unauthorized()
    {
        $response = new Response();

        $response->set_code(Response::UNAUTHORIZED);
        $response->set_data(['error' => 'Você não possui permissão para acessar esta área']);
        $data['response'] = $response;
        $this->load->view('errors/padrao/home', $data);
    }

    /**
     * Esse método verifica apenas se é superusuário e 
     * se o campo de senha ao realizar um dos métodos está correto.
     */
    private function verify_password_superuser()
    {
        if ($this->session->user['is_superusuario']) 
        {
            $method = null;
            
            /**
             * URI Segment:
             * 0 - HOST - localhost
             * 1 - Controller - Funcionario
             * 2 - Method - activate
             */
            if ($this->uri->segment(2) !== null) 
            {
                $method = $this->uri->segment(2);
                
                if ($this->method_authorization($method)) 
                {
                    $this->authenticate_password();
                }
                
            }
        }   
    }


    private function authenticate_password()
    {
        $response = new Response();
                    
        // Validação da sua senha
        if (!authenticate_operation($this->input->post('senha'), $this->session->user['password_user'])) 
        {
            // Caso a senha esteja incorreta
            $response->set_code(Response::UNAUTHORIZED);
            $response->set_data(['password_user' => 'Senha informada incorreta']);
            $response->send();
            die();
        }
    }

    private function method_authorization($method)
    {
       return ($method == 'insert' ||
                $method == 'update' ||
                $method == 'activate' ||
                $method == 'deactivate' ||
                $method == 'insert_update');
    }

    private function send_response($response)
    {
        if($this->is_web)
        {
            $response->send();
            die();
        }
        return $response;
    }

    private function store_log($method)
    {
        if ($this->method_authorization($method))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }


    public function add_password_to_form_validation()
    {
        $this->form_validation->set_rules(
            'senha', 
            'senha', 
            'trim|required|min_length[8]'
        );
    }

    public function is_superuser()
    {
        return $this->session->user['is_superusuario'];
    }


    public function begin_transaction()
    {
        $this->db->trans_start();
    }


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
}