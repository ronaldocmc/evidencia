<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once dirname(__FILE__) . "/Response.php";
require_once APPPATH . "core/CRUD_Controller.php";
require_once dirname(__FILE__) . "/Response.php";
require_once 'vendor/autoload.php';

class Relatorio extends CRUD_Controller
{
    public $response;

    public function __construct()
    {
        parent::__construct();
        
        date_default_timezone_set('America/Sao_Paulo');

        $this->load->library('form_validation');
        $this->load->helper('exception');

        $this->load->model('Relatorio_model', 'report_model');
        $this->load->model('Ordem_Servico_model', 'ordem_servico_model');
        $this->load->model('Funcionario_model', 'funcionario_model');
        $this->response = new Response();
    }

    public function novo_relatorio()
    {
        //Carregando os models para recuperação de dados a serem exibidos na view Novo Relatório
        $this->load->model('Servico_model', 'servico_model');
        $this->load->model('Tipo_Servico_model', 'tipo_servico_model');
        $this->load->model('Setor_model', 'setor_model');

        //Selecionando o setores
        $setores = $this->setor_model->get_all(
            '*',
            ["organizacao_fk" => $this->session->user['id_organizacao']],
            -1,
            -1
        );
        //Selecionando os tipos de serviços
        $tipos_servicos = $this->tipo_servico_model->get(
            '*',
            ["departamentos.organizacao_fk" => $this->session->user['id_organizacao']]
        );
        //Selecionando os serviços
        $servicos = $this->servico_model->get(
            '*',
            ["situacoes.organizacao_fk" => $this->session->user['id_organizacao']]
        );
        //Selecionando os funcionários com a função de revisor
        $funcionarios = $this->funcionario_model->get(
            "*",
            [
                "funcionarios.organizacao_fk" => $this->session->user['id_organizacao'],
                "funcionarios.funcao_fk" => "6",
            ]
        );
        //Carregando CSS utilizados na view
        $this->session->flashdata('css', [
            0 => base_url('assets/css/modal_desativar.css'),
        ]);
        //Carregando os scripts utilizados na view
        $this->load_scripts();

        //Finalmente, carregando para view os dados recuperados no banco
        load_view([
            0 => [
                'src' => 'dashboard/administrador/relatorio/novo_relatorio',
                'params' => [
                    'setores' => $setores,
                    'tipos_servicos' => $tipos_servicos,
                    'servicos' => $servicos,
                    //pegamos os motoristas de caminhão
                    'motoristas_de_caminhao' => $funcionarios,
                    'message' => $this->session->flashdata('error'),
                ],
            ],
        ], 'administrador');
    }

    private function load_scripts()
    {
        $this->session->set_flashdata('scripts', [
            0 => base_url('assets/js/constants.js'),
            1 => base_url('assets/vendor/datatables/datatables.min.js'),
            2 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js'),
            3 => base_url('assets/js/jquery.noty.packaged.min.js'),
            4 => base_url('assets/js/dashboard/relatorio/relatorios_gerais.js'),
            5 => base_url('assets/js/utils.js'),
        ]);
    }

    private function check_if_is_not_empty($array, $error_message){
        if(!isset($array)){
            throw new MyException($error_message, Response::BAD_REQUEST);
        }
    }

    private function validate_dates($initial, $final){
        if(!$this->validateDate($initial)){
            throw new MyException('Data inicial inválida.', Response::BAD_REQUEST);
        }

        if(!$this->validateDate($final)){
            throw new MyException('Data final inválida.', Response::BAD_REQUEST);
        }

        if ($initial > $final) {
            throw new MyException("A data inicial deve ser menor ou igual a data final.", Response::BAD_REQUEST);
        }
    }

    //Função que realiza a validação dos itens selecionados no filtro
    private function validate_filter($filtro)
    {
        $this->check_if_is_not_empty($filtro['setor'], 'Marque pelo menos um setor.');
        $this->check_if_is_not_empty($filtro['tipo'], 'Marque pelo menos um tipo de serviço.');

        $this->validate_dates($filtro['data_inicial'], $filtro['data_final']);
    }

    //Função que recupera as ordens de serviço com as especificações do flitro, realiza também a contagem
    public function select_os_by_filter()
    {
        try{
            $filter = $this->input->post();
            
            $this->validate_filter($filter);
            
            $ordens_servicos = $this->ordem_servico_model->get_for_new_report($filter, true);

            $this->response->set_data($ordens_servicos);
            $this->response->send();

        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        }   
    }

