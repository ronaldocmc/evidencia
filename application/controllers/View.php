<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

class View extends CI_Controller {

	function __construct() {
		parent::__construct();
	}

	public function index()
	{
		$this->load->view('auth/import');
		$this->load->view('auth/nav');
		$this->load->view('auth/test');
		$this->load->view('auth/footer');
	}

	public function wiki()
	{
		$this->load->view('auth/import');
		$this->load->view('auth/nav');
		$this->load->view('auth/wiki');
		$this->load->view('auth/footer');
	}

	public function generator()
	{
		$this->load->view('auth/import');
		$this->load->view('auth/nav');
		$this->load->view('auth/generator');
		$this->load->view('auth/footer');
	}
}

?>