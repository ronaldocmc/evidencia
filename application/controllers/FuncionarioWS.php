<?php

/**
 * AccessWS
 *
 * @package     application
 * @subpackage  core
 * @author      Pietro
 */

defined('BASEPATH') or exit('No direct script access allowed');

require_once dirname(__FILE__) . "/Response.php";
require_once APPPATH . "core/MY_Controller.php";

class FuncionarioWS extends MY_Controller
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

    public function put()
    {
        $this->load->model('Funcionario_model', 'funcionario');

        $this->load->helper('exception');
        $this->load->helper('token_helper');
        $this->load->helper('insert_images');
        $this->load->library('form_validation');

        try {

            $obj = json_decode(file_get_contents('php://input'));
            $headers = apache_request_headers();
            $token_decodificado = json_decode(token_decrypt($headers['token']));

            $_POST = get_object_vars($obj);
            $_POST['img'] = isset($obj->img) ? $obj->img : null;

            // var_dump($obj);die();

            $path = upload_img(
                [
                    'id' => $token_decodificado->id_funcionario,
                    'path' => 'PATH_FUNC',
                    'is_os' => false,
                ],
                [0 => $this->input->post('img')]
            );

            $this->funcionario->__set("funcionario_pk",$token_decodificado->id_funcionario);
            $this->funcionario->__set("funcionario_nome",$_POST['funcionario_nome']);
            $this->funcionario->__set("funcionario_caminho_foto",$path[0]);

            $this->begin_transaction();

            $this->funcionario->update();

            $this->end_transaction();

      

            $data['id_pessoa'] = $token_decodificado->id_pessoa;
            $data['id_funcionario'] = $token_decodificado->id_funcionario;
            $data['id_empresa'] = $token_decodificado->id_empresa;
            $data['last_update'] = $token_decodificado->last_update;

            $token = generate_token($data);

            $this->response->add_data('token', $token);

            $this->response->set_code(Response::SUCCESS);
            $this->response->send();

        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        }
    }

}