    private function standardizes_data($data, $field_name, $id)
    {
        $standardized_data = [];

        foreach ($data as $value) {
            array_push(
                $standardized_data,
                [
                    'relatorio_fk' => $id,
                    $field_name => $value,
                ]);
        }

        return $standardized_data;
    }

    private function set_report_fields()
    {   
        $this->report_model->__set('relatorio_func_responsavel', $this->input->post('funcionario_fk'));
        $this->report_model->__set('ativo', 1);
        $this->report_model->__set('pegou_no_celular', 0);
        $this->report_model->__set('relatorio_criador', $this->session->user['id_user']);
        $this->report_model->__set('relatorio_data_inicio_filtro', $this->input->post('data_inicial'));
        $this->report_model->__set('relatorio_data_fim_filtro', $this->input->post('data_final'));
        $this->report_model->__set('relatorio_situacao', 'Andamento');
    }


    private function verify_reports_of_worker($worker_id)
    {
        $report_on_working = $this->report_model->get_all(
            '*',
            [
                'relatorio_func_responsavel' => $worker_id,
                'ativo' => 1
            ],
            -1,
            -1
        );
        
        if (!empty($report_on_working)) 
        {
            throw new MyException('Não foi possível executar operação! Funcionário já possui outro relatório em andamento.', Response::BAD_REQUEST);
        }

    }

    private function validate_form()
    {
        //Aplicando as regras ao formulário
        $this->report_model->run_form_validation();

        //Recebendo dados de filtragem (Setores, Tipos de Serviço e Funcionário)
        $filter = $this->input->post();
        $this->validate_filter($filter);
    }

    public function create_new_report()
    {
        try{
            //Configurando as regras de preenchimento de formulário
            $this->report_model->config_form_validation();

            //Se o usuário ativo for super usuário, adicionamos a regra de senha no preenchimento de formulário
            if ($this->is_superuser()) {
                $this->add_password_to_form_validation();
            }

            $this->validate_form();

            $filter = $this->input->post();

            //Verificando se existe um relatório em andamento para o funcionário selecionado
            $this->verify_reports_of_worker($this->input->post('funcionario_fk'));
            
            //Recuperando as ordens de serviço que pertencerão ao relatório conforme especificação do filtro
            $ordens_servicos = $this->ordem_servico_model->get_for_new_report($filter);

            //Caso não existam ordens nas especificações passadas, então não será possível gerar relatório
            $this->check_if_is_not_empty($ordens_servicos, "Não é possível criar um relatório sem ordens de serviço.");

            //Criando o relatório com as ordens de serviço especificadas
            $id_report = $this->save($filter, $ordens_servicos);

            $this->response->set_code(Response::SUCCESS);
            $this->response->set_data([
                'id' => $id_report,
                'message' => 'Relatório criado com sucesso!<br>Aguarde enquanto estamos recarregando a página ...',
            ]);
        
            $this->response->send();

        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        }
    }

    //Função que insere um novo relatório no banco de dados
    private function save($filter, $ordens_servicos)
    {
        try {

            //Setando os campos do Object Relatório no model.
            $this->set_report_fields();
            

            //DAQUI ATÉ O END DA TRANSACTION PODE SER FEITO DEPOIS DE TODAS VERIFICAÇÕES.
            //Abrindo uma transaction para caso de falhas de inserção
            $this->begin_transaction();

            //Realizando o insert do relatório no banco de dados
            $report_id = $this->report_model->insert();

            //Padronizando os dados de setor e de tipos de serviços para a inserção (array)
            $data_sector = $this->standardizes_data($filter['setor'], 'setor_fk', $report_id);
            $data_service_type = $this->standardizes_data($filter['tipo'], 'tipo_servico_fk', $report_id);

            //Efetuando a inserção dos elementos do filtro setor e tipo de serviço.
            $this->report_model->insert_filter_data($data_sector, 'filtros_relatorios_setores');
            $this->report_model->insert_filter_data($data_service_type, 'filtros_relatorios_tipos_servicos');

            $worker = $this->funcionario_model->get_one('funcionario_nome', ['funcionario_pk' => $this->input->post('funcionario_fk')]);

            foreach ($ordens_servicos as $os) {
                $this->report_model->insert_report_os(['relatorio_fk' => $report_id, 'os_fk' => $os->ordem_servico_pk]);

                //TODO remover quando a trigger funcionar
                //Registrando no histórico o último dado atualizado da OS
                $this->ordem_servico_model->handle_historico($os->ordem_servico_pk);

                //Setando o object do model da ordem de serviço com os dados de atualização
                $this->ordem_servico_model->__set('ordem_servico_pk', $os->ordem_servico_pk);
                $this->ordem_servico_model->__set('situacao_atual_fk', 2);
                $this->ordem_servico_model->__set('ordem_servico_comentario', 'Ordem de Serviço atribuída ao relatório do funcionário ' . $worker->funcionario_nome);
                $this->ordem_servico_model->__set('ordem_servico_atualizacao', date('Y-m-d H:i:s'));

                //Atualizando a situação atual da ordem de serviço (Situação 2 - Em andamento)
                $this->ordem_servico_model->update();

            }

            $this->end_transaction();

            return $report_id;

        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        }
    }

