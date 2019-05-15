<?php

require_once APPPATH.'core/MY_Model.php';

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Setor_model extends MY_Model
{
    const NAME = 'setor';
    const TABLE_NAME = 'setores';
    const PRI_INDEX = 'setor_pk';

    const FORM = array(
        'setor_pk',
        'setor_nome',
        'organizacao_fk',
        'ativo',
    );

    public function config_form_validation_primary_key()
    {
        $this->CI->form_validation->set_rules(
            'setor_pk',
            'Setor',
            'trim|required|is_natural'
        );
    }

    public function config_form_validation()
    {
        $this->CI->form_validation->set_rules(
            'setor_nome',
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
