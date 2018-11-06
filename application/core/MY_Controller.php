<?php

/**
 * MY_Controller
 *
 * @package     application
 * @subpackage  core
 * @author      Gustavo
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class MY_Controller extends CI_Controller
{

    /**
     * Variável que pega a instancia do CodeIgniter
     *
     * @var object
     */
    private $ci;

    private $response;

    /**
     * Construtor da classe, carregando os helpers necessário
     * e chamando o method_filter()
     */
    public function __construct($response)
    {   
        parent::__construct();
        $this->ci = &get_instance();
        $this->load->helper('request');
        $this->load->helper('attempt');
        $this->load->helper('token');
        $this->load->model('Token_model', 'modeltoken');
        $this->load->model('tentativa_model');  
        
        $this->response = $response;
        $this->method_filter();
    }

    /**
     * Verifica o tipo de requisição que o servidor estará recebendo
     * e redireciona o fluxo para o método que corresponde à requisição
     */
    public function method_filter()
    {
        $method = '';

		//Se for POST e possuir mais um parametro na URL, redirecione para esse parametro
        if (is_post_request() && null !== $this->uri->segment(2) && $this->uri->segment(2) != "post") {
            $method = $this->uri->segment(2);
        } else {
            //Verifica as tentativas
            $attempt_result = verify_attempt($this->input->ip_address());
            
            
			//Se ele estiver liberado
            if ($attempt_result === true) {
                
                $header_obj = apache_request_headers();

                var_dump($header_obj); die();

                // var_dump($header_obj);die();

                
                //Verifica o token e lá dentro cria um novo token
                $new_token['token'] = verify_token($header_obj['token'], $this->response);
                
                // var_dump($new_token['token']);die();
				if ($new_token['token'] == false) {
					$this->response->send();
					die();
				}else{
                    $this->response->set_data($new_token);
                }
			} else {
                $this->response->set_code(Response::FORBIDDEN);
				$this->response->set_data($attempt_result);
				$this->response->send();
				die();
            }
            $method = $this->ci->input->server('REQUEST_METHOD');
        }
        $this->$method();
    }
}