    public function set_string_filter_date($data)
    {
        return 'Relatório contendo as ordens de serviço emitidas do dia ' .
        date('d/m/Y', strtotime($data->relatorio_data_inicio_filtro)) .
        ' até ' . date('d/m/Y', strtotime($data->relatorio_data_fim_filtro));
    }

    public function set_string_filter_sectors($data)
    {
        $string = '';

        for ($i = 0; $i < count($data); $i++) {
            if ($i == 0) { //se for o primeiro registro
            } else if ($i == count($data) - 1) { //se for o ultimo registro:
                $string .= ' e ';
            } else { // se tiver no meio
                $string .= ', ';
            }
            $string .= $data[$i]->setor_nome;
        }

        return $string;
    }

    public function set_string_filter_type_services($data)
    {
        $string = '';

        for ($i = 0; $i < count($data); $i++) {
            if ($i == 0) { //se for o primeiro registro
            } else if ($i == count($data) - 1) { //se for o ultimo registro:
                $string .= ' e ';
            } else { // se tiver no meio
                $string .= ', ';
            }
            $string .= $data[$i]->tipo_servico_nome;
        }

        return $string;
    }

    public function select_orders_of_report($id)
    {

        $ordens_servicos = $this->report_model->get_orders_of_report(['relatorio_fk' => $id]);
        
            foreach ($ordens_servicos as $os) {
                $os->ordem_servico_atualizacao = date('d/m/Y H:i:s', strtotime($os->ordem_servico_atualizacao));

                //Se não tiver sido entregue, vamos mostrar: Não Finalizado, se tiver, vamos printar a situação atual
                if ($os->situacao_atual_fk == 2) {
                    $os->ordem_servico_comentario = 'Não Finalizado';
                } else {
                    $os->ordem_servico_comentario = $os->situacao_nome;
                }
            }

        return $ordens_servicos;
    }

    public function get_filters($report_id)
    {

        $data_filter = [];

        $this->load->model('setor_model');
        $this->load->model('tipo_servico_model', 'ts_model');

        $sector_filters = $this->setor_model->get_all(
            'setor_pk, setor_nome',
            ['relatorio_fk' => $report_id],
            -1,
            -1,
            [
                ['table' => 'filtros_relatorios_setores', 'on' => 'filtros_relatorios_setores.setor_fk = setores.setor_pk'],
            ]
        );

        $data_filter['setores'] = $sector_filters;

        $services_type_filters = $this->ts_model->get_all(
            'tipo_servico_pk, tipo_servico_nome',
            ['relatorio_fk' => $report_id],
            -1,
            -1,
            [
                ['table' => 'filtros_relatorios_tipos_servicos', 'on' => 'filtros_relatorios_tipos_servicos.tipo_servico_fk = tipos_servicos.tipo_servico_pk'],
            ]
        );

        $data_filter['tipos_servicos'] =  $services_type_filters;

        return $data_filter;
    }

