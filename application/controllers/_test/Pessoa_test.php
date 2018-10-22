<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__) . "\..\Pessoa.php";

class Pessoa_test extends CI_Controller {
	private $pes;

	function __construct() {
		$this->pes =  new Pessoa();
		$this->CI =& get_instance();

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

	 	
		$this->CI->load->library('form_validation');
		$this->CI->load->library('unit_test');
		$this->CI->load->model('pessoa_model');


		$method = 'insert';
		$test_case = [
			[
				'pessoa_nome' => 'teste',
				'pessoa_cpf' => '447.667.578-66',
				'contato_cel' => '(18)99999999',
				'contato_email' => 'teste@teste.com',
				'contato_tel' => '(18)999999999',
				'expected' => 200,
				'test_name' => 'Insert Correto',
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
		        'expected' => 400,
		        'test_name' => 'Celular maior',
		        'session' => [
		          'is_superusuario' => false,
		          'id_organizacao' => 'prudenco'
		        ]
		      ],	
		];

		
		
    foreach($test_case as $c):

      $this->CI->session->set_userdata('user',$c['session']);

      $_POST['pessoa_nome'] = $c['pessoa_nome'];
      $_POST['pessoa_cpf'] = $c['pessoa_cpf'];
      $_POST['contato_email'] = $c['contato_email'];
      $_POST['contato_tel'] = $c['contato_tel'];
      $_POST['contato_cel'] = $c['contato_cel'];

      $this->CI->form_validation->set_data($_POST);

      ob_start();
      $this->pes->$method();
      $output = ob_get_contents();
      $var = json_decode($output);
      ob_end_clean();
      if (isset($var->data) && isset($var->data->id))
      {
        $id = $var->data->id;
        $this->CI->pessoa_model->delete($id);
      }

      $this->CI->unit->run($var->code,$c['expected'], $c['test_name'], $output);
      
      $this->CI->form_validation->reset_validation();

    endforeach;

    header("Content-Type: text/html; charset=UTF-8",true);
    echo "<a href=".base_url('test/'.$this->class_name.'/index').">Voltar</a>";
    echo $this->CI->unit->report();

	}
}
?>