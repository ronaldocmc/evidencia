<?php

require_once APPPATH.'core/MY_Model.php';

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Organizacao_Cidade_model extends MY_Model
{
    const NAME = 'organizacao_cidade';
    const TABLE_NAME = 'organizacoes_cidades';
    const PRI_INDEX = 'organizacao_fk';

    const FORM = array(
        'organizacao_fk',
        'municipio_fk'
    );

    public function delete_city($data) {
    	$this->CI->db->where($data);
    	$this->CI->db->delete('organizacoes_cidades');
    }
}
