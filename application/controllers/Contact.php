
<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once dirname(__FILE__) . "/Response.php";

class Contact extends CI_Controller
{
    /**
     * Variavel que representa a resposta do servidor ao usuário
     *
     * @var Response
     */
    public $response;

    //-------------------------------------------------------------------------------

    /**
     * Construtor da Classe
     *
     * Chama o construtor da classe pai
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('America/Sao_Paulo');
        $this->response = new Response();
        $this->load->model('Log_model', 'log_model');
    }

    //--------------------------------------------------------------------------------

    /**
     * Método padrão da classe Contact
     *
     * @return void
     */
    public function index()
    {
        $this->load->view('contact/restore_login', null, false);
    }


    /**
     * Método chamado quando é necessário criar uma senha para um usuário por via do e-mail
     *
     * @return void
     */
    public function reset_password($token = '', $define = NULL)
    {
        if ($token !== '') 
        {
            $this->load->model('recuperacao_model');

            $restore = [
                'recuperacao_token' => $token,
                'recuperacao_tempo >' => date('Y-m-d H:i:s', strtotime('- 1 day', strtotime(date('Y-m-d H:i:s')))),
            ];

            $restore_fetch = $this->recuperacao_model->get($restore);

            if ($restore_fetch !== false)
            {
                $data = [
                    'token' => $token,
                ];

                $this->load->view('access/header_html', false);

                if ($define === NULL) 
                {
                    $this->load->view('contact/reset_password', $data, false);
                }
                else
                {
                    $this->load->view('contact/define_password', $data, false);
                }
            } 
            else
            {
                $this->response->set_code(Response::NOT_FOUND);
                $this->response->__set('message','Seu pedido expirou. Você já utilizou esse código de alteração de senha.');
                $this->load->view('errors/padrao/home',['response' => $this->response]);
            }
        } 
        else if ($token === '' && $this->session->userdata('id_user') !== null) 
        {
            $data = [
                'token' => '',
            ];
            $this->load->view('access/header_html', false);
            $this->load->view('contact/reset_password', $data, false);
        } 
        else 
        {
            $this->response->set_code(Response::NOT_FOUND);
            $this->response->__set('message','Seu pedido expirou. Você já utilizou esse código de alteração de senha.');
            $this->load->view('errors/padrao/home',['response' => $this->response]);
        }
    }


    /**
     * Método chamado pela view de definição de senha para login
     *
     * @return void
     */
    public function define_password($token)
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('new_password',
            'Senha Atual',
            'trim|required|min_length[8]|max_length[128]'
        );

        $this->form_validation->set_rules('new_password_repeat',
            'Repetir Senha',
            'trim|required|min_length[8]|max_length[128]|matches[new_password]'
        );

        $mensagem = '';

        if ($this->form_validation->run() === true) 
        {
            $this->load->model('contato_model');
            $this->load->model('recuperacao_model');
            $this->load->model('acesso_model');

            // De acordo com o token, pega a tupla da recuperação de senha
            $rec = $this->recuperacao_model->get([
                'recuperacao_token' => $token
            ]);

            if ($rec !== false) 
            {
                // Se existir, procura o contato da pessoa referenciada pela tupla da recuperação
                $contato = $this->contato_model->get([
                    'pessoa_fk' => $rec->pessoa_fk
                ]);

                if ($contato !== false) 
                {
                    // Se o contato existir, coloca o e-amil na tabela de acessos
                    $acesso['pessoa_fk'] = $rec->pessoa_fk;
                    $acesso['acesso_login'] = $contato->contato_email;
                    $acesso['acesso_senha'] = hash(ALGORITHM_HASH, $this->input->post('new_password') . SALT);

                    if ($this->acesso_model->insert($acesso) !== false) 
                    {
                        // Retira a tupla da tabela de recuperação
                        $this->recuperacao_model->delete([
                            'pessoa_fk' => $rec->pessoa_fk
                        ]);
                        
                        // Redireciona para a página inicial do site
                        redirect(base_url());
                    }
                }
                else
                {
                    $mensagem = 'Usuário não encontrado';
                }
            }
            else
            {
                $mensagem = 'Operação de definição de senha experida';
            }

        }
        else
        {
            $mensagem = 'Senha informada inválida';
        }

