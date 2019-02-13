<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH . "core\MY_Model.php";

class Ordem_Servico_model extends MY_Model
{

    const NAME = 'ordem_servico';
    const TABLE_NAME = 'ordem_servico';
    const PRI_INDEX = 'ordem_servico_pk';

    const FORM = array(
        'ordem_servico_pk',
        'prioridade_fk',
        'procedencia_fk',
        'servico_fk',
        'setor_fk',
        'funcionario_fk',
        'situacao_inicial_fk',
        'situacao_atual_fk',
        'ordem_servico_desc',
        'ordem_servico_comentario',
    );

    function get()
    {

        $this->CI->db->select('*');
        $this->CI->db->from($this->getTableName());

        $this->CI->db->join('prioridades', 'prioridades.prioridade_pk = ' . $this->getTableName() . '.prioridade_fk');
        $this->CI->db->join('procedencias', 'procedencias.procedencia_pk = ' . $this->getTableName() . '.procedencia_fk');
        $this->CI->db->join('servicos', 'servicos.servico_pk = ' . $this->getTableName() . '.servico_fk');
        $this->CI->db->join('setores', 'setores.setor_pk = ' . $this->getTableName() . '.setor_fk');
        $this->CI->db->join('funcionarios', 'funcionarios.funcionario_pk = ' . $this->getTableName() . '.funcionario_fk');
        $this->CI->db->join('situacoes', 'situacoes.situacao_pk = ' . $this->getTableName() . '.situacao_inicial_fk');
        $this->CI->db->join('situacoes', 'situacoes.situacao_pk = ' . $this->getTableName() . '.situacao_atual_fk');

        return $this->CI->db->get()->result();
    }

    function config_form_validation()
    {

        $this->CI->form_validation->set_rules(
            'prioridade_fk',
            'Prioridade',
            'trim|required|is_natural'
        );

        $this->CI->form_validation->set_rules(
            'procedencia_fk',
            'Procedência',
            'trim|required|is_natural'
        );

        $this->CI->form_validation->set_rules(
            'servico_fk',
            'Serviço',
            'trim|required|is_natural'
        );

        $this->CI->form_validation->set_rules(
            'setor_fk',
            'Setor',
            'trim|required|is_natural'
        );

        $this->CI->form_validation->set_rules(
            'funcionario_fk',
            'Funcionario',
            'trim|required|is_natural'
        );

        $this->CI->form_validation->set_rules(
            'situacao_inicial_fk',
            'Situacao Inicial',
            'trim|required|is_natural'
        );

        $this->CI->form_validation->set_rules(
            'situacao_atual_fk',
            'Situacao Atual',
            'trim|required|is_natural'
        );

        $this->CI->form_validation->set_rules(
            'ordem_servico_desc',
            'Descricao',
            'trim|required'
        );

        $this->CI->form_validation->set_rules(
            'ordem_servico_comentario',
            'Comentario',
            'trim|required'
        );
    }

}
