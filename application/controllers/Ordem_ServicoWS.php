<?php

/**
 * AccessWS
 *
 * @package     application
 * @subpackage  controllers
 * @author      Pietro
 */

defined('BASEPATH') or exit('No direct script access allowed');

require_once dirname(__FILE__) . "\Response.php";
require_once dirname(__FILE__) . "\Localizacao.php";
require_once APPPATH . "core\MY_Controller.php";

class Ordem_ServicoWS extends MY_Controller
{

    private $response;

    public function __construct()
    {
        $this->response = new Response();
        parent::__construct($this->response);

		date_default_timezone_set('America/Sao_Paulo');
        exit();
    }

    /**
     * Destrutor da classe
     */
    public function __destruct()
    {

    }

    public function index(){

	}

	// Método responsável por inserir os históricos de uma ordem de serviço.
	public function put(){
		
		$this->load->model('ordem_servico_model');
		$this->load->model('servico_model');
		$this->load->model('funcionario_model');
		$this->load->model('historico_model');
		$this->load->helper('token_helper');
		$this->response = new Response();

		$obj = json_decode(file_get_contents('php://input'));
		$headers = apache_request_headers();


		// $_POST['comentario'] = $obj->comentario;

		// var_dump($obj->comentario);die();

	
		$id_funcionario = $this->funcionario_model->get(['funcionarios.pessoa_fk' => get('id_pessoa', $headers['token'])]);

		
		$data_historico = [

			'ordem_servico_fk' => $obj->ordem_servico_fk,

			'historico_ordem_comentario' => $obj->comentario,

			'funcionario_fk' => $id_funcionario[0]->funcionario_pk,

			'situacao_fk'	=> $obj->situacao_fk,

		];	            		

		$return_historico = $this->historico_model->insert($data_historico);

		
		// if($situacao->situacao_foto_obrigatoria == 1 && $_POST['image'] == null)

		// {
		// 	$this->response->set_code(Response::BAD_REQUEST);

		// 	$this->response->set_data("Foto obrigatória não foi enviada!"); 

		// }


			if ($obj->foto != NULL) {

				//Se ele enviou, então realizamos o upload. Caso os dados estejam duplicados esses dados são removidos

				$file = $this->upload_img($obj->ordem_servico_fk ,$obj->foto);

	

				//Padronizando os dados para efetuar o insert

				$data_imagem = array(

					'historico_ordem_fk' => $return_historico['id'],

					'imagem_situacao_caminho' => $file,

				);



				$setou = $this->ordem_servico_model->insert_image($data_imagem);


				if($setou['code'] == 0)

				{

					$this->response->set_code(Response::SUCCESS);

					$this->response->set_data("Ordem de serviço foi cadastrada e histórico registrado com sucesso!");

				}

				else

				{

					$this->response->set_code(Response::DB_ERROR_INSERT);

					$this->response->set_data("Não foi possivel inserir a imagem!");

				}



		}

		else

		{

			$this->response->set_code(Response::SUCCESS);

			$this->response->set_data("Ordem de serviço foi cadastrada e histórico registrado com sucesso!");

		} 


	$this->response->send();
	}


