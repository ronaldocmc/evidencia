<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH.'core/Response.php';

date_default_timezone_set('America/Sao_Paulo');

require_once APPPATH.'core/CRUD_Controller.php';

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
        $this->load->helper('images');

        $this->load->helper('exception');

        $this->response = new Response();
    }

    private function check_old_password()
    {
        $this->add_password_to_form_validation();
        $this->funcionario_model->run_form_validation();
        $old_password = $this->input->post('senha');

        if (!authenticate_operation($this->input->post('senha'), $this->session->user['password_user'])) {
            throw new MyException('Senha informada incorreta', Response::UNAUTHORIZED);
        }
    }

    public function update_password()
    {
        try {
            $this->check_old_password();

            $this->begin_transaction();

            $this->funcionario_model->__set('funcionario_pk', $this->session->user['id_user']);

            $this->funcionario_model->__set(
                'funcionario_senha',
                hash(ALGORITHM_HASH, $this->input->post('new_password').SALT)
            );

            $this->funcionario_model->update_funcionario($this->session->user['id_user']);

            $this->end_transaction();

            $this->response->set_code(Response::SUCCESS);
            $this->response->send();
        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        }
    }

    private function update()
    {
        $this->funcionario_model->__set('funcionario_pk', $_POST['funcionario_pk']);
        // In case the user is sending a new profile picture
        // we need to remove it from Blob Storage
        if ($this->input->post('img')) {
            $old_image = $this->funcionario_model->get_image_path($_POST['funcionario_pk']);
            $old_image = $old_image[0]->funcionario_caminho_foto;
            if ($old_image != null) {  // If the user had no profile picture set we skip the removal
                remove_image($old_image);
            }
            $path = upload_img(
                [
                    'id' => $_POST['funcionario_pk'],
                    'path' => 'PATH_FUNC',
                    'is_os' => false,
                ],
                [0 => $this->input->post('img')]
            );

            if ($path != null) {
                $this->funcionario_model->__set('funcionario_caminho_foto', $path[0]);
                $user_data = $this->session->user;
                $user_data['image_user_min'] = $path[0];
                $user_data['image_user'] = $path[0];
                $this->session->set_userdata('user', $user_data);
            }
        }

        if (isset($_POST['setor_fk'])) {
            $this->funcionario_model->update_funcionario($_POST['funcionario_pk'], $_POST['setor_fk']);
        } else {
            $this->funcionario_model->update_funcionario($_POST['funcionario_pk']);
        }
    }

    private function insert()
    {
        $this->funcionario_model->__set('funcionario_senha', hash(ALGORITHM_HASH, $_POST['funcionario_senha'].SALT));

        if (isset($_POST['setor_fk'])) {
            $id = $this->funcionario_model->insert_funcionario($_POST['setor_fk']);
        } else {
            $id = $this->funcionario_model->insert_funcionario(null);
        }

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
            $this->response->add_data('path', $path[0]);
        }

        return $id;
    }

    public function get()
    {
        $response = new Response();

        $funcionarios = $this->funcionario_model->get(
            'funcionarios.funcionario_pk, funcionarios.organizacao_fk, funcionarios.ativo, funcionarios.funcionario_login, funcionarios.funcionario_nome, funcionarios.funcionario_caminho_foto, funcionarios.funcionario_cpf,
            funcionarios.funcao_fk, funcionarios.departamento_fk, funcoes.funcao_nome, funcoes.funcao_pk, organizacoes.organizacao_pk ',
            [
                'funcionarios.organizacao_fk' => $this->session->user['id_organizacao'],
            ]
        );

        $func_sets = $this->funcionario_model->get_setores([
            'funcionarios.organizacao_fk' => $this->session->user['id_organizacao'],
        ]);

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
            [
                'organizacao_fk' => $this->session->user['id_organizacao'],
            ],
            -1,
            -1
        );

        $departamentos = $this->departamento_model->get_all(
            '*',
            [
                'organizacao_fk' => $this->session->user['id_organizacao'],
            ],
            -1,
            -1
        );

        $setores = $this->setor_model->get_all(
            '*',
            [
                'organizacao_fk' => $this->session->user['id_organizacao'],
            ],
            -1,
            -1
        );

        $response->add_data('self', $funcionarios);
        $response->add_data('setores', $setores);
        $response->add_data('departamentos', $departamentos);
        $response->add_data('funcoes', $funcoes);

        $response->send();
    }

    public function save()
    {
        try {
            $this->funcionario_model->config_form_validation();

            $organizacao_pk = $this->session->user['id_organizacao'];

            $_POST['organizacao_fk'] = $organizacao_pk;

            if ($this->is_superuser()) {
                $this->add_password_to_form_validation();
            }

            $this->funcionario_model->fill();
            $this->funcionario_model->__set('organizacao_fk', $organizacao_pk);
            $this->funcionario_model->run_form_validation();

            $this->begin_transaction();

            if (isset($_POST['funcionario_pk']) && $_POST['funcionario_pk'] != '') {
                $this->update();
            } else {
                $id = $this->insert();

                $new = $this->funcionario_model->get(
                    '
                    funcionarios.funcionario_pk,
                    funcionarios.organizacao_fk,
                    funcionarios.ativo,
                    funcionarios.funcionario_login,
                    funcionarios.funcionario_nome,
                    funcionarios.funcionario_caminho_foto,
                    funcionarios.funcionario_cpf,
                    funcionarios.funcao_fk,
                    funcoes.funcao_nome,
                    funcoes.funcao_pk,
                    organizacoes.organizacao_pk ',
                    [
                        'funcionarios.organizacao_fk' => $this->session->user['id_organizacao'],
                        'funcionarios.funcionario_pk' => $id,
                    ]
                );

                $func_sets = $this->funcionario_model->get_setores([
                    'funcionarios.organizacao_fk' => $this->session->user['id_organizacao'],
                    'funcionarios.funcionario_pk' => $id,
                ]);

                $setor_aux = [];
                foreach ($func_sets as $fc) {
                    $setor_aux[] = $fc->setor_fk;
                }
                if (!empty($setor_aux)) {
                    $new[0]->setor_fk = $setor_aux;
                }

                $this->response->add_data('id', $id);
                $this->response->add_data('new', $new[0]);
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
            $this->funcionario_model->__set('funcionario_pk', $_POST['funcionario_pk']);

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
            $this->funcionario_model->__set('funcionario_pk', $_POST['funcionario_pk']);

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
        $url = base_url().'Contact/reset_password/'.$token.'/define';

        return $this->send_email->send_email('email/create_password.php', 'Definir Senha de Acesso', $url, $email_user);
    }

    public function change_password()
    {
        try {
            $password = hash(ALGORITHM_HASH, $_POST['funcionario_senha'].SALT);

            $this->funcionario_model->__set('funcionario_pk', $_POST['funcionario_pk']);
            $this->funcionario_model->__set('funcionario_senha', $password);

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
