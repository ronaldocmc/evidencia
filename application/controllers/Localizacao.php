<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . "/Response.php";

class Localizacao extends CI_Controller 
{
    public $CI;
    public $user_call = FALSE;

    function __construct() 
    {
        if ($this->CI = & get_instance() === NULL)
        {
            parent::__construct();
            $this->CI = & get_instance();
            $this->user_call = FALSE;
        }
        $this->CI->load->library('form_validation');
    }

    public function index() 
    {

    }

    /**
    * Método responsável por retornar os bairrosde determinado munici´pio
    *
    * @param Requisição GET com o municipio
    * @return Objeto Response contendo os bairros, ou não, caso no município seja inexistente, ou os bairros
    */
    public function bairros($id_cidade)
    {
        $this->load->model('bairro_model');
        $response = new Response();

        $bairros = $this->bairro_model->get([
            'municipio_fk' => $id_cidade
        ]);

        if ($bairros === false)
        {
            $response->set_code(Response::NOT_FOUND);
        }
        else
        {
            $response->set_code(Response::SUCCESS);
            $response->set_data($bairros);
        }

        $response->send();
    }


      /**
      * Método responsável por retornar os logradouros que mais se parecem com àquele
      * digitado pelo usuário nos formulários.
      *
      * @param Requisição POST com o logradouro e a cidade
      * @return Objeto Response contendo os logradouros mais semelhantes
      */
      public function logradouros()
      {
        $this->load->model('logradouro_model');
        $response = new Response();

        $dados['logradouro_nome'] = strtoupper($this->input->post('logradouro_nome'));
        $dados['municipio_pk'] = $this->input->post('municipio_pk');

        $logradouros = $this->logradouro_model->get_similar($dados);

        if ($logradouros === false)
        {
            $response->set_code(Response::NOT_FOUND);
        }
        else
        {
            $response->set_code(Response::SUCCESS);
            $response->set_data($logradouros);
        }
        $response->send();
    }


    /**
      * Método responsável por retornar os estados registrados no banco
      *
      * @return Objeto Response contendo os estados
    */
    public function get_estados()
    {
        $this->load->model('estado_model');
        $response = new Response();

        $estados = $this->estado_model->get();

        if (!$estados)
        {
            $response->set_code(Response::NOT_FOUND);
        }
        else
        {
            $response->set_code(Response::SUCCESS);
            $response->set_data($estados);
        }

        $response->send();
    }


    /**
      * Método responsável por retornar os municípios de um estado registrados no banco
      *
      * @param UF do estado dos municípios
      * @return Objeto Response contendo os municípios
    */
    public function get_municipios($estado)
    {
        $this->load->model('municipio_model');
        $response = new Response();

        $estado = strtoupper($estado);

        $municipios = $this->municipio_model->get([
            'estado_fk' => $estado
        ]);

        if (!$municipios)
        {
            $response->set_code(Response::NOT_FOUND);
        }
        else
        {
            $response->set_code(Response::SUCCESS);
            $response->set_data($municipios);
        }

        $response->send();
    }


    private function check_form_validation()
    {
        $this->CI->form_validation->set_rules(
            'logradouro_nome',
            'logradouro_nome',
            'trim|max_length[128]'
        );

        $this->CI->form_validation->set_rules(
            'local_num',
            'local_num',
            'trim|max_length[10]'
        );

        $this->CI->form_validation->set_rules(
            'local_complemento',
            'local_complemento',
            'trim|max_length[128]'
        );

        $this->CI->form_validation->set_rules(
            'bairro',
            'bairro',
            'trim|max_length[128]'
        );

        $this->CI->form_validation->set_rules(
            'municipio_pk',
            'cidade',
            'trim|max_length[128]'
        );

        $this->CI->form_validation->set_rules(
            'estado_pk',
            'estado_pk',
            'trim|max_length[2]'
        );
    }


