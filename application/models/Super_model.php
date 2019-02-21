<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Super_model Class
 *
 * @package     Evidencia
 * @category    Model
 * @author      Pedro Cerdeirinha & Matheus Palmeira 
 */
class Super_model extends CI_Model {

    /**
     * @name string TABLE_NAME Holds the name of the table in use by this model
     */
    const TABLE_NAME = 'superusuarios';

    /**
     * @name string PRI_INDEX Holds the name of the tables' primary index used in this model
     */
    const PRI_INDEX = 'superusuario_pk';


    public function get_all_superusers_without_me($pk)
    {
        $this->db->select('acessos.*, populacao.*, contatos.* , imagens_perfil.imagem_caminho, '.self::TABLE_NAME.".*"); 
        $this->db->from(self::TABLE_NAME);
        $this->db->join('acessos','acessos.pessoa_fk = '.self::TABLE_NAME.'.'.self::PRI_INDEX,'left');
        $this->db->join('populacao','populacao.pessoa_pk = '.self::TABLE_NAME.'.'.self::PRI_INDEX);
        $this->db->join('contatos','contatos.pessoa_fk = '.self::TABLE_NAME.'.'.self::PRI_INDEX);
        $this->db->join('imagens_perfil','imagens_perfil.pessoa_fk = '.self::TABLE_NAME.'.'.self::PRI_INDEX,'left');
        
        $this->db->where('populacao.pessoa_pk != ', $pk);

        $result = $this->db->get()->result();
        if ($result) {
           return $result;
        } else {
            return false;
        }
    }


    public function get_login($where = NULL)
    {
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
        $this->db->where(self::TABLE_NAME.'.ativo', 1);
        
        $result = $this->db->get()->result();
        if ($result) {
            if ($where !== NULL) {
                return array_shift($result);
            } else {
                return $result;
            }
        } else {
            return false;
        }
    }
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
        if ($result) {
            if ($where !== NULL) {
                return array_shift($result);
            } else {
                return $result;
            }
        } else {
            return false;
        }
    }

    public function get_pessoa_pk($pessoa_cpf){
        $this->db->select('pessoa_pk');
        $this->db->from('populacao');
        $this->db->where('pessoa_cpf', $pessoa_cpf);
        return $this->db->get()->row()->pessoa_pk;
    }

    /**
     * Inserts new data into database
     *
     * @param Array $data Associative array with field_name=>value pattern to be inserted into database
     * @return mixed Inserted row ID, or false if error occured
     */
    public function insert_super($data) {
        $result = $this->db->query('call insert_super_usuario('.$data.');');
        return $this->db->error();
    }



    /* 
     * Apaga os dados das tabelas caso algo de errado.
     * 
     */

    public function reset($pessoa_pk){
        // DELETE FROM `recuperacoes_senha` WHERE `recuperacoes_senha`.`pessoa_fk` = 11;
        // DELETE FROM `contatos` WHERE `contatos`.`pessoa_fk` = 11;
        // DELETE FROM `populacao` WHERE `populacao`.`pessoa_pk` = 11;
        $this->db->where('pessoa_fk',$pessoa_pk);
        $this->db->delete('recuperacoes_senha');

        $this->db->flush_cache();

        $this->db->where('pessoa_fk',$pessoa_pk);
        $this->db->delete('contatos');

        $this->db->flush_cache();

        $this->db->where('pessoa_pk',$pessoa_pk);
        $this->db->delete('populacao');

    }


     /**
      * Updates selected record in the database
      *
      * @param Array $data Associative array field_name=>value to be updated
      * @param Array $where Optional. Associative array field_name=>value, for where condition. If specified, $id is not used
      * @return int Number of affected rows by the update query
      */
     public function update_super(Array $data, $where = array()) {
       if (!is_array($where)) {
           $where = array(self::PRI_INDEX => $where);
       }
       $this->db->update(self::TABLE_NAME, $data, $where);
       return $this->db->affected_rows();
   }

   public function new_password($where, $password){
       return $this->db->update('acessos', $password, $where);
   }

    /**
     * Updates selected record in the database
     *
     * @param Array $data Associative array field_name=>value to be updated
     * @param Array $where Optional. Associative array field_name=>value, for where condition. If specified, $id is not used
     * @return int Number of affected rows by the update query
     */
    public function update(Array $data_pessoa, Array $data_contato, $imagem_path, $id) {
        $this->db->where('pessoa_pk',$id);
        $success['pessoa'] = $this->db->update('populacao', $data_pessoa);
        $this->db->flush_cache();

        $this->db->where('pessoa_fk',$id);
        $success['contato'] = $this->db->update('contatos', $data_contato);
        $this->db->flush_cache();

        if($imagem_path != null)
        {   
            $this->db->where('pessoa_fk',$id);
            $success['imagem'] = $this->db->update('imagens_perfil',['imagem_caminho' => $imagem_path]);
            $this->db->flush_cache();
        }
        $succes['imagem'] = false;
        return $success;
    }

    public function insert_image($data_imagem){
       return $this->db->insert('imagens_perfil',$data_imagem);
   }

   public function get_image($id){
    $this->db->where('pessoa_fk', $id);
    $this->db->select('imagem_caminho');
    $select = $this->db->get('imagens_perfil'); 
    return $select->result();

}

    /**
     * Deletes specified record from the database
     *
     * @param Array $where Optional. Associative array field_name=>value, for where condition. If specified, $id is not used
     * @return int Number of rows affected by the delete query
     */
    public function delete($pessoa_pk) {
        $this->db->where('pessoa_fk',$pessoa_pk);
        $this->db->delete('recuperacoes_senha');

        $this->db->flush_cache();

        $this->db->where('pessoa_fk',$pessoa_pk);
        $this->db->delete('contatos');

        $this->db->flush_cache();

        $this->db->where('pessoa_fk',$pessoa_pk);
        $this->db->delete('super_usuarios');

        $this->db->flush_cache();

        $this->db->where('pessoa_pk',$pessoa_pk);
        $this->db->delete('populacao');
    }
}

?>