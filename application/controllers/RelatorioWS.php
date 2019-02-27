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
        $this->load->model('Relatorio_model', 'relatorio');
        $this->load->helper('exception');

        try {

            $obj = json_decode(file_get_contents('php://input'));
            $headers = apache_request_headers();
            $token_decodificado = json_decode(token_decrypt($headers['token']));

            $where['relatorios.relatorio_func_responsavel'] = $token_decodificado->id_funcionario;
            $where['relatorios.ativo'] = 1;

            $relatorio = $this->relatorio->get_one(
                '*',
                $where,
                -1,
                -1
            );

            if ($relatorio == null){
                throw new MyException('Nenhum relatório ativo foi encontrado!', Response::NOT_FOUND);
            }

            $ordens_servicos = $this->relatorio->get_all(
                'ordens_servicos.ordem_servico_pk, ordens_servicos.ordem_servico_cod, ordens_servicos.ordem_servico_desc, ordens_servicos.ordem_servico_comentario, ordens_servicos.ordem_servico_criacao,
                funcionarios.funcionario_nome, funcionarios.funcionario_caminho_foto,
                localizacoes.localizacao_lat, localizacoes.localizacao_long, localizacoes.localizacao_rua, localizacoes.localizacao_num,
                prioridades.prioridade_nome,
                procedencias.procedencia_nome,
                servicos.servico_nome',
                $where,
                -1,
                -1,
                [
                    ['table' => 'relatorios_os', 'on' => 'relatorios_os.relatorio_fk = relatorios.relatorio_pk'],
                    ['table' => 'ordens_servicos', 'on' => 'relatorios_os.os_fk = ordens_servicos.ordem_servico_pk'],
                    ['table' => 'localizacoes', 'on' => 'ordens_servicos.localizacao_fk = localizacoes.localizacao_pk'],
                    ['table' => 'funcionarios', 'on' => 'ordens_servicos.funcionario_fk = funcionarios.funcionario_pk'],
                    ['table' => 'prioridades', 'on' => 'ordens_servicos.prioridade_fk = prioridades.prioridade_pk'],
                    ['table' => 'procedencias', 'on' => 'ordens_servicos.procedencia_fk = procedencias.procedencia_pk'],
                    ['table' => 'servicos', 'on' => 'ordens_servicos.servico_fk = servicos.servico_pk'],
                ]
            );

            $imagens = null;
            if ($ordens_servicos) {
                $imagens = $this->relatorio->get_images($relatorio->relatorio_pk);

                foreach ($ordens_servicos as $os) {
                    $os->ordem_servico_criacao = date('d/m/Y H:i:s', strtotime($os->ordem_servico_criacao));
                    $os->imagens = [];

                    foreach ($imagens as $img) {
                        if ($os->ordem_servico_pk == $img->ordem_servico_fk) {

                            array_push($os->imagens, $img);

                            unset($img);
                        }
                    }
                }

            }

            $this->response->add_data("relatorio", $relatorio);
            $this->response->add_data("ordens_servicos", $ordens_servicos);

            $this->response->set_code(Response::SUCCESS);
            $this->response->send();

        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        }
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

            $token_decodificado = json_decode(token_decrypt($header_obj['Token']));
            $id_funcionario = $token_decodificado->id_funcionario;

            $this->update_relatorio($id_funcionario);
            //se deu certo vai enviar o response dentro desse método
            $this->response->set_code(Response::SUCCESS);

        } else {
            $this->response->set_code(Response::FORBIDDEN);
            $this->response->set_message($attempt_result);
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
    public function update_relatorio($id_funcionario)
    {
        $this->load->model('relatorio_model');
        $this->load->model('historico_model');

        //Pegamos os relatórios em aberto:
        $relatorios_em_aberto = $this->relatorio_model->get_relatorios(['status' => 0, 'funcionario_fk' => $id_funcionario]);

        if (count($relatorios_em_aberto) > 0) {
            date_default_timezone_set('America/Sao_Paulo');

            //Percorremos todos os relatórios
            foreach ($relatorios_em_aberto as $relatorio) {
                //pegando as ordens do relatório:
                $ordens_relatorio = $this->get_ordens_relatorio($relatorio->relatorio_pk);
                //se existir ordens:
                if (count($ordens_relatorio) > 0) {
                    //percorremos as ordens:
                    foreach ($ordens_relatorio as $os) {
                        //pegamos o último histórico (IMPORTANTE: O ULTIMO HISTÓRICO É O ID DA SITUACAO!!!):
                        $os->ultimo_historico = $this->historico_model->get_last_historico($os->ordem_servico_pk);

                        if ($os->ultimo_historico == 2) {
                            //SE O STATUS FOR EM ANDAMENTO:
                            $this->response = new Response();
                            $this->response->set_code(Response::BAD_REQUEST);
                            $this->response->set_message('O relatório corrente ainda não foi concluído.');
                            $this->response->send();
                            die();
                        } else {
                            $this->relatorio_model->update_relatorios_os_verificada(
                                ['os_fk' => $os->os_fk, 'os_verificada' => 0], 1
                            );
                        }
                    }
                }
                //se chegou aqui é porque não achou nenhuma ordem com status em andamento, então podemos setar o status do relatório para 1:

                $this->relatorio_model->update(['status' => 1], ['relatorio_pk' => $relatorio->relatorio_pk]);

                $this->relatorio_model->set_data_entrega($relatorio->relatorio_pk);

                $this->response->set_code(Response::SUCCESS);
                $this->response->set_message('Situação do relatório atualizado com sucesso.');
                $this->response->send();
                die();
            }

        } else {
            $this->response = new Response();
            $this->response->set_code(Response::NOT_FOUND);
            $this->response->set_message('Não encontramos nenhum relatório em andamento para você.');
            $this->response->send();
            die();
        }
    }

    /**
    Retorna o relatório que está em aberto do funcionário
     **/
    private function get_relatorio_do_funcionario($id_funcionario)
    {
        $this->load->model('relatorio_model');

        //precisamos descobrir o id do relatório:
        $relatorio = $this->relatorio_model->get_relatorio_do_funcionario($id_funcionario);

        //se o relatório existir:
        if ($relatorio) {
            //vamos pegar todas as ordens de serviços que pertence a este relatório:

            // $ordens_servicos = $this->relatorio_model->get(['relatorio_fk' => $relatorio->relatorio_pk]);
            // return $ordens_servicos;

            $this->relatorio_model->update(['pegou_no_celular' => 1], ['relatorio_pk' => $relatorio->relatorio_pk]);

            return $this->get_ordens_relatorio($relatorio->relatorio_pk);
        } else {
            return false;
        }
    }

    private function get_ordens_relatorio($id_relatorio)
    {
        $this->load->model('relatorio_model');
        $this->load->model('Historico_model', 'historico_model');

        $ordens_servicos = $this->relatorio_model->get(['relatorio_fk' => $id_relatorio]);

        foreach ($ordens_servicos as $os) {
            $id_ultimo_historico = $this->historico_model->get_id_last_historico($os->ordem_servico_pk);
            $os->imagem_situacao_caminho = $this->historico_model->get_imagem($id_ultimo_historico);
        }

        return $ordens_servicos;
    }

}
