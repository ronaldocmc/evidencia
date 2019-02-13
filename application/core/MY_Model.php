<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once dirname(__FILE__) . "/Generic_Model.php";
require_once dirname(__FILE__) . "/MyException.php";
class MY_Model extends Generic_Model
{
    public $object;

    public static function getTableName()
    {
        return static::TABLE_NAME;
    }

    public static function getPriIndex()
    {
        return static::PRI_INDEX;
    }

    public static function getName()
    {
        return static::NAME;
    }

    private function check_attributes()
    {
        if (empty(static::FORM)) {
            throw new MyException('FORM não pode ser vazio!',
                Response::NOT_FOUND);
        }
    }

    function __contruct()
    {
        $this->object = [];
    }

    public function fill()
    {
        foreach(static::FORM as $field){
            if($this->CI->input->post($field) != null && $this->CI->input->post($field) != ''){
                $this->object[$field] = $this->CI->input->post($field);
            }
        }
    }

    public function __set($key, $value){
        $this->object[$key] = $value;
    }

    public function __get($key){
        return $this->object[$key];
    }

    public function insert(){
        return $this->insert_object($this->object);
    }

    public function update(){
        return $this->update_object($this->object, $this->object[$this->getPriIndex()]);
    }

    public function deactivate(){
        //TODO pegar o objeto e verificar se o ativo está 0. Se sim, retornar a mensagem de que o objeto já está desativado.
        return $this->update_object(['ativo' => 0], $this->object[$this->getPriIndex()]);
    }

    public function activate(){
        return $this->update_object(['ativo' => 1], $this->object[$this->getPriIndex()]);    
    }
}
