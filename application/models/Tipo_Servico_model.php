<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH . "core\MY_Model.php";

class Tipo_Servico_model extends MY_Model
{

    const NAME = 'tipos_servicos';
    const TABLE_NAME = 'tipos_servicos';
    const PRI_INDEX = 'tipo_servico_pk';

    const FORM = array(
        'tipo_servico_nome',
        'tipo_servico_desc',
        'prioridade_padrao_fk',
        'departamento_fk',
        'tipo_servico_abreviacao'
    );

    // @override
    public function get($where = null)
    {
        $this->db->select('*');
        $this->db->from(self::TABLE_NAME);
        $this->db->join('prioridades', 'prioridades.prioridade_pk = ' . self::TABLE_NAME . '.prioridade_padrao_fk');
        $this->db->join('departamentos', 'departamentos.departamento_pk = ' . self::TABLE_NAME . '.departamento_fk');
        if ($where !== null) {
            if (is_array($where)) {
                foreach ($where as $field => $value) {
                    $this->db->where($field, $value);
                }
            } else {
                $this->db->where(self::PRI_INDEX, $where);
            }
        }
        // var_dump($this->db->get_compiled_select());die();
        $result = $this->db->get()->result();

        if ($result) {
            return $result;
        } else {
            return false;
        }
    }


    function config_form_validation()
    {
        $this->CI->form_validation->set_rules(
            'tipo_servico_nome',
            'Nome',
            'trim|required|max_length[128]'
        );

        $this->CI->form_validation->set_rules(
            'tipo_servico_desc',
            'Descrição',
            'trim|required|max_length[128]'
        );

        $this->CI->form_validation->set_rules(
            'prioridade_padrao_fk',
            'Prioridade Padrao',
            'trim|required|is_natural'
        );

        $this->CI->form_validation->set_rules(
            'tipo_servico_abreviacao',
            'Abreviação',
            'trim|required|max_length[10]'
        );        

        $this->CI->form_validation->set_rules(
            'departamento_fk',
            'Departamento',
            'trim|required|is_natural'
        );
    }



}

