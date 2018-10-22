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

class RelatorioWS extends MY_Controller
{

    /**
     * Objeto responsável por monstar a resposta da requisição
     *
     * @var Response
     */
    private $response;
    private $relatorio;

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
     * 
     * Método responsável por retornar as ordens de serviço do relatório (do dia) da qual o funcionário está vinculado.
     *
     * @param Objeto JSON com os dados para atualizacao
     */
    public function get()
    {
        $this->load->helper('attempt');
        $this->load->helper('token');
        $this->load->model('tentativa_model');
        $this->load->model('atualizacao_model');
        
        $header_obj = apache_request_headers();
            
        $attempt_result = verify_attempt($this->input->ip_address());

        if ($attempt_result === true) {
           
            $token_decodificado = json_decode(token_decrypt($header_obj['token']));
            $id_funcionario = $token_decodificado->id_funcionario;
            
            //get relatorio
            $ordens_servicos = $this->get_relatorio_do_dia($id_funcionario);

            if($ordens_servicos)
            {
                $this->response->set_code(Response::SUCCESS);
                $this->response->add_data('ordens_servicos',$ordens_servicos);
            }
            else
            {
                $this->response->set_code(Response::NOT_FOUND);
                $this->response->set_data('Relatório não encontrado. Verifique se realmente foi gerado seu relatório no dia de hoje.');
            }

            

        } else {
            $this->response->set_code(Response::FORBIDDEN);
            $this->response->set_data($attempt_result);
        }

        $this->response->send();
        $this->__destruct();
    }


    /**
    Retorna o relatório do dia do funcionário
    **/
    public function get_relatorio_do_dia($id_funcionario){
        $this->load->model('relatorio_model');

        //precisamos descobrir o id do relatório do dia:
        $relatorio = $this->relatorio_model->get_relatorio_id_do_dia($id_funcionario);
        
        //se o relatório existir:
        if($relatorio)
        {
            //vamos pegar todas as ordens de serviços que pertence a este relatório:

            // $ordens_servicos = $this->relatorio_model->get(['relatorio_fk' => $relatorio->relatorio_pk]);
            // return $ordens_servicos;

            return $this->get_ordens_relatorio($relatorio->relatorio_pk);
        }
        else
        {
            return false;
        }
    }


    public function get_ordens_relatorio($id_relatorio){
        $this->load->model('relatorio_model');

        $ordens_servicos = $this->relatorio_model->get(['relatorio_fk' => $id_relatorio]);

        return $ordens_servicos;
    }


}
