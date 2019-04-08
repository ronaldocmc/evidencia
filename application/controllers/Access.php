
<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH . "core/MyException.php";

/**
 * Acess Class
 *
 * @package     Evidencia
 * @category    Controller
 * @author      Pedro Cerdeirinha & Matheus Palmeira & Darlan
 */
class Access extends CI_Controller
{
    /**
     * Variavel que representa a resposta do servidor ao usuário
     *
     * @var Response
     */
    public $response;
    public $authorization;

    //-------------------------------------------------------------------------------

    /**
     * Construtor da Classe
     * 
     * Chama o construtor da classe pai
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('America/Sao_Paulo');
        $this->response = new Response();
        $this->load->helper('exception');
    }

    public function index()
    {
        if ($this->session->user['id_user'] === null) {
            $this->load->view('access/header_html');
            $this->load->view('access/login', null, false);
            $this->load->view('access/pre_loader');
        } else {
            if ($this->session->user['id_organizacao'] === 'admin') {
                redirect('dashboard/superusuario');
            } else {
                redirect('dashboard/funcionario_administrador');
            }
        }
    }

    //--------------------------------------------------------------------------------
    /**
     * Método para efetuar o logout do sistema.
     *
     * @return void
     */
    public function quit()
    {
        log_message('monitoring', $this->session->user['email_user'] . ' from ' . $this->input->ip_address() . ' logged out');
        session_destroy();
        redirect(base_url());
    }

    private function load_login()
    {
        $this->load->helper('recaptcha');
        $this->load->helper('attempt');
        $this->load->model('tentativa_model');
        $this->load->library('form_validation');
        $this->load->model('Funcionario_model', 'funcionario');

        $this->set_rules_form_validation();
    }

    private function check_login_attempts()
    {
        if (ENVIRONMENT != 'testing') {

            $response = verify_attempt($this->input->ip_address());

            if ($response != true) {
                log_message('error', 'Número de tentativas excedidas para ' . $this->input->ip_address());
                throw new MyException('Número de tentativas excedidas. ' . $response, Response::FORBIDDEN);
            }
        }
    }

    private function is_superuser()
    {
        $login_array = explode('@', $this->input->post('login'));

        return ($login_array[1] == 'admin');
    }

    private function clear_login_attempts()
    {
        $this->tentativa_model->delete($this->input->ip_address());
    }

    private function check_permissions($user)
    {
        if (isset($response->funcao_pk) && ($response->funcao_pk != '4' && $response->funcao_pk != '5')) {
            throw new MyException('Você não tem autorização para acessar o sistema', Response::UNAUTHORIZED);
        }
    }

    private function set_session_superuser($superuser)
    {
        $userdata = [
            'id_user' => $superuser->superusuario_pk,
            'email_user' => null,
            'password_user' => $superuser->superusuario_senha,
            'id_organizacao' => 'admin',
            'name_user' => $superuser->superusuario_nome,
            'name_organizacao' => 'Superusuario',
            'is_superusuario' => true,
            'image_user_min' => base_url('/assets/img/default.png'),
            'image_user' => base_url('/assets/img/default.png'),
            'func_funcao' => null,
        ];

        $this->session->set_userdata('user', $userdata);
    }

    private function set_session($user)
    {
        $userdata =  [
            'id_user'           => $user->funcionario_pk,
            'email_user'        => $user->funcionario_login,
            'password_user'     => $user->funcionario_senha,
            'id_organizacao'    => $user->organizacao_pk,
            'name_user'         => $user->funcionario_nome,
            'name_organizacao'  => $user->organizacao_nome,
            'is_superusuario'   => FALSE,
            'image_user_min'    => $user->funcionario_caminho_foto !== null ? base_url($user->funcionario_caminho_foto) : base_url('assets/uploads/perfil_images/default.png'),
            'image_user'        => $user->funcionario_caminho_foto !== null ? base_url($user->funcionario_caminho_foto) : base_url('assets/uploads/perfil_images/default.png'),
            'func_funcao'       => $user->funcao_nome,
            'id_funcao'         => $user->funcao_pk
        ];

        $this->session->set_userdata('user', $userdata);
    }

    private function authenticate_superuser()
    {
        $this->load->model('Super_model', 'superuser');

        $this->superuser->__set('superusuario_login', $this->input->post('login'));
        $this->superuser->__set('superusuario_senha', hash(ALGORITHM_HASH, $this->input->post('password') . SALT));

        $superuser = $this->superuser->get_one_or_404('*');

        $this->set_session_superuser($superuser);

        $this->clear_login_attempts();
    }

    private function authenticate_user()
    {
        $this->funcionario->__set('funcionario_login', $this->input->post('login'));
        $this->funcionario->__set('funcionario_senha', hash(ALGORITHM_HASH, $this->input->post('password') . SALT));

        
        $worker = $this->funcionario->get_or_404();

        $this->check_permissions($worker);

        $this->set_session($worker);

        $this->clear_login_attempts();
    }

    //--------------------------------------------------------------------------------
    /**
     * Método que recebe as informações de usuário e valida-as e, caso seja valido
     * realiza o login e informa o sucesso, caso contrário informa as inconsistências
     *
     * @param       Input string g-recaptcha-response
     * @param       Input string login
     * @param       Input string password
     * @return      void
     */
    public function login()
    {
        try {

            $this->load_login();

            $this->check_login_attempts();

            $this->funcionario->run_form_validation();

            if ($this->is_superuser()) {
                $this->response->add_data('superusuario', 1);
                $this->authenticate_superuser();
            } else {
                $this->response->add_data('superusuario', 0);
                $this->authenticate_user();
            }

            $this->load->library('Authorization');
            $this->authorization = new Authorization();
            $this->authorization->load_all_permissions_in_memory();

            $this->response->set_code(Response::SUCCESS);
            $this->response->set_message('Login efetuado com sucesso');
            $this->response->send();
        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        }
    }

    private function set_rules_form_validation()
    {
        $this->form_validation->set_rules(
            'login',
            'Login',
            'trim|required|regex_match[/[a-zA-Z0-9_\-.+]+@[a-zA-Z0-9-]+/]|min_length[8]|max_length[128]'
        );

        $this->form_validation->set_rules(
            'password',
            'Senha',
            'trim|required|min_length[8]|max_length[128]'
        );
    }
}
