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

	function log_exception($severity, $message, $filepath, $line) {
        $current_reporting = error_reporting();
        $should_report = $current_reporting & $severity;

        if ($should_report) {
            // call the original implementation if we should report the error
            parent::log_exception($severity, $message, $filepath, $line);
        }
    }
}
?>