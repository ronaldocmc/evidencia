
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

require_once dirname(__FILE__) . "/../controllers/Response.php";

class CRUD_Controller extends CI_Controller
{
    private $ci;
    private $pseudo_session;
    private $is_web = false;

    public function __construct()
    {

        if ($this->ci = &get_instance() === null) 
        {
            $this->is_web = true;
            parent::__construct();
            $this->ci = &get_instance();
            
            if ($this->session->has_userdata('user'))
            {
                $this->pseudo_session['id_organizacao'] = $this->session->user['id_organizacao'];
                $this->pseudo_session['id_user'] = $this->session->user['id_user'];
                $this->verify_user();
                $this->verify_authentication();
            }        
            else
            {
                redirect(base_url());
            }
        } 
        else 
        { 

            // PEGAR DO TOKEN E PASSAR PARA A PSEUDO_SESSION

            // $this->pseudo_session['id_organizacao'] = $this->session->user['id_organizacao'];
            // $this->pseudo_session['id_user'] = $this->session->user['id_user'];
        }

    }


    /**
    Este método verifica se o usuário tem permissão para acessar aquela view.
    **/
    private function verify_authentication()
    {

        if($this->session->permissions)
        {
            $this->verify_controller_exceptions($this->session->permissions['controller_exceptions']);

            $this->verify_method_exceptions($this->session->permissions['method_exceptions']);
        }else
        {
            $this->load_view_unauthorized();
        }

    }

    private function verify_controller_exceptions($controller_exceptions)
    {
        $this->load->model('Log_model','log_model');
        //este é o controller que ele está acessando atualmente 
        $current_controller = $this->uri->segment(1);
        foreach($controller_exceptions as $exception)
        {
            if($current_controller == $exception)
            {
                // Salva no log que o usuário tentou acessar um controlador que não tem permissão
                $this->log_model->insert([
                    'log_pessoa_fk' => $this->session->user['id_user'],
                    'log_descricao' => 'Tentou acessar controlador' . $exception
                ]);
                $this->load_view_unauthorized();
            }
        }
    }

    private function verify_method_exceptions($method_exceptions)
    {
        $this->load->model('Log_model','log_model');
        $current_controller = $this->uri->segment(1);

        $current_method = '';
        if (null !== $this->uri->segment(2)) 
        {
            $current_method = $this->uri->segment(2);
        }
       
        foreach($method_exceptions as $exception)
        {   
            if($current_controller == $exception['controller'] && $current_method == $exception['method'])
            {
                // Salva no log que o usuário tentou acessar um método que não tem permissão
                $this->log_model->insert([
                    'log_pessoa_fk' => $this->session->user['id_user'],
                    'log_descricao' => 'Tentou acessar ' . $exception['method'] . ' do controlador ' . $current_controller
                ]);
                $this->load_view_unauthorized();   
            }
        }

        // Salva no log que o usuário acesso o método do controlador
        if ($current_method !== null && $current_method !== "") 
        {
            if ($this->store_log($current_method))
            {
                $this->log_model->insert([
                    'log_pessoa_fk' => $this->session->user['id_user'],
                    'log_descricao' => 'Acessou ' . $current_method . ' do controlador ' . $current_controller
                ]);
            }
        }
    }

    private function load_view_unauthorized()
    {
        $response = new Response();

        $response->set_code(Response::UNAUTHORIZED);
        $response->set_data(['error' => 'Você não possui permissão para acessar esta área']);
        $data['response'] = $response;
        $this->load->view('errors/padrao/home', $data);
    }

    /**
    
    Esse método verifica apenas se é superusuário e se o campo de senha ao realizar um dos métodos está correto.

    **/
    private function verify_user()
    {
        if ($this->session->user['is_superusuario']) 
        {

            $method = null;

            if (null !== $this->uri->segment(2)) 
            {
                $method = $this->uri->segment(2);
            }

            if (
                $method == 'insert' ||
                $method == 'update' ||
                $method == 'activate' ||
                $method == 'deactivate' ||
                $method == 'insert_update') 
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

        }   
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
        if (
            $method == 'insert' ||
            $method == 'update' ||
            $method == 'activate' ||
            $method == 'deactivate' ||
            $method == 'insert_update')
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }

    }

}
