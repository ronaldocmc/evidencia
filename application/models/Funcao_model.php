<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH . "core\MY_Model.php";

class Funcao_model extends MY_Model {
   
        const NAME = 'funcoes';
        const TABLE_NAME = 'funcoes';
        const PRI_INDEX = 'funcao_pk';
    
        const FORM = array(
            // 'prioridade_nome',
        );
    
        function config_form_validation()
        {
            
        }
    
    }