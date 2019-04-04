<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH . "core\MY_Model.php";

class Situacao_model extends MY_Model
{

    const NAME = 'situacoes';
    const TABLE_NAME = 'situacoes';
    const PRI_INDEX = 'situacao_pk';

    const FORM = array(
        'situacao_pk',
        'situacao_nome',
        'situacao_descricao',
        'organizacao_fk'
    );

    function config_form_validation()
    {
        $this->CI->form_validation->set_rules(
            'situacao_nome',
            'Nome',
            'trim|required|max_length[128]'
        );

        $this->CI->form_validation->set_rules(
            'situacao_descricao',
            'Descrição',
            'trim|required|max_length[128]'
        );
    }
}