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
        $this->load->model('funcionario_model');

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
                    'situacoes.organizacao_fk' => $token_decodificado->id_empresa,
                ]
            );

            $atualizar['tipo_servico'] = $this->tipo_servico_model->get(
                'tipos_servicos.*',
                [
                    'tipos_servicos.ativo' => 1,
                    "departamentos.organizacao_fk" => $token_decodificado->id_empresa,
                ]
            );

            $atualizar['prioridade'] = $this->prioridade_model->get_all(
                '*',
                ["organizacao_fk" => $token_decodificado->id_empresa],
                -1,
                -1
            );

            $atualizar['setores'] = $this->funcionario_model->get_setores(
                [
                    "funcionarios_setores.funcionario_fk" => $token_decodificado->id_funcionario,
                ]
            );

            $atualizar['prioridades'] = $this->prioridade_model->get_all(
                '*',
                [
                    "organizacao_fk" => $token_decodificado->id_empresa,
                ],
                -1,
                -1
            );

            $this->response->add_data('atualizacao', $atualizar);

        } else {
            $this->response->set_code(Response::FORBIDDEN);
            $this->response->set_message($attempt_result);
        }

        $this->response->send();
        $this->__destruct();
    }

}
