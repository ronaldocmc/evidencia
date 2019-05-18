<?php
require_once APPPATH."core/MY_Model.php";

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Recuperacao_super_model extends MY_Model {
    const NAME = 'recuperação senha';
	const TABLE_NAME = 'recuperacoes_senha';
    const PRI_INDEX = 'superusuario_fk';
    
    const FORM = array(
        'recuperacao_token'
    );

    public function delete_by_token($token)
    {
        $this->CI->db->delete($this->getTableName(), ['recuperacao_token' => $token]);
    }

    public function delete_attempt($where)
    {
        $this->CI->db->delete($this->getTableName(), ['superusuario_fk' => $where->pk]);
    }


    public function config_form_validation()
    {
        $this->CI->form_validation->set_rules(
            'recuperacao_token',
            'Token',
            'trim|required|min_length[6]|max_length[128]'
        );

        $this->form_validation->set_rules
        (
            'email',
            'Email',
            'trim|required|valid_email|max_length[128]'
        );
    }
}

?>