    /**
     * Método responsável por receber os dados de uma ordem de serviço e fazer a inserção
     */
    public function post()
	{
		$this->load->model('ordem_servico_model');
		$this->load->model('servico_model');
		$this->load->model('funcionario_model');
		$this->load->model('historico_model');
		$this->load->helper('token_helper');

		$this->response = new Response();

		
        $obj = json_decode(file_get_contents('php://input'));

		
		$headers = apache_request_headers();
        //Setando as regras do form_validation para ordens de serviço
        
        $_POST['latitude'] = $obj->latitude;
        $_POST['longitude'] = $obj->longitude;
        $_POST['logradouro_nome'] = $obj->logradouro_nome;
        $_POST['local_num'] = $obj->local_num;
        $_POST['bairro'] = $obj->bairro;
        $_POST['municipio_pk'] = "3541406";
        $_POST['estado_pk'] = "SP";
        $_POST['prioridade_fk'] = $obj->prioridade_fk;
        $_POST['situacao_fk'] = $obj->situacao_fk;
        $_POST['procedencia'] = $obj->procedencia;
        // $_POST['setor'] = get('setor', $headers['token']);
        $_POST['setor'] = $obj->setor;
        $_POST['descricao'] = $obj->descricao;
        $_POST['servico_fk'] = $obj->servico_fk;
        $_POST['image'] = isset($obj->uri) ? $obj->uri : null;
		
        $this->config_form_validation();
        
		
		if ($this->form_validation->run()) 
		{
			//Recebendo os dados preenchidos pelo usuário via post
            $data_ordem = $this->input->post();
            		
            
			//Verificando se o serviço passado é um serviço válido para a respectiva organização 

				$local = new Localizacao();

				$return_local = $local->insert();


				if ($return_local->code != 200)

				{

					return $return_local;

				}



				$data_coordenadas = null;



	            //Padronizando os dados de Coordenadas

				if ($return_local->__get('data')['id'] != null)
				{	

					$data_coordenadas = [

						'coordenada_lat' => $data_ordem['latitude'],

						'coordenada_long' => $data_ordem['longitude'],

						'local_fk' => $return_local->__get('data')['id']

					];

				}

				else

				{

					$this->response->set_code(Response::DB_ERROR_GET);

					$this->response->set_data(['erro' => 'Erro no local']);

					$this->response->send();

					return;

				}



	           	//Verificando se a coordenada passada já existe no banco de dados

				// $already_exists = $this->has_coordenada($data_ordem['latitude'], $data_ordem['longitude']);





				// if(!$already_exists) //Se ela não existe então será criada

				// {

					$return_coordenada = $this->ordem_servico_model->insert_coordenada($data_coordenadas);

					 // echo "Insert coordenadas";

				// }

				// else

	            // {	//Caso a coordenada já exista, a função retorna o id dela

	            // 	 // echo "Coordenadas já existem";

	            // 	$return_coordenada = [

	            // 		'id' => $already_exists->coordenada_pk,

	            // 		'db_error' => [

	            // 			'code' => 0,

	            // 		],

	            // 	];

	            // }



	            // if($return_coordenada['db_error']['code'] == 0)

	            // {

	            	//Padronizando os dados da Ordem de Serviço 

	            	$data_ordem_servico = [

	            		'coordenada_fk' => $return_coordenada['id'],

	            		'prioridade_fk' => $data_ordem['prioridade_fk'],

	            		'procedencia_fk' => $data_ordem['procedencia'],

	            		'ordem_servico_status' => 1,

	            		'ordem_servico_desc' => $data_ordem['descricao'],

	            		'servico_fk' => $data_ordem['servico_fk'],

	            		'setor_fk' => $data_ordem['setor']

					];

					
					
					
	            	$return_ordem = $this->ordem_servico_model->insert_os($data_ordem_servico);
										
	            	if($return_ordem['db_error']['code'] == 0)
	            	{
						
						$situacao = $this->servico_model->get_current(['servico_pk' => $data_ordem['servico_fk']]);
						
						
						$id_funcionario = $this->funcionario_model->get(['funcionarios.pessoa_fk' => get('id_pessoa', $headers['token'])]);


	            		$data_historico = [

	            			'ordem_servico_fk' => $return_ordem['id'],

	            			'funcionario_fk' => $id_funcionario[0]->funcionario_pk,

	            			'situacao_fk'	=> $obj->situacao_fk,

	            		];	            		

						$return_historico = $this->historico_model->insert($data_historico);
						
						

	            		if($return_historico['db_error']['code'] == 0)

	            		{


							// Verificando se a foto é obrigatória e se ela existe.
	            			if($situacao->situacao_foto_obrigatoria == 1 && $_POST['image'] == null)

	            			{
								$this->response->set_code(Response::BAD_REQUEST);

								$this->response->set_data("Foto obrigatória não foi enviada!"); 

							}


	            				if ($_POST['image'] != NULL) {

	                    			//Se ele enviou, então realizamos o upload. Caso os dados estejam duplicados esses dados são removidos

	            					$file = $this->upload_img($return_ordem['id'],$_POST['image']);



	                        		//Padronizando os dados para efetuar o insert

	            					$data_imagem = array(

	            						'historico_ordem_fk' => $return_historico['id'],

	            						'imagem_situacao_caminho' => $file,

	            					);



	            					$setou = $this->ordem_servico_model->insert_image($data_imagem);

	            					if($setou['code'] == 0)

	            					{

	            						$this->response->set_code(Response::SUCCESS);

	            						$this->response->set_data("Ordem de serviço foi cadastrada e histórico registrado com sucesso!");

	            					}

	            					else

	            					{

	            						$this->response->set_code(Response::DB_ERROR_INSERT);

	            						$this->response->set_data("Não foi possivel inserir a imagem!");

	            					}



	            			}

	            			else

	            			{

	            				$this->response->set_code(Response::SUCCESS);

	            				$this->response->set_data("Ordem de serviço foi cadastrada e histórico registrado com sucesso!");

	            			} 

	            		}

	            		else

	            		{

	            			$this->response->set_code(Response::DB_ERROR_INSERT);

	            			$this->response->set_data("Não foi possivel inserir o histórico de ordem de serviço");

	            		}

	            	}

	            	else

	            	{

	            		if($return_ordem['db_error']['code'] == 503)

	            		{

	            			$this->response->set_code(Response::DB_ERROR_INSERT);

	            			$this->response->set_data("Não foi possivel inserir a ordem de serviço!");

	            		}

	            	}

	            }
	            else
                {
    
                    $this->response->set_code(Response::BAD_REQUEST);
    
                    $this->response->set_data($this->form_validation->errors_array()); 
    
                }
                
            $this->response->send();
	} 
	    
    

