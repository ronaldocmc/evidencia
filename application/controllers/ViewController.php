<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
require_once APPPATH.'core/AuthorizationController.php';

class ViewController extends AuthorizationController
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->has_userdata('user')) {
            if (!$this->is_superuser()) {
                $this->check_permissions();
            }
        } else {
            redirect(base_url());
        }
    }

    public function index($name)
    {
        $this->_load_css();

        $this->_load_scripts($name);

        $this->_load_view($name, $name.'s', null);
    }

    public function funcionario()
    {
        $organizacao = $this->session->user['id_organizacao'];

        $dados['primeiro_nome'] = $this->primeiro_nome($this->session->user['name_user']);

        $this->session->set_flashdata('css', [
            0 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css'),
            1 => base_url('assets/vendor/icon-hover-effects/component.css'),
            2 => base_url('assets/vendor/icon-hover-effects/default.css'),
            3 => base_url('assets/css/dashboard.css'),
        ]);

        $this->session->set_flashdata('scripts', [
            0 => base_url('assets/js/constants.js'),
            1 => base_url('assets/js/dashboard/dashboard/dashboard.js'),
            2 => base_url('assets/vendor/masks/jquery.mask.min.js'),
            3 => base_url('assets/vendor/datatables/datatables.min.js'),
            4 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js'),
            5 => base_url('assets/js/utils.js'),
        ]);
        load_view([
            0 => [
                'src' => 'dashboard/administrador/principal/dashboard',
                'params' => $dados,
            ],
        ], 'administrador');
    }

    public function listar_relatorios()
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

    public function novo_relatorio()
    {
        //Carregando os models para recuperação de dados a serem exibidos na view Novo Relatório
        $this->load->model('Servico_model', 'servico_model');
        $this->load->model('Tipo_Servico_model', 'tipo_servico_model');
        $this->load->model('Setor_model', 'setor_model');
        $this->load->model('Funcionario_model', 'funcionario_model');

        //Selecionando o setores
        $setores = $this->setor_model->get_all(
            '*',
            ['organizacao_fk' => $this->session->user['id_organizacao']],
            -1,
            -1
        );
        //Selecionando os tipos de serviços
        $tipos_servicos = $this->tipo_servico_model->get(
            '*',
            ['departamentos.organizacao_fk' => $this->session->user['id_organizacao']]
        );
        //Selecionando os serviços
        $servicos = $this->servico_model->get(
            '*',
            ['departamentos.organizacao_fk' => $this->session->user['id_organizacao']]
        );
        //Selecionando os funcionários com a função de revisor
        $funcionarios = $this->funcionario_model->get_revisores(
            '*',
            $this->session->user['id_organizacao']
        );
        //Carregando CSS utilizados na view
        $this->session->flashdata('css', [
            0 => base_url('assets/css/modal_desativar.css'),
        ]);
        //Carregando os scripts utilizados na view
        $this->session->set_flashdata('scripts', [
            0 => base_url('assets/js/constants.js'),
            1 => base_url('assets/vendor/datatables/datatables.min.js'),
            2 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js'),
            3 => base_url('assets/js/jquery.noty.packaged.min.js'),
            4 => base_url('assets/js/dashboard/relatorio/relatorios_gerais.js'),
            5 => base_url('assets/js/utils.js'),
        ]);

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

    public function minha_conta()
    {
        $this->load->model('funcionario_model');

        $this->session->set_flashdata('css', array(
            0 => base_url('assets/vendor/cropper/cropper.css'),
            1 => base_url('assets/vendor/input-image/input-image.css'),
            2 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.css'),
            3 => base_url('assets/css/modal_desativar.css'),
            4 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css'),
            5 => base_url('assets/css/loading_input.css'),
        ));

        $this->session->set_flashdata('scripts', array(
            0 => base_url('assets/vendor/masks/jquery.mask.min.js'),
            1 => base_url('assets/vendor/bootstrap-multistep-form/jquery.easing.min.js'),
            2 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.js'),
            3 => base_url('assets/vendor/cropper/cropper.js'),
            4 => base_url('assets/vendor/input-image/input-image.js'),
            5 => base_url('assets/vendor/datatables/datatables.min.js'),
            6 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js'),
            7 => base_url('assets/js/masks.js'),
            8 => base_url('assets/js/utils.js'),
            9 => base_url('assets/js/constants.js'),
            10 => base_url('assets/js/jquery.noty.packaged.min.js'),
            11 => base_url('assets/js/dashboard/pessoa/index.js'),
            12 => base_url('assets/vendor/select-input/select-input.js'),
        ));

        $this->funcionario_model->__set('funcionario_pk', $this->session->user['id_user']);
        $return['worker'] = $this->funcionario_model->get_or_404();

        load_view([
            0 => [
                'src' => 'dashboard/commons/profile/home',
                'params' => $return,
            ],
            1 => [
                'src' => 'access/pre_loader',
                'params' => null,
            ],
        ], $this->session->user['is_superusuario'] ? 'superusuario' : 'administrador');
    }

    public function editar_informacoes_organizacao()
    {
        $this->load->model('organizacao_model', 'organizacao');
        // Pegando a organização salva na session
        if ($this->has_organization()) {
            $this->load->model('municipio_model', 'municipio');

            $data['organizacao'] = $this->organizacao->get_object($this->session->user['id_organizacao']);

            $data['municipios'] = $this->municipio->get(); //get all

            // CSS para a edição
            $this->session->set_flashdata('css', [
                0 => base_url('assets/css/modal_desativar.css'),
                1 => base_url('assets/css/loading_input.css'),
                2 => base_url('assets/css/media_query_edit_org.css'),
            ]);
            //Scripts para edição
            $this->session->set_flashdata('scripts', [
                0 => base_url('assets/js/localizacao.js'),
                1 => base_url('assets/vendor/datatables/datatables.min.js'),
                2 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js'),
                3 => base_url('assets/vendor/select-input/select-input.js'),
                4 => base_url('assets/js/dashboard/organizacao/edit_info.js'),
                5 => base_url('assets/js/constants.js'),
                6 => base_url('assets/js/utils.js'),
                7 => base_url('assets/js/jquery.noty.packaged.min.js'),
                8 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.js'),
            ]);
            load_view([
                0 => [
                    'src' => 'dashboard/administrador/organizacao/editar_informacoes',
                    'params' => $data,
                ],
            ], 'administrador');
        }
    }

    public function mapa()
    {
        $this->load->model('Prioridade_model', 'prioridade');
        $this->load->model('Situacao_model', 'situacao');
        $this->load->model('Departamento_model', 'departamento');
        $this->load->model('Servico_model', 'servico');
        $this->load->model('Tipo_Servico_model', 'tipo_servico');
        $this->load->model('Setor_model', 'setor');
        $this->load->helper('form');

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
        $setores = $this->setor->get_all(
            '*',
            ['setores.organizacao_fk' => $this->session->user['id_organizacao']],
            -1,
            -1
        );

        $this->session->set_flashdata('css', [
            0 => base_url('assets/css/modal_desativar.css'),
            1 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.css'),
            2 => base_url('assets/css/loading_input.css'),
            3 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css'),
            4 => base_url('assets/css/modal_map.css'),
            5 => base_url('assets/css/timeline.css'),
            6 => base_url('assets/css/style_card.css'),
            7 => base_url('assets/css/user_guide.css'),
        ]);
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
            9 => base_url('assets/js/dashboard/mapa/mapa.js'),
            10 => base_url('assets/vendor/select-input/select-input.js'),
            11 => base_url('assets/js/localizacao.js'),
        ]);
        $this->session->set_flashdata('mapa', [
            0 => true,
        ]);

        load_view([
            0 => [
                'src' => 'dashboard/administrador/mapa/home',
                'params' => [
                    'prioridades' => $prioridades,
                    'situacoes' => $situacoes,
                    'servicos' => $servicos,
                    'departamentos' => $departamentos,
                    'tipos_servicos' => $tipos_servicos,
                    'setores' => $setores,
                ],
            ],
            1 => [
                'src' => 'access/pre_loader',
                'params' => null,
            ],
        ], 'administrador');
    }

    private function _load_css()
    {
        $this->session->set_flashdata('css', [
            0 => base_url('assets/css/modal_desativar.css'),
            1 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.css'),
            2 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css'),
            3 => base_url('assets/css/user_guide.css'),
        ]);
    }

    private function _load_scripts($name)
    {
        $this->session->set_flashdata('scripts', [
            0 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.js'),
            1 => base_url('assets/vendor/bootstrap-multistep-form/jquery.easing.min.js'),
            2 => base_url('assets/vendor/datatables/datatables.min.js'),
            3 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js'),
            4 => base_url('assets/js/utils.js'),
            5 => base_url('assets/js/constants.js'),
            6 => base_url('assets/js/jquery.noty.packaged.min.js'),
            7 => base_url('assets/js/dashboard/'.$name.'/index.js'),
            8 => base_url('assets/js/response_messages.js'),
            9 => base_url('assets/vendor/input-image/input-image.js'),
        ]);
    }

    private function _load_view($name, $plural, $data)
    {
        load_view([
            0 => [
                'src' => 'dashboard/administrador/'.$name.'/home',
                'params' => [$plural => $data],
            ],
        ], 'administrador');
    }

    private function check_permissions()
    {
        if (!$this->is_authorized()) {
            $this->load_view_forbidden();
        }
    }

    private function has_organization()
    {
        return $this->session->user['id_organizacao'];
    }

    private function primeiro_nome($nome)
    {
        $array = explode(' ', $nome);

        return $array[0];
    }

    private function load_view_forbidden()
    {
        $response = new Response();

        $response->set_code(Response::FORBIDDEN);
        $response->set_data(['error' => 'Você não possui permissão para acessar esta área']);
        $data['response'] = $response;
        $this->load->view('errors/padrao/home', $data);
    }

    private function get_all_reports()
    {
        $current_date = date('Y-m-d H:i:s');
        $lastweek_time = mktime(0, 0, 0, date('m'), date('d') - 7, date('Y'));
        $lastweek_date = date('Y-m-d H:i:s', $lastweek_time);

        $this->load->model('Relatorio_model', 'report_model');
        $reports = $this->report_model->get_all_reports($this->session->user['id_organizacao'], $current_date, $lastweek_date);

        if ($reports) {
            //arrumando a data:
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
}
