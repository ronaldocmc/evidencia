

<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class ContactWS_test extends CI_Controller
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
        foreach ($this->class_methods as $method_name) {
            echo "<a href='" . base_url('test/' . $this->class_name . '/' . $method_name) . "'>" . $method_name . "</a><br>";
        }
    }

    public function restore_password()
    {
        $method = __FUNCTION__;

        $this->load->model('tentativa_model');
        $this->tentativa_model->delete(array('tentativa_ip' => $this->input->ip_address()));

        $test_case = [
            [
                'email' => 'inexistente@email.com',
                'test_name' => 'Email inexistente',
                'expected' => '404',
            ],
            [
                'email' => 'pietrobschiavinato@outlook.com',
                'test_name' => 'Email existente',
                'expected' => '200',
            ],
            [
                'email' => 'pietrobschiavinato@outlook.com',
                'test_name' => 'Entrada duplicada',
                'expected' => '503',
            ],
            [
                'email' => 'pietrobschiavinato.outlook.com',
                'test_name' => 'Email invalido',
                'expected' => '400',
            ],
        ];

        foreach ($test_case as $c):

            $var = json_decode($this->send_request($c, $method));

            $this->unit->run($var->code, $c['expected'], $c['test_name'], json_encode($var));
            $this->form_validation->reset_validation();

        endforeach;

        $this->load->model('recuperacao_model');
        $this->recuperacao_model->delete(array('pessoa_fk'=>14));

        header("Content-Type: text/html; charset=UTF-8", true);
        echo "<a href=" . base_url('test/' . $this->class_name . '/index') . ">Inicio</a>";
        echo $this->unit->report();
    }

    private function send_request($array, $method)
    {
        $data_string = json_encode($array);

        $ch = curl_init('http://localhost/evidencia_v2/ContactWS/' . $method);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
        ));
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}
