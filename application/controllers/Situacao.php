<?php 


if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__)."\Response.php"); 

require_once APPPATH."core\CRUD_Controller.php";

class Situacao extends CRUD_Controller {

	function __construct() 
	{
		date_default_timezone_set('America/Sao_Paulo');
		parent::__construct();
		$this->load->model('situacao_model');
	}

	function index() 
	{
		//Para testes
		// $response = new Response();
		// ---

		$situacoes = $this->situacao_model->get([
			'organizacao_fk' => $this->session->user['id_organizacao']
		]);

		// Para testes
		// if(!$situacoes)
		// {
		// 	$response->set_code(Response::NOT_FOUND);
		// }
		// else
		// {
		// 	$response->set_code(Response::SUCCESS);
		// 	$response->set_data($situacoes);
		// }
		// $response->send();
		// ----


		$this->session->set_flashdata('css',[
			0 => base_url('assets/css/modal_desativar.css'),
			1 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.css'),
			2 => base_url('assets/css/loading_input.css'),
			3 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css')
		]);

		$this->session->set_flashdata('scripts',[
			0 => base_url('assets/vendor/masks/jquery.mask.min.js'),
			1 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.js'),
			2 => base_url('assets/js/masks.js'),
			3 => base_url('assets/vendor/bootstrap-multistep-form/jquery.easing.min.js'),
			4 => base_url('assets/vendor/datatables/datatables.min.js'),
			5 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js'),
			6 => base_url('assets/js/utils.js'),
			7 => base_url('assets/js/constants.js'),
			8 => base_url('assets/js/jquery.noty.packaged.min.js'),
			9 => base_url('assets/js/dashboard/situacao/index.js'),
			10 => base_url('assets/vendor/select-input/select-input.js')
		]);

		load_view([
			0 => [
				'src' => 'dashboard/administrador/situacao/home',
				'params' => ['situacoes' => $situacoes]
			]
		],'administrador');
	}

    /**
     * Função responsável por validar os dados vindos da requisição de insert_update
     *
     * @param Requisição POST com situacao_nome, situacao_descricao, situacao_foto_obrigatoria (bool)
     * 		  e situacao_pk (opcional)
     * @return Objeto Response caso falhe, ou então, TRUE, caso esteja correto
     */
    private function form_valition_insert_update()
    {
    	$this->load->library('form_validation');

    	// Regras da validação
    	$this->form_validation->set_rules(
    		'situacao_nome',
    		'situacao_nome',
    		'trim|required|max_length[50]'
    	);
    	
    	$this->form_validation->set_rules(
    		'situacao_descricao',
    		'situacao_descricao',
    		'trim|required'
    	);

    	$this->form_validation->set_rules(
    		'situacao_foto_obrigatoria',
    		'situacao_foto_obrigatoria',
    		'required'
    	);

    	if($this->input->post('situacao_pk') != '')
    	{
    		$this->form_validation->set_rules(
    			'situacao_pk',
    			'situacao_pk',
    			'trim|required|numeric'
    		);
    	}

    	if($this->session->user['is_superusuario'])
    	{
    		$this->form_validation->set_rules(
    			'senha',
    			'senha',
    			'trim|required|min_length[8]'
    		);
    	}

    	if($this->form_validation->run())
    	{
    		return true;
    	}
    	else
    	{
    		$response = new Response();
    		$response->set_code(Response::BAD_REQUEST);
    		$response->set_data($this->form_validation->errors_array());
    		return $response;
    	}
    }