    public function config_form_validation()
	{
        $this->load->library('form_validation');
    //Configurando as regras de validação do formulário para dados de LOCALIZAÇÃO
		$this->form_validation->set_rules(
			'latitude',
			'Latitude',
			'required|trim'
		);

		$this->form_validation->set_rules(
			'longitude',
			'Longitude',
			'required|trim'
		);

		$this->form_validation->set_rules(
			'logradouro_nome',
			'Logradouro_nome',
			'required'
		);

		$this->form_validation->set_rules(
			'local_num',
			'Local_num',
			'required'
		);

		$this->form_validation->set_rules(
			'bairro',
			'Bairro',
			'required'
		);

		$this->form_validation->set_rules(
			'municipio_pk',
			'Cidade',
			'required'
		);

		$this->form_validation->set_rules(
			'estado_pk',
			'Estado',
			'trim|max_length[2]'
		);

    //Configurando as regras de validação do formulário para dados Prioridade, Procedência e Status

		$this->form_validation->set_rules(
			'prioridade_fk',
			'Prioridade',
			'trim|required|is_natural_no_zero'
		);

		$this->form_validation->set_rules(
			'procedencia',
			'Prioridade',
			'trim|required|is_natural_no_zero'
		);

		$this->form_validation->set_rules(
			'setor',
			'Setor',
			'trim|required|is_natural_no_zero'
		);

    //Configurando as regras de validação do formulário para dados descrição
		$this->form_validation->set_rules(
			'descricao', 
			'Descricao',
			'trim|required|max_length[500]'
		);

    //Configurando as regras de validação do formulário para dados de serviço
		$this->form_validation->set_rules(
			'servico_fk', 
			'servico',
			'trim|required|is_natural_no_zero'
		);  	
	}

    
    //Função que executa o upload da imagem de perfil do usuário
	public function upload_img($id_ordem,$base64_image)
	{
		$path = "./assets/uploads/imagens_situacoes/";
		$name = hash(ALGORITHM_HASH, $id_ordem . uniqid(rand(), true));

		list($type, $base64_image) = explode(';', $base64_image);
		list(, $base64_image)      = explode(',', $base64_image);
		
		$data = base64_decode($base64_image);

		file_put_contents($path.$name, $data);

		return $path.$name;
	}

	public function has_service(){

		$data = $this->servico_model->get([
			'situacoes.organizacao_fk' => $this->session->user['id_organizacao'],
			'servico_pk' => $this->input->post('servico_fk')
		]);



		if($data){
			return true;
		}
		else
		{
			return false; 
		}

	}

		public function has_setor(){

		$data = $this->setores_model->get([
			'setores.setor_pk' => $this->input->post('setor'),
			'setores.organizacao_fk' => $this->session->user['id_organizacao']
		]);

		if($data){
			return true;
		}
		else
		{
			return false; 
		}

	}
	
	public function get(){
		
		isset($_GET['id']) ? $id = $_GET['id'] : $id = null;

		$this->load->model('ordem_servico_model');
		
		$obj = apache_request_headers();
		
		//$today = date('Y-m-d');
		// $today = date('Y-m-d H:i:s', strtotime('-5 days', strtotime($today)));

		$empresa = get('id_empresa', $obj['token']);
		$setor = $_GET['setor'];
		// $setor = get('setor', $obj['token']);
		
		// var_dump($setor);die();
		
		$where['departamentos.organizacao_fk'] = $empresa;

		if($id != null){
			$where['ordens_servicos.ordem_servico_pk'] = $id;

			$ordens_servico = $this->ordem_servico_model->getEspecifico($where);
	
			$ordens_servico['historico'] = $this->ordem_servico_model->getHistorico([
				'historicos_ordens.ordem_servico_fk' => $id
			]);

			$this->response->add_data("ordem",$ordens_servico);
		}else{
			//$where['historicos_ordens.historico_ordem_tempo >= '] = $today;
			$where['ordens_servicos.setor_fk'] = $setor;

			$ordens_servico = $this->ordem_servico_model->getJsonForMobile($where);
			$ordens = array();

			foreach($ordens_servico as $os){
				// Se for em andamento, ou encaminhada para alguem, o funcionário de campo pode ver
				if($os->situacao == 2 || $os->situacao == 1){
					array_push($ordens,$os);
				}
			}

			$this->response->add_data("ordens",$ordens);
		}

		// $this->response->add_data("empresa",$empresa);
		$this->response->send();
	}
}
