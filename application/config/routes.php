<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Access';
$route['upload'] = 'Superusuario/new_execute';
$route['download/(:any)/(:any)'] = 'Superusuario/download_img';
$route['test/(:any)/(:any)'] = '_test/$1/$2';
$route['404_override'] = '';
$route['translate_uri_dashes'] = false;
$route['home'] = 'viewcontroller/funcionario';

$route['dashboard/funcionario_administrador'] = 'ViewController/funcionario';
$route['minha_conta'] = 'ViewController/minha_conta';
$route['organizacao/editar'] = 'ViewController/editar_informacoes_organizacao';

$route['filial'] = 'ViewController/super_index/organizacao';

$route['departamento'] = 'ViewController/index/departamento';
$route['setor'] = 'ViewController/index/setor';
$route['funcionario'] = 'ViewController/index/funcionario';
$route['funcao'] = 'ViewController/index/funcao';
$route['servico'] = 'ViewController/index/servico';
$route['tipo_servico'] = 'ViewController/index/tipo_servico';
$route['ordem_servico'] = 'ViewController/index/Ordem_Servico';
// $route['prioridade'] = 'ViewController/index/prioridade';
// $route['situacao'] = 'ViewController/index/situacao';
$route['relatorio'] = 'ViewController/listar_relatorios';
$route['relatorio/novo'] = 'ViewController/novo_relatorio';
$route['mapa'] = 'ViewController/mapa';
