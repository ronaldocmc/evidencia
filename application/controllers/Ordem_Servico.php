<?php   

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once dirname(__FILE__) . "/Response.php";

require_once APPPATH . "core\CRUD_Controller.php";

require_once APPPATH . "models\Ordem_Servico_model.php";

//TODO mudar para CRUD_Controller
class Ordem_Servico extends CI_Controller
{
    public $response;

    public function __construct()
    {
        //Realizando o carregamento dos models que são utilizados em diversas funções de inserção, atualização e remoção.
        parent::__construct();
        $this->load->model('Ordem_Servico_model', 'ordem_servico_model');
        $this->load->model('Prioridade_model', 'prioridade_model');
        $this->load->model('Situacao_model', 'situacao_model');
        $this->load->model('Servico_model', 'servico_model');
        $this->load->model('Historico_model', 'historico_model');
        $this->load->model('Procedencia_model', 'procedencia_model');
        $this->load->model('Setor_model', 'setor_model');
        $this->load->model('Departamento_model', 'departamento_model');
        $this->load->model('pessoa_model');
        $this->load->library('upload');
        $this->load->model('Tipo_Servico_model', 'tipo_servico_model');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $response = new Response();
    }

    private function load()
    {
        $this->load->library('form_validation');
        $this->load->model('localizacao_model', 'localizacao');
        $this->load->helper('exception');
        $this->response = new Response();

        $this->localizacao->config_form_validation();
        $this->ordem_servico->config_form_validation();
    }

    public function index()
    {
        //Criando um array de ordens de serviço com todos os dados necessários a serem exibidos na view index
        $ordens_servico = $this->ordem_servico_model->getHome([
            'prioridades.organizacao_fk' => $this->session->user['id_organizacao'],
        ]);
        // Intervalo de uma semana para trás
        date_default_timezone_set('America/Sao_Paulo');
        $data_final = date('Y-m-d', time()) . ' 23:59:00';
        $data_inicial = date('Y-m-d', strtotime('-7 days')) . ' 00:00:00';
        // Filtra com a flag 7 (data inicial e final levadas em consideração)
        $ordens_servico = $this->filtra_ordens_view(
            $ordens_servico,
            $data_inicial,
            $data_final,
            '',
            7
        );
        if ($ordens_servico !== null) {
            foreach ($ordens_servico as $os) {
                $os->data_criacao = date('d/m/Y H:i:s', strtotime($os->data_criacao));
            }
        }
        //Criando um array de departamentos pertencentes a organização do usuário
        $departamentos = $this->departamento_model->get([
            'organizacao_fk' => $this->session->user['id_organizacao'],
        ]);
        //Criando um array de tipos de serviços com dados necessário a serem exibidos na view index
        $tipos_servico = $this->tipo_servico_model->get([
            'departamentos.organizacao_fk' => $this->session->user['id_organizacao'],
        ]);
        //Criando um array de prioridades com dados necessário a serem exibidos na view index
        $prioridades = $this->prioridade_model->get([
            'organizacao_fk' => $this->session->user['id_organizacao'],
        ]);
        //Criando um array de situações de serviços (Aberta, Em andamento, Fechada) com dados necessário a serem exibidos na view index
        $situacoes = $this->situacao_model->get([
            'organizacao_fk' => $this->session->user['id_organizacao'],
        ]);
        //Criando um array de serviços (Coleta de Entulho, Limpeza, Retirada) com dados necessário a serem exibidos na view index
        $servicos = $this->servico_model->get([
            'situacoes.organizacao_fk' => $this->session->user['id_organizacao'],
        ]);
        //Criando um array de prodecencias de serviços (Interno/Externo) com dados necessário a serem exibidos na view index
        $procedencias = $this->procedencia_model->get([
            'procedencias.organizacao_fk' => $this->session->user['id_organizacao'],
        ]);
        //Criando um array de setores (A, B, C, D) com dados necessário a serem exibidos na view index
        $setores = $this->setor_model->get([
            'setores.organizacao_fk' => $this->session->user['id_organizacao'],
        ]);
        //Carregando arquivos CSS no flashdata da session para as views
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
        //Carregando arquivos SCRIPT no flashdata da session para as views
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
                    'superusuario' => $this->session->user['is_superusuario'],
                ],
            ],
        ], 'administrador');
    }

    public function save()
    {
        try
        {
            $this->load();
            // $organizacao_pk = $this->session->user['id_organizacao'];

            if ($this->is_superuser()) {
                $this->add_password_to_form_validation();
            }

            $this->ordem_servico->fill();
            $this->localizacao->fill();
            //TODO
            //form validation tem que estar dentro dos métodos -> insert tem a validação do organizacao_pk também
            $this->organizacao->run_form_validation();

            $this->begin_transaction();

            if (isset($organizacao_pk)) {
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
        $this->organizacao->__set("localizacao_fk", $this->localizacao->insert());
        $this->organizacao->insert();
    }

    private function update()
    {
        $this->localizacao->update();
        $this->organizacao->update();
    }

    public function deactivate()
    {
        //TODO
    }

    public function activate()
    {
        //TODO
    }

    private function is_superuser()
    {
        return $this->session->user['is_superusuario'];
    }

    private function add_password_to_form_validation()
    {
        $this->form_validation->set_rules(
            'senha',
            'senha',
            'trim|required|min_length[8]'
        );
    }

    public function begin_transaction()
    {
        $this->db->trans_start();
    }

    public function end_transaction()
    {
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            if (is_array($this->db->error())) {
                throw new MyException('Erro ao realizar operação.<br>' . implode('<br>', $this->db->error()), Response::SERVER_FAIL);
            } else {
                throw new MyException('Erro ao realizar operação.<br>' . $this->db->error(), Response::SERVER_FAIL);
            }
        } else {
            $this->db->trans_commit();
        }
    }
}
