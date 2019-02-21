<?php

/**
 * Organizacao
 *
 * @package     application
 * @subpackage  controllers
 * @author      Pietro, Pedro, Gustavo
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once dirname(__FILE__) . "\Response.php";

require_once dirname(__FILE__) . "\Prioridade.php";

require_once APPPATH."core\CRUD_Controller.php";

class Organizacao extends CRUD_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('organizacao_model');
    }

    public function index()
    {
        $organizacoes = $this->organizacao_model->get();

        //CSS para crud organizações
        $this->session->set_flashdata('css',[
            0 => base_url('assets/css/modal_desativar.css'),
            1 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.css'),
            2 => base_url('assets/css/loading_input.css'),
            3 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css')
        ]);

        //Scripts para crud organizações
        $this->session->set_flashdata('scripts',[
            0 => base_url('assets/vendor/masks/jquery.mask.min.js'),
            1 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.js'),
            2 => base_url('assets/js/masks.js'),
            3 => base_url('assets/vendor/bootstrap-multistep-form/jquery.easing.min.js'),
            4 => base_url('assets/vendor/datatables/datatables.min.js'),
            5 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js'),
            6 => base_url('assets/js/localizacao.js'),
            7 => base_url('assets/js/utils.js'),
            8 => base_url('assets/js/constants.js'),
            9 => base_url('assets/js/jquery.noty.packaged.min.js'),
            10 => base_url('assets/js/dashboard/organizacao/index.js'),
            11 => base_url('assets/vendor/select-input/select-input.js')
        ]);

        load_view([
            0 => [
                'src' => 'dashboard/superusuario/organizacao/home',
                'params' => ['organizacoes' => $organizacoes]
            ],
            1 => [
                'src' => 'access/pre_loader',
                'params' => []
            ]
        ],'superusuario');

    }

    /**
     * Função responsável por receber um form via ajax, realizar a verificação dos parâmetros
     * e escolher se será um insert ou um update, baseado no parametro 'oraganizacao_pk' da requisição
     *
     * @param Dominio, Nome, CNPJ, Logradouro, Nº, Complemento, UF, Cidade, Bairro, Senha
     * @return Objeto Response
     */

    public function insert_update()
    {
        $this->load->library('form_validation');
        $response = new Response();

        $this->form_validation->set_rules(
            'organizacao_nome',
            'Nome',
            'trim|required|min_length[4]|max_length[128]'
        );

        $this->form_validation->set_rules(
            'organizacao_cnpj',
            'CNPJ',
            'trim|required|regex_match[/[0-9].\-/]|min_length[18]|max_length[18]'
        );

        $this->form_validation->set_rules(
            'logradouro_nome',
            'logradouro_nome',
            'trim|required|max_length[128]'
        );

        $this->form_validation->set_rules(
            'local_num',
            'local_num',
            'trim|required|max_length[10]'
        );

        $this->form_validation->set_rules(
            'lcoal_complemento',
            'local_complemento',
            'trim|max_length[128]'
        );

        $this->form_validation->set_rules(
            'bairro',
            'bairro',
            'trim|required|max_length[128]'
        );

        $this->form_validation->set_rules(
            'municipio_pk',
            'cidade',
            'trim|required|max_length[128]'
        );

        $this->form_validation->set_rules(
            'estado_pk',
            'estado_pk',
            'trim|required|max_length[2]'
        );


        $organizacao_pk = $this->session->user['id_organizacao'];

        // Verifica se é um superusuário
        if($this->session->user['is_superusuario'])
        {
            $this->form_validation->set_rules(
                'senha', 
                'senha', 
                'trim|required|min_length[8]'
            );
        }


        if ($this->form_validation->run()) 
        {
            // Dados da organização
            $organizacao['organizacao_nome'] = $this->input->post('organizacao_nome');
            $organizacao['organizacao_cnpj'] = $this->input->post('organizacao_cnpj');

            // Carregando os models necessários
            $this->load->model('bairro_model');
            $this->load->model('municipio_model');
            $this->load->model('logradouro_model');
            $this->load->model('estado_model');
            $this->load->model('local_model');

            // Verifica o estado
            $estado = $this->estado_model->get($this->input->post('estado_pk'));

            if($estado === FALSE)
            {
                // Caso não encontre o estado, o usuário possui más intenções
                // O fluxo da função é finalizado
                $response->set_code(Response::BAD_REQUEST);
                $response->set_data(array(
                    'estado_pk' => 'Estado não encontrado!'
                ));
                $response->send();
                return;
            }
            else
            {
                $municipio['estado_fk'] = $estado->estado_pk;
            }
            
            // Verifica o logradouro

            // No caso de insert, ele passa o nome
            $logradouro = $this->logradouro_model->get([
                'logradouro_nome' => strtoupper($this->input->post('logradouro_nome'))
            ]);

            // Se não houver registro com o nome passado, cria um novo
            if($logradouro === false)
            {
                $local['logradouro_fk'] = $this->logradouro_model->insert(array(
                    'logradouro_nome' => strtoupper($this->input->post('logradouro_nome'))
                ));

                if ($local['logradouro_fk'] === false) 
                {
                    $response->set_code(Response::DB_ERROR_INSERT);
                    $response->set_data([
                        'logradouro_nome' => 'Erro na inserção do logradouro no banco de dados'
                    ]);
                    $response->send();
                    return;
                } 
            }
            else
            {
                // Se houver, o local vai ser atualizado com o logradouro passado
                $local['logradouro_fk'] = $logradouro->logradouro_pk;
            }

            
            // Verifica se o município já existe no banco 
            $municipio = $this->municipio_model->get($this->input->post('municipio_pk'));
            if ($municipio === FALSE) 
            {
                $insert_municipio['municipio_pk'] = $this->input->post('municipio_pk');
                $insert_municipio['estado_fk'] = $this->input->post('estado_pk');
                $insert_municipio['municipio_nome'] = $this->input->post('municipio_nome');

                $bairro['municipio_fk'] = $this->municipio_model->insert($insert_municipio);

                if ($bairro['municipio_fk'] === false) 
                {
                    $response->set_code(Response::DB_ERROR_INSERT);
                    $response->set_data([
                        'municipio_pk' => 'Erro na inserção do município no banco de dados'
                    ]);
                    $response->send();
                    return;
                }
            }
            else
            {
                $bairro['municipio_fk'] = $municipio[0]->municipio_pk;
            }


            // Verifica se o bairro passado já está no banco de dados
            $bairro['bairro_nome'] = strtoupper($this->input->post('bairro'));
            $result = $this->bairro_model->get($bairro);

            if ($result) 
            {
                $local['bairro_fk'] = $result[0]->bairro_pk;
            } 
            else 
            {
                $local['bairro_fk'] = $this->bairro_model->insert($bairro);

                if ($local['bairro_fk'] === false) 
                {
                    $response->set_code(Response::DB_ERROR_INSERT);
                    $response->set_data([
                        'bairro' => 'Erro na inserção do bairro no banco de dados'
                    ]);
                    $response->send();
                    return;
                }
            }

            // Verifica o local
            $local['local_num'] = $this->input->post('local_num');
            $local['local_complemento'] = strtoupper($this->input->post('local_complemento'));
            $local_banco = $this->local_model->get($local);

            if($local_banco === FALSE)
            {
                $organizacao['local_fk'] = $this->local_model->insert($local);

                if ($organizacao['local_fk'] === false) 
                {
                    $response->set_code(Response::DB_ERROR_INSERT);
                    $response->set_data([
                        'local_num' => 'Erro na inserção do local no banco de dados'
                    ]);
                    $response->send();
                    return;
                }
            }
            else
            {
                $organizacao['local_fk'] = $local_banco->local_pk;
            }

            
            if(isset($organizacao_pk)){
                $query = $this->organizacao_model->update($organizacao, $organizacao_pk);

                if ($query === false) 
                {
                    $response->set_code(Response::DB_ERROR_UPDATE); 
                    $response->set_data([
                        'erro' => 'Erro no update de organizações'
                    ]);
                }
                else 
                {
                    $response->set_code(Response::SUCCESS);
                } 
            }
            else 
            {
                // Se não existe, cria um novo registro
                $query = $this->organizacao_model->insert($organizacao);

                if ($query === false) 
                {
                    $response->set_code(Response::DB_ERROR_INSERT);
                    $response->set_data([
                        'erro' => 'Erro na inserção de organizações'
                    ]);
                } 
                else 
                {
                    $response->set_code(Response::SUCCESS);

                    $prioridade = new Prioridade();
                    $prioridade->create_standart($query);
                }
            }
        } 
        else 
        {
            $errors = $this->form_validation->errors_array();
            $response->set_code(Response::BAD_REQUEST);
            $response->set_data($errors);
        }
        $response->send();
    }


    /**
     * Função responsável por desativar uma organização. 
     * 
     * @param Requisição POST com a PK da organização.
     * @return Objeto Response com o resultado da operação.
     */

    public function deactivate()
    {
        $response = new Response();


        // Novo status da flag
        $dados['ativo'] = 0;

        // Update da tabela, na organizacao informada
        $query = $this->organizacao_model->update($dados, 
            $this->input->post('organizacao_pk'));

        if($query)
        {
            // Caso a query tenha sucesso
            $response->set_code(Response::SUCCESS);
        }
        else
        {
            // Caso falhe
            $response->set_code(Response::DB_ERROR_UPDATE);
        }

        $response->send();
    }


    /**
     * Função responsável por reativar uma organização. A função recebe uma requisção
     * POST com a PK da organização e muda o valor do campo na tabela.
     */

    public function activate()
    {
        $response = new Response();


        // Novo status da flag
        $dados['ativo'] = 1;

        // Update da tabela, na organizacao informada
        $query = $this->organizacao_model->update($dados, 
            $this->input->post('organizacao_pk'));

        if($query)
        {
            // Caso a query tenha sucesso
            $response->set_code(Response::SUCCESS);
        }
        else
        {
            // Caso falhe
            $response->set_code(Response::DB_ERROR_UPDATE);
        }

        $response->send();
    }

    /**
    * Método responsável por permitir o acesso a uma organização ativa
    *
    * @param Requisição POST com a organização
    * @return Objeto Response
    */
    public function access()
    {
        $this->load->model('organizacao_model');
        $response = new Response();

        $organizacao = $this->organizacao_model->get([
            'organizacao_pk' => $this->input->post('organizacao_pk')
        ]);

        if ($organizacao)
        {
            if ($organizacao->ativo)
            {   
                $user = $this->session->user;
                $this->session->unset_userdata('user');
                $user['id_organizacao'] = $this->input->post('organizacao_pk');
                $this->session->set_userdata('user', $user);
                $response->set_code(Response::SUCCESS);
                $response->send();
            }
            else
            {
                $response->set_code(Response::FORBIDDEN);
                $response->send();
            }
        }
        else
        {
            $response->set_code(Response::NOT_FOUND);
            $response->send();
        }
    }


    public function edit_info()
    {
        // Pegando a organização salva na session 
        if ($this->session->user['id_organizacao'])
        {   
            $organizacao['organizacao'] = $this->organizacao_model->get([
                'organizacao_pk' => $this->session->user['id_organizacao']
            ]);
        }

        if ($organizacao['organizacao'] === false)
        {
            return;
        }

        // CSS para a edição
        $this->session->set_flashdata('css',[
            0 => base_url('assets/css/modal_desativar.css'),
            1 => base_url('assets/css/loading_input.css'),
            2 => base_url('assets/css/media_query_edit_org.css')
        ]);

        //Scripts para edição
        $this->session->set_flashdata('scripts',[
            0 => base_url('assets/js/localizacao.js'),
            1 => base_url('assets/vendor/datatables/datatables.min.js'),
            2 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js'),
            3 => base_url('assets/vendor/select-input/select-input.js'),
            4 => base_url('assets/js/dashboard/organizacao/edit_info.js'),
            5 => base_url('assets/js/constants.js'),
            6 => base_url('assets/js/utils.js'),
            7 => base_url('assets/js/jquery.noty.packaged.min.js'),
            8 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.js')

        ]);

        load_view([
            0 => [
                'src' => 'dashboard/administrador/organizacao/editar_informacoes',
                'params' => $organizacao
            ]
        ],'administrador');
    }
}
