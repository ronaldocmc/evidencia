<?php
require_once APPPATH . "core/MY_Model.php";

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Recuperacao_funcionario_model extends MY_Model
{
    const NAME = 'recuperação senha';
    const TABLE_NAME = 'recuperacoes_senha_funcionario';
    const PRI_INDEX = 'funcionario_fk';

    const FORM = array(
        'recuperacao_token',
    );

    function delete_by_token($token)
    {
        $this->CI->db->delete($this->getTableName(), ['recuperacao_token' => $token]);
    }

    // function delete_attempt($where)
    // {
    //     $this->CI->db->delete($this->getTableName(), ['funcionario_fk' => $where->pk]);
    // }

    // public function config_form_validation()
    // {
    //     $this->CI->form_validation->set_rules(
    //         'recuperacao_token',
    //         'Token',
    //         'trim|required|min_length[6]|max_length[128]'
    //     );
    // }

    function config_form_validation()
    {
        $this->CI->form_validation->set_rules(
            'email',
            'Email',
            'trim|required|valid_email|max_length[128]'
        );
    }

    function config_password_form_validation($token)
    {

        $this->CI->form_validation->set_rules('new_password',
            'Senha Atual',
            'trim|required|min_length[8]|max_length[128]'
        );

        $this->CI->form_validation->set_rules('new_password_repeat',
            'Repetir Senha',
            'trim|required|min_length[8]|max_length[128]|matches[new_password]'
        );

        if ($token === '' && $this->CI->session->userdata('id_user') !== null) 
        {
            $this->CI->form_validation->set_rules(
                'old_password',
                'Senha Antiga',
                'required'
            );
        }


    }

}
