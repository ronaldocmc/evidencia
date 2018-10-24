<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__)."/Response.php");	
/**
 * Acess Class
 *
 * @package     Evidencia
 * @category    Controller
 * @author      Pedro Cerdeirinha & Matheus Palmeira 
 */
class Access extends CI_Controller {
	/**
	 * Variavel que representa a resposta do servidor ao usuário
	 *
	 * @var Response
	 */
	public $response;
	
    //-------------------------------------------------------------------------------

	/**
	 * Construtor da Classe
	 * 
	 * Chama o construtor da classe pai
	 *
	 * @return void
	 */
	function __construct() 
	{
		parent::__construct();
		date_default_timezone_set('America/Sao_Paulo');
		$this->response = new Response();
        $this->load->model('Log_model', 'log_model');
	}
    //--------------------------------------------------------------------------------

    /**
	 * Método padrão da classe Access
	 * 
	 * @return void
	 */
    public function index()
    {
    	if ($this->session->user['id_user'] === NULL)
    	{
    		$this->load->view('access/header_html');
    		$this->load->view('access/login', NULL, FALSE);
    		$this->load->view('access/pre_loader');
    	}
    	else
    	{
    		if ($this->session->user['id_organizacao'] === 'admin')
    		{
    			redirect('dashboard/superusuario');
    		}
    		else
    		{
    			redirect('dashboard/funcionario_administrador');
    		}
    	}
    }

	//--------------------------------------------------------------------------------

    /**
	 * Método para efetuar o logout do sistema. 
	 * 
	 * @return void
	 */
    public function quit()
    {
        $this->log_model->insert([
            'log_pessoa_fk' => $this->session->user['id_user'],
            'log_descricao' => 'Logut'
        ]);

	   session_destroy(); 
        
    	redirect(base_url());
    }

	//--------------------------------------------------------------------------------

    /**
	 * Realiza a autenticação do usuário verificando no model se uma dada combinação
	 * de login e senha existe e armazena resposta no $response
	 *
	 * @param       array  $access
	 */
    private function authenticate($access)
    {
		$response = $this->model->get_login($access);

    	if ($response !== FALSE)
    	{
    		if (isset($response->funcao_nome) && $response->funcao_nome == "Funcionário de Campo")
    		{
    			$this->response->set_code(Response::UNAUTHORIZED);
    			$this->response->set_data([
    				'erro' => 'Você não tem autorização para acessar o sistema'
    			]);
    		}
    		else
    		{	
    			$this->response->set_code(Response::SUCCESS);
    			$userdata =  [
    				'id_user' => $response->pessoa_pk,
    				'name_user' => $response->pessoa_nome,
    				'password_user' => isset($response->organizacao_pk) ? null : $response->acesso_senha,
    				'id_organizacao' => isset($response->organizacao_pk)?$response->organizacao_pk:'admin',
    				'name_organizacao' => isset($response->organizacao_nome)?$response->organizacao_nome:'Superusuario',
    				'email_user' => $response->contato_email,
    				'is_superusuario' => isset($response->organizacao_pk) ? FALSE : TRUE,
    				'image_user_min' => isset($response->imagem_caminho)?base_url('/assets/uploads/perfil_images/min/'.$response->imagem_caminho):base_url('/assets/img/default.png'),
    				'image_user' => isset($response->imagem_caminho)?base_url('/assets/uploads/perfil_images/'.$response->imagem_caminho):base_url('/assets/img/default.png'),
                    'id_funcionario' => isset($response->funcionario_pk)?$response->funcionario_pk:null,
    				'func_funcao' => isset($response->funcao_nome)?$response->funcao_nome:null
    			];

                $permissions = $this->get_permissions($userdata['func_funcao'], $userdata['is_superusuario']);
                $this->session->set_userdata('permissions', $permissions);
                $this->session->set_userdata('user',$userdata);
                $this->tentativa_model->delete($this->input->ip_address());

                $this->log_model->insert([
                    'log_pessoa_fk' => $response->pessoa_pk,
                    'log_descricao' => 'Logou no sistema'
                ]);
            }
        }
        else
        {
            $this->response->set_code(Response::NOT_FOUND);
            $attempt = [
             'tentativa_ip' => $this->input->ip_address(),
             'tentativa_tempo' => date('Y/m/d H:i:s')
            ];
            $this->tentativa_model->insert($attempt);
        }
    }

