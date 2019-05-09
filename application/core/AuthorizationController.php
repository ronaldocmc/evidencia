
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

    /**
    * Authorization arrays
    */
    private $standart_methods = ['save', 'deactivate', 'activate', 'get'];
    private $controllers_methods = [
        'funcionario' => ['change_password'],
        'ordem_servico' => ['insert_situacao', 'delete'],
        'relatorio' => ['create_new_report', 'change_worker', 'receive_report']
    ];

    public function is_authorized()
    {
        if ($this->session->user['is_superusuario']) 
        {
            return true;
        }
        
        $this->load->library('Authorization');

        $this->authorization = new Authorization();

        if ($this->verify_standart_methods() || $this->verify_controllers_methods()) 
        {
            return $this->authorization->check_permission(
                $this->get_current_controller(),
                $this->get_current_method()
            );
        }
        else 
        {
            return true;
        }
    }

    protected function get_current_controller()
    {
        return strtolower($this->uri->segment(1));
    }

    protected function get_current_method()
    {
        return strtolower($this->uri->segment(2));
    }

    public function is_superuser()
    {
        return $this->session->user['is_superusuario'];
    }

    private function verify_standart_methods()
    {
        $method = $this->get_current_method();

        if (in_array($method, $this->standart_methods)) 
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    private function verify_controllers_methods()
    {
        $controller = $this->get_current_controller();
        $method = $this->get_current_method();

        if (array_key_exists($controller, $this->controllers_methods)) 
        {
            if (in_array($method, $this->controllers_methods[$controller])) 
            {
                return true;
            }
        }

        return false;
    }

}
