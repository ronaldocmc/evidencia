

<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class AccessWS_test extends CI_Controller
{
    private $class_methods;
    private $class_name;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('unit_test');
        $this->load->model('super_model');

        // $this->unit->set_test_items(array('test_name', 'result', 'notes'));

        $this->class_methods = get_class_methods($this);
        $this->class_name = get_class($this);

        unset($this->class_methods[0]);
        unset($this->class_methods[count($this->class_methods)]);
    }

    public function index()
    {
        header("Content-Type: text/html; charset=UTF-8", true);
        echo "<b>Importante:</b> Para que os testes funcionem corretamente é necessário que o IP não esteja bloqueado em TENTATIVAS_LOGIN<br>";
        foreach ($this->class_methods as $method_name) {
            echo "<a href='" . base_url('test/' . $this->class_name . '/' . $method_name) . "'>" . $method_name . "</a><br>";
        }
    }

    public function login()
    {
        $method = __FUNCTION__;

        $this->load->model('tentativa_model');
        $this->tentativa_model->delete(array('tentativa_ip' => $this->input->ip_address()));

        $test_case = [
            [
                'login_user' => 'ronaldo@admin',
                'password_user' => '12345678',
                'test_name' => 'Login correto: Ronaldo@admin',
                'expected' => '200',
            ],
            [
                'login_user' => 'inexistente@admin',
                'password_user' => '123456789',
                'test_name' => 'Login inexistente 1',
                'expected' => '404',
            ],
            [
                'login_user' => 'inexistente@admin',
                'password_user' => '123456789',
                'test_name' => 'Login inexistente 2',
                'expected' => '404',
            ],
            [
                'login_user' => 'inexistente@admin',
                'password_user' => '123456789',
                'test_name' => 'Login inexistente 3',
                'expected' => '404',
            ],
            [
                'login_user' => 'inexistente@admin',
                'password_user' => '123456789',
                'test_name' => 'Login inexistente 4',
                'expected' => '403',
            ],
            [
                'login_user' => 'ronaldo@admin',
                'password_user' => '123',
                'test_name' => 'Password inválido',
                'expected' => '400',
            ],
        ];

        foreach ($test_case as $c):

            sleep(1);

            $var = json_decode($this->send_request($c, $method));

            $this->unit->run($var->code, $c['expected'], $c['test_name'], json_encode($var));
            $this->form_validation->reset_validation();

        endforeach;

        header("Content-Type: text/html; charset=UTF-8", true);
        echo "<a href=" . base_url('test/' . $this->class_name . '/index') . ">Inicio</a>";
        echo $this->unit->report();
    }

    public function login_token()
    {
        $method = __FUNCTION__;

        $this->load->model('tentativa_model');
        $this->tentativa_model->delete(array('tentativa_ip' => $this->input->ip_address()));

        $this->load->model('token_model');
        $this->token_model->insert(array("token" => "123","pessoa_fk"=>"2",'timestamp'=>date('Y-m-d H:i:s', strtotime("5 days",strtotime( date('Y-m-d H:i:s'))))));
 

        $test_case = [
            [
                'access_id' => '14',
                'access_token' => 'a1b2c3d4',
                'test_name' => 'Token inválido',
                'expected' => '401',
            ],
            [
                'access_id' => '2',
                'access_token' => "123",
                'test_name' => 'Token válido',
                'expected' => '200',
            ],
            [
                'access_id' => '2',
                'access_token' => "123",
                'test_name' => 'Token utilizado no exemplo anterior (é alterado automaticamente após o login)',
                'expected' => '401',
            ],
        ];
        

        foreach ($test_case as $c):

            sleep(1);

            $headers = array(
                'Content-Type: application/json',
                'access_id: ' . $c['access_id'],
                'access_token: ' . $c['access_token'],
            );

            $var = json_decode($this->send_request($c, $method, $headers));

            $this->unit->run($var->code, $c['expected'], $c['test_name'], json_encode($var));
            $this->form_validation->reset_validation();

        endforeach;

        $this->token_model->delete(2);

        header("Content-Type: text/html; charset=UTF-8", true);
        echo "<a href=" . base_url('test/' . $this->class_name . '/index') . ">Inicio</a>";
        echo $this->unit->report();
    }

    public function quit()
    {
        $method = __FUNCTION__;


        $this->load->model('token_model');
        $this->load->model('tentativa_model');
        $this->token_model->insert(array('token'=>'1234','pessoa_fk'=>'1','timestamp'=>date('Y-m-d H:i:s', strtotime('5 days',strtotime(date('Y-m-d H:i:s'))))));
        $this->token_model->insert(array('token'=>'4321','pessoa_fk'=>'2','timestamp'=>date('Y-m-d H:i:s', strtotime('-5 days',strtotime(date('Y-m-d H:i:s'))))));
        $this->tentativa_model->delete(array('tentativa_ip' => $this->input->ip_address()));

        $test_case = [
            [
                'access_id' => '1',
                'access_token' => '1234',
                'test_name' => 'Dados corretos',
                'expected' => '200',
            ],
            [
                'access_id' => '1',
                'access_token' => '1234',
                'test_name' => 'Dados corretos após o primeiro quit',
                'expected' => '403',
            ],
            [
                'access_id' => '1',
                'access_token' => '1234a1d1sad1as',
                'test_name' => 'Token inexistente',
                'expected' => '403',
            ],
            [
                'access_id' => '2',
                'access_token' => '4321',
                'test_name' => 'Token vencido',
                'expected' => '9999',
            ],
            
        ];

        foreach ($test_case as $c):

            sleep(1);

            $headers = array(
                'Content-Type: application/json',
                'access_id: ' . $c['access_id'],
                'access_token: ' . $c['access_token'],
            );

            $var = json_decode($this->send_request($c, $method, $headers));

            $this->unit->run($var->code, $c['expected'], $c['test_name'], json_encode($var));
            $this->form_validation->reset_validation();

        endforeach;

        $this->token_model->delete(2);

        header("Content-Type: text/html; charset=UTF-8", true);
        echo "<a href=" . base_url('test/' . $this->class_name . '/index') . ">Inicio</a>";
        echo $this->unit->report();
    }

    private function send_request($array, $method, $headers = null)
    {
        $data_string = json_encode($array);

        $ch = curl_init('http://localhost/evidencia_v2/AccessWS/' . $method);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if($headers != NULL){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_HEADER, false);
        }

        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}
