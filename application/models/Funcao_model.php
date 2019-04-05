<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH . "core\MY_Model.php";

class Funcao_model extends MY_Model {
   
        const NAME = 'funcoes';
        const TABLE_NAME = 'funcoes';
        const PRI_INDEX = 'funcao_pk';
    
        const FORM = array(
            'funcao_pk',
            'funcao_nome',
            'ativo'
        );

        public function config_form_validation_primary_key()
        {
            $this->CI->form_validation->set_rules(
                'funcao_pk',
                'funcao_pk',
                'trim|required|is_natural'
            );
        }
    
        function config_form_validation()
        {
            
        }

        
    
    }