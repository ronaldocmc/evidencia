<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once dirname(__FILE__) . "\..\Superusuario.php";

class Superusuario_test extends CI_Controller
{
    private $superusuario;
    private $CI;
    private $class_methods;
    private $class_name;

    public function __construct()
    {
        $this->superusuario = new Superusuario();
        $this->CI = &get_instance();
        $this->CI->load->library('form_validation');
        $this->CI->load->library('unit_test');
        $this->CI->load->model('super_model');

        $this->CI->unit->set_test_items(array('test_name', 'result', 'notes'));

        $this->class_methods = get_class_methods($this);
        $this->class_name = get_class($this);

        unset($this->class_methods[0]);
        unset($this->class_methods[count($this->class_methods)]);
    }

    public function index()
    {
        header("Content-Type: text/html; charset=UTF-8", true);
        foreach ($this->class_methods as $method_name) {
            echo "<a href='" . base_url('test/' . $this->class_name . '/' . $method_name) . "'>" . $method_name . "</a><br>";
        }
    }

    public function insert()
    {
        $method = __FUNCTION__;

        $test_case = [
            [
                'pessoa_nome' => 'Pessoa Insert' . rand(0, 10000),
                'pessoa_cpf' => '892.367.230-04',
                'contato_email' => 'pietro_cheetos@hotmail.com',
                'contato_tel' => '',
                'contato_cel' => '',
                'senha_su' => '12345678',
                'expected' => 200,
                'test_name' => 'Dados corretos',
                'session' => [
                    'is_superusuario' => true,
                    'password_user' => hash(ALGORITHM_HASH, '12345678' . SALT),
                    'id_organizacao' => 'prudenco',
                ],
            ],
            [
                'pessoa_nome' => 'Pessoa Insert ' . rand(0, 10000),
                'pessoa_cpf' => '163.168.614-35',
                'contato_email' => 'pietro_cheetos@hotmail.com',
                'contato_tel' => '',
                'contato_cel' => '',
                'senha_su' => '12345678',
                'expected' => 401,
                'test_name' => 'Senha inválida',
                'session' => [
                    'is_superusuario' => true,
                    'password_user' => hash(ALGORITHM_HASH, 'wrong_password' . SALT),
                    'id_organizacao' => 'prudenco',
                ],
            ],
            [
                'pessoa_nome' => 'Pessoa Insert ' . rand(0, 10000),
                'pessoa_cpf' => '111.111.111-11',
                'contato_email' => 'pietro_cheetos@hotmail.com',
                'contato_tel' => '',
                'contato_cel' => '',
                'senha_su' => '12345678',
                'expected' => 400,
                'test_name' => 'Cpf inválido',
                'session' => [
                    'is_superusuario' => true,
                    'password_user' => hash(ALGORITHM_HASH, '12345678' . SALT),
                    'id_organizacao' => 'prudenco',
                ],
            ],
            [
                'pessoa_nome' => 'Pessoa Insert ' . rand(0, 10000),
                'pessoa_cpf' => '111-aaa.123-12',
                'contato_email' => 'pietro_cheetos@hotmail.com',
                'contato_tel' => '',
                'contato_cel' => '',
                'senha_su' => '12345678',
                'expected' => 400,
                'test_name' => 'Cpf fora do padrão',
                'session' => [
                    'is_superusuario' => true,
                    'password_user' => hash(ALGORITHM_HASH, '12345678' . SALT),
                    'id_organizacao' => 'prudenco',
                ],
            ],
            [
                'pessoa_nome' => null,
                'pessoa_cpf' => '009.509.470-91',
                'contato_email' => 'pietro_cheetos@hotmail.com',
                'contato_tel' => '',
                'contato_cel' => '',
                'senha_su' => '12345678',
                'expected' => 400,
                'test_name' => 'Nome inválido',
                'session' => [
                    'is_superusuario' => true,
                    'password_user' => hash(ALGORITHM_HASH, '12345678' . SALT),
                    'id_organizacao' => 'prudenco',
                ],
            ],
            [
                'pessoa_nome' => 'Pessoa Insert ' . rand(0, 10000),
                'pessoa_cpf' => '009.509.470-91',
                'contato_email' => 'wrong_email',
                'contato_tel' => '',
                'contato_cel' => '',
                'senha_su' => '12345678',
                'expected' => 400,
                'test_name' => 'Email inválido',
                'session' => [
                    'is_superusuario' => true,
                    'password_user' => hash(ALGORITHM_HASH, '12345678' . SALT),
                    'id_organizacao' => 'prudenco',
                ],
            ],
        ];

        foreach ($test_case as $c):

            $this->CI->session->set_userdata('user', $c['session']);

            $_POST['pessoa_nome'] = $c['pessoa_nome'];
            $_POST['pessoa_cpf'] = $c['pessoa_cpf'];
            $_POST['contato_email'] = $c['contato_email'];
            $_POST['contato_tel'] = $c['contato_tel'];
            $_POST['contato_cel'] = $c['contato_cel'];
            $_POST['senha_su'] = $c['senha_su'];

            $this->CI->form_validation->set_data($_POST);

            ob_start();
            $this->superusuario->$method();
            $output = ob_get_contents();
            $var = json_decode($output);
            ob_end_clean();

            if (isset($var->data->pessoa_fk)) {
                $pessoa_fk = $var->data->pessoa_fk;
                $this->CI->super_model->delete($pessoa_fk);
            }

            $this->CI->unit->run($var->code, $c['expected'], $c['test_name'], $output);

            $this->CI->form_validation->reset_validation();

        endforeach;

        header("Content-Type: text/html; charset=UTF-8", true);
        echo "<a href=" . base_url('test/' . $this->class_name . '/index') . ">Inicio</a>";
        echo $this->CI->unit->report();
    }

