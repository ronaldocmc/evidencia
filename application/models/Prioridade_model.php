<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH . "core\MY_Model.php";

class Prioridade_model extends MY_Model
{

    const NAME = 'prioridades';
    const TABLE_NAME = 'prioridades';
    const PRI_INDEX = 'prioridade_pk';

    const FORM = array(
        'prioridade_nome',
    );

    function config_form_validation()
    {
        $this->CI->form_validation->set_rules(
            'prioridade_nome',
            'Nome',
            'trim|required|max_length[128]'
        );
    }

}
