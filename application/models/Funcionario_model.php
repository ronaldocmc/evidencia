<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH . "core\MY_Model.php";

class Funcionario_model extends MY_Model
{

    const NAME = 'funcionarios';
    const TABLE_NAME = 'funcionarios';
    const PRI_INDEX = 'funcionario_pk';

    const FORM = array(
        'funcionario_login',
        'funcionario_senha',
        'funcionario_nome',
        'funcionario_cpf',
        'funcao_fk',
        'departamento_fk',
        'funcionario_caminho_foto',
    );

    function get($where = null)
    {
        $this->CI->db->select('*');
        $this->CI->db->from(self::TABLE_NAME);
        $this->CI->db->join('funcionarios_setores', 'funcionarios_setores.funcionario_fk = ' . self::TABLE_NAME . '.' . self::PRI_INDEX, 'left');
        $this->CI->db->join('organizacoes', 'organizacoes.organizacao_pk = ' . self::TABLE_NAME . '.organizacao_fk');
        $this->CI->db->join('funcoes', 'funcoes.funcao_pk = funcionarios.funcao_fk');
        
        
        if ($where !== null) {
            if (is_array($where)) {
                foreach ($where as $field => $value) {
                    $this->CI->db->where($field, $value);
                }
            } else {
                $this->CI->db->where(self::PRI_INDEX, $where);
            }
        }
        $this->CI->db->where(self::TABLE_NAME . '.ativo', 1);

        // var_dump($this->CI->db->get_compiled_select());die();
        
        return $this->CI->db->get()->result();
        if ($result) {
            if ($where !== null) {
                return array_shift($result);
            } else {
                return $result;
            }
        } else {
            return false;
        }
    }

    function count($where = null)
    {
        $this->CI->db->select('count(*) as total');
        $this->CI->db->from(self::TABLE_NAME);
        if ($where !== null) {
            if (is_array($where)) {
                foreach ($where as $field => $value) {
                    $this->CI->db->where($field, $value);
                }
            } else {
                $this->CI->db->where(self::PRI_INDEX, $where);
            }
        }
        $result = $this->CI->db->get()->row()->total;
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }


    // @override
    function insert_funcionario($data_departamento, $data_setores)
    {

        $id = $this->insert_object($this->object);
        
        $this->CI->db->insert('funcionarios_departamentos', $data_departamento);
        
    }

    function update_setor(array $data, $where = array())
    {
        return $this->CI->db->update('funcionarios_setores', $data, $where);
    }

    function update_departamento(array $data, $where = array())
    {
        return $this->CI->db->update('funcionarios_departamentos', $data, $where);
    }

    function update_funcao(array $data, $where = array())
    {
        return $this->CI->db->update('funcionarios_funcoes', $data, $where);
    }



}