    public function update()
    {
        $id = 14;
        $method = __FUNCTION__;

        $test_case = [
            [
                'pessoa_pk' => $id,
                'pessoa_nome' => 'Pessoa Update' . rand(0, 10000),
                'pessoa_cpf' => '163.168.614-35',
                'contato_email' => 'pietro_cheetos@hotmail.com',
                'contato_tel' => '123456789',
                'contato_cel' => '123456789',
                'senha_su' => '12345678',
                'expected' => 200,
                'test_name' => 'Dados corretos',
                'session' => [
                    'is_superusuario' => true,
                    'password_user' => hash(ALGORITHM_HASH, '12345678' . SALT),
                    'id_organizacao' => 'prudenco',
                    'image_user_min' => 'foo',
                    'image_user' => 'foo',
                    'id_user' => 'foo',
                    'name_organizacao' => 'foo',
                ],
            ],
            [
                'pessoa_pk' => $id,
                'pessoa_nome' => 'Pessoa Insert ' . rand(0, 10000),
                'pessoa_cpf' => '163.168.614-35',
                'contato_email' => 'pietro_cheetos@hotmail.com',
                'contato_tel' => '',
                'contato_cel' => '',
                'senha_su' => '12345678',
                'expected' => 401,
                'test_name' => 'Senha inválida',
                'session' => [
                    'is_superusuario' => true,
                    'password_user' => hash(ALGORITHM_HASH, 'wrong_password' . SALT),
                    'id_organizacao' => 'prudenco',
                    'image_user_min' => 'foo',
                    'image_user' => 'foo',
                    'name_organizacao' => 'foo',
                ],
            ],
            [
                'pessoa_pk' => $id,
                'pessoa_nome' => 'Pessoa Insert ' . rand(0, 10000),
                'pessoa_cpf' => '111.111.111-11',
                'contato_email' => 'pietro_cheetos@hotmail.com',
                'contato_tel' => '',
                'contato_cel' => '',
                'senha_su' => '12345678',
                'expected' => 400,
                'test_name' => 'Cpf inválido',
                'session' => [
                    'is_superusuario' => true,
                    'password_user' => hash(ALGORITHM_HASH, '12345678' . SALT),
                    'id_organizacao' => 'prudenco',
                    'image_user_min' => 'foo',
                    'image_user' => 'foo',
                    'name_organizacao' => 'foo',
                ],
            ],
            [
                'pessoa_pk' => $id,
                'pessoa_nome' => null,
                'pessoa_cpf' => '009.509.470-91',
                'contato_email' => 'pietro_cheetos@hotmail.com',
                'contato_tel' => '',
                'contato_cel' => '',
                'senha_su' => '12345678',
                'expected' => 400,
                'test_name' => 'Nome inválido',
                'session' => [
                    'is_superusuario' => true,
                    'password_user' => hash(ALGORITHM_HASH, '12345678' . SALT),
                    'id_organizacao' => 'prudenco',
                    'image_user_min' => 'foo',
                    'image_user' => 'foo',
                    'name_organizacao' => 'foo',
                ],
            ],
            [
                'pessoa_pk' => $id,
                'pessoa_nome' => 'Pessoa Insert ' . rand(0, 10000),
                'pessoa_cpf' => '009.509.470-91',
                'contato_email' => 'wrong_email',
                'contato_tel' => '',
                'contato_cel' => '',
                'senha_su' => '12345678',
                'expected' => 400,
                'test_name' => 'Email inválido',
                'session' => [
                    'is_superusuario' => true,
                    'password_user' => hash(ALGORITHM_HASH, '12345678' . SALT),
                    'id_organizacao' => 'prudenco',
                    'image_user_min' => 'foo',
                    'image_user' => 'foo',
                    'name_organizacao' => 'foo',
                ],
            ],
        ];

        foreach ($test_case as $c):

            $this->CI->session->set_userdata('user', $c['session']);

            $_POST['pessoa_pk'] = $c['pessoa_pk'];
            $_POST['pessoa_nome'] = $c['pessoa_nome'];
            $_POST['pessoa_cpf'] = $c['pessoa_cpf'];
            $_POST['contato_email'] = $c['contato_email'];
            $_POST['contato_tel'] = $c['contato_tel'];
            $_POST['contato_cel'] = $c['contato_cel'];
            $_POST['senha_su'] = $c['senha_su'];

            $this->CI->form_validation->set_data($_POST);

            ob_start();
            $this->superusuario->$method();
            $output = ob_get_contents();
            $var = json_decode($output);
            ob_end_clean();

            if (isset($var->data->pessoa_fk)) {
                $pessoa_fk = $var->data->pessoa_fk;
                $this->CI->super_model->delete($pessoa_fk);
            }

            $this->CI->unit->run($var->code, $c['expected'], $c['test_name'], $output);
            $this->CI->form_validation->reset_validation();

        endforeach;
        header("Content-Type: text/html; charset=UTF-8", true);
        echo "<a href=" . base_url('test/' . $this->class_name . '/index') . ">Inicio</a>";
        echo $this->CI->unit->report();
    }

