<?php 
require_once APPPATH . "core/Response.php";
class MyException extends Exception {
	public $code;

	public function __construct($message, $code)
	{
		parent::__construct($message);
		$this->code = $code;
	}	
	
	public function set_code($message, $code){
		$this->code = $code;
	}
}
