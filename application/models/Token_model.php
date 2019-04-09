<?php


if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH."core\MY_Model.php";

class Token_model extends MY_Model {
    const NAME = 'token';
    const TABLE_NAME = 'token';
    const PRI_INDEX = 'funcionario_fk';
    
    const FORM = array(
        'token',
        'funcionario_fk'
    );

}