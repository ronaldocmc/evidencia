<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once dirname(__FILE__) . "\..\Response.php";

class Response_test extends CI_Controller
{
    private $response;
    private $CI;
    private $class_methods;
    private $class_name;

    public function __construct()
    {
        parent::__construct();
        $this->response = new Response();

        $this->load->library('unit_test');

        $this->unit->set_test_items(array('test_name', 'result', 'notes'));

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

    public function set_code()
    {
        $method = __FUNCTION__;

        $test_case = [
            [
                'code' => Response::SUCCESS,
                'expected' => 200,
                'test_name' => "Response SUCCESS"
            ],
            [
                'code' => Response::BAD_REQUEST,
                    'expected' => 400,
                    'test_name' => "Response BAD_REQUEST"
            ],
            [
                'code' => Response::UNAUTHORIZED,
                    'expected' => 401,
                    'test_name' => "Response UNAUTHORIZED"
            ],
            [
                'code' => Response::FORBIDDEN,
                    'expected' => 403,
                    'test_name' => "Response FORBIDDEN"
            ],
            [
                'code' => Response::NOT_FOUND,
                    'expected' => 404,
                    'test_name' => "Response NOT_FOUND"
            ],
            [
                'code' => Response::INVALID_METHOD,
                    'expected' => 405,
                    'test_name' => "Response INVALID_METHOD"
            ],
            [
                'code' => Response::SERVER_FAIL,
                    'expected' => 500,
                    'test_name' => "Response SERVER_FAIL"
            ],
            [
                'code' => Response::TOKEN_TIMEOUT,
                    'expected' => 9999,
                    'test_name' => "Response TOKEN_TIMEOUT"
            ],
            [
                'code' => Response::LOGOUT_ERROR,
                    'expected' => 9998,
                    'test_name' => "Response LOGOUT_ERROR"
            ],
            [
                'code' => Response::DB_DUPLICATE_ENTRY,
                    'expected' => 1062,
                    'test_name' => "Response DB_DUPLICATE_ENTRY"
            ],
            [
                'code' => Response::DB_ERROR_INSERT,
                    'expected' => 503,
                    'test_name' => "Response DB_ERROR_INSERT"
            ],
            [
                'code' => Response::DB_ERROR_UPDATE,
                    'expected' => 501,
                    'test_name' => "Response DB_ERROR_UPDATE"
            ],
            [
                'code' => Response::DB_ERROR_GET,
                    'expected' => 502,
                    'test_name' => "Response DB_ERROR_GET"
            ],
            
        ];

        foreach ($test_case as $c):

            $this->response->$method($c['code']);

            $this->unit->run($this->response->__get('code'), $c['expected'], $c['test_name']);

        endforeach;

        header("Content-Type: text/html; charset=UTF-8", true);
        echo "<a href=" . base_url('test/' . $this->class_name . '/index') . ">Inicio</a>";
        echo $this->unit->report();
    }

    public function send(){
        $method = __FUNCTION__;

        $test_case = [
            [
                'code' => Response::SUCCESS,
                'expected' => 200,
                'data' => [
                    'test' => '123',
                ],
                'test_name' => "Response SUCCESS"
            ],
            [
                'code' => Response::BAD_REQUEST,
                    'expected' => 400,
                    'data' => [
                        'test' => '123',
                    ],
                    'test_name' => "Response BAD_REQUEST"
            ],
            [
                'code' => Response::UNAUTHORIZED,
                    'expected' => 401,
                    'data' => [
                        'test' => '123',
                    ],
                    'test_name' => "Response UNAUTHORIZED"
            ],
            [
                'code' => Response::FORBIDDEN,
                    'expected' => 403,
                    'data' => [
                        'test' => '123',
                    ],
                    'test_name' => "Response FORBIDDEN"
            ],
            [
                'code' => Response::NOT_FOUND,
                    'expected' => 404,
                    'data' => [
                        'test' => '123',
                    ],
                    'test_name' => "Response NOT_FOUND"
            ],
            [
                'code' => Response::INVALID_METHOD,
                    'expected' => 405,
                    'data' => [
                        'test' => '123',
                    ],
                    'test_name' => "Response INVALID_METHOD"
            ],
            [
                'code' => Response::SERVER_FAIL,
                    'expected' => 500,
                    'data' => [
                        'test' => '123',
                    ],
                    'test_name' => "Response SERVER_FAIL"
            ],
            [
                'code' => Response::TOKEN_TIMEOUT,
                    'expected' => 9999,
                    'data' => [
                        'test' => '123',
                    ],
                    'test_name' => "Response TOKEN_TIMEOUT"
            ],
            [
                'code' => Response::LOGOUT_ERROR,
                    'expected' => 9998,
                    'data' => [
                        'test' => '123',
                    ],
                    'test_name' => "Response LOGOUT_ERROR"
            ],
            [
                'code' => Response::DB_DUPLICATE_ENTRY,
                    'expected' => 1062,
                    'data' => [
                        'test' => '123',
                    ],
                    'test_name' => "Response DB_DUPLICATE_ENTRY"
            ],
            [
                'code' => Response::DB_ERROR_INSERT,
                    'expected' => 503,
                    'data' => [
                        'test' => '123',
                    ],
                    'test_name' => "Response DB_ERROR_INSERT"
            ],
            [
                'code' => Response::DB_ERROR_UPDATE,
                    'expected' => 501,
                    'data' => [
                        'test' => '123',
                    ],
                    'test_name' => "Response DB_ERROR_UPDATE"
            ],
            [
                'code' => Response::DB_ERROR_GET,
                    'expected' => 502,
                    'data' => [
                        'test' => '123',
                    ],
                    'test_name' => "Response DB_ERROR_GET"
            ],
            
        ];


        foreach ($test_case as $c):

            $this->response->set_code($c['code']);
            $this->response->set_data($c['data']);


            ob_start();
            $this->response->$method();
            $output = ob_get_contents();
            $var = json_decode($output);
            ob_end_clean();

            $this->unit->run($var->code, $c['expected'], $c['test_name'], $output);

        endforeach;
        header("Content-Type: text/html; charset=UTF-8", true);
        echo "<a href=" . base_url('test/' . $this->class_name . '/index') . ">Inicio</a>";
        echo $this->unit->report();
    }

    public function is_success()
    {
        $method = __FUNCTION__;

        $test_case = [
            [
                'code' => Response::SUCCESS,
                'expected' => TRUE,
                'test_name' => "Response SUCCESS"
            ],
            [
                'code' => Response::BAD_REQUEST,
                    'expected' => FALSE,
                    'test_name' => "Response BAD_REQUEST"
            ],
            [
                'code' => Response::UNAUTHORIZED,
                    'expected' => FALSE,
                    'test_name' => "Response UNAUTHORIZED"
            ],
            [
                'code' => Response::FORBIDDEN,
                    'expected' => FALSE,
                    'test_name' => "Response FORBIDDEN"
            ],
            [
                'code' => Response::NOT_FOUND,
                    'expected' => FALSE,
                    'test_name' => "Response NOT_FOUND"
            ],
            [
                'code' => Response::INVALID_METHOD,
                    'expected' => FALSE,
                    'test_name' => "Response INVALID_METHOD"
            ],
            [
                'code' => Response::SERVER_FAIL,
                    'expected' => FALSE,
                    'test_name' => "Response SERVER_FAIL"
            ],
            [
                'code' => Response::TOKEN_TIMEOUT,
                    'expected' => FALSE,
                    'test_name' => "Response TOKEN_TIMEOUT"
            ],
            [
                'code' => Response::LOGOUT_ERROR,
                    'expected' => FALSE,
                    'test_name' => "Response LOGOUT_ERROR"
            ],
            [
                'code' => Response::DB_DUPLICATE_ENTRY,
                    'expected' => FALSE,
                    'test_name' => "Response DB_DUPLICATE_ENTRY"
            ],
            [
                'code' => Response::DB_ERROR_INSERT,
                    'expected' => FALSE,
                    'test_name' => "Response DB_ERROR_INSERT"
            ],
            [
                'code' => Response::DB_ERROR_UPDATE,
                    'expected' => FALSE,
                    'test_name' => "Response DB_ERROR_UPDATE"
            ],
            [
                'code' => Response::DB_ERROR_GET,
                    'expected' => FALSE,
                    'test_name' => "Response DB_ERROR_GET"
            ],
            
        ];

        foreach ($test_case as $c):

            $this->response->set_code($c['code']);

            $this->unit->run($this->response->is_success(), $c['expected'], $c['test_name']);

        endforeach;

        header("Content-Type: text/html; charset=UTF-8", true);
        echo "<a href=" . base_url('test/' . $this->class_name . '/index') . ">Inicio</a>";
        echo $this->unit->report();
    }




  
}
