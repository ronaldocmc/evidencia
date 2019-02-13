<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__) . "/../../system/core/Model.php";
class Generic_Model extends CI_Model {
    public $CI;

    function __construct(){
        $this->CI = &get_instance();
    }

    public function get_all($select, $where, $limit, $offset, $join = NULL) {
        $this->CI->db->select($select);
        $this->CI->db->from($this->getTableName());
        if ($where !== NULL) {
            if (is_array($where)) {
                foreach ($where as $field=>$value) {
                    $this->CI->db->where($field, $value);
                }
            } else {
                $this->CI->db->where($this->getPriIndex(), $where);
            }
        }
        if($join != NULL){
            foreach($join as $j){
                $this->CI->db->join($j['table'], $j['on']);
            }
        }
        if($limit == -1 && $offset == -1){
            //pass
        }else {
            $this->CI->db->limit($limit, $offset);
        }
        // echo $this->CI->db->get_compiled_select(); die();
        $result = $this->CI->db->get()->result();
        return $result;
    }
    
    public function get_one($where = NULL) {
        $query = '';
        $query = "SELECT * FROM ".$this->getTableName()." ";
        if(is_array($where)){
            foreach($where as $field => $value){
                if($value === reset($where)){
                    $query.= " WHERE ".$field." = '".$value."'";
                 } else {
                    $query.= " AND ".$field." = '".$value."'";
                 }
            }
        }
        //echo $query;
        $result = $this->CI->db->query($query);
        if($result){
            return $result->row();
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
    public function insert_object(Array $data) {
        if ($this->CI->db->insert($this->getTableName(), $data)) {
            return $this->CI->db->insert_id();
        } else {
            throw new MyException('Não foi possível inserir na tabela '.$this->getTableName(), Response::SERVER_FAIL);
        }
    }
    /**
     * Updates selected record in the database
     *
     * @param Array $data Associative array field_name=>value to be updated
     * @param Array $where Optional. Associative array field_name=>value, for where condition. If specified, $id is not used
     * @return int Number of affected rows by the update query
     */
    public function update_object(Array $data, $where = array()) {
        if (!is_array($where)) {
            $where = array($this->getPriIndex() => $where);
        }
        $this->CI->db->update($this->getTableName(), $data, $where);
        if($this->CI->db->affected_rows() == -1){
            throw new MyException('Nenhuma linha afetada ao realizar update de '.$this->getTableName(), Response::NOT_FOUND);
        }
        return $this->CI->db->affected_rows();
    }
    /**
     * Deletes specified record from the database
     *
     * @param Array $where Optional. Associative array field_name=>value, for where condition. If specified, $id is not used
     * @return int Number of rows affected by the delete query
     */
    public function delete_object($where = array()) {
        if (!is_array()) {
            $where = array($this->getPriIndex() => $where);
        }
        $this->CI->db->delete($this->getTableName(), $where);
        if($this->CI->db->affected_rows() == -1){
            throw new MyException('Erro ao deletar '.$this->getTableName(), Response::NOT_FOUND);
        }
        return $this->CI->db->affected_rows();
    }
    public function run_form_validation(){
        if(!$this->CI->form_validation->run()){
            if(is_array($this->CI->form_validation->errors_array())){
                $errors = implode('<br>', $this->CI->form_validation->errors_array());
                throw new MyException($errors, Response::BAD_REQUEST);
            }
        }
    }
}
?>