    /**
     * Função responsável por criar ou editar uma situação
     *
     * @param Requisição POST com situacao_nome, situacao_descricao, situacao_foto_obrigatoria (bool)
     * 		  e situacao_pk (opcional)
     * @return Objeto Response
     */
    public function insert_update()
    {
    	$response = new Response();

    	// Verificação dos dados da requisição
      $result_form_validation = $this->form_valition_insert_update();


      if($result_form_validation === true)
      {
    		// Caso esteja tudo de acordo

    		// Leitura dos dados
          $situacao['situacoes.situacao_nome'] = $this->input->post('situacao_nome');
          $situacao['situacoes.situacao_descricao'] = $this->input->post('situacao_descricao');
          $situacao['situacoes.situacao_foto_obrigatoria'] = $this->input->post('situacao_foto_obrigatoria');

          if($this->input->post('situacao_pk') != '')
          {
    			// Se houver a situacao_pk, trata-se de um update
             $resultado = $this->situacao_model->update($situacao, 
                $this->input->post('situacao_pk'));

             if(!$resultado)
             {
    				// Caso o update falhe
                $response->set_code(Response::DB_ERROR_UPDATE);
                $response->set_data([
                   'erro' => 'Erro no update da situação:' . $resultado
               ]);
            }
            else
            {
    				// Caso o update obteve sucesso
                $response->set_code(Response::SUCCESS);
            }
        }
        else
        {
    			// Leitura da organização na seção para o insert
         $situacao['organizacao_fk'] = $this->session->user['id_organizacao'];

         $resultado = $this->situacao_model->insert($situacao);

         if(!$resultado)
         {
    				// Caso o insert falhe
            $response->set_code(Response::DB_ERROR_INSERT);
            $response->set_data([
               'erro' => 'Erro no insert da situação:' . $resultado
           ]);
        }
        else
        {
    				// Caso o insert dê certo
            $response->set_code(Response::SUCCESS);
            $response->set_data([
               'situacao_pk' => $resultado
           ]);
        }
    }

    		// Envio do JSON
    $response->send();
}
else
{	
    		// Caso a validação dê erro
  $result_form_validation->send();
}
}

public function get_dependents()
{
    $this->load->model('servico_model');
    date_default_timezone_set('America/Sao_Paulo');
    $response = new Response();

    $situacao = $this->situacao_model->get($this->input->post('situacao_pk'));

    if ($situacao === false) 
    {
        $response->set_code(Response::NOT_FOUND);
        $response->set_data(['erro' => 'Situação não encontrada']);
    } 
    else 
        { //se existe situação:
            //se a situação  já foi desativada:
            if ($situacao[0]->situacao_ativo == 0) 
            {
                $response->set_code(Response::BAD_REQUEST);
                $response->set_data(['erro' => 'Situação desativada']);
            }
            else
            { // se a situação está ativa: 
                $situacao_pk =  $this->input->post('situacao_pk');
                
                $servicos = $this->servico_model->get(['servicos.situacao_padrao_fk' => $situacao_pk]);
                
                
                
                $response->set_code(Response::SUCCESS);
                $response->set_data($servicos);
            }
        }

        $response->send();
        return;
    }


    /**
     * Função responsável por desativar uma situação
     *
     * @param Requisição POST com a situacao_pk
     * @return Objeto Response
     */
    public function deactivate()
    {
        $this->load->model('servico_model');
    	$this->load->library('form_validation');
    	$response = new Response();

    	$this->form_validation->set_rules(
    		'situacao_pk',
    		'situacao_pk',
    		'trim|required|numeric'
    	);

    	if($this->form_validation->run())
    	{
            $situacao_pk =  $this->input->post('situacao_pk');

            $servicos = $this->servico_model->get(['servicos.situacao_padrao_fk' => $situacao_pk]);

            //se não existir serviços vinculados a esta situacao:
            if($servicos === false)
            {
                $situacao['situacao_ativo'] = 0;

                $resultado = $this->situacao_model->update($situacao, 
                    $this->input->post('situacao_pk'));

                if(!$resultado)
                {
                    $response->set_code(Response::DB_ERROR_UPDATE);
                    $response->set_data([
                        'erro' => 'Erro na desativação da situação:' . $resultado
                    ]);
                }
                else
                {
                    $response->set_code(Response::SUCCESS);
                }
            }
            else //se existir:   
            {
                $response->set_code(Response::BAD_REQUEST);
                $response->set_data(['erro' => 'Situação ainda possui serviço(s) vinculado(s).']);
            }

        }
        else
        {
          $response->set_code(Response::BAD_REQUEST);
          $response->set_data($this->form_validation->errors_array());
        }

      $response->send();
  }

	    /**
     * Função responsável por ativar uma situação
     *
     * @param Requisição POST com a situacao_pk
     * @return Objeto Response
     */
        public function activate()
        {
           $this->load->library('form_validation');
           $response = new Response();

           $this->form_validation->set_rules(
              'situacao_pk',
              'situacao_pk',
              'trim|required|numeric'
          );

           if($this->form_validation->run())
           {
              $situacao['situacao_ativo'] = 1;

              $resultado = $this->situacao_model->update($situacao, 
                 $this->input->post('situacao_pk'));

              if(!$resultado)
              {
                 $response->set_code(Response::DB_ERROR_UPDATE);
                 $response->set_data([
                    'erro' => 'Erro na ativação da situação:' . $resultado
                ]);
             }
             else
             {
                 $response->set_code(Response::SUCCESS);
             }
         }
         else
         {
          $response->set_code(Response::BAD_REQUEST);
          $response->set_data($this->form_validation->errors_array());
      }

      $response->send();
  }
}



?>