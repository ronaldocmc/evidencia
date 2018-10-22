<?php 

/**
 * Estes métodos verificam o tipo de requisição que está 
 * chegando no servidor, retornando um boolean de acordo
 * com a comparação
 *
 * @author      Pietro
 */

function is_post_request()
{
	$ci =& get_instance();
	return strtolower($ci->input->server('REQUEST_METHOD')) == 'post';
}

function is_get_request()
{
	$ci  =& get_instance();
	return strtolower($ci->input->server('REQUEST_METHOD')) == 'get';
}

function is_delete_request()
{
	$ci  =& get_instance();
	return strtolower($ci->input->server('REQUEST_METHOD')) == 'delete';
}

function is_put_request()
{
	$ci  =& get_instance();
	return strtolower($ci->input->server('REQUEST_METHOD')) == 'put';
}

?>