<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once dirname(__FILE__) . "/Response.php";

require_once APPPATH . "core\CRUD_Controller.php";

require_once APPPATH . "models\Ordem_Servico_model.php";

require_once APPPATH . "core\MyException.php";

//TODO mudar para CRUD_Controller
class Ordem_Servico extends CRUD_Controller
{
    public $response;

    public function __construct()
    {
        parent::__construct();
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

    public function index()
    {
        $ordens_servico = $this->ordem_servico->get_all(
            'ordens_servicos.ordem_servico_pk,
            ordens_servicos.ordem_servico_cod,
            ordens_servicos.ativo,
            ordens_servicos.ordem_servico_desc,
            ordens_servicos.ordem_servico_criacao,
            ordens_servicos.ordem_servico_atualizacao,
            ordens_servicos.ordem_servico_comentario,
            ordens_servicos.funcionario_fk,
            prioridades.prioridade_pk,
            prioridades.prioridade_nome,
            servicos.servico_pk,
            servicos.servico_nome,
            si.situacao_pk as situacao_inicial_pk,
            si.situacao_nome as situacao_inicial_nome,
            sa.situacao_pk as situacao_atual_pk,
            sa.situacao_nome as situacao_atual_nome,
            setores.setor_pk,
            setores.setor_nome,
            localizacoes.localizacao_lat,
            localizacoes.localizacao_long,
            localizacoes.localizacao_rua,
            localizacoes.localizacao_num,
            localizacoes.localizacao_bairro,
            localizacoes.localizacao_ponto_referencia,
            municipios.municipio_nome,
            funcionarios.funcionario_nome,
            funcionarios.funcionario_caminho_foto,
            procedencias.procedencia_nome,
            ',
            [
                'procedencias.organizacao_fk' => $this->session->user['id_organizacao'],
            ],
            -1,
            -1,
            [
                ['table' => 'prioridades', 'on' => 'prioridades.prioridade_pk = ordens_servicos.prioridade_fk'],
                ['table' => 'procedencias', 'on' => 'procedencias.procedencia_pk = ordens_servicos.procedencia_fk'],
                ['table' => 'servicos', 'on' => 'servicos.servico_pk = ordens_servicos.servico_fk'],
                ['table' => 'situacoes as si', 'on' => 'si.situacao_pk = ordens_servicos.situacao_inicial_fk'],
                ['table' => 'situacoes as sa', 'on' => 'sa.situacao_pk = ordens_servicos.situacao_atual_fk'],
                ['table' => 'setores', 'on' => 'setores.setor_pk = ordens_servicos.setor_fk'],
                ['table' => 'localizacoes', 'on' => 'localizacoes.localizacao_pk = ordens_servicos.localizacao_fk'],
                ['table' => 'municipios', 'on' => 'municipios.municipio_pk = localizacoes.localizacao_municipio'],
                ['table' => 'funcionarios', 'on' => 'funcionarios.funcionario_pk = ordens_servicos.funcionario_fk'],
            ]
        );

        $imagens = $this->ordem_servico->get_images($this->session->user['id_organizacao']);

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

        $this->load();
        $departamentos = $this->departamento->get_all(
            '*',
            ['organizacao_fk' => $this->session->user['id_organizacao']],
            -1,
            -1
        );

        $tipos_servico = $this->tipo_servico->get_all(
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
            ['organizacao_fk' => $this->session->user['id_organizacao']],
            -1,
            -1
        );

        $situacoes = $this->situacao->get_all(
            '*',
            ['organizacao_fk' => $this->session->user['id_organizacao']],
            -1,
            -1
        );

        $servicos = $this->servico->get_all(
            '*',
            ['situacoes.organizacao_fk' => $this->session->user['id_organizacao']],
            -1,
            -1,
            [
                ['table' => 'situacoes', 'on' => 'situacoes.situacao_pk = servicos.situacao_padrao_fk'],
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
                'params' => [
                    'ordens_servico' => $ordens_servico,
                    'prioridades' => $prioridades,
                    'situacoes' => $situacoes,
                    'servicos' => $servicos,
                    'departamentos' => $departamentos,
                    'tipos_servico' => $tipos_servico,
                    'setores' => $setores,
                    'procedencias' => $procedencias,
                    'municipios' => $municipios,
                    'superusuario' => $this->session->user['is_superusuario'],
                ],
            ],
        ], 'administrador');
    }

    public function save()
    {
        try
        {
            $this->load->model('Localizacao_model', 'localizacao');

            $this->load->library('form_validation');
            $this->load->helper('exception');
            $this->load->helper('insert_images');

            $this->ordem_servico->fill();

            $this->localizacao->add_lat_long(
                $this->input->post('localizacao_lat'),
                $this->input->post('localizacao_long')
            );
            $this->localizacao->fill();

            $this->ordem_servico->config_form_validation();
            $this->localizacao->config_form_validation();

            if ($this->input->post('ordem_servico_pk') !== '') {
                $this->ordem_servico->config_form_validation_primary_key();
            }

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
            [0 => $this->input->post('img')]//talvez seja interessante a view já mandar no formato de array mesmo quando é uma.
        );

        $this->ordem_servico->insert_images($paths, $id);
    }

    private function update()
    {

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
                [0 => $this->input->post('image_os')]//talvez seja interessante a view já mandar no formato de array mesmo quando é uma.
            );

            $this->begin_transaction();

            $this->ordem_servico->handle_historico($id);

            $this->ordem_servico->update();

            $this->ordem_servico->insert_images($paths, $id);

            $this->end_transaction();

            $this->response->set_code(Response::SUCCESS);
            $this->response->send();

        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        }
    }

    public function deactivate()
    {
        //TODO
    }

    public function activate()
    {
        //TODO
    }
}
