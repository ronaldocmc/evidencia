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
            
            //$this->update_relatorio($id_funcionario);

            //get relatorio
            $ordens_servicos = $this->get_relatorio_do_funcionario($id_funcionario);

            if($ordens_servicos)
            {
                $this->response->set_code(Response::SUCCESS);
                $this->response->add_data('ordens_servicos',$ordens_servicos);
            }
            else
            {
                $this->response->set_code(Response::NOT_FOUND);
                $this->response->set_data('Relatório não encontrado. Verifique se realmente existe um relatório em aberto para você.');
            }

            

        } else {
            $this->response->set_code(Response::FORBIDDEN);
            $this->response->set_data($attempt_result);
        }

        $this->response->send();
        $this->__destruct();
    }

    /**
    * FINALIZAR RELATÓRIO
    **/
    public function put()
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
            
            $this->update_relatorio($id_funcionario); 
            //se deu certo vai enviar o response dentro desse método            

        } else {
            $this->response->set_code(Response::FORBIDDEN);
            $this->response->set_data($attempt_result);
        }

        $this->response->send();
        $this->__destruct();
    }

    /**
    * Neste método, vamos receber os relatórios que estão em aberto do funcionário
    * e verificar se o último histórico das ordens de serviço daquele relatório estão 
    * diferente de "Em Andamento".
    * Caso esteja, o status do relatório vai para 1, isto é, o relatório foi concluído.
    * O status estar em 1 não significa que o relatório foi concluído com sucesso, 
    * significa que não é mais o relatório corrente (em execução).
    * Caso haja uma ordem com status "Em Andamento", vamos dar a mensagem que aquele 
    * relatório ainda não foi concluído.
    **/
    public function update_relatorio($id_funcionario){
        $this->load->model('relatorio_model');
        $this->load->model('historico_model');

        //Pegamos os relatórios em aberto:
        $relatorios_em_aberto = $this->relatorio_model->get_relatorios(['status' => 0, 'funcionario_fk' => $id_funcionario]);

        if(count($relatorios_em_aberto) > 0){
            //Percorremos todos os relatórios
            foreach($relatorios_em_aberto as $relatorio){
                //pegando as ordens do relatório:
                $ordens_relatorio = $this->get_ordens_relatorio($relatorio->relatorio_pk);
                //se existir ordens:
                if(count($ordens_relatorio) > 0){
                    //percorremos as ordens:
                    foreach($ordens_relatorio as $os){
                        //pegamos o último histórico (IMPORTANTE: O ULTIMO HISTÓRICO É O ID DA SITUACAO!!!):
                        $os->ultimo_historico = $this->historico_model->get_last_historico($os->ordem_servico_pk);
                       
                        if($os->ultimo_historico == 2){ //SE O STATUS FOR EM ANDAMENTO:
                            $this->response = new Response();
                            $this->response->set_code(Response::BAD_REQUEST);
                            $this->response->set_data('O relatório corrente ainda não foi concluído.');
                            $this->response->send();
                            die();
                        }
                    }
                }
                //se chegou aqui é porque não achou nenhuma ordem com status em andamento, então podemos setar o status do relatório para 1:
                $this->relatorio_model->update(['status' => 1], ['relatorio_pk' => $relatorio->relatorio_pk]);
                $this->response->set_code(Response::SUCCESS);
                $this->response->set_data('Situação do relatório atualizado com sucesso.');
                $this->response->send();
            }

        }else {
            $this->response = new Response();
            $this->response->set_code(Response::NOT_FOUND);
            $this->response->set_data('Não encontramos nenhum relatório em andamento para você.');
            $this->response->send();
            die();
        }
    }


    /**
    Retorna o relatório que está em aberto do funcionário
    **/
    private function get_relatorio_do_funcionario($id_funcionario){
        $this->load->model('relatorio_model');

        //precisamos descobrir o id do relatório:
        $relatorio = $this->relatorio_model->get_relatorio_do_funcionario($id_funcionario);
        
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


    private function get_ordens_relatorio($id_relatorio){
        $this->load->model('relatorio_model');
        $this->load->model('Historico_model', 'historico_model');

        $ordens_servicos = $this->relatorio_model->get(['relatorio_fk' => $id_relatorio]);

        foreach($ordens_servicos as $os){
            $id_ultimo_historico = $this->historico_model->get_id_last_historico($os->ordem_servico_pk);
            $os->imagem_situacao_caminho = $this->historico_model->get_imagem($id_ultimo_historico);
        }

        return $ordens_servicos;
    }


}
