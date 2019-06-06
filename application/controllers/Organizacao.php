<?php   

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH . "core/Response.php";

require_once APPPATH."core/CRUD_Controller.php";

require_once APPPATH."models/Organizacao_model.php";

class Organizacao extends CRUD_Controller {
    public $response;

    public function __construct()
    {
        parent::__construct();
        $this->response = new Response();
        $this->load->helper('exception');
        $this->load->library('form_validation');
        $this->load->model('organizacao_model', 'organizacao');
    }

    private function load()
    {
        $this->load->model('localizacao_model', 'localizacao');

        $this->localizacao->config_form_validation();
        $this->organizacao->config_form_validation();
    }

    public function get() {
        $this->load->model('Municipio_model', 'municipio');

        $organizacao = $this->organizacao->get_all(
            '*',
            ['organizacao_pk' => $this->session->user['id_organizacao']],
            -1,
            -1,
            [
                ['table' => 'localizacoes', 'on' => 'localizacoes.localizacao_pk = organizacoes.localizacao_fk'],
                ['table' => 'municipios', 'on' => 'municipios.municipio_pk = localizacoes.localizacao_municipio']
            ]
        );

        $municipios = $this->municipio->get_all('*', null, -1, -1);

        $this->response->add_data('self', $organizacao);
        $this->response->add_data('municipios', $municipios);
        $this->response->send(); 
    }

    public function index()
    {
        $this->load->model('municipio_model', 'municipio');
        
        $data['organizacoes'] = $this->organizacao->get();

        $data['municipios'] = $this->municipio->get(); //get all

        //CSS para crud organizações
        $this->session->set_flashdata('css',[
            0 => base_url('assets/css/modal_desativar.css'),
            1 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.css'),
            2 => base_url('assets/css/loading_input.css'),
            3 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css')
        ]);

        //Scripts para crud organizações
        $this->session->set_flashdata('scripts',[
            0 => base_url('assets/vendor/masks/jquery.mask.min.js'),
            1 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.js'),
            2 => base_url('assets/js/masks.js'),
            3 => base_url('assets/vendor/bootstrap-multistep-form/jquery.easing.min.js'),
            4 => base_url('assets/vendor/datatables/datatables.min.js'),
            5 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js'),
            6 => base_url('assets/js/localizacao.js'),
            7 => base_url('assets/js/utils.js'),
            8 => base_url('assets/js/constants.js'),
            9 => base_url('assets/js/jquery.noty.packaged.min.js'),
            10 => base_url('assets/js/dashboard/organizacao/index.js'),
            11 => base_url('assets/vendor/select-input/select-input.js')
        ]);

        load_view([
            0 => [
                'src' => 'dashboard/superusuario/organizacao/home',
                'params' => $data
            ],
            1 => [
                'src' => 'access/pre_loader',
                'params' => []
            ]
        ],'superusuario');

    }


    private function _organization_is_active($organization)
    {
        if(!$organization->ativo)
        {
            throw new MyException(
                'Organização inativa.', 
                Response::FORBIDDEN
            );
        }
    }

    public function access()
    {
        try{
            $organization = $this->organizacao->get_one_or_404(
                'organizacao_pk, ativo',
                ['organizacao_pk' => $this->input->post('organizacao_pk')]
            );

            $this->_organization_is_active($organization);

            $user = $this->session->user;
            $this->session->unset_userdata('user');
            $user['id_organizacao'] = $organization->organizacao_pk;
            $this->session->set_userdata('user', $user);

            $this->response->set_code(Response::SUCCESS);
            $this->response->send();
           
            
        }catch(MyException $e){
            handle_my_exception($e);
        } catch(Exception $e){
            handle_exception($e);
        }
    }


    public function save()
    {
        try
        {
            $this->load();

            if($this->is_superuser())
            {
                $this->add_password_to_form_validation();
                $organizacao_pk = $this->input->post('organizacao_pk');
            }
            else 
            {
                $organizacao_pk = $this->session->user['id_organizacao'];
                $_POST['organizacao_pk'] = $organizacao_pk;
            }
            
            $this->organizacao->fill();
            $this->localizacao->fill();

            $this->organizacao->run_form_validation();

            $this->begin_transaction();

            $organizacao = $this->organizacao->get_one('organizacao_pk', $this->input->post('organizacao_pk'));
            
            if($organizacao)
            {
                $this->update();
            } 
            else 
            {
                $this->insert();
            }

            $this->end_transaction();

            $this->response->set_code(Response::SUCCESS);
            $this->response->send();

        }catch(MyException $e){
            handle_my_exception($e);
        } catch(Exception $e){
            handle_exception($e);
        }
    }

    private function insert()
    {
        $this->organizacao->__set("localizacao_fk", $this->localizacao->insert());
        $this->organizacao->insert();
    }

    private function update()
    {
        $organizacao = $this->organizacao->get_one('localizacao_fk', $this->input->post('organizacao_pk'));
    
        $this->localizacao->__set("localizacao_pk", $organizacao->localizacao_fk);
    
        $this->localizacao->update();
        $this->organizacao->update();
    }

    public function deactivate()
    {
        try{
            $this->add_password_to_form_validation();
            $this->organizacao->fill();

            $this->organizacao->run_form_validation();

            $this->organizacao->deactivate();
            
            $this->response->set_code(Response::SUCCESS);
            $this->response->set_message('Organização desativada com sucesso!');
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
            $this->organizacao->fill();

            $this->organizacao->run_form_validation();
            $this->organizacao->activate();
            
            $this->response->set_code(Response::SUCCESS);
            $this->response->set_message('Organização ativada com sucesso!');
            $this->response->send();

        }catch(MyException $e){
            handle_my_exception($e);
        } catch(Exception $e){
            handle_exception($e);
        }
    }    
}

?>