    private function get_report_detail_data($report)
    {
        $report_id = $report->relatorio_pk;

        $workers = $this->funcionario_model->get(
            "funcionarios.funcionario_pk, funcionarios.funcionario_nome",
            [
                "funcionarios.organizacao_fk" => $this->session->user['id_organizacao'],
                "funcionarios.funcao_fk" => "6",
            ]
        );

        $responsible = $this->funcionario_model->get_one(
            'funcionario_pk, funcionario_nome', 
            ['funcionario_pk' => $report->relatorio_func_responsavel]
        );

        //Recebendo as ordens de serviço do relatório em questão
        $ordens_servicos = $this->select_orders_of_report($report_id);

        //Recebendo os filtros utilizados no relatório
        $data_filters = $this->get_filters($report_id);

        $strings_filters = $this->get_strings_filters($report, $data_filters['setores'], $data_filters['tipos_servicos']);

        $params = [
            'ordens_servicos' => $ordens_servicos,
            'funcionario' => $responsible,
            'funcionarios' => $workers,
            'relatorio' => $report,
            'filtros' => $strings_filters,
        ];

        return $params;
    }

    private function load_css_detail()
    {
        $this->session->set_flashdata('css', [
            0 => base_url('assets/css/modal_desativar.css'),
            1 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.css'),
            2 => base_url('assets/css/loading_input.css'),
            3 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css'),
            4 => base_url('assets/css/modal_map.css'),
        ]);
    }

    private function load_script_detail()
    {
        $this->session->set_flashdata('scripts', [
            0 => base_url('assets/vendor/masks/jquery.mask.min.js'),
            1 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.js'),
            2 => base_url('assets/js/masks.js'),
            3 => base_url('assets/vendor/bootstrap-multistep-form/jquery.easing.min.js'),
            4 => base_url('assets/vendor/datatables/datatables.min.js'),
            5 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js'),
            6 => base_url('assets/js/utils.js'),
            7 => base_url('assets/js/constants.js'),
            8 => base_url('assets/js/jquery.noty.packaged.min.js'),
            9 => base_url('assets/vendor/select-input/select-input.js'),
            10 => base_url('assets/js/dashboard/relatorio/detalhe-relatorio.js'),
        ]);
    }

    public function imprimir($report_id)
    {
        $report = $this->report_model->get_one('*', ['relatorio_pk' => $report_id]);

        if($report){

            $this->load_css_detail();
            $this->load_script_detail();

            $this->load->view([
                0 => [
                    'src'    => 'dashboard/administrador/relatorio/imprimir_relatorio',
                    'params' => $this->get_report_detail_data($report)
                ], 'administrador' 
            ]);

        } else {
            $this->load->view('errors/html/error_404');
        }
    }


    public function detalhes($report_id)
    {
        $report = $this->report_model->get_one('*', ['relatorio_pk' => $report_id]);

        if($report){

            $this->load_css_detail();
            $this->load_script_detail();
        
                load_view([
                    0 => [
                        'src'    => 'dashboard/administrador/relatorio/detalhe_relatorio',
                        'params' => $this->get_report_detail_data($report)
                    ],
                ], 'administrador');
        } else {
            $this->load->view('errors/html/error_404');
        }
    }

    private function get_strings_filters($filter_date, $filter_sector, $filter_type_services)
    {
        //criar o método que preenche o filtro data
        $string_filters['data'] = $this->set_string_filter_date($filter_date);

        //criar um método para preecnher os filtros:
        $string_filters['setor'] = $this->set_string_filter_sectors($filter_sector);

        $string_filters['tipos_servicos'] = $this->set_string_filter_type_services($filter_type_services);

        return $string_filters;
    }

    private function validateDate($date)
    {
        $tempDate = explode('-', $date);
        // checkdate(month, day, year)
        return checkdate($tempDate[1], $tempDate[2], $tempDate[0]);
    }

    private function verify_report_was_started($id)
    {
        $report = $this->report_model->get_one('relatorios.pegou_no_celular', ['relatorio_pk' => $id]);

        //ou seja, se o relatório já foi iniciado:
        if ($report->pegou_no_celular == 1) {
            throw new MyException("O funcionário já recebeu o relatório no celular. Impossível realizar operação.", Response::UNAUTHORIZED);
        }
    }

    

    // Recebe por parâmetro o id do relatório
    public function change_worker($id)
    {
        try {
            $this->verify_report_was_started($id);
            $this->verify_reports_of_worker($this->input->post('funcionario_fk'));

            $this->report_model->__set('relatorio_pk', $id);
            $this->report_model->__set('relatorio_func_responsavel', $this->input->post('funcionario_fk'));

            $this->begin_transaction();
            $this->report_model->update();
            $this->end_transaction();

            $this->response->set_code(Response::SUCCESS);
            $this->response->set_data("Funcionário alterado com sucesso.");

            $this->response->send();

        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        }   
    }

