<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH . "core/Response.php";

require_once APPPATH . "core/CRUD_Controller.php";

require_once APPPATH . "models/Ordem_Servico_model.php";

require_once APPPATH . "core/MyException.php";

class Ordem_Servico extends CRUD_Controller
{
    public $response;

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('America/Sao_Paulo');
        $this->load->model('Ordem_Servico_model', 'ordem_servico');
        $this->load->library('upload');
        $this->load->helper('form');
        $this->response = new Response();
    }

    private function load()
    {
        $this->load->model('Departamento_model', 'departamento');
        $this->load->model('Tipo_Servico_model', 'tipo_servico');
        $this->load->model('Prioridade_model', 'prioridade');
        $this->load->model('Situacao_model', 'situacao');
        $this->load->model('Servico_model', 'servico');
        $this->load->model('Procedencia_model', 'procedencia');
        $this->load->model('Setor_model', 'setor');
        $this->load->model('Localizacao_model', 'localizacao');

        $this->load->library('form_validation');
        $this->load->helper('exception');

        $this->response = new Response();
    }

    private function choose_filter($option)
    {
        $where = null;

        if ($option == 'semana') {

            $current_date = date('Y-m-d H:i:s');
            $lastweek_time = mktime(0, 0, 0, date("m"), date("d") - 7, date("Y"));
            $lastweek_date = date('Y-m-d H:i:s', $lastweek_time);

            $where = "ordens_servicos.ordem_servico_criacao BETWEEN '" . $lastweek_date . "' AND '" . $current_date . "'";
        }

        if ($option == 'finalizadas') {
            $where = 'ordens_servicos.situacao_atual_fk = 5';
        }

        if ($option == 'recusadas') {
            $where = 'ordens_servicos.situacao_atual_fk =  3 OR ordens_servicos.situacao_atual_fk = 4';
        }

        if ($option == 'desativadas') {
            $where = 'ordens_servicos.ativo = 0';
        }

        if ($option == 'ativadas') {
            $where = 'ordens_servicos.ativo = 1';
        }

        if ($option == 'abertas') {
            $where = 'ordens_servicos.situacao_atual_fk = 1';
        }

        if ($option == 'andamento') {
            $where = 'ordens_servicos.situacao_atual_fk = 2';
        }

        return $where;
    }
    
    public function filtro_tabela(){

        try {

            $where = $this->choose_filter($this->input->post('filtro'));
            // echo $where; die();
            $ordens_servico = $this->ordem_servico->get_home($this->session->user['id_organizacao'], $where);

            $this->response->set_code(Response::SUCCESS);
            $this->response->set_data($ordens_servico);
            $this->response->send();

        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        }
    }

    public function index()
    {
        $this->load();
        $this->session->set_flashdata('css', [
            0 => base_url('assets/css/modal_desativar.css'),
            1 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.css'),
            2 => base_url('assets/css/loading_input.css'),
            3 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css'),
            3 => base_url('assets/css/modal_map.css'),
            4 => base_url('assets/vendor/cropper/cropper.css'),
            5 => base_url('assets/vendor/input-image/input-image.css'),
            6 => base_url('assets/css/timeline.css'),
            7 => base_url('assets/css/style_card.css'),
            8 => base_url('assets/css/user_guide.css'),
        ]);

        $this->session->set_flashdata('scripts', [
            0 => base_url('assets/vendor/masks/jquery.mask.min.js'),
            1 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.js'),
            2 => base_url('assets/js/masks.js'),
            3 => base_url('assets/vendor/bootstrap-multistep-form/jquery.easing.min.js'),
            4 => base_url('assets/vendor/datatables/datatables.min.js'),
            5 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js'),
            6 => base_url('assets/js/constants.js'),
            7 => base_url('assets/js/utils.js'),
            8 => base_url('assets/js/jquery.noty.packaged.min.js'),
            9 => base_url('assets/js/dashboard/ordem_servico/index.js'),
            10 => base_url('assets/vendor/select-input/select-input.js'),
            11 => base_url('assets/js/localizacao.js'),
            12 => base_url('assets/vendor/cropper/cropper.js'),
            13 => base_url('assets/vendor/input-image/input-image.js'),
            14 => base_url('assets/js/date-eu.js'),
        ]);
        $this->session->set_flashdata('mapa', [
            0 => true,
        ]);
        load_view([
            0 => [
                'src' => 'access/pre_loader',
                'params' => null,
            ],
            1 => [
                'src' => 'dashboard/administrador/ordem_servico/home',
                'params' => null,
            ],
        ], 'administrador');
    }

    public function get()
    {
        $response = new Response();
        $this->load();

        $ordens_servico = $this->ordem_servico->get_home(
            $this->session->user['id_organizacao'],
            ['ordens_servicos.ativo' => 1]
        );

        $imagens = $this->ordem_servico->get_images($this->session->user['id_organizacao'], null);

        if ($ordens_servico !== null) {
            foreach ($ordens_servico as $os) {
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

        $departamentos = $this->departamento->get_all(
            '*',
            ['organizacao_fk' => $this->session->user['id_organizacao']],
            -1,
            -1
        );

        $tipos_servicos = $this->tipo_servico->get_all(
            '*',
            ['departamentos.organizacao_fk' => $this->session->user['id_organizacao']],
            -1,
            -1,
            [
                ['table' => 'departamentos', 'on' => 'departamentos.departamento_pk = tipos_servicos.departamento_fk'],
            ]
        );

        $prioridades = $this->prioridade->get_all(
            '*',
            ['ativo' => 1],
            -1,
            -1
        );

        $situacoes = $this->situacao->get_all(
            '*',
            ['ativo' => 1],
            -1,
            -1
        );

        $servicos = $this->servico->get_all(
            '*',
            ['departamentos.organizacao_fk' => $this->session->user['id_organizacao']],
            -1,
            -1,
            [
                ['table' => 'situacoes', 'on' => 'situacoes.situacao_pk = servicos.situacao_padrao_fk'],
                ['table' => 'tipos_servicos', 'on' => 'tipos_servicos.tipo_servico_pk = servicos.tipo_servico_fk'],
                ['table' => 'departamentos', 'on' => 'departamentos.departamento_pk = tipos_servicos.departamento_fk'],
            ]
        );

        $procedencias = $this->procedencia->get_all(
            '*',
            ['organizacao_fk' => $this->session->user['id_organizacao']],
            -1,
            -1
        );

        $setores = $this->setor->get_all(
            '*',
            ['organizacao_fk' => $this->session->user['id_organizacao']],
            -1,
            -1
        );

        $municipios = $this->localizacao->get_cities();

        $response->add_data('self', $ordens_servico);
        $response->add_data('departamentos', $departamentos);
        $response->add_data('tipos_servicos', $tipos_servicos);
        $response->add_data('prioridades', $prioridades);
        $response->add_data('situacoes', $situacoes);
        $response->add_data('servicos', $servicos);
        $response->add_data('procedencias', $procedencias);
        $response->add_data('setores', $setores);
        $response->add_data('municipios', $municipios);

        $response->send();

    }

    public function save()
    {
        try
        {

            
            $this->load->model('Localizacao_model', 'localizacao');
            $this->load->library('form_validation');
            $this->load->helper('exception');

            $this->ordem_servico->config_form_validation();
            $this->localizacao->config_form_validation();

            $this->localizacao->add_lat_long(
                $this->input->post('localizacao_lat'),
                $this->input->post('localizacao_long')
            );
            $this->input->post('localizacao_ponto_referencia') !== '' ?
            $this->localizacao->__set('localizacao_ponto_referencia', $this->input->post('localizacao_ponto_referencia')) :
            $this->localizacao->__set('localizacao_ponto_referencia', null);
            $this->localizacao->fill();

            // $this->ordem_servico->__set('situacao_atual_fk', $this->input->post('situacao_inicial_fk'));
            $this->ordem_servico->fill();

            if ($this->input->post('ordem_servico_pk') !== '') {
                $this->ordem_servico->config_form_validation_primary_key();
            }
            $this->ordem_servico->run_form_validation();

            $this->begin_transaction();

            if ($this->input->post('ordem_servico_pk') !== '') {
                $this->update();
            } else {
                $this->insert();
            }

            $this->end_transaction();
            
            $this->response->set_code(Response::SUCCESS);
            $this->response->send();

        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        }
    }

    private function insert()
    {
        $this->load->helper('insert_images');
        $this->ordem_servico->__set("localizacao_fk", $this->localizacao->insert());
        $this->ordem_servico->__set("funcionario_fk", $this->session->user['id_user']);

        $id = $this->ordem_servico->insert_os($this->session->user['id_organizacao']);
        
        $paths = upload_img(
            [
                'id' => $id,
                'path' => 'PATH_OS',
                'is_os' => true,
                'situation' => $this->ordem_servico->__get('situacao_atual_fk'),
            ],
            $this->input->post('imagens') //Recebe um array de imagens
        );

        $this->ordem_servico->insert_images($paths, $id, $this->session->user['id_organizacao']);

        $new = $this->ordem_servico->get_home($this->session->user['id_organizacao'], ['ordem_servico_pk' => $id]);
        $new[0]->imagens = $this->ordem_servico->get_images($this->session->user['id_organizacao'], ['ordem_servico_fk' => $id]);
        $this->response->add_data('id', $id);
        $this->response->add_data('new', $new[0]);

    }

    private function update()
    {
        $this->ordem_servico->__set('ordem_servico_pk', $this->input->post('ordem_servico_pk'));
        $this->ordem_servico->__set("localizacao_fk", $this->localizacao->insert());
        $this->ordem_servico->update();
    }

    public function get_historico($id)
    {
        $historicos = $this->ordem_servico->get_historico($id);

        $this->response->set_code(Response::SUCCESS);
        $this->response->add_data('historicos', $historicos);
        $this->response->send();
    }

    public function insert_situacao($id)
    {
        try {
            $this->load->helper('exception');
            $this->load->helper('insert_images');

            $this->ordem_servico->__set("ordem_servico_comentario", $_POST['ordem_servico_comentario']);
            $this->ordem_servico->__set("situacao_atual_fk", $_POST['situacao_atual_fk']);
            $this->ordem_servico->__set("ordem_servico_pk", $id);

            $paths = upload_img(
                [
                    'id' => $id,
                    'path' => 'PATH_OS',
                    'is_os' => true,
                    'situation' => $this->ordem_servico->__get('situacao_atual_fk'),
                ],
                $this->input->post('imagens')
            );

            $this->begin_transaction();

            $this->ordem_servico->handle_historico($id);

            $this->ordem_servico->update();

            $this->ordem_servico->insert_images($paths, $id, $this->session->user['id_organizacao']);
            $new = (object) [];
            // $new = $this->ordem_servico->get_home($this->session->user['id_organizacao'], ['ordem_servico_pk' => $id]);
            $new->imagens = $this->ordem_servico->get_images($this->session->user['id_organizacao'], ['ordem_servico_fk' => $id]);
            $this->response->add_data('id', $id);
            $this->response->add_data('new', $new);

            $this->end_transaction();

            $this->response->set_code(Response::SUCCESS);
            $this->response->send();

        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        }
    }

    public function delete($id)
    {

        try {
            $this->load->helper('exception');
            $this->load->model('relatorio_model', 'relatorio');

            $this->ordem_servico->__set("ordem_servico_pk", $id);

            $os = $this->relatorio->get_orders_of_report(
                [
                    'relatorios_os.os_fk' => $id,
                ],
                true
            );

            if ($os) {
                throw new MyException('A ordem não pode ser excluida pois está presente em um relatório', Response::BAD_REQUEST);
            }

            $os = $this->ordem_servico->get_historico($id);

            if ($os) {
                throw new MyException('A ordem não pode ser excluída pois possúi um histórico de modificações', Response::BAD_REQUEST);
            }

            $this->ordem_servico->__set("ativo", 0);

            $this->begin_transaction();

            $this->ordem_servico->update();

            $this->end_transaction();

            $this->response->set_code(Response::SUCCESS);
            $this->response->send();

        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        }
    }

    public function get_map()
    {
        $ordens_servico = $this->ordem_servico->get_map($this->input->post());

        $response = new Response();
        $response->set_data($ordens_servico);
        $response->send();
    }

    public function get_specific($ordem_servico_pk)
    {
        $ordem_servico = $this->ordem_servico->get_home(
            $this->session->user['id_organizacao'],
            ['ordens_servicos.ordem_servico_pk' => $ordem_servico_pk]
        );
        $os_hist = $this->ordem_servico->get_historico($ordem_servico_pk);
        $os_images = $this->ordem_servico->get_images_id($ordem_servico_pk);

        $response = new Response();
        $response->add_data('historico', $os_hist);
        $response->add_data('imagens', $os_images);
        $response->add_data('ordem_servico', $ordem_servico);
        $response->send();
    }
}
