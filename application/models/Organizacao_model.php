<?php
require_once APPPATH."core\MY_Model.php";

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Organizacao_model extends MY_Model {
    const NAME = 'organização';
	const TABLE_NAME = 'organizacoes';
    const PRI_INDEX = 'organizacao_pk';
    
    const FORM = array(
        'organizacao_pk',
        'organizacao_cnpj',
        'organizacao_nome'
    );


    public function get()
    {
        $this->CI->db->select('*');
        $this->CI->db->from('organizacoes');
        $this->CI->db->join('localizacoes', 'organizacoes.localizacao_fk = localizacoes.localizacao_pk');
        $this->CI->db->join('municipios', 'localizacoes.localizacao_municipio = municipios.municipio_pk');
        return $this->CI->db->get()->result();  
    }

    public function get_object($pk)
    {
        $this->CI->db->select('*');
        $this->CI->db->from('organizacoes');
        $this->CI->db->join('localizacoes', 'organizacoes.localizacao_fk = localizacoes.localizacao_pk');
        $this->CI->db->where('organizacao_pk', $pk);
        return $this->CI->db->get()->row();
    }

    //    return $this->get_all(
    //     '*', //select *
    //     array(), //WHERE VAZIO 
    //     -1, //SEM LIMIT
    //     -1, //SEM OFFSET
    //     //JOIN com localizacoes
    //     [
    //         0 => [
    //             'table' => 'localizacoes',
    //             'on' => 'organizacoes.localizacao_fk = localizacoes.localizacao_pk'
    //         ]
    //     ] 
    //    );

//    public function config_form_validation_primary_key()
//    {
//         $this->CI->form_validation->set_rules(
//             'organizacao_pk',
//             'Domínio',
//             'trim|min_length[4]|max_length[128]'
//         );
//    }

    public function config_form_validation()
    {
        $this->CI->form_validation->set_rules(
            'organizacao_pk',
            'Domínio',
            'trim|required|min_length[4]|max_length[128]'
        );
        $this->CI->form_validation->set_rules(
            'organizacao_nome',
            'Nome',
            'trim|required|min_length[4]|max_length[128]'
        );

        $this->CI->form_validation->set_rules(
            'organizacao_cnpj',
            'CNPJ',
            'trim|required|regex_match[/[0-9].\-/]|min_length[18]|max_length[18]'
        );
    }

    public function get_and_increase_cod($organization)
    {
        $this->CI->db->select('organizacoes.proximo_cod');
        $this->CI->db->from('organizacoes');
        $this->CI->db->where('organizacoes.organizacao_pk', $organization);
        $cod = $this->CI->db->get()->result();

        $this->CI->db->set('organizacoes.proximo_cod', intval($cod[0]->proximo_cod) + 1);
        $this->CI->db->where('organizacoes.organizacao_pk', $organization);
        $this->CI->db->update('organizacoes');

        return intval($cod[0]->proximo_cod);
    }
}

?>