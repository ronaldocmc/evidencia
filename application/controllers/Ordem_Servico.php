<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."core/CRUD_Controller.php";
require_once dirname(__FILE__) . "/Response.php";
require_once dirname(__FILE__) . "/Pessoa.php";
require_once 'vendor/autoload.php';


class Ordem_Servico extends CRUD_Controller {

	private $localizacao;
	public $response; 

	function __construct() 
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
		$this->load->library('upload');
		$this->load->model('Tipo_Servico_model', 'tipo_servico_model');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$response = new Response();
	}

	function index()
	{
		//Criando um array de ordens de serviço com todos os dados necessários a serem exibidos na view index
		$ordens_servico = $this->ordem_servico_model->getHome([
			'prioridades.organizacao_fk' => $this->session->user['id_organizacao']
		]);

		//Criando um array de departamentos pertencentes a organização do usuário 
		$departamentos = $this->departamento_model->get([
			'organizacao_fk' => $this->session->user['id_organizacao']
		]);

		//Criando um array de tipos de serviços com dados necessário a serem exibidos na view index
		$tipos_servico = $this->tipo_servico_model->get([
			'departamentos.organizacao_fk' => $this->session->user['id_organizacao']
		]);

		//Criando um array de prioridades com dados necessário a serem exibidos na view index
		$prioridades = $this->prioridade_model->get([
			'organizacao_fk' => $this->session->user['id_organizacao']
		]);

		//Criando um array de situações de serviços (Aberta, Em andamento, Fechada) com dados necessário a serem exibidos na view index
		$situacoes = $this->situacao_model->get([
			'organizacao_fk' => $this->session->user['id_organizacao']
		]);

		//Criando um array de serviços (Coleta de Entulho, Limpeza, Retirada) com dados necessário a serem exibidos na view index
		$servicos = $this->servico_model->get([
			'situacoes.organizacao_fk' => $this->session->user['id_organizacao']
		]);

		//Criando um array de prodecencias de serviços (Interno/Externo) com dados necessário a serem exibidos na view index
		$procedencias = $this->procedencia_model->get(['procedencias.organizacao_fk' => $this->session->user['id_organizacao']
	]);

		//Criando um array de setores (A, B, C, D) com dados necessário a serem exibidos na view index
		$setores = $this->setor_model->get(['setores.organizacao_fk' => $this->session->user['id_organizacao']
	]);


		//Carregando arquivos CSS no flashdata da session para as views 
		$this->session->set_flashdata('css',[
			0 => base_url('assets/css/modal_desativar.css'),
			1 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.css'),
			2 => base_url('assets/css/loading_input.css'),
			3 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css'),
			3 => base_url('assets/css/modal_map.css'),
			4 => base_url('assets/vendor/cropper/cropper.css'),
			5 => base_url('assets/vendor/input-image/input-image.css'),
			6 => base_url('assets/css/timeline.css'),
		]);

		//Carregando arquivos SCRIPT no flashdata da session para as views 
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
			9 => base_url('assets/js/dashboard/ordem_servico/index.js'),
			10 => base_url('assets/vendor/select-input/select-input.js'),
			11 => base_url('assets/js/localizacao.js'),
			12 => base_url('assets/vendor/cropper/cropper.js'),
			13 => base_url('assets/vendor/input-image/input-image.js')
		]);

		$this->session->set_flashdata('mapa',[
			0 => true
		]);

		load_view_ordem_servico([
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
					'superusuario' => $this->session->user['is_superusuario']
				]
			],
		],'administrador');    
	}


	public function config_form_validation()
	{
		//Carregando a biblioteca nativa form_validation 
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
			'Procedência',
			'trim|required|is_natural_no_zero'
		);

		$this->form_validation->set_rules(
			'setor',
			'Setores',
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


	//Função que retorna um local novo ou já existente para a view. 
	public function local(){

		//Instanciando um novo response; 
		$this->response_local = new Response();

		//Recebendo local selecionado no mapa da view;
		$local_pk = $this->input->get(); 

		//Carregando model de local
		$this->load->model('local_model');

		//Recuperando o local 
		$data_local = $this->local_model->getHome($local_pk);

		//Se data_local retornou sucesso, então padronizamos os dados enviamos a response
		if($data_local)
		{
			$this->response_local->set_code(Response::SUCCESS);
			$this->response_local->set_data([
				'estado_nome' => $data_local->estado_pk,
				'cidade_nome' => $data_local->municipio_nome,
				'logradouro_nome' => $data_local->logradouro_nome,
				'local_num' => $data_local->local_num,
				'local_complemento' => $data_local->local_complemento,
				'local_referencia' => $data_local->local_referencia, //Inserindo o ponto de referencia no retorno para view
				'bairro_nome' => $data_local->bairro_nome
			]);
			$this->response_local->send();
		}
	}


	//Função que executa o upload da imagem relacionada a ordem de serviço
	public function upload_img($id_ordem,$base64_image)
	{	
    	//Definindo a pasta e o nome da imagem
		$path = "./assets/uploads/imagens_situacoes/";
		$name = hash(ALGORITHM_HASH, $id_ordem . uniqid(rand(), true));

        //Recebemos uma imagem em base 64, portanto e necessário remover o cabeçalho dela. 
		list($type, $base64_image) = explode(';', $base64_image);
		list(, $base64_image)      = explode(',', $base64_image);

        //Decodificando o texto na base 64
		$data = base64_decode($base64_image);

        //Função que cria um novo arquivo pelo caminho da pasta e nome gerados. 
		file_put_contents($path.$name, $data);

		return $path.$name;
	}

    //FUNÇÃO ANTIGA DE UPLOAD DE IMAGEM - RECEBIA UM BLOB - AGORA RECEBE UMA BASE64, POIS É MAIS LEVE
	//     //Função que executa o upload da imagem de perfil do usuário
	// public function upload_img($id_ordem)
	// {
 	//        //Definindo o nome da pasta de armazenamento do arquivo
	// 	$path = "./assets/uploads/imagens_situacoes/";

 	//        //Definindo as configurações para o upload no CI
	// 	$configUpload['upload_path'] = $path;
	// 	$configUpload['allowed_types'] = '*';
	// 	$configUpload['encrypt_name'] = true;
	// 	$configUpload['file_name'] = hash(ALGORITHM_HASH, $id_ordem . uniqid(rand(), true));

	// 	$this->upload->initialize($configUpload);

 	//        verificamos se o upload foi processado com sucesso
	// 	if (!$this->upload->do_upload('img')) {
 	//            // em caso de erro retornamos os mesmos para uma variável e enviamos para a home
 	//            // return $this->CI->upload->display_errors();
	// 		return null;
	// 	} else {
 	//            //se correu tudo bem, recuperamos os dados do arquivo
	// 		$data['dadosArquivo'] = $this->upload->data();

 	//      definimos o path original do arquivo
	// 		$arquivo['path'] = $path;
	// 		$arquivo['name'] = $data['dadosArquivo']['file_name'];
	// 		return $arquivo;
	// 	}
	// }

    	//Função que verifica se o serviço solicitado pelo usuário pertence a sua organização
	public function has_coordenada($latitude, $longitude)
	{	
		//Consultando o banco a coordenada passada
		$coordenada = $this->ordem_servico_model->get_coordenadas(['coordenada_lat' => $latitude, 'coordenada_long' => $longitude]);

		//Se a operação get retornar a coordenada, significa que ela existe, caso contrário ela será inserida quando retornar
		if($coordenada){
			return $coordenada;
		}
		else
		{
			return false;
		}
	}

    //Função que verifica se o serviço solicitado pelo usuário pertence a sua organização
	public function has_service(){

		//Consultando o banco o serviço solicitado
		$data = $this->servico_model->get([
			'situacoes.organizacao_fk' => $this->session->user['id_organizacao'],
			'servico_pk' => $this->input->post('servico_fk')
		]);

		//Se a operação get retornar o serviço, significa que ele existe na organização, caso contrário o serviço não foi encontrado = não existe
		if($data){
			return true;
		}
		else
		{
			return false; 
		}

	}

	//Função que verifica se o setor solicitado pelo usuário pertence a sua organização
	public function has_setor(){

		//Consultando o setor solicitado
		$data = $this->setor_model->get([
			'setores.setor_pk' => $this->input->post('setor'),
			'setores.organizacao_fk' => $this->session->user['id_organizacao']
		]);

		//Se a operação get retornar um setor, significa que ele existe na organização, caso contrário o setor não foi encontrado = não existe
		if($data){
			return true;
		}
		else
		{
			return false; 
		}

	}

	//Função que chama a função de inserção de imagem relacionando-a com a situação atual. 
	public function insert_imagem_situacao($return_ordem, $return_historico, $flag, $data_local=NULL, $cod_os=NULL, 
		$endereco_os=NULL)
	{
	    //Se o usuário enviou, então realizamos o upload
		$file = $this->upload_img($return_ordem['id'], $this->input->post('img'));

	    //Padronizando os dados para efetuar o insert
		$data_imagem = array(
			'historico_ordem_fk' => $return_historico['id'],
			'imagem_situacao_caminho' => $file,

		);

		//Realizando a inserção do caminho da imagem no banco de dados
		$setou = $this->ordem_servico_model->insert_image($data_imagem);

		//Se foi inserida com sucesso e é uma nova inserção retorna o registro efetuado com sucesso da ordem e histórico
		if($setou['code'] == 0)
		{
			if($flag == 1){
				$this->response->set_code(Response::SUCCESS);
				$this->response->set_data([
					'mensagem' => "Ordem de serviço foi cadastrada e histórico registrado com sucesso!",
					'ordem_servico_pk' => $return_ordem['id'],
					'historico_ordem_pk' => $return_historico['id'],
					'ordem_servico_cod' => $cod_os,
					'local_pk' => $data_local['local_fk'],
					'data_criacao' => date('d/m/Y h:i:s'),
					'endereco_os' => $endereco_os
				]);
			}
			else // Se foi inserido somente uma nova situação de histórico, retorna o registro efetuado com sucesso do historico. 
			{
				$this->response->set_code(Response::SUCCESS);
				$this->response->set_data([
					'mensagem' => "Novo histórico registrado com sucesso!",
					'ordem_servico_pk' => $return_ordem['id'],
					'historico_ordem_pk' => $return_historico['id']
				]);
			}

		}
		else
		{	//Se a inserção apresentou algum erro, avisamos o usuário que a ordem/historico foi registrada porém, a imagem não foi inserida 
			if($flag == 1)
			{
				$this->response->set_code(Response::DB_ERROR_INSERT);
				$this->response->set_data([
					'mensagem' => "Não foi possivel inserir a imagem! Contudo, a ordem de serviço foi cadastrada com sucesso!",
					'ordem_servico_pk' => $return_ordem['id'],
					'historico_ordem_pk' => $return_historico['id'],
					'local_fk' => $data_local['local_fk'],
					'ordem_servico_cod' => $cod_os,
					'data_criacao' => date('d/m/Y h:i:s'),
					'endereco_os' => $endereco_os
				]);
			}
			else
			{
				$this->response->set_code(Response::DB_ERROR_INSERT);
				$this->response->set_data("Não foi possivel inserir a imagem! Contudo, novo historico foi registrado com sucesso!");
			}
		}
	}

	//Função que realiza a inserção de uma nova ordem de serviço e de um novo histórico
	public function insert()
	{	
		//Instanciando uma nova response
		$this->response = new Response();

		//Setando as regras do form_validation para ordens de serviço
		$this->config_form_validation();

		//Se os formulários forem preenchidos e enviados corretamente 
		if ($this->form_validation->run()) 
		{

			//Recebendo os dados preenchidos pelo usuário via post
			$data_ordem = $this->input->post();

			//Verificando se o serviço e o setor passado é são válidos/existem para a respectiva organização 
			if($this->has_service() && $this->has_setor())
			{
				//Instanciando um novo local, por meio dele é inserido o local que o usuário passou via post
				$local = new Localizacao();
				$return_local = $local->insert();
				
				//Se o local foi inserido com sucesso, prosseguimos, caso contrário retornamos o erro 
				if ($return_local->code != 200)
				{
					return $return_local;
				}

				//Instanciando uma variável que receberá dados relacionados a coordenadas e local 
				$data_coordenadas = null;

	            //Padronizando os dados de Coordenadas
				if ($return_local->__get('data')['id'] != null) //Se o local foi inserido com sucesso, retornará o ID que vai compor os dados de coordenada
				{	
					$data_coordenadas = [
						'coordenada_lat' => $data_ordem['latitude'],
						'coordenada_long' => $data_ordem['longitude'],
						'local_fk' => $return_local->__get('data')['id']
					];
					$endereco_os = $data_ordem['logradouro_nome'] . ', ' .
					$data_ordem['local_num'] . ' - ' .
					$data_ordem['bairro'];
				}
				else
				{
					$this->response->set_code(Response::DB_ERROR_GET);
					$this->response->set_data(['mensagem' => 'Erro no local']);
					$this->response->send();
					return;
				}

	           	//Verificando se a coordenada passada já existe no banco de dados
				$already_exists = $this->has_coordenada($data_ordem['latitude'], $data_ordem['longitude']);


				if(!$already_exists) //Se ela não existe então será criada
				{
					$return_coordenada = $this->ordem_servico_model->insert_coordenada($data_coordenadas);
				}
				else
	            {	//Caso a coordenada já exista, a função retorna o id dela
	            	$return_coordenada = [
	            		'id' => $already_exists->coordenada_pk,
	            		'db_error' => [
	            			'code' => 0,
	            		],
	            	];
	            }

	            //Se a inserção for bem sucedida ou a coordenada já existir prosseguimos
	            if($return_coordenada['db_error']['code'] == 0)
	            {
	            	// Configuração do horário para pegar o ano e gerar o código da OS
	            	date_default_timezone_set('America/Sao_Paulo');

	            	// Pegando o número que fará parte do código da OS
	            	$proximo_cod = $this->ordem_servico_model->get_cont_and_update($this->session->user['id_organizacao']);

	            	$codigo_os = $data_ordem['abreviacao'].date('Y')."/".$proximo_cod;

	            	//Padronizando os dados da Ordem de Serviço 
	            	$data_ordem_servico = [
	            		'coordenada_fk' => $return_coordenada['id'],
	            		'prioridade_fk' => $data_ordem['prioridade_fk'],
	            		'procedencia_fk' => $data_ordem['procedencia'],
	            		'ordem_servico_status' => 1,
	            		'ordem_servico_desc' => $data_ordem['descricao'],
	            		'servico_fk' => $data_ordem['servico_fk'],
	            		'setor_fk' => $data_ordem['setor'],
	            		'ordem_servico_cod' => $codigo_os
	            	];

	            	//Realizando a inserção da nova ordem de serviço no banco de dados 
	            	$return_ordem = $this->ordem_servico_model->insert_os($data_ordem_servico);

	            	//Se a inserção foi bem sucedida, prosseguimos
	            	if($return_ordem['db_error']['code'] == 0)
	            	{	
	            		//Para inserir o histórico dessa nova ordem de serviço, é necessário obter a situação padrão do respectivo serviço 
	            		$situacao = $this->servico_model->get_current(['servico_pk' => $data_ordem['servico_fk']]);

	            		//Além da situação padrão, o histórico requer o registro do funcionário responsável pela atualização corrente do histórico, portanto como é a primeira situação, é necessário obter a identificação do usuário criador da OS
	            		$this->load->model('funcionario_model');
	            		$id_funcionario = $this->funcionario_model->get(['funcionarios.pessoa_fk' => $this->session->user['id_user']]);

	            		//Padronizando os dados de inserção do novo histórico da ordem de serviço
	            		$data_historico = [
	            			'ordem_servico_fk' => $return_ordem['id'],
	            			'funcionario_fk' => $id_funcionario[0]->funcionario_pk,
	            			'situacao_fk'	=> $situacao->situacao_padrao_fk,
	            			'historico_ordem_comentario' => $data_ordem['descricao']
	            		];

	            		//Realizando a inserção do histórico no banco de dados
	            		$return_historico = $this->historico_model->insert($data_historico);

	            		//Se a inserção foi bem sucedida, progredimos para inserir a imagem que poderá ser obrigatória ou opcional
	            		if($return_historico['db_error']['code'] == 0)
	            		{	
	            				//Caso a foto seja obrigatória, verificamos se ela realmente foi enviada pelo usuário, caso não tenha sido. Retornaremos erro. Caso foi enviada, efetuaremos a inserção
	            			if($this->input->post('img') !== "null")
	            			{	
	            					//Realizando a inserção do caminho da imagem (armazenada no servidor), no banco de dados, por meio da função abaixo. 
	            				$this->insert_imagem_situacao($return_ordem,$return_historico, 1, $data_coordenadas['local_fk'],
	            					$codigo_os, $endereco_os);
	            			}
	            				else //Se a imagem não era obrigatória e não foi enviada, então o processo foi finalizado e executado com sucesso, retornamos o response
	            				{	
	            					$this->response->set_code(Response::SUCCESS);
	            					$this->response->set_data([
	            						'mensagem' => "Ordem de serviço foi cadastrada e histórico registrado com sucesso!",
	            						'ordem_servico_pk' => $return_ordem['id'],
	            						'historico_ordem_pk' => $return_historico['id'],
	            						'local_fk' => $data_coordenadas['local_fk'],
	            						'ordem_servico_cod' => $codigo_os,
	            						'data_criacao' => date('d/m/Y h:i:s'),
	            						'endereco_os' => $endereco_os
	            					]);
	            				} 
	            			}
	            		else //Caso tenha acontecido algum erro de inserção no histórico, retornamos para o usuário
	            		{	
	            			$this->response->set_code(Response::DB_ERROR_INSERT);
	            			$this->response->set_data("Não foi possivel inserir o histórico de ordem de serviço");
	            		}
	            	}
	            	else
	            	{	//Caso ocorra algum erro de inserção da ordem de serviço, retornamos para o usuário
	            		if($return_ordem['db_error']['code'] == 503)
	            		{
	            			$this->response->set_code(Response::DB_ERROR_INSERT);
	            			$this->response->set_data("Não foi possivel inserir a ordem de serviço!");
	            		}
	            	}
	            }
	            else //Caso ocorra algum erro de inserção da localização, retornamos para o usuário
	            {
	            	$this->response->set_code(Response::DB_ERROR_INSERT);
	            	$this->response->set_data("Não foi possivel inserir a localização da ordem de serviço, portanto ordem não foi inserida!");
	            }
	        } 
	        else //Caso o serviço passado pelo usuário ou setor não pertença a organização, retornamos o erro para o usuário
	        {
	        	$this->response->set_code(Response::BAD_REQUEST);
	        	$this->response->set_data("Serviço selecionado não existe na organização!");  
	        }
	    } 
	    else //Caso algum dos dados requeridos no formulário preenchido pelo usuário não tenha sido enviado corretamente, retornamos o erro para o usuário 
	    {
	    	$this->response->set_code(Response::BAD_REQUEST);
	    	$this->response->set_data($this->form_validation->errors_array()); 
	    }

	    //Enviando o response
	    $this->response->send();

	}


	//Função que realiza a atualização de dados de uma ordem de serviço 
	public function update_os()
	{
		$this->response = new Response(); 

		//Setando as regras do form_validation para ordens de serviço
		$this->config_form_validation();

		//Verificando se as regras de formulário foram respeitadas, isto é, o usuário respondeu-os corretamente. 
		if ($this->form_validation->run()) 
		{

			//Recebendo os dados preenchidos pelo usuário via post
			$data_ordem = $this->input->post();

			//Verificando se o serviço passado pelo usuário é um serviço válido para a respectiva organização, bem como o setor
			if($this->has_service() && $this->has_setor()) 
			{
				//Instanciando um novo local, por meio dele é inserido o local que o usuário passou via post
				$local = new Localizacao();
				$return_local = $local->insert();

				//Se o local foi inserido com sucesso, prosseguimos, caso contrário retornamos o erro
				if ($return_local->code != 200)
				{
					return $return_local;
				}

				$data_coordenadas = null;

				//Padronizando os dados a serem inseridos sobre as coordenadas do local.
				if ($return_local->__get('data')['id'] != null) //Se o local foi inserido com sucesso, retornará o ID que vai compor os dados de coordenada
				{	
					//Padronizando os dados de Coordenadas
					$data_coordenadas = [
						'coordenada_lat' => $data_ordem['latitude'],
						'coordenada_long' => $data_ordem['longitude'],
						'local_fk' => $return_local->data['id']
					];
					$endereco_os = $data_ordem['logradouro_nome'] . ', ' .
					$data_ordem['local_num'] . ' - ' .
					$data_ordem['bairro'];
					
				}
				else
				{
					$this->response->set_code(Response::DB_ERROR_GET);
					$this->response->set_data(['erro' => 'Erro no local']);
					$this->response->send();
					return;
				}

	            //Verificando se a coordenada passada já existe no banco de dados
				$already_exists = $this->has_coordenada($data_ordem['latitude'], $data_ordem['longitude']);
				
				if(!$already_exists) //Se ela não existe então será inserida no banco
				{
					$return_coordenada = $this->ordem_servico_model->insert_coordenada($data_coordenadas);  
				}
				else
	            {	//Caso a coordenada já exista, a função retorna o id dela
	            	$return_coordenada = [
	            		'id' => $already_exists->coordenada_pk,
	            		'db_error' => [
	            			'code' => 0,
	            		],
	            	];
	            }

	            //Se a inserção for bem sucedida ou a coordenada já existir prosseguimos
	            if($return_coordenada['db_error']['code'] == 0)
	            {

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

	            	//Recuperamos a situação inicial do histórico e a última situação inserida, pois caso o usuário mude a Situação Inicial da Ordem de Serviço, ela deve ser alterada também no histórico
	            	$return_historico = $this->historico_model->get_first_and_last_historico(['ordem_servico_fk' => $data_ordem['ordem_servico_pk']]);

	            	//Recebendo a situação passada pelo usuário
	            	$situacao = $this->input->post('situacao_fk');

	            	$return_situacao = 0; 

	            	//Verificamos se houve alguma modificação da situação armazenada no banco e da situação editada pelo usuário 
	            	if($return_historico[0]->situacao_inicial_pk != $situacao){

	            		//Caso exista alguma diferença, então realizamos o update da informação
	            		$return_situacao = $this->historico_model->update(['historico_ordem_pk' => $return_historico[0]->historico_ordem_pk],['situacao_fk' => $situacao]);

	            	}

	            	//Realizando a atualização de todos os dados da ordem de serviço. 
	            	$return_ordem = $this->ordem_servico_model->update_os($data_ordem_servico, $data_ordem['ordem_servico_pk']);

	            	//Recuperando as coordenadas da respectiva ordem de serviço 
	            	$coordenada = $this->ordem_servico_model->get_coordenadas(['coordenada_pk' => $data_ordem_servico['coordenada_fk']]);

	            	//Se o uptdate foi efetuado com sucesso, retornamos SUCCESS
	            	if($return_ordem == 1)
	            	{
	            		$this->response->set_code(Response::SUCCESS);
	            		$this->response->set_data([
	            			'mensagem' => "Ordem de serviço foi modificada com sucesso!",
	            			'ordem_servico_pk' => $data_ordem['ordem_servico_pk'],
	            			'historico_ordem_pk' => $return_historico[0]->historico_ordem_pk,
	            			'local_fk' => $coordenada->local_fk,
	            			'endereco_os' => $endereco_os
	            		]);
	            	}
	            	else
	            	{	//caso o update não tenha retornada nenhuma linha modificada, existem duas possíveis situações: o usuário só modificou a situação, ou não modificou nada e clicou em salvar. 
	            		if($return_ordem == 0)
	            		{	

	            			//Verificando se o usuário alterou a situação 
	            			if($return_situacao == 1){
	            				$this->response->set_code(Response::SUCCESS);
	            				$this->response->set_data([
	            					'mensagem' => "A situação inicial da ordem de serviço foi modificada com sucesso!",
	            					'ordem_servico_pk' => $data_ordem['ordem_servico_pk'],
	            					'historico_ordem_pk' => $return_historico[0]->historico_ordem_pk,
	            					'local_fk' => $coordenada->local_fk,
	            					'endereco_os' => $endereco_os
	            				]);
	            			}
	            			else{ //O usuário não modificou nenhuma informação da ordem de serviço
	            				$this->response->set_code(Response::SUCCESS);
	            				$this->response->set_data([
	            					'mensagem' => "Os dados não foram alterados.<br>Certifique-se de ter modificado alguma informação.",
	            					'ordem_servico_pk' => $data_ordem['ordem_servico_pk'],
			            			'historico_ordem_pk' => $return_historico[0]->historico_ordem_pk,
			            			'local_fk' => $coordenada->local_fk,
			            			'endereco_os' => $endereco_os
	            				]);	
	            			}
	            		}
	            	}
	            }
	            else
	            {	//Caso não seja possível alterar a localização da ordem de serviço, retornamos erro
	            	$this->response->set_code(Response::DB_ERROR_INSERT);
	            	$this->response->set_data("Não foi possivel alterar a localização da ordem de serviço, portanto ordem não foi modificada!");
	            }
	        } 
	        else 
	        {	// Caso o serviço enviado pelo usuário não pertença a organização ou o setor não pertença, retornamos erro
	        	$this->response->set_code(Response::BAD_REQUEST);
	        	$this->response->set_data("Serviço selecionado não existe na organização!");  
	        }
	    } 
	    else 
	    {	//Caso o formulário não tenha sido preenchido corretamente pelo usuário, retornamos erro. 
	    	$this->response->set_code(Response::BAD_REQUEST);
	    	$this->response->set_data($this->form_validation->errors_array()); 
	    }


	    //Retornando o response 
	    $this->response->send();

	}


	//Função que realiza a inserção de uma nova situação/novo histórico de determinada ordem de serviço
	public function new_historico_os()
	{	

		//Instanciando um novo response 
		$this->response = new Response();

		//Realizando a atribuição de regras para o formulário a ser respondido pelo usuário
		$this->form_validation->set_rules(
			'ordem_servico_fk',
			'Ordem_Servico',
			'trim|required'
		);

		$this->form_validation->set_rules(
			'situacao_fk',
			'Situacao',
			'trim|required'
		);

		$this->form_validation->set_rules(
			'comentario',
			'Comentario',
			'required'
		);

		$this->form_validation->set_rules(
			'historico_pk',
			'Historico',
			'trim|required|is_natural_no_zero'
		);


		//Verificando se o usuário respeitou as regras de formulário e enviou os dados corretamente
		if($this->form_validation->run()) 
		{	
			//Recebendo os dados enviados pelo usuário (via POST)
			$data_historico = $this->input->post();

			//Carregando o model de funcionário para identificar o usuário 	
			$this->load->model('funcionario_model');
			$id_funcionario = $this->funcionario_model->get(['funcionarios.pessoa_fk' => $this->session->user['id_user']]);

			//Recuperando a ordem de serviço que possui o respectivo histórico sendo atualizado. 
			$ordem = $this->ordem_servico_model->get(['ordem_servico_pk' => $data_historico['ordem_servico_fk']]);

			//Se a ordem de serviço está ativa, isto é ela não foi excluída anteriormente, então prosseguimos (segurança máxima)
			if($ordem[0]->ordem_servico_status == 1){

				//Padronizando os dados da nova situação a ser registrado como histórico
				$data_novo_historico = [
					'ordem_servico_fk' => $data_historico['ordem_servico_fk'],
					'funcionario_fk' => $id_funcionario[0]->funcionario_pk,
					'situacao_fk'	=> $data_historico['situacao_fk'],
					'historico_ordem_comentario' => $data_historico['comentario']
				];

				//Realizando a inserção do novo histórico
				$return_historico = $this->historico_model->insert($data_novo_historico);

				//Se a inserção foi realizada com sucesso, então prosseguimos
				if($return_historico['db_error']['code'] == 0)
				{	
						//Atribuindo o ID da ordem de serviço para a variável $return_ordem['id'];
					$return_ordem['id'] = $data_historico['ordem_servico_fk'];

						if($this->input->post('img') !== "null") //Se a imagem foi enviada, prosseguimos para inserir com imagem
						{
							//Chamando a função que executa o salvamento da imagem no servidor e a inserção do caminho no banco de dados, em seguida retorna diratamente pro usuário sucesso ou falha na execução. 
							$this->insert_imagem_situacao($return_ordem,$return_historico, 2);
						}
						else
						{	
							//Caso não tenha enviado a foto, sucesso
							$this->response->set_data([
								'mensagem' => "Novo histórico registrado com sucesso!",
								'ordem_servico_pk' => $return_ordem['id'],
								'historico_ordem_pk' => $return_historico['id']
							]);
						} 
					}
					else
				{	//Se ocorreu algum erro de inserção do histórico, retornamos erro
					$this->response->set_code(Response::DB_ERROR_INSERT);
					$this->response->set_data("Não foi possivel inserir o histórico de ordem de serviço");
				}
			}
			else
			{	//Se existe a tentativa de criar um histórico para uma ordem de serviço que foi excluída, retornamos erro
				$this->response->set_code(Response::BAD_REQUEST);
				$this->response->set_data("A ordem de serviço desse histórico foi excluída´!"); 
			}
		} 
		else 
		{	//Se o usuário não preencheu corretamente o formulário e enviou dados incompletos, retornamos erro
			$this->response->set_code(Response::BAD_REQUEST);
			$this->response->set_data($this->form_validation->errors_array()); 
		}


		//enviando o response para a view
		$this->response->send();
	}


	//Função que realiza a ativação de uma ordem de serviço
	public function activate()
	{	

		//Instanciando um novo response
		$this->response = new Response();

		//Configurando as regras de validação dos dados a serem recebidos via post
		$this->form_validation->set_rules(
			'ordem_servico_pk',
			'Ordem',
			'trim|required');

		//Verificando se os dados foram preenchidos corretamente/neste caso precisa chegar o ID da ordem de serviço
		if($this->form_validation->run())
		{	

			//Recebendo os dados via post (ID ordem)
			$data = $this->input->post();

			//Realizando a atualização do status da ordem para 1 = ativada 
			$return_activate = $this->ordem_servico_model->update_status(['ordem_servico_pk' => $data['ordem_servico_pk']], ['ordem_servico_status' => 1]);

			//Se a atualização ocorreu bem, retornamos sucesso
			if($return_activate){
				$this->response->set_code(Response::SUCCESS);
				$this->response->set_data("Ordem de Serviço foi excluída com sucesso!");
			}
			else
			{	//Caso contrário, retornamos erro
				$this->response->set_code(Response::DB_ERROR_INSERT);
				$this->response->set_data("Não foi possivel deletar a ordem de serviço");	
			}
		}
		else
		{
			//Caso o ID da ordem de serviço não tenha sido enviado via post, erro. 
			$this->response->set_code(Response::BAD_REQUEST);
			$this->response->set_data($this->form_validation->errors_array()); 
		}

		//Enviando a response para o usuário (resposta servidor)
		$this->response->send();
	}

	//Função que realiza a desativação/exclusão de uma ordem de serviço
	public function deactivate()
	{	
		//Instanciando uma nova response
		$this->response = new Response();

		//Configurando as regras de validação dos dados a serem recebidos via post
		$this->form_validation->set_rules(
			'ordem_servico_pk',
			'Ordem',
			'trim|required'
		);
		//Verificando se os dados foram preenchidos corretamente/neste caso precisa chegar o ID da ordem de serviço
		if($this->form_validation->run())
		{	
			//Recebendo os dados via post (ID ordem)
			$data = $this->input->post();


			//Realizando a atualização do status da ordem para 0 = desativada/Excluída 
			$return_delete = $this->ordem_servico_model->update_status(['ordem_servico_pk' => $data['ordem_servico_pk']], ['ordem_servico_status' => 0]);
			
			//Se a atualização ocorreu bem (return_delete = true), retornamos sucesso 
			if($return_delete){
				$this->response->set_code(Response::SUCCESS);
				$this->response->set_data("Ordem de Serviço foi excluída com sucesso!");
			}
			else
			{	//Caso contrário, erro. 
				$this->response->set_code(Response::DB_ERROR_INSERT);
				$this->response->set_data("Não foi possivel deletar a ordem de serviço");	
			}
		}
		else
		{
			//Caso o ID da ordem de serviço não tenha sido enviado via post, erro.
			$this->response->set_code(Response::BAD_REQUEST);
			$this->response->set_data($this->form_validation->errors_array()); 
		}

		//Enviando a response para o usuário (resposta servidor)
		$this->response->send();
	}


	public function json(){
		// echo file_get_contents(base_url('assets/js/dashboard/ordem_servico/ordens.json'));
		$today = date('Y-m-d');
		$date = date('Y-m-d H:i:s', strtotime('-90 days', strtotime($today)));

		$ordens_servico['ordens'] = $this->ordem_servico_model->getJsonForWeb([
			'departamentos.organizacao_fk' => $this->session->user['id_organizacao'],
			'historicos_ordens.historico_ordem_tempo >= ' => $date	,
		]);

		echo json_encode($ordens_servico);
	}


	//Função que gera um json para preencher os históricos 
	public function json_especifico($id, $flag){

		if($flag == '0'){
			$ordens_servico['ordem'] = $this->ordem_servico_model->getEspecifico([
				'departamentos.organizacao_fk' => $this->session->user['id_organizacao'],
				'ordens_servicos.ordem_servico_pk' => $id
			]);

			$ordens_servico['ordem']['historico'] = $this->ordem_servico_model->getHistorico([
				'historicos_ordens.ordem_servico_fk' => $id
			]);
		}
		else
		{
			if($flag == '1'){
				$ordens_servico['ordem']['historico'] = $this->ordem_servico_model->getHistorico([
					'historicos_ordens.ordem_servico_fk' => $id
				]);
			}
		}

		echo json_encode($ordens_servico);
	}

}
?>