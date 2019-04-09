<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH."core\MY_Model.php";

class Tentativa_model extends MY_Model {
    const NAME = 'tentativas_login';
    const TABLE_NAME = 'tentativas_login';
    const PRI_INDEX = 'tentativa_ip';
    
    const FORM = array(
        'tentativa_ip',
        'tentativa_tempo',
    );

    public function delete($where = array()) {
        if (!is_array($where)) {
            $where = array(self::PRI_INDEX => $where);
        }
        $this->db->where($where);
        $this->db->or_where('tentativa_tempo < ',date('Y/m/d')." 00:00:00");
        // $this->db->get_compiled_delete(); die();
        $this->db->delete(self::TABLE_NAME);
        return $this->db->affected_rows();
    }

}
