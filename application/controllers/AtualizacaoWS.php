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

class AtualizacaoWS extends MY_Controller
{

    /**
     * Objeto responsável por monstar a resposta da requisição
     *
     * @var Response
     */
    private $response;

    /**
     * Construtor da classe, responsável por setar a timezone
     * e chamar o construtor do pai
     */
    public function __construct()
    {
        date_default_timezone_set('America/Sao_Paulo');
        $this->response = new Response();
        parent::__construct($this->response);
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
     * Método responsável por averiguar a data da ultima atualizacao do usuário e enviar os dados para deixar
     * o aplicativo atualizado.
     *
     * @param Objeto JSON com os dados para atualizacao
     */
    public function get()
    {
        $this->load->helper('attempt');
        $this->load->helper('token');
        $this->load->model('tentativa_model');
        $this->load->model('atualizacao_model');

        $obj = json_decode(file_get_contents('php://input'));
        
        $now = date('Y-m-d H:i:s');
        $header_obj = apache_request_headers();
            
        $attempt_result = verify_attempt($this->input->ip_address());

        if ($attempt_result === true) {
           
            $token_decodificado = json_decode(token_decrypt($header_obj['token']));
            $last_update = $token_decodificado->last_update;

            $last_update = "01/01/2018";

            // var_dump($token_decodificado);die();

            // var_dump($last_update);die();;

            $empresa_fk = $token_decodificado->id_empresa;
            
            $atualizar = $this->atualizacao_model->get($empresa_fk, $last_update);
            // $atualizar = $this->atualizacao_model->get($empresa_fk, $last_update);
            
            $this->response->add_data('atualizacao',$atualizar);
            
            $data['id_pessoa'] = $token_decodificado->id_pessoa;
            $data['id_empresa'] = $token_decodificado->id_empresa;
            $data['last_update'] = $now;
            $token = generate_token($data);

            $this->response->add_data('token',$token);

        } else {
            $this->response->set_code(Response::FORBIDDEN);
            $this->response->set_data($attempt_result);
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
