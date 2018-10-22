<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__) . "\..\Funcionario.php";

class Funcionario_test extends CI_Controller {
  private $func;
  private $CI;

  function __construct() {
    $this->func = new Funcionario();
    $this->CI =& get_instance();

    $this->CI->load->library('form_validation');
    $this->CI->load->library('unit_test');
    $this->CI->load->model('pessoa_model');

    $this->class_methods = get_class_methods($this);
    $this->class_name = get_class($this);
    unset($this->class_methods[0]);
    unset($this->class_methods[count($this->class_methods)]);
  }

  public function index(){

    header("Content-Type: text/html; charset=UTF-8", true);
    foreach ($this->class_methods as $method_name) {
      echo "<a href='".base_url('test/'.$this->class_name.'/'.$method_name)."'>".$method_name."</a><br>";
    }
  }

  public function insert() {
    $method = 'insert';
    $test_case = [ 
      [
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-0000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541703', 
        'municipio_nome' => 'Quatá',
        'estado_pk' => 'SP',
        'senha_su' => '',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 200,
        'test_name' => 'municipio novo',
        'session' => [
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      //Inserts feito por Administrador
      [
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-0000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP',
        'senha_su' => '',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 200,
        'test_name' => 'insert correto',
        'session' => [
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_nome' => '',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-0000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP',
        'senha_su' => '',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 400,
        'test_name' => 'Nome Vazio',
        'session' => [
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_nome' => 'pes',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-0000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP',
        'senha_su' => '',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 400,
        'test_name' => 'Nome Pequeno',
        'session' => [
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_nome' => 'Nam quis nulla. Integer malesuada. In in enim a arcu imperdiet malesuada. Sed vel lectus. Donec odio urna, tempus molestie, port',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-0000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP',
        'senha_su' => '',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 200,
        'test_name' => 'Nome Máximo permitido',
        'session' => [
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_nome' => 'Nam quis nulla. Integer malesuada. In in enim a arcu imperdiet malesuada. Sed vel lectus. Donec odio urna, tempus molestie, port1',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-0000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP',
        'senha_su' => '',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 400,
        'test_name' => 'Nome maior que permetido',
        'session' => [
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_nome' => 'pess',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-0000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP',
        'senha_su' => '',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 200,
        'test_name' => 'Nome mínimo',
        'session' => [
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-0000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP',
        'senha_su' => '',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 400,
        'test_name' => 'CPF vazio',
        'session' => [
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020044',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-0000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP',
        'senha_su' => '',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 400,
        'test_name' => 'CPF incorreto',
        'session' => [
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-4',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-0000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP',
        'senha_su' => '',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 400,
        'test_name' => 'CPF pequeno',
        'session' => [
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-444',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-0000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP',
        'senha_su' => '',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 400,
        'test_name' => 'CPF grande',
        'session' => [
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '',
        'contato_cel' => '',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP',
        'senha_su' => '',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 200,
        'test_name' => 'Telefone e Celular vazio',
        'session' => [
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '',
        'contato_cel' => '',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP',
        'senha_su' => '',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 200,
        'test_name' => 'Telefone e Celular vazio',
        'session' => [
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '',
        'contato_cel' => '',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP',
        'senha_su' => '',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 200,
        'test_name' => 'Telefone e Celular vazio',
        'session' => [
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-00000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP',
        'senha_su' => '12345678',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 400,
        'test_name' => 'Telefone maior',
        'session' => [
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-0000',
        'contato_cel' => '(18) 90000-00000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP',
        'senha_su' => '12345678',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 400,
        'test_name' => 'Celular maior',
        'session' => [
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-00000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP',
        'senha_su' => '',
        'funcao_fk' => '',
        'departamento_fk' => '5',
        'expected' => 400,
        'test_name' => 'Função Vazia',
        'session' => [
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-00000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP',
        'senha_su' => '',
        'funcao_fk' => '999',
        'departamento_fk' => '5',
        'expected' => 400,
        'test_name' => 'Função Inexistente',
        'session' => [
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-00000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP',
        'senha_su' => '',
        'funcao_fk' => '1',
        'departamento_fk' => '',
        'expected' => 400,
        'test_name' => 'Departamento Vazio',
        'session' => [
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-00000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP',
        'senha_su' => '',
        'funcao_fk' => '4',
        'departamento_fk' => '999',
        'expected' => 400,
        'test_name' => 'Departamento Inexistente',
        'session' => [
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      //Inserts feitos por Superusuário
      [
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-0000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP',
        'senha_su' => '12345678',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 200,
        'test_name' => 'insert super correto',
        'session' => [
          'is_superusuario' => true,
          'password_user' => hash(ALGORITHM_HASH,'12345678'.SALT),
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-0000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP',
        'senha_su' => '87654321',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 401,
        'test_name' => 'senha super incorreta',
        'session' => [
          'is_superusuario' => true,
          'password_user' => hash(ALGORITHM_HASH,'12345678'.SALT),
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-0000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP',
        'senha_su' => '1234567',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 400,
        'test_name' => 'senha pequena',
        'session' => [
          'is_superusuario' => true,
          'password_user' => hash(ALGORITHM_HASH,'12345678'.SALT),
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-0000',
        'contato_cel' => '(18) 90000-0000',
        'senha_su' => 'Nam quis nulla. Integer malesuada. In in enim a arcu imperdiet malesuada. Sed vel lectus. Donec odio urna, tempus molestie, port',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 200,
        'test_name' => 'senha máxima correta',
        'session' => [
          'is_superusuario' => true,
          'password_user' => hash(ALGORITHM_HASH,'Nam quis nulla. Integer malesuada. In in enim a arcu imperdiet malesuada. Sed vel lectus. Donec odio urna, tempus molestie, port'.SALT),
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-0000',
        'contato_cel' => '(18) 90000-0000',
        'senha_su' => 'Nam quis nulla. Integer malesuada. In in enim a arcu imperdiet malesuada. Sed vel lectus. Donec odio urna, tempus molestie, port1',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 400,
        'test_name' => 'senha maior que permitido correta',
        'session' => [
          'is_superusuario' => true,
          'password_user' => hash(ALGORITHM_HASH,'Nam quis nulla. Integer malesuada. In in enim a arcu imperdiet malesuada. Sed vel lectus. Donec odio urna, tempus molestie, port1'.SALT),
          'id_organizacao' => 'prudenco'
        ]
      ],
    ];


    foreach($test_case as $c):

      $this->CI->session->set_userdata('user',$c['session']);

      foreach ($c as $key => $value) {
        $_POST[$key] = $value;
      }

      $this->CI->form_validation->set_data($_POST);

      ob_start();
      $this->func->$method();
      $output = ob_get_contents();
      $var = json_decode($output);
      ob_end_clean();

      if (isset($var->data) && isset($var->data->pessoa_fk))
      {
        $id = $var->data->pessoa_fk;
        $this->CI->pessoa_model->delete($id);
      }

      $this->CI->unit->run($var->code,$c['expected'], $c['test_name'], $output);
      
      $this->CI->form_validation->reset_validation();

    endforeach;

    header("Content-Type: text/html; charset=UTF-8",true);
    echo "<a href=".base_url('test/'.$this->class_name.'/index').">Voltar</a>";
    echo $this->CI->unit->report();
  }

  public function update() {
    $method = 'update';
    $data_inicial = [
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-0000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP', 
        'senha_su' => '',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 200,
        'test_name' => 'insert correto',
        'session' => [
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ];
      $this->CI->session->set_userdata('user',$data_inicial['session']);

      foreach ($data_inicial as $key => $value) {
        $_POST[$key] = $value;
      }

      $this->CI->form_validation->set_data($_POST);

      ob_start();
      $this->func->insert();
      $output = ob_get_contents();
      $var = json_decode($output);
      ob_end_clean();
      $id_inicial = $var->data->pessoa_fk;

      $this->CI->form_validation->reset_validation();


    $test_case = [ 
      //Updates feito por Administrador
      [
        'pessoa_pk' => $id_inicial,
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-0000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP', 
        'senha_su' => '',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 200,
        'test_name' => 'insert correto',
        'session' => [
          'id_user' => 1,
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_pk' => $id_inicial,
        'pessoa_nome' => '',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-0000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP', 
        'senha_su' => '',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 400,
        'test_name' => 'Nome Vazio',
        'session' => [
          'id_user' => 1,
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_pk' => $id_inicial,
        'pessoa_nome' => 'pes',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-0000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP', 
        'senha_su' => '',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 400,
        'test_name' => 'Nome Pequeno',
        'session' => [
          'id_user' => 1,
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_pk' => $id_inicial,
        'pessoa_nome' => 'Nam quis nulla. Integer malesuada. In in enim a arcu imperdiet malesuada. Sed vel lectus. Donec odio urna, tempus molestie, port',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-0000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP', 
        'senha_su' => '',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 200,
        'test_name' => 'Nome Máximo permitido',
        'session' => [
          'id_user' => 1,
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_pk' => $id_inicial,
        'pessoa_nome' => 'Nam quis nulla. Integer malesuada. In in enim a arcu imperdiet malesuada. Sed vel lectus. Donec odio urna, tempus molestie, port1',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-0000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP', 
        'senha_su' => '',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 400,
        'test_name' => 'Nome maior que permetido',
        'session' => [
          'id_user' => 1,
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_pk' => $id_inicial,
        'pessoa_nome' => 'pess',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-0000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP', 
        'senha_su' => '',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 200,
        'test_name' => 'Nome mínimo',
        'session' => [
          'id_user' => 1,
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_pk' => $id_inicial,
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-0000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP', 
        'senha_su' => '',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 400,
        'test_name' => 'CPF vazio',
        'session' => [
          'id_user' => 1,
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_pk' => $id_inicial,
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020044',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-0000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP', 
        'senha_su' => '',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 400,
        'test_name' => 'CPF incorreto',
        'session' => [
          'id_user' => 1,
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_pk' => $id_inicial,
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-4',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-0000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP', 
        'senha_su' => '',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 400,
        'test_name' => 'CPF pequeno',
        'session' => [
          'id_user' => 1,
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_pk' => $id_inicial,
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-444',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-0000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP', 
        'senha_su' => '',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 400,
        'test_name' => 'CPF grande',
        'session' => [
          'id_user' => 1,
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_pk' => $id_inicial,
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '',
        'contato_cel' => '',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP', 
        'senha_su' => '',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 200,
        'test_name' => 'Telefone e Celular vazio',
        'session' => [
          'id_user' => 1,
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_pk' => $id_inicial,
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '',
        'contato_cel' => '',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP', 
        'senha_su' => '',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 200,
        'test_name' => 'Telefone e Celular vazio',
        'session' => [
          'id_user' => 1,
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_pk' => $id_inicial,
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '',
        'contato_cel' => '',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP', 
        'senha_su' => '',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 200,
        'test_name' => 'Telefone e Celular vazio',
        'session' => [
          'id_user' => 1,
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_pk' => $id_inicial,
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-00000',
        'contato_cel' => '(18) 90000-0000',
        'senha_su' => '12345678',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP', 
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 400,
        'test_name' => 'Telefone maior',
        'session' => [
          'id_user' => 1,
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_pk' => $id_inicial,
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-0000',
        'contato_cel' => '(18) 90000-00000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP', 
        'senha_su' => '12345678',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 400,
        'test_name' => 'Celular maior',
        'session' => [
          'id_user' => 1,
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_pk' => $id_inicial,
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-00000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP', 
        'senha_su' => '',
        'funcao_fk' => '',
        'departamento_fk' => '5',
        'expected' => 400,
        'test_name' => 'Função Vazia',
        'session' => [
          'id_user' => 1,
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_pk' => $id_inicial,
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-0000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP', 
        'senha_su' => '',
        'funcao_fk' => '3',
        'departamento_fk' => '5',
        'expected' => 400,
        'test_name' => 'Funcionario de Campo sem setor - incorreto',
        'session' => [
          'id_user' => 1,
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_pk' => $id_inicial,
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-0000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP', 
        'senha_su' => '',
        'funcao_fk' => '3',
        'setor_fk' => '99',
        'departamento_fk' => '5',
        'expected' => 500,
        'test_name' => 'Funcionario de Campo com setor inexistente - incorreto',
        'session' => [
          'id_user' => 1,
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_pk' => $id_inicial,
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-0000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP', 
        'senha_su' => '',
        'funcao_fk' => '3',
        'setor_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 200,
        'test_name' => 'Funcionario de Campo com setor existente - correto',
        'session' => [
          'id_user' => 1,
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_pk' => $id_inicial,
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-0000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP', 
        'senha_su' => '',
        'funcao_fk' => '999',
        'departamento_fk' => '5',
        'expected' => 500,
        'test_name' => 'Função Inexistente',
        'session' => [
          'id_user' => 1,
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_pk' => $id_inicial,
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-00000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP', 
        'senha_su' => '',
        'funcao_fk' => '1',
        'departamento_fk' => '',
        'expected' => 400,
        'test_name' => 'Departamento Vazio',
        'session' => [
          'id_user' => 1,
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_pk' => $id_inicial,
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-00000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP', 
        'senha_su' => '',
        'funcao_fk' => '4',
        'departamento_fk' => '999',
        'expected' => 400,
        'test_name' => 'Departamento Inexistente',
        'session' => [
          'id_user' => 1,
          'is_superusuario' => false,
          'id_organizacao' => 'prudenco'
        ]
      ],
      //Inserts feitos por Superusuário
      [
        'pessoa_pk' => $id_inicial,
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-0000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP', 
        'senha_su' => '12345678',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 200,
        'test_name' => 'insert super correto',
        'session' => [
          'id_user' => 1,
          'is_superusuario' => true,
          'password_user' => hash(ALGORITHM_HASH,'12345678'.SALT),
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_pk' => $id_inicial,
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-0000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP', 
        'senha_su' => '87654321',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 401,
        'test_name' => 'senha super incorreta',
        'session' => [
          'id_user' => 1,
          'is_superusuario' => true,
          'password_user' => hash(ALGORITHM_HASH,'12345678'.SALT),
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_pk' => $id_inicial,
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-0000',
        'contato_cel' => '(18) 90000-0000',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP', 
        'senha_su' => '1234567',
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 400,
        'test_name' => 'senha pequena',
        'session' => [
          'id_user' => 1,
          'is_superusuario' => true,
          'password_user' => hash(ALGORITHM_HASH,'12345678'.SALT),
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_pk' => $id_inicial,
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-0000',
        'contato_cel' => '(18) 90000-0000',
        'senha_su' => 'Nam quis nulla. Integer malesuada. In in enim a arcu imperdiet malesuada. Sed vel lectus. Donec odio urna, tempus molestie, port',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP', 
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 200,
        'test_name' => 'senha máxima correta',
        'session' => [
          'id_user' => 1,
          'is_superusuario' => true,
          'password_user' => hash(ALGORITHM_HASH,'Nam quis nulla. Integer malesuada. In in enim a arcu imperdiet malesuada. Sed vel lectus. Donec odio urna, tempus molestie, port'.SALT),
          'id_organizacao' => 'prudenco'
        ]
      ],
      [
        'pessoa_pk' => $id_inicial,
        'pessoa_nome' => 'pessoa',
        'pessoa_cpf' => '595.249.020-44',
        'contato_email' => 'teste@teste.com',
        'contato_tel' => '(18) 3000-0000',
        'contato_cel' => '(18) 90000-0000',
        'senha_su' => 'Nam quis nulla. Integer malesuada. In in enim a arcu imperdiet malesuada. Sed vel lectus. Donec odio urna, tempus molestie, port1',
        'logradouro_nome' => 'RUA AURORA LISBOA', 
        'local_num' => '96', 
        'local_complemento' => '', 
        'bairro' => 'jardim maracanã', 
        'municipio_pk' => '3541406', 
        'estado_pk' => 'SP', 
        'funcao_fk' => '1',
        'departamento_fk' => '5',
        'expected' => 400,
        'test_name' => 'senha maior que permitido correta',
        'session' => [
          'id_user' => 1,
          'is_superusuario' => true,
          'password_user' => hash(ALGORITHM_HASH,'Nam quis nulla. Integer malesuada. In in enim a arcu imperdiet malesuada. Sed vel lectus. Donec odio urna, tempus molestie, port1'.SALT),
          'id_organizacao' => 'prudenco'
        ]
      ],
    ];


    foreach($test_case as $c):

      $this->CI->session->set_userdata('user',$c['session']);

      foreach ($c as $key => $value) {
        $_POST[$key] = $value;
      }

      $this->CI->form_validation->set_data($_POST);

      ob_start();
      $this->func->$method();
      $output = ob_get_contents();
      $var = json_decode($output);
      ob_end_clean();

      if (isset($var->data) && isset($var->data->pessoa_fk))
      {
        $id = $var->data->pessoa_fk;
      }

      $this->CI->unit->run($var->code,$c['expected'], $c['test_name'], $output);
      
      $this->CI->form_validation->reset_validation();

    endforeach;
    $this->CI->pessoa_model->delete($id_inicial);

    header("Content-Type: text/html; charset=UTF-8",true);
    echo "<a href=".base_url('test/'.$this->class_name.'/index').">Voltar</a>";
    echo $this->CI->unit->report();
  }

}
?>