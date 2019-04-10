<?php
require_once APPPATH."core/MY_Model.php";

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Permissao_model extends MY_Model {
    const NAME = 'Permissão';
    const TABLE_NAME = 'permissoes';
    const PRI_INDEX = 'permissao_pk';

    public function get_permissions($function_id)
    {
        $this->CI->db->select('acao_nome as acao, entidade, controller, permissao_pk');

        $this->CI->db->from(self::TABLE_NAME);
        $this->CI->db->join('permissao_acoes', 
        'permissao_acoes.acao_pk = permissoes.acao_fk');
        $this->CI->db->join('permissao_entidades', 
        'permissao_entidades.entidade_pk = permissoes.entidade_fk');
        $this->CI->db->join('funcoes_permissoes', 
        'funcoes_permissoes.permissao_fk = permissoes.permissao_pk');

        $this->CI->db->order_by('entidade', 'ASC');

        $this->CI->db->where('funcoes_permissoes.funcao_fk', $function_id);

        $result = $this->CI->db->get()->result();
        return $result;
    }

    public function get_all_permissions()
    {
        $this->CI->db->select('funcao_pk, funcao_nome, acao_nome, 
        acao_metodo, entidade, controller, permissao_pk ');
        $this->CI->db->from('funcoes_permissoes');
        $this->CI->db->join('permissoes', 'permissoes.permissao_pk = funcoes_permissoes.permissao_fk');
        $this->CI->db->join('permissao_acoes', 'permissao_acoes.acao_pk = permissoes.acao_fk');
        $this->CI->db->join('funcoes', 'funcoes.funcao_pk = funcoes_permissoes.funcao_fk');
        $this->CI->db->join('permissao_entidades', 'permissao_entidades.entidade_pk = permissoes.entidade_fk');
        
        $this->CI->db->order_by('funcao_pk', 'ASC');    

        // echo $this->CI->db->get_compiled_select(); die();

        $result = $this->CI->db->get()->result();
        return $result;
    }

    public function check_permission($function_id, $controller, $method)
    {
        $this->CI->db->select('permissao_pk');

        $this->CI->db->from(self::TABLE_NAME);
        $this->CI->db->join('permissao_acoes', 
        'permissao_acoes.acao_pk = permissoes.acao_fk');
        $this->CI->db->join('permissao_entidades', 
        'permissao_entidades.entidade_pk = permissoes.entidade_fk');
        $this->CI->db->join('funcoes_permissoes', 
        'funcoes_permissoes.permissao_fk = permissoes.permissao_pk');

        $this->CI->db->where('funcoes_permissoes.funcao_fk', $function_id);
        $this->CI->db->where('permissao_entidades.controller', $controller);
        $this->CI->db->where('permissao_acoes.acao_metodo', $method);

        $result = $this->CI->db->count_all_results();

        return ($result >= 1) ? true : false;
    }   

    public function get_really_all_permissions()
    {
        $this->CI->db->select('acao_pk, entidade_pk, acao_nome, acao_metodo, entidade, controller, permissao_pk');
        $this->CI->db->from('permissoes');
        $this->CI->db->join('permissao_acoes', 'permissao_acoes.acao_pk = permissoes.acao_fk');
        $this->CI->db->join('permissao_entidades', 'permissao_entidades.entidade_pk = permissoes.entidade_fk');

        $result = $this->CI->db->get()->result();
        return $result;
    } 
}

?>