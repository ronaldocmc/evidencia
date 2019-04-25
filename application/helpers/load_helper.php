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