<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Servico_model extends CI_Model
{

    /**
     * @name string TABLE_NAME Holds the name of the table in use by this model
     */
    const TABLE_NAME = 'servicos';

    /**
     * @name string PRI_INDEX Holds the name of the tables' primary index used in this model
     */
    const PRI_INDEX = 'servico_pk';

    /**
     * Retrieves record(s) from the database
     *
     * @param mixed $where Optional. Retrieves only the records matching given criteria, or all records if not given.
     *                      If associative array is given, it should fit field_name=>value pattern.
     *                      If string, value will be used to match against PRI_INDEX
     * @return mixed Single record if ID is given, or array of results
     */
    public function get(Array $where = null)
    {   $this->db->select('*');
        $this->db->from(self::TABLE_NAME);
        $this->db->join('situacoes', 'situacoes.situacao_pk = ' . self::TABLE_NAME . '.situacao_padrao_fk', 'left');
        $this->db->join('tipos_servicos', 'tipos_servicos.tipo_servico_pk = ' . self::TABLE_NAME . '.tipo_servico_fk');
        if ($where !== null) {
            if (is_array($where)) {
                foreach ($where as $field => $value) {
                    $this->db->where($field, $value);
                }
            } else {
                $this->db->where(self::PRI_INDEX, $where);
            }
        }
        $result = $this->db->get()->result();
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    public function get_objects($where = null){
        $this->db->from(self::TABLE_NAME);
        if ($where !== null) {
            if (is_array($where)) {
                foreach ($where as $field => $value) {
                    $this->db->where($field, $value);
                }
            } else {
                $this->db->where(self::PRI_INDEX, $where);
            }
        }
        $result = $this->db->get()->result();
        if ($result) {
            return $result;
        } else {
            return false;
        }

    }

    public function get_current($where = null){
        $this->db->from(self::TABLE_NAME);
        $this->db->join('situacoes', 'situacoes.situacao_pk = ' . self::TABLE_NAME . '.situacao_padrao_fk', 'left');
        $this->db->join('tipos_servicos', 'tipos_servicos.tipo_servico_pk = ' . self::TABLE_NAME . '.tipo_servico_fk');
        if ($where !== null) {
            if (is_array($where)) {
                foreach ($where as $field => $value) {
                    $this->db->where($field, $value);
                }
            } else {
                $this->db->where(self::PRI_INDEX, $where);
            }
        }
        $result = $this->db->get()->row();
        if ($result) {
            return $result;
        } else {
            return false;
        }
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
            $id = $this->db->insert_id();

            if ($this->db->insert('geral_atualizacao', array('empresa_fk' => $this->session->user['id_organizacao']))) {
                $id_geral = $this->db->insert_id();
                $data_geral = array(
                    'geral_atualizacao_fk' => $id_geral,
                    'servico_fk' => $id,
                );

                if ($this->db->insert('servico_atualizacao', $data_geral)) {
                    return $id;
                } else {
                    $this->db->delete('geral_atualizacao', array('geral_atualizacao_pk' => $id_geral));
                    return false;
                }

            } else {
                $this->delete($id_situacao);
            }

        }
        return false;
    }

    /**
     * Updates selected record in the database
     *
     * @param Array $data Associative array field_name=>value to be updated
     * @param Array $where Optional. Associative array field_name=>value, for where condition. If specified, $id is not used
     * @return int Number of affected rows by the update query
     */
    public function update(array $data, $where = array())
    {
        if (!is_array($where)) {
            $where = array(self::PRI_INDEX => $where);
        }
        $this->db->update(self::TABLE_NAME, $data, $where);
        $affected = $this->db->affected_rows();

        if ($affected > 0) {
            $this->load->model('atualizacao_model');

            $this->atualizacao_model->update($where[self::PRI_INDEX], 'prioridade_fk', 'prioridade_atualizacao');
        }

        return $affected;
    }


    /**
     * Deletes specified record from the database
     *
     * @param Array $where Optional. Associative array field_name=>value, for where condition. If specified, $id is not used
     * @return int Number of rows affected by the delete query
     */
    public function delete($where = array()) {
        if (!is_array($where)) {
            $where = array(self::PRI_INDEX => $where);
        }
        $this->db->delete(self::TABLE_NAME, $where);
        return $this->db->affected_rows();
    }
}