    public function deactivate($id)
    {
        try{
            $this->report_model->__set('relatorio_pk', $id);
            $this->verify_report_was_started($id);

            $ordens_servicos = $this->report_model->get_orders_of_report(['relatorio_fk' => $id]);

            foreach ($ordens_servicos as $os) 
            {
                //Registrando no histórico o último dado atualizado da OS
                $this->ordem_servico_model->handle_historico($os->ordem_servico_pk);

                //Setando o FORM da ordem de serviço com os dados de atualização
                $this->ordem_servico_model->__set('ordem_servico_pk', $os->ordem_servico_pk);
                $this->ordem_servico_model->__set('situacao_atual_fk', 1);
                $this->ordem_servico_model->__set('ordem_servico_comentario', 'O Relatório foi destruído.');
                $this->ordem_servico_model->__set('ordem_servico_atualizacao', date('Y-m-d H:i:s'));

                //Atualizando a situação atual da ordem de serviço (Situação 1 - Aberta)
                $this->ordem_servico_model->update();
            }

            $this->report_model->__set('relatorio_situacao', 'Inativo');
            $this->report_model->__set('Ativo', 0);
            $this->report_model->deactivate();
            $this->end_transaction();

            $this->response->set_code(Response::SUCCESS);
            $this->response->set_data("Relatório deletado com sucesso.");
            $this->response->send();

        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        }  

    }

    private function get_all_reports()
    {

        $reports = $this->report_model->get_all(
            '*',
            null, //['relatorios.ativo' => 1],
            -1,
            -1,
            [
                ['table' => 'funcionarios', 'on' => 'funcionarios.funcionario_pk = relatorios.relatorio_func_responsavel'],
            ]);

        if ($reports) {
            //arrumar a data:
            foreach ($reports as $r) {

                $r->relatorio_data_criacao = date('d/m/Y H:i:s', strtotime($r->relatorio_data_criacao));
                $r->quantidade_os = $this->report_model->get_orders_of_report(['relatorio_fk' => $r->relatorio_pk], true);

                if ($r->relatorio_data_entrega == null) {
                    $r->relatorio_data_entrega = '-- --';
                } else {
                    $r->relatorio_data_entrega = date('d/m/Y H:i:s', strtotime($r->relatorio_data_entrega));
                }
            }
        }

        return $reports;
    }

    // Index será responsável pela listagem dos relatórios
    public function index()
    {

        $reports = $this->get_all_reports();

        $this->session->set_flashdata('css', array(
            0 => base_url('assets/vendor/cropper/cropper.css'),
            1 => base_url('assets/vendor/input-image/input-image.css'),
            2 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.css'),
            3 => base_url('assets/css/modal_desativar.css'),
            4 => base_url('assets/css/user_guide.css'),
        ));

        $this->session->set_flashdata('scripts', array(
            0 => base_url('assets/vendor/masks/jquery.mask.min.js'),
            1 => base_url('assets/vendor/datatables/datatables.min.js'),
            2 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js'),
            3 => base_url('assets/vendor/bootstrap-multistep-form/jquery.easing.min.js'),
            4 => base_url('assets/js/utils.js'),
            5 => base_url('assets/js/constants.js'),
            6 => base_url('assets/js/jquery.noty.packaged.min.js'),
            7 => base_url('assets/js/dashboard/relatorio/home.js'),
        ));

        $this->load->helper('form');
        load_view([
            0 => [
                'src' => 'dashboard/administrador/relatorio/home',
                'params' => [
                    'relatorios' => $reports,
                ],
            ],
            1 => [
                'src' => 'access/pre_loader',
                'params' => null,
            ],
        ], 'administrador');
    }

    private function verify_password()
    {
        if (!authenticate_operation($this->input->post('senha'), $this->session->user['password_user'])) {
            throw new MyException("Senha incorreta.", Response::UNAUTHORIZED);
        }
    }

    private function receive_all_reports()
    {
        $reports = $this->report_model->get_all('*', ['pegou_no_celular' => 1, 'ativo' => 1], -1, -1);

        if ($reports) {
            foreach ($reports as $r) {
                $this->restore_orders_of_report($r->relatorio_pk);
            }
        } else {
            throw new MyException('Não há relatórios para serem recebidos.', Response::NOT_FOUND);
        }
    }

