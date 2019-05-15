<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once dirname(__FILE__).'/Response.php';
require_once APPPATH.'core/CRUD_Controller.php';

class Tipo_Servico extends CRUD_Controller
{
    public function __construct()
    {
        date_default_timezone_set('America/Sao_Paulo');
        parent::__construct();
        $this->load->model('tipo_servico_model');
    }

    public function index()
    {
        //Para testes
        // $response = new Response();
        // ---

        $this->load->model('departamento_model');
        $this->load->model('prioridade_model');

        $tipos_servicos = $this->tipo_servico_model->get([
            'departamentos.organizacao_fk' => $this->session->user['id_organizacao'],
            'departamentos.ativo' => '1',
        ]);

        $depts_aux = $this->departamento_model->get([
            'organizacao_fk' => $this->session->user['id_organizacao'],
            'departamentos.ativo' => '1',
        ]);

        $prioridades_aux = $this->prioridade_model->get([
            'organizacao_fk' => $this->session->user['id_organizacao'],
            'prioridades.prioridade_desativar_tempo' => null,
        ]);

        if ($prioridades_aux !== false) {
            foreach ($prioridades_aux as $p) {
                $prioridades[$p->prioridade_pk] = $p->prioridade_nome;
            }
        }

        if ($depts_aux !== false) {
            foreach ($depts_aux as $d) {
                $departamentos[$d->departamento_pk] = $d->departamento_nome;
            }
        }

        // Para testes
        // if(!$depts_aux)
        // {
        // 	$response->set_code(Response::NOT_FOUND);
        // }
        // else
        // {
        // 	$response->set_code(Response::SUCCESS);
        // 	$response->set_data($depts_aux);
        // }
        // $response->send();
        // ----

        $this->session->set_flashdata('css', [
            0 => base_url('assets/css/modal_desativar.css'),
            1 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.css'),
            2 => base_url('assets/css/loading_input.css'),
            3 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css'),
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
            9 => base_url('assets/js/dashboard/tipo-servico/index.js'),
            10 => base_url('assets/vendor/select-input/select-input.js'),
        ]);

        $this->load->helper('form');

        load_view([
            0 => [
                'src' => 'dashboard/administrador/tipo-servico/home',
                'params' => [
                    'tipos_servicos' => $tipos_servicos,
                    'prioridades' => $prioridades,
                    'departamentos' => $departamentos,
                ],
            ],
        ], 'administrador');
    }

    /**
     * Função responsável por validar os dados vindos da requisição de insert_update.
     *
     * @param Requisição POST com tipo_servico_nome, tipo_servico_desc, prioridade_fk e
     *		  departamento_fk, e, se setada, a tipo_servico_pk
     *
     * @return Objeto Response caso falhe, ou então, TRUE, caso esteja correto
     */
    private function form_validation_insert_update()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules(
            'tipo_servico_nome',
            'tipo_servico_nome',
            'trim|required|max_length[30]'
        );

        $this->form_validation->set_rules(
            'tipo_servico_abreviacao',
            'tipo_servico_abreviacao',
            'trim|required|max_length[10]'
        );

        $this->form_validation->set_rules(
            'tipo_servico_desc',
            'tipo_servico_desc',
            'trim|required|max_length[200]'
        );

        $this->form_validation->set_rules(
            'prioridade_pk',
            'prioridade_pk',
            'trim|numeric'
        );

        $this->form_validation->set_rules(
            'departamento_pk',
            'departamento_pk',
            'trim|required|numeric'
        );

        if ($this->input->post('tipo_servico_pk') != '') {
            $this->form_validation->set_rules(
                'tipo_servico_pk',
                'tipo_servico_pk',
                'trim|required|numeric'
            );
        }

