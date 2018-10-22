<?php 


if (!defined('BASEPATH')) exit('No direct script access allowed');

class Procedencia_model extends CI_Model {

        /**
     * @name string TABLE_NAME Holds the name of the table in use by this model
     */
        const TABLE_NAME = 'procedencias';

    /**
     * @name string PRI_INDEX Holds the name of the tables' primary index used in this model
     */
    const PRI_INDEX = 'procedencia_pk';

    /**
     * Retrieves record(s) from the database
     *
     * @param mixed $where Optional. Retrieves only the records matching given criteria, or all records if not given.
     *                      If associative array is given, it should fit field_name=>value pattern.
     *                      If string, value will be used to match against PRI_INDEX
     * @return mixed Single record if ID is given, or array of results
     */
    public function get($where = null)
    {
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
}

?>