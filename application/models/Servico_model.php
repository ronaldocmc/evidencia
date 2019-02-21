<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH . "core\MY_Model.php";

class Servico_model extends MY_Model
{
    const NAME = 'servicos';
    const TABLE_NAME = 'servicos';
    const PRI_INDEX = 'servico_pk';

    const FORM = array(
        'servico_nome',
        'servico_desc',
        'situacao_padrao_fk',
        'tipo_servico_fk',
        'servico_abreviacao',
    );

    // @override
    function get($select, $where = null)
    {
        $this->CI->db->select($select);
        $this->CI->db->from(self::TABLE_NAME);
        $this->CI->db->join('situacoes', 'situacoes.situacao_pk = ' . self::TABLE_NAME . '.situacao_padrao_fk', 'left');
        $this->CI->db->join('tipos_servicos', 'tipos_servicos.tipo_servico_pk = ' . self::TABLE_NAME . '.tipo_servico_fk');
        if ($where !== null) {
            if (is_array($where)) {
                foreach ($where as $field => $value) {
                    $this->CI->db->where($field, $value);
                }
            } else {
                $this->CI->db->where(self::PRI_INDEX, $where);
            }
        }
        $result = $this->CI->db->get()->result();
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    function config_form_validation()
    {
        $this->CI->form_validation->set_rules(
            'servico_nome',
            'Nome',
            'trim|required|max_length[128]'
        );

        $this->CI->form_validation->set_rules(
            'servico_desc',
            'Descrição',
            'trim|required|max_length[128]'
        );

        $this->CI->form_validation->set_rules(
            'tipo_servico_fk',
            'Tipo de serviço',
            'trim|required|is_natural'
        );

        $this->CI->form_validation->set_rules(
            'servico_abreviacao',
            'Abreviação',
            'trim|required|max_length[10]'
        );

        $this->CI->form_validation->set_rules(
            'situacao_padrao_fk',
            'Situação Padrão',
            'trim|required|is_natural'
        );
    }
}