    private function receive_single_report($report_id)
    {
        $report = $this->report_model->get_one('*', ['pegou_no_celular' => 1, 'ativo' => 1, 'relatorios.relatorio_pk' => $report_id]);

        if($report !== NULL){
            $this->restore_orders_of_report($report_id);
        } else {
            throw new MyException('O relatório já foi recebido!', Response::NOT_FOUND);
        }
    }

    public function receive_report($report_id = NULL)
    {
        try{

            $this->load->helper('password_helper');

            $this->add_password_to_form_validation();

            $this->report_model->run_form_validation();

            $this->verify_password();

            //devemos receber TODOS os relatórios que estão em Andamento
            if($report_id === NULL){ 
                $this->receive_all_reports();

            } else {
                $this->receive_single_report($report_id);
            }

            $this->response->set_code(Response::SUCCESS);
            $this->response->set_data(['message' => 'Relatórios recebidos com sucesso!']);
            $this->response->send();

        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        }
    }

    private function restore_orders_of_report($id)
    {   
        try {
            $all_executed = TRUE;
            $ordens_servico = $this->report_model->get_orders_of_report(['relatorio_fk' => $id]);

            $this->begin_transaction();
            foreach ($ordens_servico as $os) {

                if ($os->situacao_atual_fk == '2') //2 é EM ANDAMENTO
                {

                    //Registrando no histórico o último dado atualizado da OS
                    $this->ordem_servico_model->handle_historico($os->ordem_servico_pk);

                    //Setando o FORM da ordem de serviço com os dados de atualização
                    $this->ordem_servico_model->__set('ordem_servico_pk', $os->ordem_servico_pk);
                    $this->ordem_servico_model->__set('situacao_atual_fk', 1);
                    $this->ordem_servico_model->__set('ordem_servico_comentario', 'Ordem de Serviço não executada no relatório.');
                    $this->ordem_servico_model->__set('ordem_servico_atualizacao', date('Y-m-d H:i:s'));

                    //Atualizando a situação atual da ordem de serviço (Situação 2 - Em andamento)
                    $this->ordem_servico_model->update();

                    $all_executed = FALSE;

                }
            }

            $this->report_model->__set('relatorio_data_entrega', date('Y-m-d H:i:s'));
            $this->report_model->__set('relatorio_situacao', 'Finalizado');
            $this->report_model->__set('relatorio_pk', $id);
            $this->report_model->__set('ativo', 0);

            if(!$all_executed){
                $this->report_model->__set('relatorio_situacao', 'Não Finalizado'); 
            }

            $this->report_model->update();
            $this->end_transaction();

        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        }
    }






    //DAQUI PARA BAIXO POSSÍVELMENTE LIXO
    // public function mapa()
    // {
    //     $this->load->model('Prioridade_model', 'prioridade_model');
    //     $this->load->model('Situacao_model', 'situacao_model');
    //     $this->load->model('Departamento_model', 'departamento_model');
    //     $this->load->model('Servico_model', 'servico_model');
    //     $this->load->model('Tipo_Servico_model', 'tipo_servico_model');
    //     $this->load->helper('form');

    //     $prioridades = $this->prioridade_model->get([
    //         'organizacao_fk' => $this->session->user['id_organizacao'],
    //     ]);
    //     $situacoes = $this->situacao_model->get([
    //         'organizacao_fk' => $this->session->user['id_organizacao'],
    //     ]);
    //     $servicos = $this->servico_model->get([
    //         'situacoes.organizacao_fk' => $this->session->user['id_organizacao'],
    //     ]);
    //     $departamentos = $this->departamento_model->get([
    //         'departamentos.organizacao_fk' => $this->session->user['id_organizacao'],
    //     ]);
    //     $tipos_servicos = $this->tipo_servico_model->get([
    //         'departamentos.organizacao_fk' => $this->session->user['id_organizacao'],
    //     ]);

    //     $this->session->set_flashdata('css', [
    //         0 => base_url('assets/css/modal_desativar.css'),
    //         1 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.css'),
    //         2 => base_url('assets/css/loading_input.css'),
    //         3 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css'),
    //         4 => base_url('assets/css/modal_map.css'),
    //         5 => base_url('assets/css/timeline.css'),
    //         6 => base_url('assets/css/style_card.css'),
    //         7 => base_url('assets/css/user_guide.css'),
    //     ]);

