
<?php 

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Historico_model extends CI_Model {

    /**
     * @name string TABLE_NAME Holds the name of the table in use by this model
     */
    const TABLE_NAME = 'historicos_ordens';

    /**
     * @name string PRI_INDEX Holds the name of the tables' primary index used in this model
     */
    const PRI_INDEX = 'historico_ordem_pk';

    /**
     * Retrieves record(s) from the database
     *
     * @param mixed $where Optional. Retrieves only the records matching given criteria, or all records if not given.
     *                      If associative array is given, it should fit field_name=>value pattern.
     *                      If string, value will be used to match against PRI_INDEX
     * @return mixed Single record if ID is given, or array of results
     */
    public function get($where = NULL) {
        $this->db->select('*');
        $this->db->from(self::TABLE_NAME);
        if ($where !== NULL) {
            if (is_array($where)) {
                foreach ($where as $field=>$value) {
                    $this->db->where($field, $value);
                }
            } else {
                $this->db->where(self::PRI_INDEX, $where);
            }
        }
        $result = $this->db->get()->result();
        // $result=(array) $return;
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

        public function get_first_and_last_historico($where = NULL) {
        $this->db->select('(SELECT historicos_ordens.situacao_fk FROM historicos_ordens WHERE historicos_ordens.ordem_servico_fk = ordens_servicos.ordem_servico_pk  ORDER BY historicos_ordens.historico_ordem_tempo ASC LIMIT 1) as situacao_inicial_pk, (SELECT historicos_ordens.historico_ordem_pk FROM historicos_ordens WHERE historicos_ordens.ordem_servico_fk = ordens_servicos.ordem_servico_pk  ORDER BY historicos_ordens.historico_ordem_tempo DESC LIMIT 1) as historico_ordem_pk');
        $this->db->from(self::TABLE_NAME);
         $this->db->join('ordens_servicos','ordens_servicos.ordem_servico_pk = '.self::TABLE_NAME. '.'.'ordem_servico_fk');
        if ($where !== NULL) {
            if (is_array($where)) {
                foreach ($where as $field=>$value) {
                    $this->db->where($field, $value);
                }
            } else {
                $this->db->where(self::PRI_INDEX, $where);
            }
        }
        $result = $this->db->get()->result();
        // $result=(array) $return;
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
    public function insert(Array $data) {
        if ($this->db->insert(self::TABLE_NAME, $data)) {
            $return = [
                'id'=> $this->db->insert_id(),
                'db_error' =>$this->db->error() 
            ];

        } else {

            $return = [
                'id'=> $this->db->insert_id(),
                'db_error' =>$this->db->error() 
            ];
        }

        return $return;
    }

    /**
     * Updates selected record in the database
     *
     * @param Array $data Associative array field_name=>value to be updated
     * @param Array $where Optional. Associative array field_name=>value, for where condition. If specified, $id is not used
     * @return int Number of affected rows by the update query
     */
    public function update($where = array(), Array $data) {
            if (!is_array($where)) {
                $where = array(self::PRI_INDEX => $where);
            }
        $this->db->update(self::TABLE_NAME, $data, $where);

        // var_dump($this->db->affected_rows()); die();

        return $this->db->affected_rows();
    }

    /**
     * Deletes specified record from the database
     *
     * @param Array $where Optional. Associative array field_name=>value, for where condition. If specified, $id is not used
     * @return int Number of rows affected by the delete query
     */
    public function delete($where = array()) {
        $this->db->delete(self::TABLE_NAME, $where);
        return $this->db->affected_rows();
    }



    public function get_max_data_os($os) {
        $this->db->select('*');

        $this->db->from(self::TABLE_NAME);

        $this->db->where(self::TABLE_NAME.'.ordem_servico_fk', $os);
        $this->db->order_by('historicos_ordens.historico_ordem_pk', 'DESC');
        $this->db->limit(1);
        
        $result =  $this->db->get()->result();
        if ($result) 
        {
            return ($result);
        } 
        else 
        {
            return false;
        }
    }


    public function getHistoricoForMobile($where)
    {
        $this->db->select('
            populacao.pessoa_nome AS funcionario,
            historicos_ordens.historico_ordem_tempo AS data,
            situacoes.situacao_nome AS situacao,
            historicos_ordens.historico_ordem_comentario AS comentario,
            imagens_situacoes.imagem_situacao_caminho as foto,
            imagens_perfil.imagem_caminho as funcionario_foto
        ');

        $this->db->from('historicos_ordens');
        $this->db->join('funcionarios','historicos_ordens.funcionario_fk = funcionarios.funcionario_pk');
        $this->db->join('populacao', 'funcionarios.pessoa_fk = populacao.pessoa_pk');
        $this->db->join('situacoes', 'situacoes.situacao_pk = historicos_ordens.situacao_fk');
        $this->db->join('imagens_situacoes', 'imagens_situacoes.historico_ordem_fk = historicos_ordens.historico_ordem_pk','LEFT');
        $this->db->join('imagens_perfil', 'populacao.pessoa_pk = imagens_perfil.pessoa_fk','LEFT');
        
        $this->db->order_by('historicos_ordens.historico_ordem_pk', 'DESC');
        $this->db->limit(1);

        if ($where !== NULL) {
            if (is_array($where)) {
                foreach ($where as $field=>$value) {
                    $this->db->where($field, $value);
                }
            } else {
                $this->db->where('historicos_ordens.ordem_servico_fk', $where);
            }
        }


         // echo $this->db->get_compiled_select();die();
        $result = $this->db->get()->result();

        if ($result) {
            return ($result);
        } else {
            return false;
        }
    }
}
        
 ?>