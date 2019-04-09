<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH."core\MY_Model.php";

class Tentativa_recuperacao_model extends MY_Model {
    const NAME = 'tentativas_recuperacoes';
    const TABLE_NAME = 'tentativas_recuperacoes';
    const PRI_INDEX = 'tentativa_ip';
    
    const FORM = array(
        'tentativa_ip',
        'tentativa_tempo',
        'tentativa_email'
    );

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
            $this->db->order_by('tentativa_tempo');
        }
        $result = $this->db->get()->result();
        if ($result) 
        {
            return $result;
        } 
        else
        {
            return false;
        }
    }

}
