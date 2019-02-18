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

        $this->response = new Response();
    }

    public function index()
    {
        $funcionarios = $this->funcionario_model->get(["organizacao_fk" => $this->session->user['id_organizacao']]);
        $funcoes = $this->funcao_model->get_all(
            '*',
            null,
            -1,
            -1
        );

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
                    'departamentos' => null,
                    'funcionarios' => $funcionarios,
                    'funcoes' => $funcoes_drop,
                    'setores' => null,
                ],
            ],
            1 => [
                'src' => 'access/pre_loader',
                'params' => null,
            ],
        ], 'administrador');
    }

    public function new_email($token, $email_user)
    {
        $this->load->library('send_email');
        $url = base_url() . 'Contact/reset_password/' . $token . '/define';

        return $this->send_email->send_email('email/create_password.php', 'Definir Senha de Acesso', $url, $email_user);
    }

    public function generate_recuperation($id_user, $contato_email)
    {
        $token = hash(ALGORITHM_HASH, (date("Y-m-d H:i:s") . $id_user . SALT));

        $data_recuperacao = array(
            'pessoa_fk' => $id_user,
            'recuperacao_token' => $token,
        );

        //Realizamos o insert na tabela recuperacao_senha para que o novo usuário
        $this->load->model('recuperacao_model');

        if ($this->recuperacao_model->insert($data_recuperacao)) {
            $email = $this->new_email($data_recuperacao['recuperacao_token'], $contato_email);

            if ($email) {
                $this->response->set_code(Response::SUCCESS);
                $this->response->set_data(
                    array(
                        'mensagem' => "Um e-mail foi enviado para " . $contato_email,
                        'pessoa_fk' => $id_user,
                    )
                );
            } else {
                $this->response->set_code(Response::SERVER_FAIL);
                $this->response->set_data($email);
                $this->model->reset($id_user);
            }
        }
    }

    public function change_password()
    {
        if ($this->session->has_userdata('user')) {
            //Pego os dados via post (AJAX)
            $data = $this->input->post();

            //Premissa que o usuário comum está logado
            $accepted = true;

            if ($this->session->user['is_superusuario']) {
                //Utilizando o helper de autenticação de senha para realiar o insert
                $this->load->helper('Password_helper');
                $accepted = authenticate_operation($data['senha'], $this->session->user['password_user']);
            }

            if ($accepted) {
                $where = [
                    'pessoa_fk' => $data['pessoa_fk'],
                ];

                $this->form_validation->set_rules(
                    'new_password',
                    'Senha',
                    'trim|required|min_length[8]|max_length[128]'
                );

                if (!$this->form_validation->run()) {

                    $this->response->set_code(Response::DB_ERROR_UPDATE);
                    $this->response->set_data("A senha deve possuir 8 ou mais caracteres!");

                } else {
                    $new_password = hash(ALGORITHM_HASH, $data['new_password'] . SALT);
                    if ($this->pessoa_model->new_password($where, ['acesso_senha' => $new_password])) {
                        $this->response->set_code(Response::SUCCESS);
                        $this->response->set_data("Senha alterada com sucesso!");
                    } else {
                        $this->response->set_code(Response::DB_ERROR_UPDATE);
                        $this->response->set_data("Não foi possível alterar a senha do funcionário no sistema");
                    }
                }
            } else {
                $this->response->set_code(Response::UNAUTHORIZED);
                $this->response->set_data("Operação não autorizada! Senha informada incorreta.");
            }

            $this->response->send();
        } else {
            redirect(base_url('access/index'));

        }

    }

}
