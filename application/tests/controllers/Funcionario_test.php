<?php
class Funcionario_test extends TestCase
{

	//$access_test;

	function get($url) { 
		$process = curl_init($url); 
		curl_setopt($process, CURLOPT_HTTPHEADER, [0 => 'Content-Type: application/json']); 
		curl_setopt($process, CURLOPT_HEADER, 0); 
		curl_setopt($process, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)'); 
		
		// curl_setopt($process,CURLOPT_ENCODING , 'gzip'); 
		curl_setopt($process, CURLOPT_TIMEOUT, 30); 
		curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1); 
		$return = curl_exec($process); 
		curl_close($process); 

		return $return; 
	} 
	// function post($url,$data) { 
	// 	$process = curl_init($url); 
	// 	curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers); 
	// 	curl_setopt($process, CURLOPT_HEADER, 1); 
	// 	curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent); 
	// 	if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file); 
	// 	if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file); 
	// 	curl_setopt($process, CURLOPT_ENCODING , $this->compression); 
	// 	curl_setopt($process, CURLOPT_TIMEOUT, 30); 
	// 	if ($this->proxy) curl_setopt($process, CURLOPT_PROXY, $this->proxy); 
	// 	curl_setopt($process, CURLOPT_POSTFIELDS, $data); 
	// 	curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
	// 	curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1); 
	// 	curl_setopt($process, CURLOPT_POST, 1); 
	// 	$return = curl_exec($process); 
	// 	curl_close($process); 

	// 	return $return; 
	// }

	/**
	* @test
	* 
	**/
	public function test_index()
	{	

		$output = $this->request('GET', 'funcionario/index');
		//$output = $this->get(base_url('funcionario'));
		$this->assertResponseCode(200);
		// $this->assertResponseCode(200); 
		// $this->assertContains('<title>Sistema Evidência</title>', $output); //titulo da página de login
		// $this->assertEquals('/access', $_SERVER['REQUEST_URI']);
	}



}