    public function update_password()
    {
        $id = 14;
        $method = __FUNCTION__;

        $test_case = [
            [
                'old_password' => '12345678',
                'new_password' => '123456789',
                'confirm_new_password' => '123456789',
                'expected' => 200,
                'test_name' => 'Dados corretos',
                'session' => [
                    'is_superusuario' => true,
                    'password_user' => hash(ALGORITHM_HASH, '12345678' . SALT),
                    'id_organizacao' => 'prudenco',
                    'image_user_min' => 'foo',
                    'image_user' => 'foo',
                    'id_user' => $id,
                    'name_organizacao' => 'foo',
                ],
            ],
            [
                'old_password' => 'wrong_password',
                'new_password' => '123456789',
                'confirm_new_password' => '123456789',
                'expected' => 401,
                'test_name' => 'Password inválido',
                'session' => [
                    'is_superusuario' => true,
                    'password_user' => hash(ALGORITHM_HASH, '12345678' . SALT),
                    'id_organizacao' => 'prudenco',
                    'image_user_min' => 'foo',
                    'image_user' => 'foo',
                    'id_user' => $id,
                    'name_organizacao' => 'foo',
                ],
            ],
            [
                'old_password' => 'wrong_password',
                'new_password' => '1234',
                'confirm_new_password' => '1234',
                'expected' => 400,
                'test_name' => 'Nova senha inválida',
                'session' => [
                    'is_superusuario' => true,
                    'password_user' => hash(ALGORITHM_HASH, '12345678' . SALT),
                    'id_organizacao' => 'prudenco',
                    'image_user_min' => 'foo',
                    'image_user' => 'foo',
                    'id_user' => $id,
                    'name_organizacao' => 'foo',
                ],
            ],
            [
                'old_password' => '12345678',
                'new_password' => '12345678',
                'confirm_new_password' => '12345678',
                'expected' => 200,
                'test_name' => 'Nada mudou',
                'session' => [
                    'is_superusuario' => true,
                    'password_user' => hash(ALGORITHM_HASH, '12345678' . SALT),
                    'id_organizacao' => 'prudenco',
                    'image_user_min' => 'foo',
                    'image_user' => 'foo',
                    'id_user' => $id,
                    'name_organizacao' => 'foo',
                ],
            ],
        ];

        foreach ($test_case as $c):

            $this->CI->session->set_userdata('user', $c['session']);

            $_POST['old_password'] = $c['old_password'];
            $_POST['new_password'] = $c['new_password'];
            $_POST['confirm_new_password'] = $c['confirm_new_password'];

            $this->CI->form_validation->set_data($_POST);

            ob_start();
            $this->superusuario->$method();
            $output = ob_get_contents();
            $var = json_decode($output);
            ob_end_clean();

            $this->CI->unit->run($var->code, $c['expected'], $c['test_name'], $output);
            $this->CI->form_validation->reset_validation();

        endforeach;
        header("Content-Type: text/html; charset=UTF-8", true);
        echo "<a href=" . base_url('test/' . $this->class_name . '/index') . ">Inicio</a>";
        echo $this->CI->unit->report();
    }

