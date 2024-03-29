<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH.'core/Response.php';

class Authorization
{
    protected $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('Permissao_model', 'model');
    }

    public function refresh_permissions_in_memory()
    {
        $this->CI->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));

        $this->CI->cache->delete('permissions');

        $this->load_all_permissions_in_memory();
    }

    public function load_all_permissions_in_memory()
    {
        $this->CI->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));

        $memory_permissions = $this->_get_memory_permissions();

        $this->CI->cache->save('permissions', $memory_permissions, 36000); //1 hour
    }

    public function return_permissions($function_id = null)
    {
        if ($function_id == null) {
            $this->_check_if_has_user();
            $function_id = $this->CI->session->user['id_funcao'];
        }

        return $this->_get_permissions($function_id);
    }

    public function check_permission($controller, $method, $function_id = null)
    {
        if ($function_id == null) {
            $this->_check_if_has_user();

            $function_id = $this->CI->session->user['id_funcao'];
        }

        // log_message('monitoring', 'Action: [' . strtoupper($method ? $method : 'load') . '] in [' . strtoupper($controller) . '] by USER [' . $this->CI->session->user['email_user'] . ']');

        if ($method === '') {
            $method = 'get';
        }

        $authorized = $this->check_permission_on_memory($function_id, $controller, $method);
        //$authorized = $this->CI->model->check_permission($function_id, $controller, $method);

        return $authorized;
    }

    public function get_all_permissions()
    {
        $return_permissions = $this->CI->model->get_really_all_permissions();

        return $return_permissions;
    }

    private function check_permission_on_memory($function_id, $controller, $method)
    {
        $this->CI->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));

        $permissions = $this->CI->cache->get('permissions');

        if (array_key_exists($function_id, $permissions)) {
            $permissions_of_function = $permissions[$function_id]['permissions'];

            foreach ($permissions_of_function as $p) {
                if (
                    strtolower($p['controller']) == strtolower($controller)
                    && strtolower($p['method']) == strtolower($method)
                ) {
                    return true;
                }
            }

            return false;
        } else {
            // echo 'caiu aqui';
            return false;
        }
    }

    private function _has_user()
    {
        return ($this->CI->session->has_userdata('user')) ? true : false;
    }

    private function _check_if_has_user()
    {
        if (!$this->_has_user()) {
            throw new MyException(
                'É necessário que o usuário esteja logado.',
                Response::UNAUTHORIZED
            );
        }
    }

    private function _is_empty($data)
    {
        ($data == null || $data == '') ? true : false;
    }

    private function _get_memory_permissions()
    {
        $memory_permissions = [];

        $permissions = $this->CI->model->get_all_permissions();

        foreach ($permissions as $p) {
            $permission = [
                'action' => $p->acao_nome,
                'method' => $p->acao_metodo,
                'entity' => $p->entidade,
                'controller' => $p->controller,
                'id' => $p->permissao_pk,
            ];

            if (array_key_exists($p->funcao_pk, $memory_permissions)) {
                array_push($memory_permissions[$p->funcao_pk]['permissions'], $permission);
            } else {
                $memory_permissions[$p->funcao_pk]['function'] = ['name' => $p->funcao_nome];

                $memory_permissions[$p->funcao_pk]['permissions'] = [];
                array_push($memory_permissions[$p->funcao_pk]['permissions'], $permission);
            }
        }

        return $memory_permissions;
    }

    private function _get_permissions($function_id)
    {
        $return_permissions = [];

        $permissions = $this->CI->model->get_permissions($function_id);

        foreach ($permissions as $p) {
            $data = [];
            $data['entity'] = $p->entidade;
            $data['name'] = $p->acao.' '.$p->entidade;
            $data['entity'] = $p->entidade;
            $data['action'] = $p->acao;
            $data['controller'] = $p->controller;
            $data['id'] = $p->permissao_pk;
            array_push($return_permissions, $data);
        }

        return $return_permissions;
    }
}
