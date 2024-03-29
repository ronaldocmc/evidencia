
<?php

/**
 * CRUD_Controller.
 *
 * @author      Pietro
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH.'core/Response.php';
require_once APPPATH.'core/MyException.php';
require_once APPPATH.'core/AuthorizationController.php';

class CRUD_Controller extends AuthorizationController
{
    private $ci;
    private $pseudo_session;
    private $is_web = false;
    private $authorization;

    public function __construct()
    {
        $this->ci = &get_instance();

        if ($this->ci === null) {
            $this->is_web = true;
            parent::__construct();

            $this->check_if_has_user();
        }
    }

    /**
     * Authorization methods.
     */
    private function check_if_has_user()
    {
        if ($this->session->has_userdata('user')) {
            // $this->set_pseudo_session();
            $this->verify_password_superuser();
            if (!$this->is_superuser()) {
                $this->check_permissions();
            }
        } else {
            redirect(base_url());
        }
    }

    private function check_permissions()
    {
        if (!$this->is_authorized()) {
            $this->return_forbidden_response();
        }
    }

    private function return_forbidden_response()
    {
        log_message('error', 'Attempt to access unauthorized area by ['.$this->session->user['email_user'].'] from address '.$this->input->ip_address());
        $response = new Response();

        $response->set_code(Response::FORBIDDEN);
        $response->set_data(['error' => 'Você não possui permissão para acessar esta área']);
        $response->send();
        die();
    }

    private function set_pseudo_session()
    {
        $this->pseudo_session['id_organizacao'] = $this->session->user['id_organizacao'];
        $this->pseudo_session['id_user'] = $this->session->user['id_user'];
    }

    /**
     * Esse método verifica apenas se é superusuário e
     * se o campo de senha ao realizar um dos métodos está correto.
     */
    private function verify_password_superuser()
    {
        if ($this->session->user['is_superusuario']) {
            $method = null;

            /*
             * URI Segment:
             * 0 - HOST - localhost
             * 1 - Controller - Funcionario
             * 2 - Method - activate
             */
            if ($this->uri->segment(2) !== null) {
                $method = $this->uri->segment(2);

                if ($this->method_authorization($method)) {
                    $this->authenticate_password();
                }
            }
        }
    }

    private function authenticate_password()
    {
        $response = new Response();

        // Validação da sua senha
        if (!authenticate_operation($this->input->post('senha'), $this->session->user['password_user'])) {
            // Caso a senha esteja incorreta
            $response->set_code(Response::UNAUTHORIZED);
            $response->set_data(['password_user' => 'Senha informada incorreta']);
            $response->send();
            die();
        }
    }

    private function method_authorization($method)
    {
        log_message('monitoring', strtoupper($method).' request on '.$this->get_current_controller());

        return $method == 'insert' ||
            $method == 'update' ||
            $method == 'activate' ||
            $method == 'deactivate' ||
            $method == 'insert_update' ||
            $method == 'save';
    }

    /**
     * Methods used in controller for CRUD operations.
     */
    public function add_password_to_form_validation()
    {
        $this->form_validation->set_rules(
            'senha',
            'senha',
            'trim|required|min_length[8]'
        );
    }

    public function begin_transaction()
    {
        $this->db->trans_start();
    }

    public function end_transaction()
    {
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            log_message('error', 'Erro ao realizar operação: ['.$this->db->error().']');
            if (is_array($this->db->error())) {
                throw new MyException('Erro ao realizar operação.<br>'.implode('<br>', $this->db->error()), Response::SERVER_FAIL);
            } else {
                throw new MyException('Erro ao realizar operação.<br>'.$this->db->error(), Response::SERVER_FAIL);
            }
        } else {
            $this->db->trans_commit();
        }
    }

    public function get_current_user()
    {
        return $this->session->user['email_user'];
    }
}
