<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once dirname(__FILE__) . "/Response.php";

date_default_timezone_set('America/Sao_Paulo');

require_once APPPATH . "core/CRUD_Controller.php";

class Funcionario extends CRUD_Controller
{

    public $CI;
    public $response;
    public $pessoa;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('funcionario_model');
        $this->load->model('funcao_model');
        $this->load->model('departamento_model');
        $this->load->model('setor_model');

        $this->load->library('form_validation');
        $this->load->library('upload');
        $this->load->helper('insert_images');

        $this->load->helper('exception');

        $this->response = new Response();
    }

    public function index()
    {
        $funcionarios = $this->funcionario_model->get(
            "funcionarios.funcionario_pk, funcionarios.organizacao_fk, funcionarios.ativo, funcionarios.funcionario_login, funcionarios.funcionario_nome, funcionarios.funcionario_caminho_foto, funcionarios.funcionario_cpf,
            funcionarios.funcao_fk, funcoes.funcao_nome, funcoes.funcao_pk, organizacoes.organizacao_pk ",
            // "*",
            [
                "organizacao_fk" => $this->session->user['id_organizacao'],
            ]
        );

        $func_sets = $this->funcionario_model->get_setores([
            'funcionarios.organizacao_fk' => $this->session->user['id_organizacao'],
        ]);

        // var_dump($func_sets);die();

        if ($funcionarios == false) {
            $funcionarios = [];
        }

        foreach ($funcionarios as $func) {
            $setor_aux = [];
            foreach ($func_sets as $fc) {
                if ($fc->funcionario_fk == $func->funcionario_pk) {
                    $setor_aux[] = $fc->setor_fk;
                }
            }
            if (!empty($setor_aux)) {
                $func->setor_fk = $setor_aux;
            }
        }

        $funcoes = $this->funcao_model->get_all(
            '*',
            null,
            -1,
            -1
        );

        $departamentos = $this->departamento_model->get_all(
            '*',
            null,
            -1,
            -1
        );

        $setores = $this->setor_model->get_all(
            '*',
            null,
            -1,
            -1
        );

        // var_dump($setores);die();

        // Passa as funções para o formato necessário no form_dropdown do form_helper (nativo do CI)
        foreach ($funcoes as $key => $f) {
            $funcoes_drop[$f->funcao_pk] = $f->funcao_nome;
        }

        // echo "<pre>";var_dump($funcoes);die();

        $this->session->set_flashdata('css', array(
            0 => base_url('assets/vendor/cropper/cropper.css'),
            1 => base_url('assets/vendor/input-image/input-image.css'),
            2 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.css'),
            3 => base_url('assets/css/modal_desativar.css'),
            4 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css'),
            5 => base_url('assets/css/user_guide.css'),
        ));

        $this->session->set_flashdata('scripts', array(
            0 => base_url('assets/vendor/masks/jquery.mask.min.js'),
            1 => base_url('assets/vendor/bootstrap-multistep-form/jquery.easing.min.js'),
            2 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.js'),
            3 => base_url('assets/vendor/cropper/cropper.js'),
            4 => base_url('assets/vendor/input-image/input-image.js'),
            5 => base_url('assets/vendor/datatables/datatables.min.js'),
            6 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js'),
            7 => base_url('assets/js/masks.js'),
            8 => base_url('assets/js/utils.js'),
            9 => base_url('assets/js/constants.js'),
            10 => base_url('assets/js/jquery.noty.packaged.min.js'),
            11 => base_url('assets/js/dashboard/funcionario/index.js'),
            12 => base_url('assets/js/localizacao.js'),
            13 => base_url('assets/vendor/select-input/select-input.js'),
        ));

        $this->load->helper('form');

        load_view([
            0 => [
                'src' => 'dashboard/administrador/funcionario/home',
                'params' => [
                    'departamentos' => $departamentos,
                    'funcionarios' => $funcionarios,
                    'funcoes' => $funcoes_drop,
                    'setores' => $setores,
                ],
            ],
            1 => [
                'src' => 'access/pre_loader',
                'params' => null,
            ],
        ], 'administrador');
    }

    public function save()
    {
        try
        {
            $this->funcionario_model->config_form_validation();

            $organizacao_pk = $this->session->user['id_organizacao'];

            $_POST['organizacao_fk'] = $organizacao_pk;

            if ($this->is_superuser()) {
                $this->add_password_to_form_validation();
            }

            $this->funcionario_model->fill();
            $this->funcionario_model->__set("organizacao_fk", $organizacao_pk);

            $this->funcionario_model->run_form_validation();

            $this->begin_transaction();

            if (isset($_POST['funcionario_pk'])) {
                $this->funcionario_model->__set("funcionario_pk", $_POST['funcionario_pk']);

                $path = upload_img(
                    [
                        'id' => $_POST['funcionario_pk'],
                        'path' => 'PATH_FUNC',
                        'is_os' => false,
                    ],
                    [0 => $this->input->post('img')]
                );

                if($path != null){
                    $this->funcionario_model->__set("funcionario_caminho_foto", $path[0]);
                    $this->response->add_data("path", $path[0]);
                }
                $this->funcionario_model->update_funcionario($_POST['funcionario_pk'], $_POST['setor_fk']);

                
            } else {

                $this->funcionario_model->__set("funcionario_senha", hash(ALGORITHM_HASH, $_POST['funcionario_senha'] . SALT));

                $id = $this->funcionario_model->insert_funcionario($_POST['setor_fk']);

                $path = upload_img(
                    [
                        'id' => $id,
                        'path' => 'PATH_FUNC',
                        'is_os' => false,
                    ],
                    [0 => $this->input->post('img')]
                );

                if ($path != null) {
                    $this->funcionario_model->update_image($path[0], $id);
                    $this->response->add_data("path", $path[0]);
                }
            }

            $this->end_transaction();

            $this->response->set_code(Response::SUCCESS);
            $this->response->send();

        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        }
    }

    public function deactivate()
    {
        try {
            $this->funcionario_model->__set("funcionario_pk", $_POST['funcionario_pk']);

            $this->funcionario_model->deactivate();

            $this->response->set_code(Response::SUCCESS);
            $this->response->set_message('Funcionario desativado com sucesso!');
            $this->response->send();

        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        }
    }

    public function activate()
    {
        try {
            $this->funcionario_model->__set("funcionario_pk", $_POST['funcionario_pk']);

            $this->funcionario_model->activate();

            $this->response->set_code(Response::SUCCESS);
            $this->response->set_message('Funcionario ativado com sucesso!');
            $this->response->send();

        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        }
    }

    public function new_email($token, $email_user)
    {
        $this->load->library('send_email');
        $url = base_url() . 'Contact/reset_password/' . $token . '/define';

        return $this->send_email->send_email('email/create_password.php', 'Definir Senha de Acesso', $url, $email_user);
    }

    public function change_password()
    {
        try {
            $password = hash(ALGORITHM_HASH, $_POST['funcionario_senha'] . SALT);

            $this->funcionario_model->__set("funcionario_pk", $_POST['funcionario_pk']);
            $this->funcionario_model->__set("funcionario_senha", $password);

            $this->funcionario_model->update();

            $this->response->set_code(Response::SUCCESS);
            $this->response->set_message('A senha foi alterada!');
            $this->response->send();
        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        }

    }

}