    //Este método irá pegar as permissões do tipo do usuário
 public function get_permissions($func_funcao, $is_superusuario)
 {
    $controller_exceptions = array();
    $method_exceptions     = array();
    $permissions           = array();
    
    if($is_superusuario)
    {
            //pass;

    }
    else
    {
        if($func_funcao == 'Administrador')
        {   
                //exceptions são os controllers que ele não tem permissão para acessar
            $controller_exceptions = array(
                0 => 'organizacao',
                1 => 'superusuario',
            );
        }
        else
        {
            if($func_funcao == 'Atendente')
            {
                $controller_exceptions = array(
                    0 => 'organizacao',
                    1 => 'superusuario',
                    2 => 'departamento',
                    3 => 'funcionario',
                    4 => 'prioridade',
                    5 => 'servico',
                    6 => 'setor',
                    7 => 'situacao',
                    8 => 'tipo_servico'
                );
                    //agora temos o caso se ele tem permissão para acessar um controller, mas não tem permissão para acessar um método do controlador:
                $method_exceptions = array(
                    0 =>  array(
                        'controller' => 'dashboard',
                        'method'     => 'superusuario'
                    ),
                    1 => array(
                        'controller' => 'ordem_servico',
                        'method'     => 'deactivate'
                    ),
                ); 
            }


        }
    }

    $permissions = array(
        'controller_exceptions' => $controller_exceptions,
        'method_exceptions'     => $method_exceptions
    );
    return $permissions;
 }

	//--------------------------------------------------------------------------------

    /**
	 * Método que recebe as informações de usuário e valida-as e, caso seja valido
	 * realiza o login e informa o sucesso, caso contrário informa as inconsistências
	 *
	 * @param       Input string g-recaptcha-response
	 * @param       Input string login
	 * @param       Input string password
	 * @return      void
	 */
    public function login() 
    {
    	$this->load->helper('recaptcha');
    	$this->load->helper('attempt'); 
    	$this->load->model('tentativa_model');
    	$this->load->library('form_validation');

		//Faz a verificação de que o usuário não trata-se de um robo
        if(ENVIRONMENT == 'testing')
        {
            $captcha_response = TRUE;
            $attempt_response = TRUE;
        }else{
        	$captcha_response = get_captcha($this->input->post('g-recaptcha-response'));
        	$attempt_response = verify_attempt($this->input->ip_address());
        }
    	if ($captcha_response === TRUE && $attempt_response === TRUE)
    	{	
    		$this->form_validation->set_rules('login', 
    			'Login',
    			'trim|required|regex_match[/[a-zA-Z0-9_\-.+]+@[a-zA-Z0-9-]+/]|min_length[8]|max_length[128]'
    		);
    		$this->form_validation->set_rules('password',
    			'Senha', 
    			'trim|required|min_length[8]|max_length[128]'
    		);
         
    		if ($this->form_validation->run() === TRUE) 
    		{
    			$id = explode('@',$this->input->post('login'));
                
    			$access =[
    				'acessos.acesso_senha' => hash(ALGORITHM_HASH,$this->input->post('password').SALT)
    			];

    			if ($id[1] != 'admin')
    			{
    				$this->load->model('Funcionario_model', 'model');
                    $access['contatos.contato_email'] = $this->input->post('login');
    			}
    			else
    			{
    				$this->load->model('Super_model','model');
                    $access['acessos.acesso_login'] = $id[0];
    			}
                $this->authenticate($access);
    		} 
    		else 
    		{	    		
    			$this->response->set_code(Response::BAD_REQUEST);
    			$this->response->set_data($this->form_validation->errors_array());
    		}
    	} 
    	else
    	{
    		if ($captcha_response !== TRUE)
    		{
    			$this->response->set_code(Response::UNAUTHORIZED);
    			$this->response->set_data($captcha_response);
    		}
    		else
    		{
    			$this->response->set_code(Response::FORBIDDEN);
    			$this->response->set_data($attempt_response);
    		}
    	}
    	$this->response->send();          
    }
}
