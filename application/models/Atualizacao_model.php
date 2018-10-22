<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Atualizacao_model extends CI_Model
{

    /**
     * @name string TABLE_NAME Holds the name of the table in use by this model
     */
    const TABLE_NAME = 'geral_atualizacao';

    /**
     * @name string PRI_INDEX Holds the name of the tables' primary index used in this model
     */
    const PRI_INDEX = 'geral_atualizacao_pk';

    /**
     * Retrieves record(s) from the database
     *
     * @param mixed $where Optional. Retrieves only the records matching given criteria, or all records if not given.
     *                      If associative array is given, it should fit field_name=>value pattern.
     *                      If string, value will be used to match against PRI_INDEX
     * @return mixed Single record if ID is given, or array of results
     */
    public function get($organizacao_fk, $timestamp = null)
    {
        $atualizar['servico'] = $this->get_servico($organizacao_fk, $timestamp);
        $atualizar['tipo_servico'] = $this->get_tipo_servico($organizacao_fk, $timestamp);
        $atualizar['prioridade'] = $this->get_prioridade($organizacao_fk, $timestamp);
        $atualizar['situacao'] = $this->get_situacao($organizacao_fk, $timestamp);

        return $atualizar;
    }

    public function get_situacao($organizacao_fk, $timestamp)
    {
        $this->db->select('
        situacoes.situacao_pk, situacoes.situacao_nome, situacoes.situacao_descricao, situacoes.situacao_foto_obrigatoria, situacoes.situacao_ativo as status
        ');
        
        $this->db->from('geral_atualizacao');
        $this->db->where('geral_atualizacao.empresa_fk',$organizacao_fk);
        if ($timestamp != null) {
            $this->db->where('geral_atualizacao.geral_atualizacao_tempo >=', $timestamp);
        }

        $this->db->join('situacao_atualizacao', 'situacao_atualizacao.geral_atualizacao_fk = geral_atualizacao.geral_atualizacao_pk', 'LEFT');
        $this->db->join('situacoes', 'situacoes.situacao_pk = situacao_atualizacao.situacao_fk');

        return $this->db->get()->result();
    }

    public function get_prioridade($organizacao_fk, $timestamp)
    {
        
        $this->db->select('prioridade_fk, max(prazo_inserido_tempo) as tempo');
        $this->db->from('historico_prazo');
        $this->db->group_by('prioridade_fk');
        $this->db->order_by('prazo_inserido_tempo','ASC');
        
        $query = '('.$this->db->get_compiled_select().') as p2';
        
        $this->db->where('prioridades.organizacao_fk',$organizacao_fk);
       
        if ($timestamp != null) {
            $this->db->where('geral_atualizacao.geral_atualizacao_tempo >=', $timestamp);
        }

        $this->db->select('prioridade_atualizacao.*, prioridades.prioridade_pk, p1.prazo_duracao, prioridades.prioridade_nome, prioridade_desativar_tempo as status');
        $this->db->from('historico_prazo as p1');
        $this->db->join($query, 'p2.prioridade_fk = p1.prioridade_fk  and p2.tempo=p1.prazo_inserido_tempo');
        $this->db->order_by('p1.prazo_inserido_tempo','DESC');
        
        
        $this->db->join('prioridades','p1.prioridade_fk = prioridades.prioridade_pk');
        $this->db->join('prioridade_atualizacao','prioridade_atualizacao.prioridade_fk = prioridades.prioridade_pk');
        $this->db->join('geral_atualizacao','prioridade_atualizacao.geral_atualizacao_fk = geral_atualizacao.geral_atualizacao_pk');
        
        // echo $this->db->get_compiled_select();die();
        return $this->db->get()->result();
    }

    public function get_tipo_servico($organizacao_fk, $timestamp)
    {

        
        $this->db->select('
        tipos_servicos.tipo_servico_pk, tipos_servicos.tipo_servico_nome, tipos_servicos.tipo_servico_desc, tipos_servicos.prioridade_padrao_fk, tipos_servicos.tipo_servico_status as status
        ');
        
        $this->db->from('geral_atualizacao');
        $this->db->where('geral_atualizacao.empresa_fk',$organizacao_fk);
        if ($timestamp != null) {
            $this->db->where('geral_atualizacao.geral_atualizacao_tempo >=', $timestamp);
        }

        $this->db->join('tipo_servico_atualizacao', 'tipo_servico_atualizacao.geral_atualizacao_fk = geral_atualizacao.geral_atualizacao_pk', 'LEFT');
        $this->db->join('tipos_servicos', 'tipos_servicos.tipo_servico_pk = tipo_servico_atualizacao.tipo_servico_fk');

        return $this->db->get()->result();
    }

    public function get_servico($organizacao_fk, $timestamp)
    {
        $this->db->select('
        servicos.servico_pk, servicos.servico_nome, servicos.servico_desc, servicos.situacao_padrao_fk, servicos.tipo_servico_fk, servicos.servico_status as status
        ');
        
        $this->db->from('geral_atualizacao');
        $this->db->where('geral_atualizacao.empresa_fk',$organizacao_fk);
        if ($timestamp != null) {
            $this->db->where('geral_atualizacao.geral_atualizacao_tempo >=', $timestamp);
        }

        $this->db->join('servico_atualizacao', 'servico_atualizacao.geral_atualizacao_fk = geral_atualizacao.geral_atualizacao_pk');
        $this->db->join('servicos', 'servicos.servico_pk = servico_atualizacao.servico_fk');

        return $this->db->get()->result();
    }

    /**
     * Inserts new data into database
     *
     * @param Array $data Associative array with field_name=>value pattern to be inserted into database
     * @return mixed Inserted row ID, or false if error occured
     */
    public function insert(array $data)
    {
        if ($this->db->insert(self::TABLE_NAME, $data)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    /**
     * Updates selected record in the database
     *
     * @param Array $data Associative array field_name=>value to be updated
     * @param Array $where Optional. Associative array field_name=>value, for where condition. If specified, $id is not used
     * @return int Number of affected rows by the update query
     */
    public function update($pk_value, $fk_name, $table)
    {
        $id = $this->db->select('geral_atualizacao_fk')->from($table)->where(array($fk_name => $pk_value))->get()->row()->geral_atualizacao_fk;

        $this->db->flush_cache();

        $this->db->update('geral_atualizacao', array('geral_atualizacao_tempo' => date('Y-m-d H:i:s')), array('geral_atualizacao_pk' => $id));

        return $this->db->affected_rows();
    }

    /**
     * Deletes specified record from the database
     *
     * @param Array $where Optional. Associative array field_name=>value, for where condition. If specified, $id is not used
     * @return int Number of rows affected by the delete query
     */
    public function delete($where = array())
    {
        if (!is_array($where)) {
            $where = array(self::PRI_INDEX => $where);
        }
        $this->db->delete(self::TABLE_NAME, $where);
        return $this->db->affected_rows();
    }
}
