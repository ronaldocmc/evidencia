<?php
class Funcionario_model_test extends TestCase
{
	private $model;
	const EXISTENT_EMAIL = 'darlannakamura@hotmail.com';
	const EXISTENT_PASSWORD  = '67092b61f77b8303e5265125bd2dbc4488797d7e446b1766896c9eedf4764152dad7330e2d75792113644bc5c08e9928b4d9f49c2f0951684e740fb358da39d6';
	const INEXISTENT_EMAIL = 'not_valid@hotmail.com';
	const INEXISTENT_PASSWORD = 'unknown_pwd';
	const EXISTENT_ORGANIZATION = 'prudenco';

	public function setUp()
	{
		$this->resetInstance();
		$this->CI->load->model('Funcionario_model');
		$this->model = $this->CI->Funcionario_model;
	}

	/**
	*
	*
	* @test
	* Testando se o método get_login deve ser utilizado considerando o where como parâmetro.
	* caso não deva ser usado sem o where, é interessante especificar os argumentos a receber * como parâmetro.
	**/
	public function test_get_login_without_where_param()
	{	
		$funcionarios = $this->model->get_login();
		$this->assertInternalType('array',$funcionarios);
		$this->assertEquals(2, count($funcionarios));

		$funcionario = $funcionarios[0];

		$this->assertObjectHasAttribute('funcionario_pk', $funcionario);
		$this->assertObjectHasAttribute('pessoa_pk', $funcionario);
		$this->assertObjectHasAttribute('pessoa_nome', $funcionario);
		$this->assertObjectHasAttribute('organizacao_pk', $funcionario);
		$this->assertObjectHasAttribute('organizacao_nome', $funcionario);
		$this->assertObjectHasAttribute('acesso_senha', $funcionario);
		$this->assertObjectHasAttribute('contato_email', $funcionario);
		$this->assertObjectHasAttribute('imagem_caminho', $funcionario);
		$this->assertObjectHasAttribute('funcao_nome', $funcionario);
	}

	/**
	* @test
	* Este teste tem a finalidade de verificar se o get_login com o parâmetro where contendo
	* email e senha e com uma combinação válida, retornará uma instância em forma de array e
	* contendo 1 registro.
	**/
	public function test_get_login_with_where_param_and_existent_login()
	{

		$existent_login = [
			'contatos.contato_email' => self::EXISTENT_EMAIL,
			'acessos.acesso_senha'   => self::EXISTENT_PASSWORD
		];

		$funcionario = $this->model->get_login($existent_login);
		$this->assertInternalType('object', $funcionario);

		$this->assertObjectHasAttribute('funcionario_pk', $funcionario);
		$this->assertObjectHasAttribute('pessoa_pk', $funcionario);
		$this->assertObjectHasAttribute('pessoa_nome', $funcionario);
		$this->assertObjectHasAttribute('organizacao_pk', $funcionario);
		$this->assertObjectHasAttribute('organizacao_nome', $funcionario);
		$this->assertObjectHasAttribute('acesso_senha', $funcionario);
		$this->assertObjectHasAttribute('contato_email', $funcionario);
		$this->assertObjectHasAttribute('imagem_caminho', $funcionario);
		$this->assertObjectHasAttribute('funcao_nome', $funcionario);
	}

	/**
	* @test
	* Este teste tem a finalidade de verificar se o get_login com o parâmetro where contendo
	* email e senha e com uma combinação inválida, retornará false.
	**/
	public function test_get_login_with_where_param_and_inexistent_login()
	{

		$mock = 
		[
			'contatos.contato_email' => self::INEXISTENT_EMAIL,
			'acessos.acesso_senha'   => self::INEXISTENT_PASSWORD
		];

		$funcionarios = $this->model->get_login($mock);
		$this->assertEquals(false, $funcionarios);

	}

	/**
	* @test
	* Este teste tem a finalidade de verificar se a quantidade de  funcionários cadastrados são no total 2.
	**/
	public function test_count_without_where_param()
	{
		$result = $this->model->count();
		$this->assertEquals(2, $result);
	}


	/**
	* @test
	* Este teste tem a finalidade de verificar se a quantidade de  funcionários cadastrados são no total 2.
	**/
	public function test_count_with_where_param()
	{
		$result = $this->model->count(['organizacao_fk' => self::EXISTENT_ORGANIZATION]);
		$this->assertEquals(2, $result);
	}



	


}