        $error = array('heading' => 'Erro.', 'message' => $mensagem);
        $this->load->view('errors/html/error_general.php', $error);
        
    }

    public function new_password($token = '')
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('new_password',
            'Senha Atual',
            'trim|required|min_length[8]|max_length[128]'
        );

        $this->form_validation->set_rules('new_password_repeat',
            'Repetir Senha',
            'trim|required|min_length[8]|max_length[128]|matches[new_password]'
        );

        if ($token === '' && $this->session->userdata('id_user') !== null) 
        {
            $this->form_validation->set_rules('old_password',
                'Senha Antiga',
                'required'
            );
        }
        
        if ($this->form_validation->run() === true) 
        {
            if ($token === '' && $this->session->userdata('id_user') !== null) 
            {
                $access = [
                    'pessoa_fk' => $this->session->userdata('id_user'),
                    'acesso_senha' => hash(ALGORITHM_HASH, $this->input->post('old_password') . SALT),
                ];
                $this->load->model('acesso_model');
                $access_fetch = $this->acesso_model->get($access);

                if ($access_fetch !== false) 
                {
                    $access = [
                        'acesso_senha' => hash(ALGORITHM_HASH, $this->input->post('new_password') . SALT),
                    ];

                    $where_access = [
                        'pessoa_fk' => $access_fetch->pessoa_fk,
                    ];

                    $this->acesso_model->update($access, $where_access);

                    // Cria o log
                    $this->log_model->insert([
                        'log_pessoa_fk' => $access_fetch->pessoa_fk,
                        'log_descricao' => 'Alterou sua senha por dentro do sistema'
                    ]);

                    redirect(base_url());
                } 
                else 
                {
                    $error = array('heading' => 'Senha inválida.', 'message' => 'A senha informada está incorreta. Não foi possível altera-la');
                    $this->load->view('errors/html/error_404', $error);
                }
            } 
            else 
            {
                $this->load->model('recuperacao_model');

                $restore = [
                    'recuperacao_token' => $token,
                    'recuperacao_tempo >' => date('Y-m-d H:i:s', strtotime('- 1 day', strtotime(date('Y-m-d H:i:s')))),
                ];

                $restore_fetch = $this->recuperacao_model->get($restore);

                if ($restore_fetch !== false) 
                {
                    $this->load->model('acesso_model');
                    $this->load->model('contato_model');
                    $this->load->model('tentativa_recuperacao_model','tentativa_model');

                    $contact = $this->contato_model->get($restore_fetch->pessoa_fk);

                    if($contact !== null)

                    $access = [
                        'acesso_senha' => hash(ALGORITHM_HASH, $this->input->post('new_password') . SALT),
                    ];

                    $where_access = [
                        'pessoa_fk' => $restore_fetch->pessoa_fk,
                    ];

                    $this->recuperacao_model->delete($restore_fetch->pessoa_fk);
                    $this->acesso_model->update($access, $where_access);
                    $this->tentativa_model->delete([
                        'tentativa_ip' => $this->input->ip_address(),
                        'tentativa_email' => $contact->contato_email
                    ]);

                    redirect(base_url());
                } 
                else 
                {
                    $error = array('heading' => 'Seu pedido expirou', 'message' => 'Você já utilizou esse código de alteração de senha.');
                    $this->load->view('errors/html/error_404', $error);
                }
            }
        } else {
            $error = array('heading' => 'Dados inválidos', 'message' => 'Senha de confirmação inválida..');
            $this->load->view('errors/html/error_404', $error);
        }
    }

    public function restore_password()
    {
        $this->load->library('form_validation');
        $this->load->library('send_email');
        $this->load->helper('recaptcha');
        $this->load->helper('attempt');

        // $captcha_response = get_captcha($this->input->post('g-recaptcha-response'));
        $captcha_response = true;

        $attempt_response = verify_attempt_restore($this->input->ip_address(),$this->input->post('email'));

        if ($captcha_response === true && $attempt_response === true) 
        {
            $this->form_validation->set_rules('email',
                'Email',
                'trim|required|valid_email|max_length[128]'
            );
            if ($this->form_validation->run() === true) 
            {
                $this->load->model('contato_model');

                $contact = [
                    'contato_email' => $this->input->post('email'),
                ];
                $contact_fetch = $this->contato_model->get($contact);

                if ($contact_fetch !== false) 
                {
                    $this->load->model('recuperacao_model');
                    $this->recuperacao_model->delete($contact_fetch->pessoa_fk);

                    $restore = [
                        'pessoa_fk' => $contact_fetch->pessoa_fk,
                        'recuperacao_token' => hash(ALGORITHM_HASH, date('Y/m/d H:i:s') . SALT . $contact_fetch->pessoa_fk),
                        'recuperacao_tempo' => date('Y/m/d H:i:s'),
                    ];
                    $this->recuperacao_model->insert($restore);

                    $attempt = [
                        'tentativa_ip' => $this->input->ip_address(),
                        'tentativa_tempo' => date('Y/m/d H:i:s'),
                        'tentativa_email' => $this->input->post('email')
                    ];
                    $this->tentativa_model->insert($attempt);

                    $this->send_email->send_email('email/restore_password', 'Recuperação de Senha - Evidencia', base_url() . 'contact/reset_password/' . $restore['recuperacao_token'], $contact_fetch->contato_email);

                    $this->response->set_code(Response::SUCCESS);
                    $this->response->set_message('Enviado com sucesso');

                    // Insere a ação no log
                    $this->log_model->insert([
                        'log_pessoa_fk' => $contact_fetch->pessoa_fk,
                        'log_descricao' => 'Acessou a opção de Esqueci minha senha'
                    ]);
                } 
                else 
                {
                    $this->response->set_code(Response::NOT_FOUND);
                    $this->response->set_message('O e-mail inserido não foi encontrado. Por favor, recupere a senha com o e-mail cadastrado no sistema.');
                }
            } 
            else 
            {
                $this->response->set_code(Response::BAD_REQUEST);
                $this->response->set_message(implode('<br>', $this->form_validation->errors_array()));
            }
        } 
        else 
        {
            if ($captcha_response !== true) 
            {
                $this->response->set_code(Response::UNAUTHORIZED);
                $this->response->set_message('Acesso negado. ' . $captcha_response);
            } 
            else 
            {
                $this->response->set_code(Response::FORBIDDEN);
                $this->response->set_message('Acesso bloqueado. ' . $attempt_response);
            }
        }
        $this->response->send();
    }

    //Função que cria um acesso para um novo usuário, ou seja, cria um token de primeiro acesso para recuperação de senha
    public function create_access()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules(
            'acesso_senha',
            'Senha',
            'trim|required|min_length[8]|max_length[128]'
        );

        $this->form_validation->set_rules(
            'acesso_login',
            'Login',
            'trim|required|min_length[3]|max_length[128]|is_unique[acessos.acesso_login]'
        );

        $this->form_validation->set_rules(
            'confirme-senha',
            'Senha2',
            'trim|required|min_length[8]|max_length[128]|matches[acesso_senha]'
        );

        if ($this->form_validation->run() === true) 
        {
            //Realizando o load do model recuperacao_mmodel
            $this->load->model('recuperacao_model', 'rmodel');

            $where = array(
                'recuperacao_token' => $this->input->post('token'),
            );

            //Chamando função get para realizar a operação e encontrar o usuário especifico do acesso
            $retorno = $this->rmodel->get($where);
            if ($retorno) //Se o token existir então inserimos o novo acesso
            {
                //Padronizando dados recebidos via post para inserção
                $data_insert = array(
                    'pessoa_fk' => $this->input->post('pessoa_fk'),
                    'acesso_login' => $this->input->post('acesso_login'),
                    'acesso_senha' => hash(ALGORITHM_HASH, $this->input->post('acesso_senha') . SALT),
                );

                if ($this->rmodel->insert_acesso($data_insert)) 
                {
                    $row = $this->rmodel->delete_token($where);

                    if ($row == 1) 
                    {
                        //Se a inserção foi bem sucedida então response é positiva e a remoção feita
                        $this->response->set_code(Response::SUCCESS);
                        $this->response->set_data("Novo acesso foi cadastrado com sucesso!");
                    } 
                    else 
                    {
                        $this->response->set_code(Response::DB_ERROR_INSERT);
                        $this->response->set_data("Usuário foi cadastrado no entanto o token não foi apagado");
                    }
                } 
                else 
                {
                    $this->response->set_code(Response::DB_ERROR_INSERT);
                    $this->response->set_data("Não foi possível inserir novo acesso do superusuário");
                }
            } 
            else 
            {
                $this->load->view('errors/html/error_404');
            }
        } 
        else //Se os formulários não foram preenchidos corretamente (form_validation)
        {
            $this->response->set_code(Response::BAD_REQUEST);
            $this->response->set_data($this->form_validation->errors_array());
        }

        $this->response->send();
    }

    //Função que cadastra uma nova senha e um login para um novo usuário que acessou o token de recuperação
    public function first_login($token)
    {
        $this->load->model('recuperacao_model', 'rmodel');
        $this->load->model('funcionario_model');
        $this->load->model('super_model');

        //Padronizando os dados para select
        $where = array(
            'recuperacao_token' => $token,
        );

        //Chamando função get para realizar a operação e encontrar o usuário especifico do acesso
        $retorno = $this->rmodel->get($where);

        
        if ($retorno) //Se o token existir então exibimos a view para preenchimento de senha e login
        {
            $super = $this->super_model->get([
                'super_usuarios.pessoa_fk' => $retorno->pessoa_fk,
                'super_usuarios.usuario_status' => 1
            ]);

            if ($super===FALSE)
            {
                $func = $this->funcionario_model->get([
                    'funcionarios.pessoa_fk' => $retorno->pessoa_fk,
                    'funcionarios.funcionario_status' => 1
                ]);

                $retorno->organizacao_fk = $func!==FALSE?$func[count($func)-1]->organizacao_fk:"admin";
            }
            else
            {
                $retorno->organizacao_fk = "admin";
            }

            $this->session->set_flashdata('css', array(
                0 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.css'),
                1 => base_url('assets/css/modal_desativar.css'),
            ));

            $this->session->set_flashdata('scripts', array(
                0 => base_url('assets/vendor/masks/jquery.mask.min.js'),
                1 => base_url('assets/vendor/bootstrap-multistep-form/jquery.easing.min.js'),
                2 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.js'),
                3 => base_url('assets/js/utils.js'),
                4 => base_url('assets/js/constants.js'),
                5 => base_url('assets/js/jquery.noty.packaged.min.js'),
                6 => base_url('assets/js/dashboard/first-login/index.js'),
            ));

            load_view([
                0 => [
                    'src' => 'dashboard/commons/first-login/home',
                    'params' => $retorno,
                ],
                1 => [
                    'src' => 'access/pre_loader',
                    'params' => null,
                ],
            ], 'administrador', false);
        } 
        else //Se não, carregamos a view de erro.
        {
            $this->load->view('errors/html/error_404');
        }
    }

}
