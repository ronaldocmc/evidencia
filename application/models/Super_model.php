<?php
require_once APPPATH."core\MY_Model.php";

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Super_model extends MY_Model {
    const NAME = 'superusuario';
	const TABLE_NAME = 'superusuarios';
    const PRI_INDEX = 'superusuario_pk';
    
    const FORM = array(
        'superusuario_pk',
        'superusuario_nome',
        'superusuario_email',
        'superusuario_login',
        'superusuario_senha'
    );

    public function get($superuser)
    {
        $this->CI->db->select('*');
        $this->CI->db->from('superusuarios');

        foreach($superuser as $k => $v){
            $this->CI->db->where($k, $superuser[$k]);
        }
        return $this->CI->db->get()->row();  
    }

    public function get_all_without_me($pk)
    {
        $this->CI->db->select('*');
        $this->CI->db->from('superusuarios');
        $this->CI->db->where('superusuarios.superusuario_pk !=', $pk);
        return $this->CI->db->get()->result();  
    }

    /**
     * Se o @admin não está no login, adiciona.
     */
    private function set_admin_to_login()
    {
        //se possui o "@"
        if(strpos($this->__get('superusuario_login'), '@') != false)
        {
            $array = explode('@',$this->__get('superusuario_login'));

            if($array[1] != 'admin')
            {
                $this->__set('superusuario_login', $array[0].'@admin');
            }
        } 
        else 
        {
            $this->__set('superusuario_login', $this->__get('superusuario_login')."@admin");
        }
        
    }

    public function insert()
    {
        // $this->__set("superusuario_senha", hash(ALGORITHM_HASH, $this->__get("superusuario_senha") . SALT));
        //$this->insert();

        $this->set_admin_to_login();

        $this->__set('ativo', 0);
        $this->__set('superusuario_senha', '');
        return parent::insert();
    }

    public function add_confirm_pasword_to_form_validation()
    {
        $this->CI->form_validation->set_rules(
            'superusuario_senha',
            'Senha',
            'trim|required|min_length[8]|max_length[32]'
        );

        $this->CI->form_validation->set_rules(
            'confirme-senha',
            'Confirmação de Senha',
            'trim|required|min_length[8]|max_length[128]|matches[superusuario_senha]'
        );

    }

    public function config_form_validation()
    {
        $this->CI->form_validation->set_rules(
            'superusuario_login',
            'Login',
            'trim|required|min_length[4]|max_length[32]'
        );

       
        $this->CI->form_validation->set_rules(
            'superusuario_nome',
            'Nome',
            'trim|required|min_length[4]|max_length[128]'
        );

        $this->CI->form_validation->set_rules(
            'superusuario_email',
            'Email',
            'trim|required|regex_match[/[a-zA-Z0-9_\-.+]+@[a-zA-Z0-9-]+/]|max_length[128]'
        );
    }
}

?>