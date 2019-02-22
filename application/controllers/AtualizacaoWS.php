<?php

/**
 * AccessWS
 *
 * @package     application
 * @subpackage  core
 * @author      Pietro, Gustavo
 */

defined('BASEPATH') or exit('No direct script access allowed');

require_once dirname(__FILE__) . "/Response.php";
require_once APPPATH . "core/MY_Controller.php";

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

        $this->load->model('servico_model');
        $this->load->model('tipo_servico_model');
        $this->load->model('prioridade_model');
        $this->load->model('setor_model');

        $obj = json_decode(file_get_contents('php://input'));

        $now = date('Y-m-d H:i:s');
        $header_obj = apache_request_headers();

        $attempt_result = verify_attempt($this->input->ip_address());

        if ($attempt_result === true) {

            $token_decodificado = json_decode(token_decrypt($header_obj['Token']));
            // $token_decodificado->id_empresa
            // $token_decodificado->id_funcionario
            // $last_update = $token_decodificado->last_update;

            $atualizar['servico'] = $this->servico_model->get(
                "servicos.*",
                [
                'servicos.ativo' => 1,
                'situacoes.organizacao_fk' => $token_decodificado->id_empresa
                ]
            );

            
            $atualizar['tipo_servico'] = $this->tipo_servico_model->get(
                'tipos_servicos.*',
                [
                    'tipos_servicos.ativo' => 1,
                    "departamentos.organizacao_fk" => $token_decodificado->id_empresa
                ]
            );
            
            $atualizar['prioridade'] = $this->prioridade_model->get_all(
                '*',
                ["organizacao_fk" => $token_decodificado->id_empresa],
                -1,
                -1
            );

            
            $atualizar['setores'] = $this->setor_model->get_all(
                '*',
                [
                    "setores.ativo" => 1,
                    "organizacao_fk" => $token_decodificado->id_empresa
                ],
                -1,
                -1
            );


            $this->response->add_data('atualizacao', $atualizar);

            $data['id_pessoa'] = $token_decodificado->id_pessoa;
            $data['id_empresa'] = $token_decodificado->id_empresa;
            $data['last_update'] = $now;
            
            $token = generate_token($data);

            $this->response->add_data('token', $token);

        } else {
            $this->response->set_code(Response::FORBIDDEN);
            $this->response->set_message($attempt_result);
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

            $new_token = verify_token($obj['Token'], $this->response);

            if ($new_token) {
                $dados['token'] = $new_token;
                $this->response->set_data($dados);
                $this->tentativa_model->delete($this->input->ip_address());
            } else {
                $this->response->set_code(Response::UNAUTHORIZED);
                $this->response->set_message('Seção experida');
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
            $this->response->set_message('Erro ao sair');
        }
        $this->response->send();
        $this->__destruct();
    }
}