    public function insert()
    {

        if (!$this->user_call)
        {
            $response = new Response();
            if ($this->CI->form_validation->run() == TRUE ) {

                // Carregando os models necessários
                $this->CI->load->model('bairro_model');
                $this->CI->load->model('municipio_model');
                $this->CI->load->model('logradouro_model');
                $this->CI->load->model('estado_model');
                $this->CI->load->model('local_model');

                //CASO NÃO SEJA OBRIGATÓRIO O ENDEREÇO MESMO QUE NÃO SEJA 
                //PASSADO NENHUMA INFORMAÇÃO O RESULTADO ESTARÁ CORRETO


                //se a rua OU o bairro OU número for vazio, não adianta cadastrar o endereço:
                if ($this->CI->input->post('logradouro_nome') === '' || $this->CI->input->post('bairro') === '' || $this->CI->input->post('local_num') == '0')
                {
                    $response->set_code(Response::SUCCESS);
                    $response->set_data(['message'=>'Dados não são obrigatórios']);
                    return $response;
                }


                // Verifica o estado
                $estado = $this->CI->estado_model->get($this->CI->input->post('estado_pk'));
                if($estado === FALSE)
                {
                    // Caso não encontre o estado, o usuário possui más intenções
                    // O fluxo da função é finalizado
                    $response->set_code(Response::BAD_REQUEST);
                    $response->set_data(array(
                        'estado_pk' => 'Estado não encontrado!'
                    ));
                    return $response;
                }
                else
                {
                    $municipio['estado_fk'] = $estado->estado_pk;
                }
                
                // Verifica o logradouro

                // No caso de insert, ele passa o nome
                $logradouro = $this->CI->logradouro_model->get([
                    'logradouro_nome' => strtoupper($this->CI->input->post('logradouro_nome'))
                ]);

                // Se não houver registro com o nome passado, cria um novo
                if($logradouro == null)
                {
                    $local['logradouro_fk'] = $this->CI->logradouro_model->insert(array(
                        'logradouro_nome' => strtoupper($this->CI->input->post('logradouro_nome'))
                    ));

                    if ($local['logradouro_fk'] === false) 
                    {
                        $response->set_code(Response::DB_ERROR_INSERT);
                        $response->set_data([
                            'logradouro_nome' => 'Erro na inserção do logradouro no banco de dados'
                        ]);
                        return $response;
                    } 
                }
                else
                {
                    // Se houver, o local vai ser atualizado com o logradouro passado
                    $local['logradouro_fk'] = $logradouro->logradouro_pk;
                }

                
                // Verifica se o município já existe no banco 
                $municipio = $this->CI->municipio_model->get($this->CI->input->post('municipio_pk'));
                if ($municipio === FALSE) 
                {
                    $insert_municipio['municipio_pk'] = $this->CI->input->post('municipio_pk');
                    $insert_municipio['estado_fk'] = $this->CI->input->post('estado_pk');
                    $insert_municipio['municipio_nome'] = $this->CI->input->post('municipio_nome');

                    $bairro['municipio_fk'] = $this->CI->municipio_model->insert($insert_municipio);

                    if ($bairro['municipio_fk'] === false) 
                    {
                        $response->set_code(Response::DB_ERROR_INSERT);
                        $response->set_data([
                            'municipio_pk' => 'Erro na inserção do município no banco de dados'
                        ]);
                        return $response;
                    }
                }
                else
                {
                    $bairro['municipio_fk'] = $municipio[0]->municipio_pk;
                }


                // Verifica se o bairro passado já está no banco de dados
                $bairro['bairro_nome'] = strtoupper($this->CI->input->post('bairro'));
                $result = $this->CI->bairro_model->get($bairro);

                if ($result) 
                {
                    $local['bairro_fk'] = $result[0]->bairro_pk;
                } 
                else 
                {
                    $local['bairro_fk'] = $this->CI->bairro_model->insert($bairro);

                    if ($local['bairro_fk'] === false) 
                    {
                        $response->set_code(Response::DB_ERROR_INSERT);
                        $response->set_data([
                            'bairro' => 'Erro na inserção do bairro no banco de dados'
                        ]);
                        return $response;
                    }
                }

                // Verifica o local
                $local['local_num'] = $this->CI->input->post('local_num');
                $local['local_complemento'] = strtoupper($this->CI->input->post('local_complemento'));
                $local['local_referencia'] = strtoupper($this->CI->input->post('ponto_referencia')); //Alterei aqui
                $local_banco = $this->CI->local_model->get($local);

                if($local_banco === FALSE)
                {
                    $organizacao['local_fk'] = $this->CI->local_model->insert($local);

                    if ($organizacao['local_fk'] === false) 
                    {
                        $response->set_code(Response::DB_ERROR_INSERT);
                        $response->set_data([
                            'local_num' => 'Erro na inserção do local no banco de dados'
                        ]);
                    }
                    else
                    {
                       $response->set_code(Response::SUCCESS);
                       $response->set_data([
                        'id' => $organizacao['local_fk'],
                        ]);
                   }
               }
               else
               {
                $response->set_code(Response::SUCCESS);
                $response->set_data([
                    'id' => $local_banco->local_pk
                ]);
            }
        } 
        else 
        {
            $errors = $this->CI->form_validation->errors_array();
            $response->set_code(Response::BAD_REQUEST);
            $response->set_data($errors);
        }
        return $response;
    }
}
}

?>