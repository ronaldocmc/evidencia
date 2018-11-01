<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Funcionario_model Class
 *
 * @package     Evidencia
 * @category    Model
 * @author      Pedro Cerdeirinha & Matheus Palmeira 
 */
class Funcionario_model extends CI_Model {

    /**
     * @name string TABLE_NAME Holds the name of the table in use by this model
     */
    const TABLE_NAME = 'funcionarios';

    /**
     * @name string PRI_INDEX Holds the name of the tables' primary index used in this model
     */
    const PRI_INDEX = 'funcionario_pk';

    public function get_login($where = NULL)
    {
        $this->db->select('
            funcionarios.funcionario_pk,
            populacao.pessoa_pk,
            populacao.pessoa_nome,
            organizacoes.organizacao_pk,
            organizacoes.organizacao_nome,
            acessos.acesso_senha,
            contatos.contato_email,
            imagens_perfil.imagem_caminho,
            funcoes.funcao_nome,
            funcoes.funcao_pk
            ');
        $this->db->from(self::TABLE_NAME);
        $this->db->join('acessos','acessos.pessoa_fk = '.self::TABLE_NAME.'.pessoa_fk');
        $this->db->join('funcionarios_setores','funcionarios_setores.funcionario_fk = '.self::TABLE_NAME.'.'.self::PRI_INDEX, 'left');
        $this->db->join('populacao','populacao.pessoa_pk = '.self::TABLE_NAME.'.pessoa_fk');
        $this->db->join('organizacoes','organizacoes.organizacao_pk = '.self::TABLE_NAME.'.organizacao_fk');
        $this->db->join('contatos','contatos.pessoa_fk = '.self::TABLE_NAME.'.pessoa_fk');
        $this->db->join('imagens_perfil','imagens_perfil.pessoa_fk = '.self::TABLE_NAME.'.pessoa_fk','left');
        $this->db->join('funcionarios_funcoes','funcionarios_funcoes.funcionario_fk = '.self::TABLE_NAME.'.'.self::PRI_INDEX);
        $this->db->join('funcoes','funcoes.funcao_pk = funcionarios_funcoes.funcao_fk');
        
        if ($where !== NULL) {
            if (is_array($where)) {
                foreach ($where as $field=>$value) {
                    $this->db->where($field, $value);
                }
            } else {
                $this->db->where(self::PRI_INDEX, $where);
            }
        }
        //echo $this->db->get_compiled_select();
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


    public function get_login_mobile($where = NULL)
    {
        $this->db->select('
            funcionarios.funcionario_pk, 
            populacao.pessoa_pk, 
            populacao.pessoa_nome, 
            funcionarios_funcoes.funcao_fk, 
            funcionarios.organizacao_fk, 
            funcionarios.funcionario_status');
        $this->db->from(self::TABLE_NAME);
        $this->db->join('acessos','acessos.pessoa_fk = '.self::TABLE_NAME.'.pessoa_fk');
        $this->db->join('populacao','populacao.pessoa_pk = '.self::TABLE_NAME.'.pessoa_fk');
        $this->db->join('organizacoes','organizacoes.organizacao_pk = '.self::TABLE_NAME.'.organizacao_fk');
        $this->db->join('contatos','contatos.pessoa_fk = '.self::TABLE_NAME.'.pessoa_fk');
        $this->db->join('funcionarios_funcoes','funcionarios_funcoes.funcionario_fk = '.self::TABLE_NAME.'.'.self::PRI_INDEX);
        $this->db->join('funcoes','funcoes.funcao_pk = funcionarios_funcoes.funcao_fk');
        
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


    /**
     * Retrieves record(s) from the database
     *
     * @param mixed $where Optional. Retrieves only the records matching given criteria, or all records if not given.
     *                      If associative array is given, it should fit field_name=>value pattern.
     *                      If string, value will be used to match against PRI_INDEX
     * @return mixed Single record if ID is given, or array of results
     */
    public function get($where = NULL) {
        $this->db->select('
            funcionarios.funcionario_pk,
            funcionarios.funcionario_status,
            populacao.pessoa_pk,
            populacao.pessoa_nome, 
            populacao.pessoa_cpf,
            contatos.contato_cel,
            contatos.contato_tel,
            contatos.contato_email,
            locais.local_num,
            locais.local_complemento,
            locais.local_pk,
            logradouros.logradouro_pk,
            logradouros.logradouro_nome,
            bairros.bairro_pk,
            bairros.bairro_nome,
            municipios.municipio_pk,
            municipios.municipio_nome,
            estados.estado_pk,
            funcoes.funcao_pk,
            funcoes.funcao_nome,
            departamentos.departamento_pk,
            departamentos.departamento_nome,
            funcionarios_setores.setor_fk
            ');
        $this->db->from(self::TABLE_NAME);
        $this->db->join('funcionarios_funcoes','funcionarios_funcoes.funcionario_fk = '.self::TABLE_NAME.'.'.self::PRI_INDEX);
        $this->db->join('funcionarios_setores','funcionarios_setores.funcionario_fk = '.self::TABLE_NAME.'.'.self::PRI_INDEX, 'left');
        $this->db->join('enderecos_pessoas','enderecos_pessoas.pessoa_fk = '.self::TABLE_NAME.'.pessoa_fk','left');
        $this->db->join('locais', 'locais.local_pk = enderecos_pessoas.local_fk','left');
        $this->db->join('logradouros', 'locais.logradouro_fk = logradouros.logradouro_pk','left');
        $this->db->join('bairros', 'bairros.bairro_pk = locais.bairro_fk','left');
        $this->db->join('municipios', 'bairros.municipio_fk = municipios.municipio_pk','left');
        $this->db->join('estados', 'municipios.estado_fk = estados.estado_pk','left');
        $this->db->join('populacao','populacao.pessoa_pk = '.self::TABLE_NAME.'.pessoa_fk');
        $this->db->join('funcoes','funcoes.funcao_pk = funcionarios_funcoes.funcao_fk');
        $this->db->join('contatos','contatos.pessoa_fk = '.self::TABLE_NAME.'.pessoa_fk');
        $this->db->join('funcionarios_departamentos', 'funcionarios.funcionario_pk = funcionarios_departamentos.funcionario_fk', 'LEFT');
        $this->db->join('departamentos', 'departamentos.departamento_pk = funcionarios_departamentos.departamento_fk', 'LEFT');
        $this->db->group_by('funcionario_pk');


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
            return $result;
        } else {
            return false;
        }
    }


    public function count($where = NULL)
    {
        $this->db->select('count(*) as total');
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
        $result = $this->db->get()->row()->total;
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }


    public function insert_setor(Array $data_setor){
        $this->db->insert('funcionarios_setores', $data_setor);
        if (!$this->db->error()['code'])
        {
            return TRUE;
        }
        else
        {
            return FALSE;   
        }
    }


    public function insert_departamento(Array $data_departamento){
        $this->db->insert('funcionarios_departamentos', $data_departamento);
        if (!$this->db->error()['code'])
        {
            return TRUE;
        }
        else
        {
            return FALSE;   
        }
    }


    /**
     * Inserts new data into database
     *
     * @param Array $data Associative array with field_name=>value pattern to be inserted into database
     * @return mixed Inserted row ID, or false if error occured
     */
    public function insert(Array $data_func, $funcao) {
        if($this->db->insert(self::TABLE_NAME,$data_func))
        {
            $id = $this->db->insert_id();
            $data_funcao = [
                'funcao_fk' => $funcao,
                'funcionario_fk' => $id
            ];

            $this->db->insert('funcionarios_funcoes',$data_funcao);

            $response = [
                'funcionario_fk' => $id,
                'db_error' => $this->db->error()
            ];
        }
        else{
            $response = [
                'funcionario_fk' => NULL,
                'db_error' => $this->db->error()
            ];
        }

        return $response;
    }

    /**
     * Updates selected record in the database
     *
     * @param Array $data Associative array field_name=>value to be updated
     * @param Array $where Optional. Associative array field_name=>value, for where condition. If specified, $id is not used
     * @return int Number of affected rows by the update query
     */
    public function update(Array $data, $where = array()) {
        if (!is_array($where)) {
            $where = array(self::PRI_INDEX => $where);
        }
        if ($this->db->update(self::TABLE_NAME, $data, $where))
        {
            $this->db->select('*');
            $this->db->from(self::TABLE_NAME);
            foreach ($where as $field=>$value) {
                $this->db->where($field, $value);
            }
            $result = $this->db->get()->row()->funcionario_pk;
            return $result;
        }
        else
        {
            return FALSE;
        }
    }

    public function update_status(Array $data, $where = array()){
        return $this->db->update('funcionarios', $data, $where);

    }

    public function update_setor(Array $data, $where = array()){
        return $this->db->update('funcionarios_setores', $data, $where);
    }

    public function update_departamento(Array $data, $where = array()){
        return $this->db->update('funcionarios_departamentos', $data, $where);
    }

    public function update_funcao(Array $data, $where = array())
    {
        return $this->db->update('funcionarios_funcoes', $data, $where);
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
    
    // public function get_pessoa($field, $value){
    //     $this->db->select('*');
    //     $this->db->from('populacao');
    //     $this->db->where($field, $value);

    //     $result = 
    // }

    public function get_dpto($where = array()){
        $this->db->select('*');
        $this->db->from('departamentos');
        if ($where !== NULL) {
            if (is_array($where)) {
                foreach ($where as $field=>$value) {
                    $this->db->where($field, $value);
                }
            } else {
                $this->db->where('departamento_pk', $where);
            }
        }

        $result = $this->db->get()->result();
        if ($result) {
            if ($where !== NULL) {
                return array_shift($result);
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public function get_funcionarios_departamentos($where){
        $this->db->select('*');
        $this->db->from('funcionarios_departamentos');
        if ($where !== NULL) {
            if (is_array($where)) {
                foreach ($where as $field=>$value) {
                    $this->db->where($field, $value);
                }
            } else {
                $this->db->where('funcionario_fk', $where);
            }
        }
        
        $result = $this->db->get()->result();
        if ($result) {
            if ($where !== NULL) {
                return array_shift($result);
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public function get_funcao($where){
        $this->db->select('*');
        $this->db->from('funcoes');
        if ($where !== NULL) {
            if (is_array($where)) {
                foreach ($where as $field=>$value) {
                    $this->db->where($field, $value);
                }
            } else {
                $this->db->where('funcao_pk', $where);
            }
        }
        
        $result = $this->db->get()->result();
        if ($result) {
            if ($where !== NULL) {
                return array_shift($result);
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public function get_setor($where){
        $this->db->select('*');
        $this->db->from('funcionarios_setores');
        if ($where !== NULL) {
            if (is_array($where)) {
                foreach ($where as $field=>$value) {
                    $this->db->where($field, $value);
                }
            } else {
                $this->db->where('funcionario_fk', $where);
            }
        }
        
        $result = $this->db->get()->result();
        if ($result) {
            return ($result);
        } else {
            return false;
        }
    }

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

}
?>