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
    private function authenticate($response)
    {
    	if ($response !== FALSE)
    	{
    		if (isset($response->funcao_pk) && ($response->funcao_pk != '4' && $response->funcao_pk != '5'))
    		{
    			$this->response->set_code(Response::UNAUTHORIZED);
    			$this->response->set_message('Você não tem autorização para acessar o sistema');
    		}
    		else
    		{	
    			$this->response->set_code(Response::SUCCESS);
                $this->response->set_message('Login efetuado com sucesso');
    			$userdata =  [
                    'id_user' => isset($response->funcionario_pk) ? $response->funcionario_pk : $response->superusuario_pk,
                    'email_user' => isset($response->funcionario_pk) ? $response->funcionario_login : null,
                    'password_user' => isset($response->organizacao_pk) ? $response->funcionario_senha : $response->superusuario_senha,
                    'id_organizacao' => isset($response->organizacao_pk) ? $response->organizacao_pk : 'admin',
                    'name_user' => isset($response->funcionario_nome) ? $response->funcionario_nome : $response->superusuario_nome,
                    'name_organizacao' => isset($response->organizacao_nome)?$response->organizacao_nome:'Superusuario',
                    'is_superusuario' => isset($response->organizacao_pk) ? FALSE : TRUE,
                    'image_user_min' => isset($response->funcionario_caminho_foto) ? base_url('/assets/uploads/perfil_images/min/'.$response->funcionario_caminho_foto) : base_url('/assets/img/default.png'),
                    'image_user' => isset($response->funcionario_caminho_foto) ? base_url('/assets/uploads/perfil_images/'.$response->funcionario_caminho_foto) : base_url('/assets/img/default.png'),
    				'func_funcao' => isset($response->funcao_nome)?$response->funcao_nome : null
    			];
                $permissions = $this->get_permissions($userdata['func_funcao'], $userdata['is_superusuario']);
                $this->session->set_userdata('permissions', $permissions);
                $this->session->set_userdata('user',$userdata);
                $this->tentativa_model->delete($this->input->ip_address());
            }
        }
        else
        {
            $this->response->set_code(Response::NOT_FOUND);
            $this->response->set_message('Usuário não encontrado!');
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
                    0 => 'superusuario',
                    // 0 => 'organizacao',
                );
                $method_exceptions = array(
                    0 => array(
                        'controller' => 'organizacao',
                        'method'     => 'deactivate',
                    ),
                    
                    1 => array(
                        'controller' => 'organizacao',
                        'method'     => 'activate',
                    ),
                    2 => array(
                        'controller' => 'organizacao',
                        'method'     => 'index',
                    )
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
            $attempt_response = TRUE;
        }
        else
        {
        	$attempt_response = verify_attempt($this->input->ip_address());
        }
        
    	if ($attempt_response)
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
                $response = '';
    			$login = explode('@',$this->input->post('login'));
                if ($login[1] != 'admin') 
                {
                    $access['funcionario_login'] = $this->input->post('login');
                    $access['funcionario_senha'] = hash(ALGORITHM_HASH,$this->input->post('password').SALT);
                    $this->load->model('Funcionario_model', 'func_model');
                    $response = $this->func_model->get('*',$access)[0];
                }
                else
                {
                    $access['superusuario_login'] = $this->input->post('login');
                    $access['superusuario_senha'] = hash(ALGORITHM_HASH,$this->input->post('password').SALT);
                    $this->load->model('Super_model', 'su_model');
                    $response = $this->su_model->get($access);
                }
                
                $this->authenticate($response);
    		} 
    		else 
    		{	    		
    			$this->response->set_code(Response::BAD_REQUEST);
    			$this->response->set_message(implode('<br>', $this->form_validation->errors_array()));
    		}
    	} 
    	else
    	{
			$this->response->set_code(Response::FORBIDDEN);
            $this->response->set_message('Número de tentativas excedidas. ' . $attempt_response);
    	}
    	$this->response->send();          
    }
}