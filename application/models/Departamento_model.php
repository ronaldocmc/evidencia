<?php
require_once APPPATH."core/MY_Model.php";

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Departamento_model extends MY_Model {
    const NAME = 'departamento';
    const TABLE_NAME = 'departamentos';
    const PRI_INDEX = 'departamento_pk';
    
    const FORM = array(
        'departamento_pk',
        'departamento_nome',
        'organizacao_fk',
        'ativo'
    );

    public function config_form_validation_primary_key()
    {
        $this->CI->form_validation->set_rules(
            'departamento_pk',
            'Departamento',
            'trim|required|is_natural'
        );
    }

    public function config_form_validation()
    {
        $this->CI->form_validation->set_rules(
            'departamento_nome',
            'Nome',
            'trim|required'
        );

        $this->CI->form_validation->set_rules(
            'organizacao_fk',
            'Organizacao',
            'trim|required'
        );
    }
}

?>