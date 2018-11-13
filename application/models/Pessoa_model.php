<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pessoa_model extends CI_Model {

    /**
     * @name string TABLE_NAME Holds the name of the table in use by this model
     */
    const TABLE_NAME = 'populacao';

    /**
     * @name string PRI_INDEX Holds the name of the tables' primary index used in this model
     */
    const PRI_INDEX = 'pessoa_pk';

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
        $this->db->join('contatos','contatos.pessoa_fk = '.self::TABLE_NAME.'.'.self::PRI_INDEX,'left');
        $this->db->join('imagens_perfil','imagens_perfil.pessoa_fk = '.self::TABLE_NAME.'.'.self::PRI_INDEX,'left');
        $this->db->join('enderecos_pessoas','enderecos_pessoas.pessoa_fk = '.self::TABLE_NAME.'.'.self::PRI_INDEX,'left');
        $this->db->join('locais', 'locais.local_pk = enderecos_pessoas.local_fk','left');
        $this->db->join('logradouros', 'locais.logradouro_fk = logradouros.logradouro_pk','left');
        $this->db->join('bairros', 'bairros.bairro_pk = locais.bairro_fk','left');
        $this->db->join('municipios', 'bairros.municipio_fk = municipios.municipio_pk','left');
        $this->db->join('estados', 'municipios.estado_fk = estados.estado_pk','left');
        
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
        // $result = $this->db->get_compiled_select();
        // print_r($result);
        // die();

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
     * Updates selected record in the database
     *
     * @param Array $data Associative array field_name=>value to be updated
     * @param Array $where Optional. Associative array field_name=>value, for where condition. If specified, $id is not used
     * @return int Number of affected rows by the update query
     */

    public function update_contato(Array $data_contato, $where){
        $success = $this->db->update('contatos', $data_contato);
        return $success; 
    }

    public function insert(Array $data_pessoa, Array $data_contato, $data_endereco = NULL, $imagem_path = NULL) {
        $success['pessoa'] = $this->db->insert(self::TABLE_NAME, $data_pessoa);
        $success['db_error'] = $this->db->error();
        $success['pessoa_pk'] = $this->db->insert_id();

        $this->db->flush_cache();

        if (isset($data_endereco))
        {
            $data_endereco['pessoa_fk'] =  $success['pessoa_pk'];
            $success['endereco'] = $this->db->insert('enderecos_pessoas',$data_endereco);

            $this->db->flush_cache();
        }
        else
        {
            $success['endereco'] = TRUE;
        }

        $data_contato['pessoa_fk'] = $success['pessoa_pk'];
        $success['contato'] = $this->db->insert('contatos', $data_contato);
        $this->db->flush_cache();

        if($imagem_path != null)
        {   
            $success['imagem'] = $this->db->insert('imagens_perfil',[
                                                                        'imagem_caminho' => $imagem_path,
                                                                        'pessoa_fk' => $success['pessoa_pk']
                                                                    ]);
            $this->db->flush_cache();
        }
        $succes['imagem'] = false;
        return $success;
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
    public function update(Array $data_pessoa, Array $data_contato, $data_endereco = NULL, $imagem_path = NULL, $id) {
        $this->db->where(self::TABLE_NAME.'.'.self::PRI_INDEX,$id);
        $success['pessoa'] = $this->db->update(self::TABLE_NAME, $data_pessoa);
        $this->db->flush_cache();

        if (isset($data_endereco))
        {
            $data_endereco['pessoa_fk'] = $id;
            if ( $success['endereco'] = $this->db->insert('enderecos_pessoas',$data_endereco) === FALSE)
            {
                $this->db->flush_cache();
                $this->db->where('enderecos_pessoas.pessoa_fk',$id);
                $success['endereco'] = $this->db->update('enderecos_pessoas', $data_endereco);
            }
            $this->db->flush_cache();
        }
        else
        {
            $success['endereco'] = TRUE;
        }
        $this->db->where('contatos.pessoa_fk',$id);

        $success['contato'] = $this->db->update('contatos', $data_contato);
        $this->db->flush_cache();

        if($imagem_path != null)
        {   
            $this->db->where('imagens_perfil.pessoa_fk',$id);
            $success['imagem'] = $this->db->update('imagens_perfil',['imagem_caminho' => $imagem_path]);
            $this->db->flush_cache();
        }
        $succes['imagem'] = false;
        return $success;
    }


   public function get_image($id){
	    $this->db->where('pessoa_fk', $id);
	    $this->db->select('imagem_caminho');
	    $select = $this->db->get('imagens_perfil'); 
	    return $select->result();
	}


	public function insert_image($data_imagem){
       return $this->db->insert('imagens_perfil',$data_imagem);
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
?>