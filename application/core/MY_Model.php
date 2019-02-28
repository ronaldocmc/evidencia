<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH."core/Generic_Model.php";
require_once APPPATH."core/MyException.php";
//require_once dirname(__FILE__) . "/Generic_Model.php";
// require_once dirname(__FILE__) . "/MyException.php";
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

    private function check_if_key_exists($key, $array) 
    {
        if(!array_key_exists($key, $array))
        {
            throw new MyException(
                'O atributo ativo não existe em '.$this->getName(),
                Response::NOT_FOUND
            );
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
        if(array_key_exists($this->getPriIndex(), $this->object)){
            return $this->update_object($this->object, $this->object[$this->getPriIndex()]);
        } else {
            throw new MyException(
                'Primary Index de '.$this->getName().' deve estar preenchido!', 
                Response::NOT_FOUND
            );
        }
    }

    public function deactivate(){

        $this->object = $this->get_one('*', [$this->getPriIndex() => $this->object[$this->getPriIndex()]]);
        // var_dump($this->object);die();
        $this->check_if_key_exists('ativo', $this->object);

        if($this->object->ativo == 0){
            throw new MyException(
                $this->getName().' já está desativado!',
                Response::BAD_REQUEST
            );
        } else {
            $field = $this->getPriIndex();
            return $this->update_object(['ativo' => 0], $this->object->$field);
        }
    }

    public function activate(){
        $this->object = $this->get_one('*', [$this->getPriIndex() => $this->object[$this->getPriIndex()]]);

        $this->check_if_key_exists('ativo', $this->object);

        if($this->object->ativo == 1){
            throw new MyException(
                $this->getName().' já está ativo!',
                Response::BAD_REQUEST
            );
        } else {
            $field = $this->getPriIndex();
            return $this->update_object(['ativo' => 1], $this->object->$field);
        }  
    }
}