    public function deactivate()
    {
        $id = 14;
        $method = __FUNCTION__;

        $test_case = [
            [
                'pessoa_pk' => $id,
                'senha_su' => '123456789',
                'expected' => 200,
                'test_name' => 'Dados corretos',
                'session' => [
                    'is_superusuario' => true,
                    'password_user' => hash(ALGORITHM_HASH, '123456789' . SALT),
                    'id_organizacao' => 'prudenco',
                    'id_user' => $id,
                ],
            ],
            [
                'pessoa_pk' => -1,
                'senha_su' => '123456789',
                'expected' => 500,
                'test_name' => 'Usuário inexistente',
                'session' => [
                    'is_superusuario' => true,
                    'password_user' => hash(ALGORITHM_HASH, '123456789' . SALT),
                    'id_organizacao' => 'prudenco',
                    'id_user' => $id,
                ],
            ],
        ];

        foreach ($test_case as $c):

            $this->CI->session->set_userdata('user', $c['session']);

            $_POST['pessoa_pk'] = $c['pessoa_pk'];
            $_POST['senha_su'] = $c['senha_su'];

            $this->CI->form_validation->set_data($_POST);

            ob_start();
            $this->superusuario->$method();
            $output = ob_get_contents();
            $var = json_decode($output);
            ob_end_clean();


            $this->CI->unit->run($var->code, $c['expected'], $c['test_name'], $output);
            $this->CI->form_validation->reset_validation();

        endforeach;
        header("Content-Type: text/html; charset=UTF-8", true);
        echo "<a href=" . base_url('test/' . $this->class_name . '/index') . ">Inicio</a>";
        echo $this->CI->unit->report();
    }

    public function activate()
    {
        $id = 14;
        $method = __FUNCTION__;

        $test_case = [
            [
                'pessoa_pk' => $id,
                'senha_su' => '123456789',
                'expected' => 200,
                'test_name' => 'Dados corretos',
                'session' => [
                    'is_superusuario' => true,
                    'password_user' => hash(ALGORITHM_HASH, '123456789' . SALT),
                    'id_organizacao' => 'prudenco',
                    'id_user' => $id,
                ],
            ],
            [
                'pessoa_pk' => -1,
                'senha_su' => '123456789',
                'expected' => 500,
                'test_name' => 'Usuário inexistente',
                'session' => [
                    'is_superusuario' => true,
                    'password_user' => hash(ALGORITHM_HASH, '123456789' . SALT),
                    'id_organizacao' => 'prudenco',
                    'id_user' => $id,
                ],
            ],
            [
                'pessoa_pk' => $id,
                'senha_su' => '123456789',
                'expected' => 401,
                'test_name' => 'Senha incorreta',
                'session' => [
                    'is_superusuario' => true,
                    'password_user' => hash(ALGORITHM_HASH, 'wrong_password' . SALT),
                    'id_organizacao' => 'prudenco',
                    'id_user' => $id,
                ],
            ],
        ];

        foreach ($test_case as $c):

            $this->CI->session->set_userdata('user', $c['session']);

            $_POST['pessoa_pk'] = $c['pessoa_pk'];
            $_POST['senha_su'] = $c['senha_su'];

            $this->CI->form_validation->set_data($_POST);

            ob_start();
            $this->superusuario->$method();
            $output = ob_get_contents();
            $var = json_decode($output);
            ob_end_clean();


            $this->CI->unit->run($var->code, $c['expected'], $c['test_name'], $output);
            $this->CI->form_validation->reset_validation();

        endforeach;
        header("Content-Type: text/html; charset=UTF-8", true);
        echo "<a href=" . base_url('test/' . $this->class_name . '/index') . ">Inicio</a>";
        echo $this->CI->unit->report();
    }

    // public function create_access(){
    //     $this->CI->load->model('recuperacao_model', 'rmodel');
    //     $data_rmodel = array(
    //         'pessoa_fk' => $id,
    //         'recuperacao_token' => 'abc' 
    //     );
    //     $this->rmodel->insert()
    // }

}
