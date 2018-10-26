<?php
function load_view($views = array(), $subdirectory, $enable_menu = true)
{
    $CI = &get_instance();
    $CI->load->view('dashboard/commons/head', null, false);
    if ($enable_menu) {
        $CI->load->view('dashboard/' . $subdirectory . '/principal/menu', null, false);
    }
    foreach ($views as $view) {
        $CI->load->view($view['src'], $view['params'], false);
    }
    $CI->load->view('dashboard/commons/footer', null, false);
}

function load_view_ordem_servico($views = array(), $subdirectory, $enable_menu = true)
{
	$CI = &get_instance();
    $CI->load->view('dashboard/commons/head', null, false);
    $CI->load->view('dashboard/administrador/ordem_servico/main_scripts', null, false);
    if ($enable_menu) {
        $CI->load->view('dashboard/' . $subdirectory . '/principal/menu', null, false);
    }
    foreach ($views as $view) {
        $CI->load->view($view['src'], $view['params'], false);
    }
    $CI->load->view('dashboard/administrador/ordem_servico/scripts', null, false);
    $CI->load->view('dashboard/administrador/ordem_servico/footer', null, false);
    //$CI->load->view('dashboard/commons/footer', null, false);
}