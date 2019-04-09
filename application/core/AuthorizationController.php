
<?php

/**
 * CRUD_Controller
 *
 * @package     application
 * @subpackage  core
 * @author      Darlan
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH . "core/Response.php";
require_once APPPATH . "core/MyException.php";

class AuthorizationController extends CI_Controller
{
    public function is_authorized()
    {
        $this->load->library('Authorization');

        $this->authorization = new Authorization();

        return $this->authorization->check_permission(
            $this->get_current_controller(),
            $this->get_current_method()
        );
    }

    private function get_current_controller()
    {
        return strtolower($this->uri->segment(1));
    }

    private function get_current_method()
    {
        return strtolower($this->uri->segment(2));
    }

    public function is_superuser()
    {
        return $this->session->user['is_superusuario'];
    }
}
