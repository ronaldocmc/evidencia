<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH . "core/MY_Model.php";

class Tipo_Servico_model extends MY_Model
{

    const NAME = 'tipos_servicos';
    const TABLE_NAME = 'tipos_servicos';
    const PRI_INDEX = 'tipo_servico_pk';

    const FORM = array(
        'tipo_servico_pk',
        'tipo_servico_nome',
        'tipo_servico_desc',
        'prioridade_padrao_fk',
        'departamento_fk',
        'tipo_servico_abreviacao',
        'ativo'
    );

    // @override
    public function get($select, $where = null)
    {
        $this->CI->db->select($select);
        $this->CI->db->from(self::TABLE_NAME);
        $this->CI->db->join('prioridades', 'prioridades.prioridade_pk = ' . self::TABLE_NAME . '.prioridade_padrao_fk', 'LEFT');
        $this->CI->db->join('departamentos', 'departamentos.departamento_pk = ' . self::TABLE_NAME . '.departamento_fk');
        if ($where !== null) {
            if (is_array($where)) {
                foreach ($where as $field => $value) {
                    $this->CI->db->where($field, $value);
                }
            } else {
                $this->CI->db->where(self::PRI_INDEX, $where);
            }
        }
        // echo($this->CI->db->get_compiled_select());die();
        $result = $this->CI->db->get()->result();

        return $result;
    }


    public function config_form_validation()
    {
        $this->CI->form_validation->set_rules(
            'tipo_servico_nome',
            'Nome',
            'trim|required|max_length[128]'
        );

        $this->CI->form_validation->set_rules(
            'tipo_servico_desc',
            'Descrição',
            'trim|max_length[128]'
        );

        $this->CI->form_validation->set_rules(
            'prioridade_padrao_fk',
            'Prioridade Padrao',
            'trim|is_natural'
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

    public function config_form_validation_primary_key()
    {
        $this->CI->form_validation->set_rules(
            'tipo_servico_pk',
            'Tipo de Servico',
            'trim|required|is_natural'
        );
    }

    public function get_dependents($departamento)
    {
        $this->CI->db->select('tipo_servico_nome as name');
        $this->CI->db->from('tipos_servicos');
        $this->CI->db->where('departamento_fk', $departamento);
        return $this->CI->db->get()->result();
    }

    public function get_dependents_prioridade($prioridade)
    {
        $this->CI->db->select('tipo_servico_nome as name');
        $this->CI->db->from('tipos_servicos');
        $this->CI->db->where('prioridade_padrao_fk', $prioridade);
        return $this->CI->db->get()->result();
    }
}