    //     $this->session->set_flashdata('scripts', [
    //         0 => base_url('assets/vendor/masks/jquery.mask.min.js'),
    //         1 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.js'),
    //         2 => base_url('assets/js/masks.js'),
    //         3 => base_url('assets/vendor/bootstrap-multistep-form/jquery.easing.min.js'),
    //         4 => base_url('assets/vendor/datatables/datatables.min.js'),
    //         5 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js'),
    //         6 => base_url('assets/js/utils.js'),
    //         7 => base_url('assets/js/constants.js'),
    //         8 => base_url('assets/js/jquery.noty.packaged.min.js'),
    //         9 => base_url('assets/js/dashboard/ordem_servico/relatorio.js'),
    //         10 => base_url('assets/vendor/select-input/select-input.js'),
    //         11 => base_url('assets/js/localizacao.js'),
    //     ]);

    //     $this->session->set_flashdata('mapa', [
    //         0 => true,
    //     ]);

    //     load_view([
    //         0 => [
    //             'src' => 'dashboard/administrador/mapa/home',
    //             'params' => [
    //                 'prioridades' => $prioridades,
    //                 'situacoes' => $situacoes,
    //                 'servicos' => $servicos,
    //                 'departamentos' => $departamentos,
    //                 'tipos_servicos' => $tipos_servicos,
    //             ],
    //         ],
    //         1 => [
    //             'src' => 'access/pre_loader',
    //             'params' => null,
    //         ],
    //     ], 'administrador');
    // }

// public function relatorios_gerais()
    // {
    //     $this->load->model('departamento_model');
    //     $this->load->model('setor_model');
    //     $this->load->model('situacao_model');
    //     $this->load->model('prioridade_model');
    //     $this->load->model('servico_model');

    //     $departamentos = $this->departamento_model->get([
    //         'departamentos.organizacao_fk' => $this->session->user['id_organizacao'],
    //     ]);

    //     $setores = $this->setor_model->get([
    //         'organizacao_fk' => $this->session->user['id_organizacao'],
    //     ]);

    //     $situacoes = $this->situacao_model->get([
    //         'organizacao_fk' => $this->session->user['id_organizacao'],
    //     ]);

    //     $prioridades = $this->prioridade_model->get([
    //         'organizacao_fk' => $this->session->user['id_organizacao'],
    //     ]);

    //     $servicos = $this->servico_model->get([
    //         'situacoes.organizacao_fk' => $this->session->user['id_organizacao'],
    //     ]);

    //     $this->session->set_flashdata('scripts', [
    //         0 => base_url('assets/js/constants.js'),
    //         1 => base_url('assets/js/jquery.noty.packaged.min.js'),
    //         2 => base_url('assets/js/dashboard/relatorio/relatorios_gerais.js'),
    //     ]);

    //     load_view([
    //         0 => [
    //             'src' => 'dashboard/administrador/ordem_servico/relatorios_gerais',
    //             'params' => [
    //                 'prioridades' => $prioridades,
    //                 'situacoes' => $situacoes,
    //                 'servicos' => $servicos,
    //                 'departamentos' => $departamentos,
    //                 'setores' => $setores,
    //             ],
    //         ],
    //     ], 'administrador');
    // }

    // public function relatorio_especifico()
    // {
    //     $this->load->model('departamento_model');
    //     $this->load->model('setor_model');
    //     // $this->load->model('procedencia_model');
    //     $this->load->model('situacao_model');
    //     $this->load->model('prioridade_model');
    //     $this->load->model('tipo_servico_model');
    //     $this->load->model('servico_model');
    //     $this->load->model('estado_model');
    //     $this->load->model('municipio_model');
    //     $this->load->model('bairro_model');
    //     $this->load->helper('form');

    //     $departamentos = $this->departamento_model->get([
    //         'departamentos.organizacao_fk' => $this->session->user['id_organizacao'],
    //     ]);

    //     $setores = $this->setor_model->get([
    //         'organizacao_fk' => $this->session->user['id_organizacao'],
    //     ]);

    //     // Pegar procedencias

    //     $situacoes = $this->situacao_model->get([
    //         'organizacao_fk' => $this->session->user['id_organizacao'],
    //     ]);

    //     $prioridades = $this->prioridade_model->get([
    //         'organizacao_fk' => $this->session->user['id_organizacao'],
    //     ]);

    //     $tipos_servicos = $this->tipo_servico_model->get([
    //         'departamentos.organizacao_fk' => $this->session->user['id_organizacao'],
    //     ]);

