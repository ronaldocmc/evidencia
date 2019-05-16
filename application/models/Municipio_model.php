<?php
require_once APPPATH."core/MY_Model.php";

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Municipio_model extends MY_Model {
    const NAME = 'municipio';
	const TABLE_NAME = 'municipios';
    const PRI_INDEX = 'municipio_pk';
    
    const FORM = array(
        'municipio_pk',
        'municipio_nome',
        'municipio_cod'
    );


    public function get()
    {
        $this->CI->db->select('*');
        $this->CI->db->from('municipios');
        return $this->CI->db->get()->result();  
    }

}

?>