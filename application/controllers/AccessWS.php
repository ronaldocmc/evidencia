<?php

/**
 * AccessWS.
 *
 * @author      Pietro, Gustavo
 */
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH.'core/Response.php';
require_once APPPATH.'core/MY_Controller.php';

class AccessWS extends MY_Controller
{
    /**
     * Constante que guarda a duração de um token.
     *
     * @var string
     */
    const DURACAO_TOKEN = '5 days';

    /**
     * Objeto responsável por monstar a resposta da requisição.
     *
     * @var Response
     */
    private $response;

    /**
     * Array que guarda os dados da requisição json.
     *
     * @var array
     */
    private $data_json;

    /**
     * Construtor da classe, responsável por setar a timezone
     * e chamar o construtor do pai.
     */
    public function __construct()
    {
        date_default_timezone_set('America/Sao_Paulo');
        parent::__construct(new Response());
        exit();
    }

    /**
     * Destrutor da classe.
     */
    public function __destruct()
    {
    }

    public function index()
    {
    }

    /**
     * Método responsável por averiguar as informação recebidas pela requsição,
     * essas informação são o login_user e password_user, referentes à tentativa,
     * de login de um usuário. Ao logar o usuário, o sistema cria um token e um
     * tempo de vida para o mesmo.
     *
     * @param Objeto JSON com os dados de login do usuário
     */
    public function login()
    {
        $this->response = new Response();
        $this->load->helper('attempt');
        $this->load->helper('token');
        $this->load->helper('string');
        $this->load->library('form_validation');
        $this->load->library('Authorization');
        $this->load->model('Funcionario_model', 'funcionario_model');
        $this->load->model('Tentativa_model');

        $authorization = new Authorization();

        $today = date('Y-m-d H:i:s');

        $obj = json_decode(file_get_contents('php://input'));
        log_message('MONITORING', 'User: ['.$obj->login_user.'] trying to log in from '.$this->input->ip_address());

        $attempt_result = true;
        // $attempt_result = verify_attempt($this->input->ip_address());

        $login = explode('@', $obj->login_user);

        $data['funcionarios.funcionario_login'] = $obj->login_user;
        $data['funcionarios.funcionario_senha'] = hash(ALGORITHM_HASH, $obj->password_user.SALT);

        $this->form_validation->set_data($data);

        $this->form_validation->set_rules(
            'funcionarios.funcionario_login',
            'Login',
            'trim|required|regex_match[/[a-zA-Z0-9_\-.+]+@[a-zA-Z0-9-]+/]|min_length[8]|max_length[128]');

        $this->form_validation->set_rules(
            'funcionarios.funcionario_senha',
            'Senha',
            'trim|required|min_length[8]|max_length[128]');

        if ($this->form_validation->run()) {
            if ($attempt_result === true) {
                if ($login[1] === 'admin') {
                    //Se for superusuário, não tem login no app
                    $this->response->set_code(Response::NOT_FOUND);
                    $this->response->set_message('Esse tipo de usuário não tem acesso ao aplicativo');
                    $this->response->set_data($this->data_json);
                    $this->response->send();
                    die();
                }

                $user = $this->funcionario_model->get('*', $data);

                // var_dump($user);die();

                if ($user[0]) {
                    $user = $user[0];

                    if ($user->ativo == 0) {
                        $this->response->set_code(Response::UNAUTHORIZED);
                        $this->response->set_message('Usuário desativado');
                        $this->response->set_data(null);
                        $this->response->send();
                        die();
                    }

                    $data_token['id_pessoa'] = $user->funcionario_pk;
                    $data_token['id_funcionario'] = $user->funcionario_pk;
                    $data_token['id_empresa'] = $user->organizacao_fk;
                    $data_token['last_update'] = '01/01/2000';

                    $permissions = $authorization->return_permissions($user->funcao_fk);

                    $dados['token'] = generate_token($data_token);

                    $d = [];
                    $d['nome'] = $user->funcionario_nome;
                    $d['tipo'] = $user->funcao_nome;
                    $d['count'] = 10;
                    $d['permissions'] = $permissions;

                    $setores = $this->funcionario_model->get_setores(
                        ['funcionarios.funcionario_pk' => $user->funcionario_pk]
                    );

                    if (count($setores) > 0) {
                        $d['setores'] = get_string($setores, 'explode', ' ', 1);
                    } else {
                        $d['setores'] = '';
                    }

                    $dados['dados'] = $d;

                    $this->response->set_data($dados);
                    $this->tentativa_model->delete_ip($this->input->ip_address());
                } else {
                    $this->response->set_code(Response::NOT_FOUND);
                    $this->response->set_message('Usuário não encontrado');
                    $attempt = [
                        'tentativa_ip' => $this->input->ip_address(),
                        'tentativa_tempo' => $today,
                    ];
                    $this->tentativa_model->insert($attempt);
                }
            } else {
                $this->response->set_code(Response::FORBIDDEN);
                $this->response->set_message('Máximo de tentativas alcançado. '.$attempt_result);
            }
        } else {
            $this->response->set_code(Response::BAD_REQUEST);
            $this->response->set_message(implode('<br>', $this->form_validation->errors_array()));
        }

        $this->response->send();
        $this->__destruct();
    }

    /**
     * Método responsável efetuar o login de um usuário do app, após ele abrir o app
     * é enviada uma requisição mandando o token e o id do usuário.
     */
    public function login_token()
    {
        $this->response = new Response();
        $this->load->helper('attempt');
        $this->load->helper('token');
        $this->load->model('tentativa_model');

        $attempt_result = verify_attempt($this->input->ip_address());

        if ($attempt_result === true) {
            $obj = apache_request_headers();

            $new_token = verify_token($obj[TOKEN], $this->response);

            if ($new_token) {
                $dados['token'] = $new_token;
                $this->response->set_data($dados);
                $this->tentativa_model->delete_ip($this->input->ip_address());
            } else {
                $this->response->set_code(Response::UNAUTHORIZED);
                $this->response->set_message('Seção expirada');
            }
        } else {
            $this->response->set_code(Response::FORBIDDEN);
            $this->response->set_message($attempt_result);
        }
        $this->response->send();
        $this->__destruct();
    }

    /**
     * Método responsável por deslogar o usuário logado, destruindo o seu token
     * da tabela de token.
     */
    public function quit()
    {
        $this->load->model('token_model', 'modeltoken');
        $this->load->helper('token');
        $this->response = new Response();

        if (verify_token($this->data_json, $this->response)) {
            $obj = apache_request_headers();

            $this->data_json['pessoa_fk'] = $obj['access_id'];

            if (!$this->modeltoken->delete($this->data_json['pessoa_fk'])) {
                $this->data_json['pessoa_fk'] = null;
                $this->data_json['token'] = null;
                $this->data_json['timestamp'] = null;
            }
        } else {
            $this->response->set_data(Response::LOGOUT_ERROR);
            $this->response->set_message('Token inválido');
        }
        $this->response->send();
        $this->__destruct();
    }
}
