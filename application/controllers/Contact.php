
<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH.'core/Response.php';

class Contact extends CI_Controller
{
    /**
     * Variavel que representa a resposta do servidor ao usuário.
     *
     * @var Response
     */
    public $response;
    public $kind_user;
    public $select;

    //-------------------------------------------------------------------------------

    /**
     * Construtor da Classe.
     *
     * Chama o construtor da classe pai
     */
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('America/Sao_Paulo');

        if ($this->is_superuser()) {
            $this->load->model('recuperacao_super_model', 'recuperacao_model');
            $this->load->model('super_model', 'worker_model');

            $this->kind_user = 'superusuario';
            $this->select = 'superusuario_pk as pk, superusuario_email as email';
        } else {
            $this->load->model('recuperacao_funcionario_model', 'recuperacao_model');
            $this->load->model('funcionario_model', 'worker_model');

            $this->kind_user = 'funcionario';
            $this->select = 'funcionario_pk as pk, funcionario_login as email';
        }

        $this->load->model('tentativa_recuperacao_model');
        $this->load->model('funcionario_model');

        $this->load->helper('recaptcha');
        $this->load->helper('attempt');
        $this->load->helper('exception');

        $this->load->library('send_email');
        $this->load->library('form_validation');

        $this->response = new Response();
    }

    /**
     * Método padrão da classe Contact.
     */
    public function index()
    {
        $this->load->view('contact/restore_login', null, false);
    }

    /**
     * Método chamado quando é necessário criar ou redefinir a senha para um usuário por via do e-mail.
     */
    private function verify_token($token)
    {
        $restore = [
            'recuperacao_token' => $token,
            'recuperacao_tempo >' => date('Y-m-d H:i:s', strtotime('- 1 day', strtotime(date('Y-m-d H:i:s')))),
        ];

        $restore_fetch = $this->recuperacao_model->get_all(
            $this->kind_user.'_fk as pk,
                recuperacao_token,
                recuperacao_tempo',
            $restore,
            -1,
            -1
        );

        if (!$restore_fetch) {
            throw new MyException('Seu pedido expirou. Você já utilizou esse código de alteração de senha.', Response::NOT_FOUND);
            // $this->load->view('errors/padrao/home',['response' => $this->response]);
        }

        return $restore_fetch;
    }

    public function reset_password($token = '', $define = null)
    {
        try {
            $this->verify_token($token);

            $data = [
                'token' => $token,
                'define' => $define,
            ];

            $this->load->view('dashboard/commons/head.php', false);
            $this->load->view('contact/define_password', $data, false);
            $this->load->view('dashboard/commons/footer.php', false);
        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        }
    }

    private function delete_attempts($worker)
    {
        $attempts = $this->tentativa_recuperacao_model->get_all(
            '*',
            [
                'tentativa_ip' => $this->input->ip_address(),
                'tentativa_email' => $worker->email,
            ],
            -1,
            -1
        );

        foreach ($attempts as $att) {
            $this->tentativa_recuperacao_model->__set('tentativa_ip', $att->tentativa_ip);
            $this->tentativa_recuperacao_model->__set('tentativa_email', $att->tentativa_email);

            $this->tentativa_recuperacao_model->delete();
        }
    }

    private function clear_attempts_and_token($worker)
    {
        $this->recuperacao_model->__set($this->kind_user.'_fk', $worker->pk);
        $this->recuperacao_model->__set('recuperacao_token', $token);

        $this->recuperacao_model->delete();
        $this->delete_attempts($worker);
    }

    private function save_new_password($worker)
    {
        $this->worker_model->__set('funcionario_pk', $worker->pk);
        $this->worker_model->__set('funcionario_senha', hash(ALGORITHM_HASH, $this->input->post('new_password').SALT));

        $this->begin_transaction();
        $this->worker_model->update();
        $this->clear_attempts_and_token($worker);
        $this->end_transaction();

        $this->response->set_code(Response::SUCCESS);
    }

    public function new_password($token)
    {
        $this->recuperacao_model->config_password_form_validation($token);
        $this->recuperacao_model->run_form_validation();
        $restore_fetch = $this->verify_token($token);
        $worker = $this->fetch_contact($restore_fetch[0]->pk);
        $this->save_new_password($worker[0]);
        redirect(base_url());
    }

    public function is_superuser()
    {
        if (isset($this->session->user['is_superusuario'])) {
            return $this->session->user['is_superusuario'];
        } else {

            if (strpos($this->input->post('email'), '@') !== false) {
                $login = explode('@', $this->input->post('email'));
                
                if ($login[1] === 'admin') {
                    return true;
                } else {
                    return false;
                }
            } else {
                throw new MyException("Insira um e-mail válido", 4);
                
            }
        }
    }

    private function fetch_contact_by_email()
    {
        $contact_fetch = $this->worker_model->get_all(
            $this->select,
            [$this->kind_user.'_login' => $this->input->post('email')],
            -1,
            -1
        );

        if (empty($contact_fetch)) {
            throw new MyException('O e-mail inserido não foi encontrado. Por favor, recupere a senha com o e-mail cadastrado no sistema.', Response::NOT_FOUND);
        }

        return $contact_fetch;
    }

    private function fetch_contact($pk)
    {
        $contact_fetch = $this->funcionario_model->get(
            $this->select,
            [$this->kind_user.'_pk' => $pk],
            -1,
            -1
        );

        if (empty($contact_fetch)) {
            throw new MyException('Usuário não foi encontrado no sistema.', Response::NOT_FOUND);
        }

        return $contact_fetch;
    }

    private function auth_restore()
    {
        $ip = $this->input->ip_address();
        $email = $this->input->post('email');

        if (!verify_attempt_restore($ip, $email)) {
            throw new MyException('Acesso bloqueado.', Response::FORBIDDEN);
        }
    }

    private function save($worker)
    {
        $this->recuperacao_model->__set($this->kind_user.'_fk', $worker->pk);
        $this->recuperacao_model->delete();

        $token = hash(ALGORITHM_HASH, date('Y/m/d H:i:s').SALT.$worker->pk);

        $this->recuperacao_model->__set($this->kind_user.'_fk', $worker->pk);
        $this->recuperacao_model->__set('recuperacao_token', $token);
        $this->recuperacao_model->__set('recuperacao_tempo', date('Y/m/d H:i:s'));
        $this->recuperacao_model->insert();

        $this->tentativa_recuperacao_model->__set('tentativa_ip', $this->input->ip_address());
        $this->tentativa_recuperacao_model->__set('tentativa_tempo', date('Y/m/d H:i:s'));
        $this->tentativa_recuperacao_model->__set('tentativa_email', $worker->email);
        $this->tentativa_recuperacao_model->insert();

        $status = $this->send_email->send_email('email/restore_password', 'Recuperação de Senha - Evidencia', base_url().'contact/reset_password/'.$token, $worker->email);
        $this->response->set_code($status);
        // $this->response->set_data('Enviado com sucesso!');
    }

    public function restore_password()
    {
        try {
            $this->auth_restore();

            $worker = $this->fetch_contact_by_email();

            $this->begin_transaction();
            $this->save($worker[0]);
            $this->end_transaction();

            $this->response->send();
        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        }
    }

    public function begin_transaction()
    {
        $this->db->trans_start();
    }

    public function end_transaction()
    {
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            if (is_array($this->db->error())) {
                throw new MyException('Erro ao realizar operação.<br>'.implode('<br>', $this->db->error()), Response::SERVER_FAIL);
            } else {
                throw new MyException('Erro ao realizar operação.<br>'.$this->db->error(), Response::SERVER_FAIL);
            }
        } else {
            $this->db->trans_commit();
        }
    }

    //Função que cadastra uma nova senha e um login para um novo usuário que acessou o token de recuperação
    public function first_login($token)
    {
        $this->load->model('recuperacao_super_model', 'rsmodel');
        $this->load->model('Super_model', 'super_model');

        //Padronizando os dados para select
        $where = array(
            'recuperacao_token' => $token,
        );

        //Chamando função get para realizar a operação e encontrar o usuário especifico do acesso
        $retorno = $this->rsmodel->get_one('*', $where);

        if ($retorno) { //Se o token existir então exibimos a view para preenchimento de senha e login
            $super = $this->super_model->get([
                'superusuario_pk' => $retorno->superusuario_fk,
                'ativo' => 0,
            ]);

            $retorno->organizacao_fk = "admin";
            $retorno->superusuario_login = $super->superusuario_login;

            $this->session->set_flashdata('css', array(
                0 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.css'),
                1 => base_url('assets/css/modal_desativar.css'),
            ));

            $this->session->set_flashdata('scripts', array(
                0 => base_url('assets/vendor/masks/jquery.mask.min.js'),
                1 => base_url('assets/vendor/bootstrap-multistep-form/jquery.easing.min.js'),
                2 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.js'),
                3 => base_url('assets/js/constants.js'),
                4 => base_url('assets/js/jquery.noty.packaged.min.js'),
                5 => base_url('assets/js/dashboard/first-login/index.js')
            ));

            load_view([
                0 => [
                    'src' => 'dashboard/commons/first-login/home',
                    'params' => $retorno
                ],
                1 => [
                    'src' => 'access/pre_loader',
                    'params' => null,
                ],
            ], 'administrador', false);
        } else { //Se não, carregamos a view de erro.
            $this->load->view('errors/html/error_404',
                [
                    'heading' => 'Não foi possível exibir a página',
                    'message' => 'Token expirado ou usário não encontrado.',
                ]
            );
        }
    }
}