        if ($this->form_validation->run()) {
            return true;
        } else {
            $response = new Response();
            $response->set_code(Response::BAD_REQUEST);
            $response->set_data($this->form_validation->errors_array());

            return $response;
        }
    }

    /**
     * Função responsável por criar ou editar uma situação.
     *
     * @param Requisição POST com tipo_servico_nome, tipo_servico_desc, prioridade_pk e
     *		  departamento_pk, e, se setada, a tipo_servico_pk
     *
     * @return Objeto Response
     */
    public function insert_update()
    {
        // Validação dos dados da requisição
        $result_form_validation = $this->form_validation_insert_update();

        if ($result_form_validation === true) {
            $response = new Response();

            // Leitura dos dados do tipo de serviço
            $tipo_servico['tipo_servico_nome'] = $this->input->post('tipo_servico_nome');
            $tipo_servico['tipo_servico_abreviacao'] = $this->input->post('tipo_servico_abreviacao');
            $tipo_servico['tipo_servico_desc'] = $this->input->post('tipo_servico_desc');

            // Verificando se a prioriedade passada existe no banco
            $this->load->model('prioridade_model');

            $prioridade = $this->prioridade_model->get($this->input->post('prioridade_pk'));

            if (!$prioridade) {
                $response->set_code(Response::NOT_FOUND);
                $response->set_data([
                    'erro' => 'Prioridade passada não encontrada',
                ]);
                $response->send();

                return;
            } else {
                $tipo_servico['prioridade_padrao_fk'] = $prioridade[0]->prioridade_pk;
            }

            // Verificando se o departamento passado existe no banco
            $this->load->model('departamento_model');

            $departamento = $this->departamento_model->get($this->input->post('departamento_pk'));

            if (!$departamento) {
                $response->set_code(Response::NOT_FOUND);
                $response->set_data([
                    'erro' => 'Departamento passada não encontrada',
                ]);
                $response->send();

                return;
            } else {
                $tipo_servico['departamento_fk'] = $departamento[0]->departamento_pk;
            }

            // Update
            if ($this->input->post('tipo_servico_pk') != '') {
                $tipo_servico['tipo_servico_pk'] = $this->input->post('tipo_servico_pk');

                // Se houver a tipo_servico_pk, trata-se de um update
                $resultado = $this->tipo_servico_model->update($tipo_servico,
                    $this->input->post('tipo_servico_pk'));

                if (!$resultado) {
                    // Caso o update falhe
                    $response->set_code(Response::DB_ERROR_UPDATE);
                    $response->set_data([
                        'erro' => 'Erro no update do tipo de serviço:'.$resultado,
                    ]);
                } else {
                    // Caso o update obteve sucesso
                    $response->set_code(Response::SUCCESS);
                }
            }
            // Insert
            else {
                $resultado = $this->tipo_servico_model->insert($tipo_servico);

                if (!$resultado) {
                    $response->set_code(Response::DB_ERROR_INSERT);
                    $response->set_data([
                        'erro' => 'Erro na inserção do tipo de serviço',
                    ]);
                } else {
                    $response->set_code(Response::SUCCESS);
                    $response->set_data([
                        'tipo_servico_pk' => $resultado,
                    ]);
                }
            }

            $response->send();
        } else {
            $result_form_validation->send();
        }
    }

    /**
     * Método responsável por desativar um tipo de serviço.
     *
     * @param pk do tipo de servico
     *
     * @return objeto Response contendo sucesso ou erros
     */
    public function deactivate()
    {
        $this->load->library('form_validation');

        $response = new Response();

        $this->form_validation->set_rules(
            'tipo_servico_pk',
            'tipo_servico_pk',
            'trim|required|numeric'
        );

        if ($this->form_validation->run()) {
            $tipo_servico = $this->tipo_servico_model->get($this->input->post('tipo_servico_pk'));

            if ($tipo_servico !== false) { // se existe tipo serviço:
                $this->load->model('servico_model');

                //BEGIN_TRANSACTION
                $servicos = $this->servico_model->get(['tipo_servico_fk' => $this->input->post('tipo_servico_pk')]);
                // $resposta = true;
                // if($servicos != false){ // ou seja, se existe serviços:
                // 	foreach($servicos as $servico)
                // 	{
                // 		if($resposta){
                // 			$resposta = $this->servico_model->update(['servico_status' => '0'], array('servico_pk' => $servico->servico_pk));
                // 		}
                // 		else{ //se deu erro:
                // 			$resposta = false;
                // 		}
                // 	}
                // }
                if ($servicos == false) {
                    $existe_servicos_dependentes = false;
                } else {
                    $existe_servicos_dependentes = true;
                }

                if ($existe_servicos_dependentes) {
                    $response = new Response();
                    $response->set_code(Response::FORBIDDEN);
                    $response->set_data(['erro' => 'Este tipo de serviço possui serviços dependentes.']);
                } else { //se não der problema na desativação dos serviços dependentes:
                    $resultado = $this->tipo_servico_model->update([
                        'tipo_servico_status' => '0',
                    ], $this->input->post('tipo_servico_pk'));

                    if ($resultado === 0) { //se der falha ao desativar o tipo de serviço:
                        $response = new Response();
                        $response->set_code(Response::DB_ERROR_UPDATE);
                        $response->set_data(['erro' => 'Erro na desativação do tipo de serviço']);
                    } else { //se der tudo certo:
                        $response->set_code(Response::SUCCESS);
                    }
                }
            } else { //se o tipo de serviço não existir
                $response = new Response();
                $response->set_code(Response::NOT_FOUND);
                $response->set_data(['erro' => 'Tipo de serviço não encontrado']);
            }
        } else { //se o form valid der erro:
            $response = new Response();
            $response->set_code(Response::BAD_REQUEST);
            $response->set_data($this->form_validation->errors_array());
        }

        $response->send();
    }

    /**
     * Função responsável por ativar um tipo de serviço.
     *
     * @param Requisição POST com a tipo_servico_pk
     *
     * @return Objeto Response
     */
    public function activate()
    {
        $this->load->library('form_validation');

        $response = new Response();

        $this->form_validation->set_rules(
            'tipo_servico_pk',
            'tipo_servico_pk',
            'trim|required|numeric'
        );

        if ($this->form_validation->run()) {
            $tipo_servico = $this->tipo_servico_model->get($this->input->post('tipo_servico_pk'));

            if ($tipo_servico !== false) {
                $resultado = $this->tipo_servico_model->update([
                    'tipo_servico_status' => '1',
                ], $this->input->post('tipo_servico_pk'));

                if ($resultado === 0) {
                    $response = new Response();
                    $response->set_code(Response::DB_ERROR_UPDATE);
                    $response->set_data(['erro' => 'Erro na ativação do tipo de serviço']);
                } else {
                    $response->set_code(Response::SUCCESS);
                }
            } else {
                $response = new Response();
                $response->set_code(Response::NOT_FOUND);
                $response->set_data(['erro' => 'Tipo de serviço não encontrado']);
            }
        } else {
            $response = new Response();
            $response->set_code(Response::BAD_REQUEST);
            $response->set_data($this->form_validation->errors_array());
        }

        $response->send();
    }

    public function get_dependent_services()
    {
        $response = new Response();

        $this->load->model('servico_model');

        $tipo_servico_pk = $this->input->post('tipo_servico_pk');
        if (!is_numeric($tipo_servico_pk)) { //se por algum motivo não for um número:
            $response = new Response();
            $response->set_code(Response::BAD_REQUEST);
            $response->set_data(['erro' => 'Erro ao localizar tipo de serviço.']);
        } else { //se é número:
            $resultado = $this->servico_model->get_objects([
                'servicos.tipo_servico_fk' => $tipo_servico_pk, 'servicos.servico_status' => '1',
            ]);

            $response->set_code(Response::SUCCESS);
            $response->set_data($resultado);
        }

        $response->send();
    }
}
