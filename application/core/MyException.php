<?php 
require_once dirname(__FILE__) . "/../controllers/Response.php";
class MyException extends Exception {
	public $code;
	// public function __construct()
	// {
	// 	parent::__construct($message);
	// 	$this->code = Response::BAD_REQUEST;
	// }
	public function __construct($message, $code)
	{
		parent::__construct($message);
		$this->code = $code;
	}	
	public function set_code($message, $code){
		$this->code = $code;
	}
}
?>