    //     $servicos = $this->servico_model->get([
    //         'situacoes.organizacao_fk' => $this->session->user['id_organizacao'],
    //     ]);

    //     $estados = $this->estado_model->get();

    //     $municipios = $this->municipio_model->get();

    //     $bairros = $this->bairro_model->get();

    //     $this->session->set_flashdata('css', [
    //         0 => base_url('assets/css/modal_desativar.css'),
    //         1 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.css'),
    //         2 => base_url('assets/css/loading_input.css'),
    //         3 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css'),
    //         4 => base_url('assets/css/modal_map.css'),
    //         5 => base_url('assets/css/timeline.css'),
    //     ]);

    //     $this->session->set_flashdata('scripts', [
    //         0 => base_url('assets/vendor/masks/jquery.mask.min.js'),
    //         1 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.js'),
    //         2 => base_url('assets/js/masks.js'),
    //         3 => base_url('assets/vendor/bootstrap-multistep-form/jquery.easing.min.js'),
    //         4 => base_url('assets/vendor/datatables/datatables.min.js'),
    //         5 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js'),
    //         6 => base_url('assets/js/utils.js'),
    //         7 => base_url('assets/js/constants.js'),
    //         8 => base_url('assets/js/jquery.noty.packaged.min.js'),
    //         9 => base_url('assets/js/dashboard/relatorio/index.js'),
    //         10 => base_url('assets/vendor/select-input/select-input.js'),
    //         11 => base_url('assets/js/localizacao.js'),
    //     ]);

    //     load_view([
    //         0 => [
    //             'src' => 'dashboard/administrador/ordem_servico/relatorio_especifico',
    //             'params' => [
    //                 'prioridades' => $prioridades,
    //                 'situacoes' => $situacoes,
    //                 'servicos' => $servicos,
    //                 'departamentos' => $departamentos,
    //                 'setores' => $setores,
    //                 'tipos_servicos' => $tipos_servicos,
    //                 'estados' => $estados,
    //                 'municipios' => $municipios,
    //                 'bairros' => $bairros,
    //                 'procedencias' => null,
    //                 'ordens_servico' => null,
    //             ],
    //         ],
    //         1 => [
    //             'src' => 'access/pre_loader',
    //             'params' => null,
    //         ],
    //     ], 'administrador');
    // }

    // public function gera_relatorio_geral($relatorio, $filtro, $situacao)
    // {
    //     $this->form_validation->set_data([
    //         'relatorio' => $relatorio,
    //         'filtro' => $filtro,
    //         'situacao' => $situacao,
    //     ]);

    //     $this->form_validation->set_rules(
    //         'relatorio',
    //         'relatorio',
    //         'trim|required|alpha'
    //     );

    //     $this->form_validation->set_rules(
    //         'filtro',
    //         'filtro',
    //         'trim|required'
    //     );

    //     $this->form_validation->set_rules(
    //         'situacao',
    //         'situacao',
    //         'trim|required'
    //     );

    //     if (true) {
    //         $this->load->model('ordem_servico_model');

    //         $ordens_servicos = $this->ordem_servico_model->get_ids_os($this->session->user['id_organizacao']);

    //         if (!$ordens_servicos) {
    //             $view = "<h4>Não há ordens de serviços em sua empresa</h4>";
    //             return;
    //         }

    //         $where['relatorio'] = $relatorio;
    //         $where['situacao'] = $situacao;

    //         switch ($where['relatorio']) {
    //             case 'data':
    //                 $where['qtd_dias'] = $filtro;
    //                 break;

    //             case 'setor':
    //                 $where['setor'] = $filtro;
    //                 break;

    //             case 'departamento':
    //                 $where['departamento'] = $filtro;
    //                 break;

    //             case 'servico':
    //                 $where['servico'] = $filtro;
    //                 break;
    //         }

    //         $data['ordens'] = $this->ordem_servico_model->get_os_relatorio($where);

    //         $data['data'] = date('d/m/Y');
    //         $data['empresa'] = 'Prudenco';

    //         $view = $this->load->view('pdf/relatorio_os', $data, true);
    //     } else {
    //         $view = "<h4>Erro ao processar o relatório, informe a equipe técnica o código 48</h4>";
    //     }

    //     $mpdf = new \Mpdf\Mpdf();
    //     $mpdf->WriteHTML($view);
    //     $mpdf->Output();
    // }

}
