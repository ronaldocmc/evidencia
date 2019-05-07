<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH . "core/MY_Model.php";

class Procedencia_model extends MY_Model
{
    const NAME = 'procedencias';
    const TABLE_NAME = 'procedencias';
    const PRI_INDEX = 'procedencia_pk';

    const FORM = array(
        'procedencia_nome',
        'procedencia_desc',
    );

    function config_form_validation()
    {
        $this->CI->form_validation->set_rules(
            'procedencia_nome',
            'Nome',
            'trim|required|max_length[128]'
        );

        $this->CI->form_validation->set_rules(
            'procedendia_desc',
            'Descrição',
            'trim|required|max_length[128]'
        );
    }
}
