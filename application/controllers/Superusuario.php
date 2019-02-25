<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once dirname(__FILE__) . "/Response.php";
require_once dirname(__FILE__) . "/Contact.php";
require_once APPPATH . "core/CRUD_Controller.php";
class Superusuario extends CRUD_Controller
{
    public $response;

    public function __construct()
    {
        try{
            parent::__construct();
            $this->load->helper('exception');
            $this->load->library('form_validation');

            $this->response = new Response();
    
            $this->check_if_is_superuser();
            
            date_default_timezone_set('America/Sao_Paulo');
            $this->load->model('Super_model', 'superuser');
            $this->load->helper('Password');
        }catch(MyException $e){
            handle_my_exception($e);
        } catch(Exception $e){
            handle_exception($e);
        }
    }


    public function index()
    {

        $this->session->set_flashdata('css', array(
            0 => base_url('assets/vendor/cropper/cropper.css'),
            1 => base_url('assets/vendor/input-image/input-image.css'),
            2 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.css'),
            3 => base_url('assets/css/modal_desativar.css'),
            4 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css'),
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
            11 => base_url('assets/js/dashboard/superusuario/index.js'),
        ));

        
        $pk = $this->session->userdata['user']['id_user'];
        $superusuarios = $this->superuser->get_all_without_me($pk);

        load_view([
            0 => [
                'src' => 'dashboard/superusuario/superusuario/home',
                'params' => ['superusuarios' => $superusuarios],
            ]
        ], 'superusuario');
    }


    public function load()
    {
        $this->superuser->config_form_validation();
    }

    private function check_if_is_superuser()
    {
        if(!$this->is_superuser()){
            throw new MyException('Falha de autenticação.', Response::UNAUTHORIZED);
        }
    }

    public function insert()
    {
        try
        {

            $this->load();
            
            $this->load->model('recuperacao_model', 'recuperacao');
            $this->add_password_to_form_validation();
            $this->superuser->fill();
            
            $this->superuser->run_form_validation();

            $this->begin_transaction();

            $superuser_id = $this->superuser->insert();

            $this->send_email_superuser($this->superuser->__get('superusuario_email'), $superuser_id);
            
            $this->end_transaction();

            $this->response->set_code(Response::SUCCESS);
            $this->response->send();
        }catch(MyException $e){
            handle_my_exception($e);
        } catch(Exception $e){
            handle_exception($e);
        }
    }

    private function send_email_superuser($superusuario_email, $superusuario_pk)
    {
        $token = hash(ALGORITHM_HASH, (date("Y-m-d H:i:s") . $superusuario_pk . SALT));

        $this->recuperacao->__set('superusuario_fk', $superusuario_pk);
        $this->recuperacao->__set('recuperacao_token', $token);
        $this->recuperacao->insert();

        $this->load->library('send_email');

        $res = $this->send_email->send_email(
            'email/first_login.php',
            'Criar acesso - Evidencia', base_url() . 'contact/first_login/' . $token, 
            $superusuario_email
        );

        if(!$res){
            throw new MyException('Ocorreu um erro ao enviar o e-mail. Por favor, tente mais tarde.', Response::SERVER_FAIL);
        }
    }


    public function update()
    {
        try
        {
            $this->load();

            $this->superuser->fill();

            
            $this->superuser->run_form_validation();
            
            unset($this->superuser->object['superusuario_login']); //impedindo a alteração do login

            $this->begin_transaction();

            $this->superuser->update();

            $this->end_transaction();

            $this->response->set_code(Response::SUCCESS);
            $this->response->send();
        }catch(MyException $e){
            handle_my_exception($e);
        } catch(Exception $e){
            handle_exception($e);
        }
    }
   
    /**
     * Função que cria um acesso para um novo usuário, 
     * ou seja, atualiza a senha do superuser e seta o status para ativo.
     * 
     * Recebe como parâmetro de POST, o token, superusuario_senha e confirme-senha.
     */
    public function create_access()
    {
        try{
            $this->load->model('recuperacao_model', 'recuperacao');

            $token = $this->input->post('token');

            $res = $this->recuperacao->get_one('superusuario_fk', ['recuperacao_token' => $token]);
            
            if($res)
            {
                $this->superuser->add_confirm_pasword_to_form_validation();

                
                // $this->superuser->fill();
                $this->superuser->__set('superusuario_senha', hash(ALGORITHM_HASH, $this->input->post('superusuario_senha') . SALT));
                $this->superuser->__set('superusuario_pk', $res->superusuario_fk);
                $this->superuser->__set('ativo', 1);

                $this->superuser->run_form_validation();

                $this->superuser->update();

                $this->recuperacao->delete_by_token($token);

                $this->response->set_code(Response::SUCCESS);
                $this->response->set_data("Novo acesso foi cadastrado com sucesso!");
            }
            else
            {
                // $this->load->view('errors/html/error_404');
                throw new MyException('Token não encontrado.', Response::NOT_FOUND);
            }
        }catch(MyException $e){
            handle_my_exception($e);
        } catch(Exception $e){
            handle_exception($e);
        }
    }


    public function deactivate()
    {
        try{
            $this->add_password_to_form_validation();
            $this->superuser->fill();

            $this->superuser->run_form_validation();

            $this->superuser->deactivate();
            
            $this->response->set_code(Response::SUCCESS);
            $this->response->set_message('Superusuário desativado com sucesso!');
            $this->response->send();

        }catch(MyException $e){
            handle_my_exception($e);
        } catch(Exception $e){
            handle_exception($e);
        }
    }

    public function activate()
    {
        try{
            $this->add_password_to_form_validation();
            $this->superuser->fill();

            $this->superuser->run_form_validation();
            $this->superuser->activate();
            
            $this->response->set_code(Response::SUCCESS);
            $this->response->set_message('Superusuário ativado com sucesso!');
            $this->response->send();

        }catch(MyException $e){
            handle_my_exception($e);
        } catch(Exception $e){
            handle_exception($e);
        }
    }

}