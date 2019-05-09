<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Response {

	const SUCCESS = 200;
	const BAD_REQUEST = 400;
	const UNAUTHORIZED = 401;
	const FORBIDDEN = 403;
	const NOT_FOUND = 404;	
	const INVALID_METHOD = 405;
	const SERVER_FAIL = 500;
	const TOKEN_TIMEOUT = 9999;
	const LOGOUT_ERROR = 9998;
	


	const DB_DUPLICATE_ENTRY = 1062;
	const DB_ERROR_UPDATE = 501;
	const DB_ERROR_GET = 502;
	const DB_ERROR_INSERT = 503;


	private $code;	 
	private $message;
	private $data;

	public function __construct($code = null)
	{
		$this->code = $code != null ? $code : self::SUCCESS;
		$this->message = "OK";
		$this->data = null;
	}

	public function __get($value)
	{
        return $this->$value;
    }

    public function __set($proper,$value)
    {
        $this->$proper = $value;
    }

    public function set_message($message)
    {
    	$this->message = $message;
    }

    public function set_use_success($status)
    {
    	$this->use_success = $status;
    }

	public function set_code($code)
	{
		$this->code = $code;
		switch ($code) 
		{
			case self::NOT_FOUND :
			$this->message = "Não encontrado";
			break;

			case self::INVALID_METHOD :
			$this->message = "Método de Requisição Inválido";
			break;

			case self::TOKEN_TIMEOUT :
			$this->message = "Acesso Negado! Sua sessão expirou.";
			break;

			case self::LOGOUT_ERROR :
			$this->message = "Erro ao sair!";
			break;

			case self::SUCCESS :
			$this->message = "Sucesso!";
			break;

			case self::UNAUTHORIZED :
			$this->message = "Acesso não autorizado.";
			break;

			case self::FORBIDDEN :
			$this->message = "Acesso Proibido.";
			break;

			case self::BAD_REQUEST :
			$this->message = "Dados Inválidos.";
			break;

			case self::DB_ERROR_INSERT :
			$this->message = "Erro ao inserir dados na tabela";
			break;

			case self::DB_ERROR_UPDATE :
			$this->message = "Erro ao atualizar dados na tabela";
			break;

			case self::DB_ERROR_GET :
			$this->message = "Erro ao selecionar dados na tabela";
			break;

			case self::DB_DUPLICATE_ENTRY :
			$this->message = "Dados duplicados.";
			break;	
			
			case self::SERVER_FAIL :
			$this->message = "Erro interno";
			break;
		}
	}


	/**
     * Altera os dados enviados na resposta
     *
     * @param Array com os dados que serão enviados
     * @return null
     */
	public function set_data($array)
	{
		$this->data = $array;
	}

	public function add_data($index, $data){
		$this->data[$index] = $data;
	}

	/**
     * Envia a requisição
     *
     * @param Array $where Optional. Associative array field_name=>value, for where condition. If specified, $id is not used
     * @return int Number of rows affected by the delete query
     */
	public function send()
	{
		$json['code'] = $this->code;
		$json['message'] = $this->message;
		$json['data'] = $this->data;
		
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($json,JSON_UNESCAPED_UNICODE);
	}

	/**
     * Verifica se a resposta da requisição é SUCCESS
     *
     * @param null
     * @return boolean TRUE ou FALSE
     */
	public function is_success()
	{
		return $this->code == self::SUCCESS;
	}
}

?>