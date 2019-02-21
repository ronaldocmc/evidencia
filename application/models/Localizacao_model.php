<?php
require_once APPPATH."core\MY_Model.php";

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Localizacao_model extends MY_Model {
    const NAME = 'localização';
	const TABLE_NAME = 'localizacoes';
    const PRI_INDEX = 'localizacao_pk';
    
    const FORM = array(
        'localizacao_rua',
        'localizacao_num',
        'localizacao_bairro',
        'localizacao_municipio'
    );

    public function config_form_validation()
    {
        $this->CI->form_validation->set_rules(
            'localizacao_rua',
            'Logradouro',
            'trim|required|max_length[128]'
        );

        $this->CI->form_validation->set_rules(
            'localizacao_num',
            'número',
            'trim|required|max_length[10]'
        );

        $this->CI->form_validation->set_rules(
            'localizacao_bairro',
            'bairro',
            'trim|required|max_length[128]'
        );

        $this->CI->form_validation->set_rules(
            'localizacao_municipio',
            'município',
            'trim|required|max_length[128]'
        );
    }

    public function get_cities()
    {
        $this->CI->db->select('*');
        $this->CI->db->from('municipios');
        return $this->CI->db->get()->result();
    }

    public function add_lat_long($lat, $long)
    {
        $this->__set('localizacao_lat', $lat);
        $this->__set('localizacao_long', $long);
    }
}
?>