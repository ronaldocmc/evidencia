<?php

/**
 * AccessWS
 *
 * @package     application
 * @subpackage  core
 * @author      Pietro, Gustavo
 */

defined('BASEPATH') or exit('No direct script access allowed');

require_once dirname(__FILE__) . "\Response.php";
require_once APPPATH . "core\MY_Controller.php";

class AccessWS extends MY_Controller
{

    /**
     * Constante que guarda a duração de um token
     *
     * @var String
     */
    const DURACAO_TOKEN = '5 days';

    /**
     * Objeto responsável por monstar a resposta da requisição
     *
     * @var Response
     */
    private $response;

    /**
     * Array que guarda os dados da requisição json
     *
     * @var array
     */
    private $data_json;

    /**
     * Construtor da classe, responsável por setar a timezone
     * e chamar o construtor do pai
     */
    public function __construct()
    {
        date_default_timezone_set('America/Sao_Paulo');
        parent::__construct(null);
        exit();
    }

    /**
     * Destrutor da classe
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
        $this->load->library('form_validation');
        $this->load->model('Super_model', 'smodel');
        $this->load->model('Funcionario_model', 'fmodel');
        $this->load->model('tentativa_model');
        $this->load->model('Funcionario_setor_model', 'funcionario_setor_model');

        $today = date('Y-m-d H:i:s');
		
        $obj = json_decode(file_get_contents('php://input'));
		
        $attempt_result = verify_attempt($this->input->ip_address());
		
        $login = explode('@', $obj->login_user);
        $data['acessos.acesso_login'] = $obj->login_user;
        $data['acessos.acesso_senha'] = $obj->password_user;

        $this->form_validation->set_data($data);
        $this->form_validation->set_rules(
            'acessos.acesso_login',
            'Login',
            'trim|required|regex_match[/[a-zA-Z0-9_\-.+]+@[a-zA-Z0-9-]+/]|min_length[8]|max_length[128]');
        $this->form_validation->set_rules(
            'acessos.acesso_senha',
            'Senha',
            'trim|required|min_length[8]|max_length[128]');

        if ($this->form_validation->run()) {
            if ($attempt_result === true) {
                $data['acessos.acesso_login'] = $login[0];
                $data['acessos.acesso_senha'] = hash(ALGORITHM_HASH, $obj->password_user . SALT);

                if ($login[1] !== 'admin') {
                    $model = 'fmodel';
                    $data['funcionarios.organizacao_fk'] = $login[1];
                } else {
                    //Se for superusuário, não tem login no app
                    $this->response->set_code(Response::NOT_FOUND);
                    $this->response->set_data($this->data_json);
                    $this->response->send();
                    die();
                }
	
                $user = $this->$model->get_login_mobile($data);
                
                if($user->funcionario_status == 0){
                    $this->response->set_code(Response::UNAUTHORIZED);
                    $this->response->set_data(null);
                    $this->response->send();
                    die();
                }

                if ($user) {
                    // var_dump($user);die();
					// $data_token['id_pessoa'] = $user->pessoa_fk;
					$data_token['id_pessoa'] = $user->pessoa_fk;
                    $data_token['id_funcionario'] = $user->funcionario_pk;
					$data_token['id_empresa'] =  $user->organizacao_fk;
					$data_token['last_update'] = "01/01/2000";

                    $dados['token'] = generate_token($data_token);

                    
					$this->response->set_data($dados);
                    $this->tentativa_model->delete($this->input->ip_address());
                } else {
                    $this->response->set_code(Response::NOT_FOUND);
                    $attempt = [
                        'tentativa_ip' => $this->input->ip_address(),
                        'tentativa_tempo' => $today,
                    ];
                    $this->tentativa_model->insert($attempt);
                }
            } else {
                $this->response->set_code(Response::FORBIDDEN);
                $this->response->set_data($attempt_result);
            }
        } else {
            $this->response->set_code(Response::BAD_REQUEST);
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
            
            $new_token = verify_token($obj['token'], $this->response);
            
            if ($new_token) {
				$dados['token'] = $new_token;
                $this->response->set_data($dados);
                $this->tentativa_model->delete($this->input->ip_address());
            } else {
                $this->response->set_code(Response::UNAUTHORIZED);
            }
        } else {
            $this->response->set_code(Response::FORBIDDEN);
            $this->response->set_data($attempt_result);
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
        }
        $this->response->send();
        $this->__destruct();
    }

    // public function get()
    // {
    //     echo "Legal";
    //     die();
    //     if(verify_token())
    //     {
    //         if(is_get_request())
    //         {
    //             create_token();

    //             $id = $_GET['id_user'];

    //             if($id == "undefined"){
    //                 $this->data_json['users'] = $this->model->get();
    //             }else{
    //                 $this->data_json['users'] = $this->model->get($id);
    //             }

    //             $this->response->set_data($this->data_json);
    //         }
    //         else
    //         {
    //             $this->response->set_code(Response::INVALID_METHOD);
    //         }
    //     }
    //     $this->response->send();
    //     $this->__destruct();
    // }

    // public function insert()
    // {
    //     if(verify_token())
    //     {
    //         if(is_post_request())
    //         {
    //             create_token();

    //             $data['login_user'] = $_POST['login_user'];
    //             $data['password_user'] = $_POST['password_user'];

    //             if(!$this->model->insert($data))
    //             {
    //                 $this->response->set_code(Response::DB_ERROR_INSERT);
    //             }
    //         }
    //         else
    //         {
    //             $this->response->set_code(Response::INVALID_METHOD);
    //         }
    //     }
    //     $this->response->send();
    //     $this->__destruct();
    // }

    // public function update()
    // {
    //     if(verify_token())
    //     {
    //         if(is_post_request())
    //         {
    //             create_token();

    //             $data['login_user'] = $_POST['login_user'];
    //             $data['password_user'] = $_POST['password_user'];
    //             $id_user = $_POST['id_user'];

    //             if(!$this->model->update($data,$id_user))
    //             {
    //                 $this->response->set_code(Response::DB_ERROR_UPDATE);
    //             }
    //         }
    //         else
    //         {
    //             $this->response->set_code(Response::INVALID_METHOD);
    //         }
    //     }
    //     $this->response->send();
    //     $this->__destruct();
    // }

    // public function delete()
    // {
    //     if(verify_token())
    //     {
    //         if(is_delete_request())
    //         {
    //             create_token();

    //             $id_user = $_POST['id_user'];

    //             if(!$this->model->delete($id_user))
    //             {
    //                 $this->response->set_code(Response::DB_ERROR_DELETE);
    //             }
    //         }
    //         else
    //         {
    //             $this->response->set_code(Response::INVALID_METHOD);
    //         }
    //     }
    //     $this->response->send();
    //     $this->__destruct();
    // }
}
