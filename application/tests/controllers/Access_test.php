<?php
require_once APPPATH."./controllers/Access.php";

class Access_test extends TestCase
{
	const EMAIL_ADMIN = 'darlannakamura@hotmail.com';
	const PASSWORD_ADMIN = '123456789';

	const EMAIL_SUPERUSER = 'superusuario@admin';
	const PASSWORD_SUPERUSER = '123456789';

	const EMAIL_EMPLOYEE = 'pietro_cheetos@hotmail.com';
	const PASSWORD_EMPLOYEE = '123456789';


	public function setUp()
	{
		$this->resetInstance();
		$this->CI->load->library('session');
		$this->CI->load->helper('url');
	}


	/**
	* @test
	* Testando se o usuário NÃO LOGADO consegue acessar a view de login:
	**/
	public function test_index_without_being_logged()
	{
		$output = $this->request('GET', 'access');
		$this->assertResponseCode(200); 
		$this->assertContains('<title>Sistema Evidência</title>', $output); //titulo da página de login
		$this->assertEquals('/access', $_SERVER['REQUEST_URI']);
	}

	public function test_login_admin()
	{
		$output = $this->request(
			'POST',
			'access/login',
			[
				'g-recaptcha-response' => 'something',
			 	'login' 			   => self::EMAIL_ADMIN,
			  	'password'             => self::PASSWORD_ADMIN
			]
		);
		$this->assertResponseCode(200); 
		$this->assertContains('{"code":200,"message":"Sucesso!","data":null}', $output);
	}

	/**
	* @test
	* Testa se o usuário da função Fiscal consegue logar no sistema.
	**/
	public function test_login_employee()
	{
		$output = $this->request(
			'POST',
			'access/login',
			[
				'g-recaptcha-response' => 'something',
			 	'login' 			   => self::EMAIL_EMPLOYEE,
			  	'password'             => self::PASSWORD_EMPLOYEE
			]
		);
		
		$this->assertResponseCode(200); 
		$this->assertContains('{"code":401', $output); //O fiscal deve receber o código 401
	}

	public function test_login_superuser()
	{
		$output = $this->request(
			'GET',
			'access/login',
			[
				'g-recaptcha-response' => 'something',
			 	'login' 			   => self::EMAIL_SUPERUSER,
			  	'password'             => self::PASSWORD_SUPERUSER
			]
		);

		$this->assertResponseCode(200); 
		$this->assertContains('{"code":200,"message":"Sucesso!","data":null}', $output);
	}

	/**
	* @test
	* @depends test_login_admin
	* Testando se o administrador LOGADO será redirecionado para seu painel:
	**/
	public function test_index_being_logged_with_admin()
	{
		$this->test_login_admin();

		$this->request('GET', 'access');
		$this->assertRedirect('dashboard/funcionario_administrador');
	}

	/**
	* @test
	* @depends test_login_superuser
	* Testando se o superuser LOGADO será redirecionado para seu painel:
	**/
	public function test_index_being_logged_with_superuser()
	{
		$this->test_login_superuser();

		$this->request('GET', 'access');
		$this->assertRedirect('dashboard/superusuario');
	}


	/**
	* @test
	* @depends test_login_superuser
	* Testando se o fiscal será redirecionado para algum painel:
	**/
	public function test_index_being_logged_with_employee()
	{
		$this->test_login_employee();

		$this->request('GET', 'access');
		$this->assertRedirect('access');
		//seria legal fazer um assert com o session, para ter certeza de que não criou.
	}

}