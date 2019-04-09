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
}
