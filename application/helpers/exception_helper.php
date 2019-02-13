<?php 
function return_exception($exception){
	$response = new Response();
	$response->set_code($exception->code);
	$response->set_data(['mensagem' => $exception->getMessage()]);
	return $response;
}
function handle_my_exception($exception){
	$response = new Response();
	$response->set_code($exception->code);
	$response->set_data(['mensagem' => $exception->getMessage()]);
	$response->send();
	die();
}
function handle_exception($exception){
	$response = new Response();
	$response->set_code(Response::SERVER_FAIL);
	$response->set_data(['mensagem' => $exception->getMessage()]);
	$response->send();
	die();
}
?>