<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__) . "\..\Localizacao.php";
 
class Localizacao2_test extends CI_Controller {
  private $loc;
  private $CI;
 
  function __construct() {
    $this->loc = new Localizacao();
    $this->CI =& get_instance();
 
    $this->CI->load->library('unit_test');
 
    $this->class_methods = get_class_methods($this);
    $this->class_name = get_class($this);
    unset($this->class_methods[0]);
    unset($this->class_methods[count($this->class_methods)]);


    $this->CI->load->model('local_model');
  }
 
  public function index(){
 
    header("Content-Type: text/html; charset=UTF-8", true);
    foreach ($this->class_methods as $method_name) 
    {
      echo "<a href='".base_url('test/'.$this->class_name.'/'.$method_name)."'>".$method_name."</a><br>";
    }
  }


  public function insert()
  {

    $method = 'insert';
    $test_case = [ 
      [ 
        'test_name' => 'inserção correta', 
        'expected' => 200, 
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '97', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP', 
        'session' => [ 
          'is_superusuario' => false, 
          'id_organizacao' => 'prudenco' 
        ] 
      ],
      [ 
        'test_name' => 'Número vazio', 
        'expected' => 400, 
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP', 
        'session' => [ 
          'is_superusuario' => false, 
          'id_organizacao' => 'prudenco'
        ] 
      ],
      [ 
        'test_name' => 'Dados não obrigatórios', 
        'expected' => 200,
        'logradouro_nome' => '', 
        'local_num' => '', 
        'local_complemento' => '', 
        'bairro' => '', 
        'municipio_pk' => '', 
        'estado_pk' => '',  
        'session' => [ 
          'is_superusuario' => false, 
          'id_organizacao' => 'prudenco'
        ] 
      ]
    ];

    foreach($test_case as $c): 
 
      $this->CI->session->set_userdata('user',$c['session']); 
 
      $_POST['local_num'] = $c['local_num']; 
      $_POST['logradouro_nome'] = $c['logradouro_nome'];
      $_POST['local_num'] = $c['local_num'];
      $_POST['local_complemento'] = $c['local_complemento']; 
      $_POST['bairro'] = $c['bairro']; 
      $_POST['municipio_pk'] = $c['municipio_pk']; 
      $_POST['estado_pk'] = $c['estado_pk']; 
      $this->CI->form_validation->set_data($_POST); 
 
      ob_start(); 
      $this->loc->$method(); 
      $output = ob_get_contents(); 
      $var = json_decode($output); 
      ob_end_clean(); 

      if(isset($var->data->id)) 
      { 
        $this->CI->local_model->delete($var->data->id); 
      } 
 
      $this->CI->unit->run($var->code,$c['expected'], $c['test_name'], $output); 
       
      $this->CI->form_validation->reset_validation(); 
 
    endforeach; 
 
    header("Content-Type: text/html; charset=UTF-8",true); 
    echo "<a href=".base_url('test/'.$this->class_name.'/index').">Voltar</a>"; 
    echo $this->CI->unit->report();  
  }


}