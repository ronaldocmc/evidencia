<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once dirname(__FILE__) . "\Historico_Prazo.php";
require_once dirname(__FILE__) . "\Response.php";
require_once APPPATH . "core\CRUD_Controller.php";

class Prioridade extends CRUD_Controller
{
    private $CI;

    public function __construct()
    {
        date_default_timezone_set('America/Sao_Paulo');
        if ($this->CI = &get_instance() === null) {
            parent::__construct();
            $this->CI = &get_instance();
        }
        $this->CI->load->model('prioridade_model');
    }

    public function index()
    {

        $prioridades = $this->prioridade_model->get([
            'organizacao_fk' => $this->CI->session->user['id_organizacao'],
            'prioridade_desativar_tempo' => null,
        ]);

        if($prioridades != null){

            foreach($prioridades as $p){
                if($p->prazo_duracao == 1){
                    $p->prazo_duracao = explode(" ", $p->prazo_duracao)[0].' hora';
                }else{
                    $p->prazo_duracao = explode(" ", $p->prazo_duracao)[0].' horas';
                }
            }
        }

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

            9 => base_url('assets/js/dashboard/prioridade/index.js'),

            10 => base_url('assets/vendor/select-input/select-input.js'),

        ]);

        load_view([

            0 => [

                'src' => 'dashboard/administrador/prioridade/home',

                'params' => ['prioridades' => $prioridades],

            ],

        ], 'administrador');
    }

    /**
     * Método responsável por criar as prioridades padrões de uma nova organização
     *
     * @param chave primária da organização
     * @return
     */
    public function create_standart($organizacao_pk)
    {
        // Para teste
        // $response = new Response();
        // ---

        $this->CI->load->model('organizacao_model');
        $organizacao = $this->CI->organizacao_model->get($organizacao_pk);
        if ($organizacao === false) {
            // Para teste
            // $response->set_code(Response::NOT_FOUND);
            // $response->set_data([
            //     'erro' => 'Organização não encontrada'
            // ]);
            // $response->send();
            // ---

            return;
        }

        $padroes = [
            [
                'prioridade_nome' => 'Baixa',
                'organizacao_fk' => $organizacao_pk,
            ],
            [
                'prioridade_nome' => 'Média',
                'organizacao_fk' => $organizacao_pk,
            ],
            [
                'prioridade_nome' => 'Alta',
                'organizacao_fk' => $organizacao_pk,
            ],
            [
                'prioridade_nome' => 'Urgente',
                'organizacao_fk' => $organizacao_pk,
            ],
        ];

        foreach ($padroes as $pos => $p) {
            $prioridades[$pos] = $this->CI->prioridade_model->insert($p);

            if ($prioridades[$pos] === false) {
                // Para teste
                // $response->set_code(Response::DB_ERROR_INSERT);
                // $response->set_data([
                //     'erro' => 'Erro na inserção'
                // ]);
                // $response->send();
                // ---

                return;
            }
        }

        // Para teste
        // $response->set_code(Response::SUCCESS);
        // $response->send();

        // return;
        // ---

        $historicos = new Historico_Prazo();
        $historicos->create_standart($prioridades);
    }

    /**
     * Método responsável por criar uma prioridade
     *
     * @param informações da prioridade a ser criada ou editada e o id da organização na seção
     * @return objeto Response contendo sucesso ou erros
     */
    public function insert_update()
    {
        $this->CI->load->library('form_validation');
        $this->CI->load->model('historico_prazo_model');
        $response = new Response();

        // Regras de form_validation
        $this->CI->form_validation->set_rules(
            'prioridade_nome',
            'prioridade_nome',
            'trim|required'
        );

        $this->CI->form_validation->set_rules(
            'prioridade_duracao',
            'prioridade_duracao',
            'trim|required|numeric|is_natural'
        );

        if ($this->CI->input->post('prioridade_pk') != '') {
            $this->CI->form_validation->set_rules(
                'prioridade_pk',
                'prioridade_pk',
                'trim|required|numeric'
            );
        }

        // Verifica se é um superusuário
        if ($this->CI->session->user['is_superusuario']) {
            $this->CI->form_validation->set_rules(
                'senha',
                'senha',
                'trim|required|min_length[8]'
            );
        }

        if ($this->CI->form_validation->run()) {
            // Lê os dados da requisição e da seção
            $prioridade['prioridade_nome'] = $this->CI->input->post('prioridade_nome');
            $prazo['prazo_duracao'] = $this->CI->input->post('prioridade_duracao') . ' hours';

            // Se não foi passada a pk da prioridade, trata-se de um insert
            if ($this->CI->input->post('prioridade_pk') == '') {
                $prioridade['organizacao_fk'] = $this->CI->session->user['id_organizacao'];

                // Inserção no banco
                $resultado = $this->CI->prioridade_model->insert($prioridade);

                // Verificação da inserção
                if ($resultado === false) {
                    $response->set_code(Response::DB_ERROR_INSERT);
                    $response->set_data([
                        'erro' => 'Erro na inserção da prioridade',
                    ]);
                    $response->send();
                }
                // Inserção do historico dos prazos dessa prioridade
                $prazo['prioridade_fk'] = $resultado;

                $resultado = $this->CI->historico_prazo_model->insert($prazo);

                // Verificação da inserção
                if ($resultado === false) {

                    $response->set_code(Response::DB_ERROR_INSERT);
                    $response->set_data([
                        'erro' => 'Erro na inserção do historico da prioridade',
                    ]);
                    $response->send();
                    return;
                }
                $response->set_code(Response::SUCCESS);
                $response->set_data(['prioridade_pk' => $prazo['prioridade_fk']]);
            }

            // Caso houver a chave primária, trata-se de um update
            else {
                // Leitura das informações do prazo
                $prazo['prioridade_fk'] = $this->CI->input->post('prioridade_pk');

                // Recupera a prioridade do banco
                $prioridade_banco = $this->CI->prioridade_model->get($this->CI->input->post('prioridade_pk'));

                //Verifica se a prioridade não está desativada
                if ($prioridade_banco[0]->prioridade_desativar_tempo != null) {
                    $response->set_code(Response::BAD_REQUEST);
                    $response->set_data([
                        'erro' => 'Prioridade desativada',
                    ]);
                    $response->send();
                    return;
                }

                // Se apenas o nome for diferente, apenas o altera
                if (
                    $prioridade_banco[0]->prioridade_nome != $prioridade['prioridade_nome'] &&
                    $prioridade_banco[0]->prazo_duracao == $prazo['prazo_duracao']
                ) {
                    // Alteração na tabela de prioridade
                    $resultado = $this->CI->prioridade_model->update($prioridade,
                        $this->CI->input->post('prioridade_pk'));

                    if ($resultado != -1) {
                        $response->set_code(Response::SUCCESS);
                    } else {
                        $response->set_code(Response::DB_ERROR_UPDATE);
                        $response->set_data([
                            'erro' => 'Erro ao alterar dados na tabela de prioridades',
                        ]);
                        $response->send();
                        return;
                    }
                }

                /* Se o nome for igual, porém o prazo for diferente, deve-se criar um novo
                registro de prioridade */
                if (
                    $prioridade_banco[0]->prioridade_nome == $prioridade['prioridade_nome'] &&
                    $prioridade_banco[0]->prazo_duracao != $prazo['prazo_duracao']
                ) {
                    $resultado = $this->CI->historico_prazo_model->insert($prazo);

                    if ($resultado === false) {

                        $response->set_code(Response::DB_ERROR_INSERT);
                        $response->set_data([
                            'erro' => 'Erro na inserção do historico da prioridade',
                        ]);
                        $response->send();
                        return;
                    } else {
                        $response->set_code(Response::SUCCESS);
                    }
                }
                /* Ou então, se o nome também for diferente, deve-se alteranar o nome da prioridade
                e inserir um novo registro dao historico*/
                else if (
                    $prioridade_banco[0]->prioridade_nome != $prioridade['prioridade_nome'] &&
                    $prioridade_banco[0]->prazo_duracao != $prazo['prazo_duracao']
                ) {
                    // Alteração na tabela de prioridade
                    $resultado = $this->CI->prioridade_model->update($prioridade,
                        $this->CI->input->post('prioridade_pk'));

                    if ($resultado) {
                        $response->set_code(Response::SUCCESS);
                    } else {
                        $response->set_code(Response::DB_ERROR_UPDATE);
                        $response->set_data([
                            'erro' => 'Erro ao alterar dados na tabela de prioridades',
                        ]);
                        $response->send();
                        return;
                    }

                    // Inserção na tabela de históricos
                    $resultado = $this->CI->historico_prazo_model->insert($prazo);

                    if ($resultado === false) {

                        $response->set_code(Response::DB_ERROR_INSERT);
                        $response->set_data([
                            'erro' => 'Erro na inserção do historico da prioridade',
                        ]);
                        $response->send();
                        return;
                    } else {
                        $response->set_code(Response::SUCCESS);
                    }
                }
            }
        } else {
            $response->set_code(Response::BAD_REQUEST);
            $response->set_data($this->form_validation->error_array());
        }

        $response->send();
    }

    /**
     * Método responsável por retornar os tipos de serviços vinculados a esta determinada prioridade.
     *
     * @param pk da prioridade
     * @return objeto Response contendo sucesso ou erros
     */
    public function get_dependents()
    {
        $this->load->model('tipo_servico_model');
        date_default_timezone_set('America/Sao_Paulo');
        $response = new Response();

        $prioridade = $this->CI->prioridade_model->get($this->CI->input->post('prioridade_pk'));

        if ($prioridade === false) 
        {
            $response->set_code(Response::NOT_FOUND);
            $response->set_data(['erro' => 'Pioridade não encontrada']);
        } 
        else 
        { //se existe prioridade:
            //se a prioridade já foi desativada:
            if ($prioridade[0]->prioridade_desativar_tempo != null) 
            {
                $response->set_code(Response::BAD_REQUEST);
                $response->set_data(['erro' => 'Pioridade desativada']);
            }
            else
            { // se a prioridade está ativa: 
                $prioridade_pk =  $this->CI->input->post('prioridade_pk');
                
                $tipos_servicos = $this->tipo_servico_model->get(['tipos_servicos.prioridade_padrao_fk' => $prioridade_pk]);
                
                
                
                $response->set_code(Response::SUCCESS);
                $response->set_data($tipos_servicos);
            }
        }

        $response->send();
        return;
    }


    /**
     * Método responsável por desativar uma prioridade
     *
     * @param pk da prioridade
     * @return objeto Response contendo sucesso ou erros
     */
    public function deactivate()
    {
        date_default_timezone_set('America/Sao_Paulo');
        $response = new Response();
        $this->load->model('tipo_servico_model');

        $prioridade = $this->CI->prioridade_model->get($this->CI->input->post('prioridade_pk'));

        if ($prioridade === false) 
        {
            $response->set_code(Response::NOT_FOUND);
            $response->set_data(['erro' => 'Pioridade não encontrada']);
            $response->send();
            return;
        } 
        else 
        {
            //prioridade desativada
            if ($prioridade[0]->prioridade_desativar_tempo != null) 
            {
                $response->set_code(Response::BAD_REQUEST);
                $response->set_data(['erro' => 'Pioridade já desativada']);
                $response->send();
                return;
            }
            else
            {

                $prioridade_pk =  $this->CI->input->post('prioridade_pk');

                $tipos_servicos = $this->tipo_servico_model->get(['tipos_servicos.prioridade_padrao_fk' => $prioridade_pk]);

                //se não existir tipos de serviços:
                if($tipos_servicos === false)
                {
                    // Update da tabela, na organizacao informada
                    $where =  $this->CI->input->post('prioridade_pk');
                    $data = ['prioridade_desativar_tempo' => date('Y-m-d H:i:s')];
                    $query = $this->prioridade_model->update($data,$where);


                    if ($query) 
                    {
                        // Caso a query tenha sucesso
                        $response->set_code(Response::SUCCESS);
                    } 
                    else 
                    {
                        // Caso falhe
                        $response->set_code(Response::DB_ERROR_UPDATE);
                    }
                }
                else //se existir
                {
                    $response->set_code(Response::BAD_REQUEST);
                    $response->set_data(['erro' => 'Pioridade ainda possui tipos de serviço vinculado.']);

                }   
            }
        }

      

        $response->send();
    }
}
