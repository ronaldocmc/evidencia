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
        $this->load->model('Organizacao_model', 'organizacao');
    }

    private function load()
    {
        $this->load->model('localizacao_model', 'localizacao');

        $this->localizacao->config_form_validation();
        $this->organizacao->config_form_validation();
    }

    public function get() {
        $this->load->model('Municipio_model', 'municipio');
        $this->load->model('Organizacao_Cidade_model', 'org_cidade');

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

        $self_municipios = $this->org_cidade->get_all(
            'municipios.municipio_pk, municipios.municipio_nome',
            $this->session->user['id_organizacao'],
            -1,
            -1,
            [
                ['table' => 'municipios', 'on' => 'municipios.municipio_pk = organizacoes_cidades.municipio_fk']
            ]
        );

        $this->response->add_data('self', $organizacao);
        $this->response->add_data('municipios', $municipios);
        $this->response->add_data('self_municipios', $self_municipios);
        $this->response->send(); 
    }

    public function index() {
        $this->load->model('Municipio_model', 'municipio');
        $this->load->model('Organizacao_Cidade_model', 'org_cidade');

        $organizacoes = $this->organizacao->get_all(
            '*',
            null,
            -1,
            -1,
            [
                ['table' => 'localizacoes', 'on' => 'localizacoes.localizacao_pk = organizacoes.localizacao_fk'],
                ['table' => 'municipios', 'on' => 'municipios.municipio_pk = localizacoes.localizacao_municipio']
            ]
        );

        $municipios = $this->municipio->get_all('*', null, -1, -1);

        foreach ($organizacoes as $i => $org) {
            $organizacoes[$i]->self_municipios = $this->org_cidade->get_all(
                'municipios.municipio_pk, municipios.municipio_nome',
                $org->organizacao_pk,
                -1,
                -1,
                [
                    ['table' => 'municipios', 'on' => 'municipios.municipio_pk = organizacoes_cidades.municipio_fk']
                ]
            );
        }

        $this->response->add_data('self', $organizacoes);
        $this->response->add_data('municipios', $municipios);
        $this->response->send(); 
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
                $this->response->set_data(['id' => $organizacao_pk]);
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

    public function add_city() {
        $this->load->model('Organizacao_Cidade_model', 'org_cidade');
        try{   
            $this->begin_transaction();

            $_POST['organizacao_fk'] = $this->session->user['id_organizacao'];
            
            $this->org_cidade->fill();
            $this->org_cidade->insert();

            $this->end_transaction();

            $this->response->set_code(Response::SUCCESS);
            $this->response->send();
        }catch(MyException $e){
            handle_my_exception($e);
        } catch(Exception $e){
            handle_exception($e);
        }
    }

    public function remove_city() {
        $this->load->model('Organizacao_Cidade_model', 'org_cidade');
        try{   
            $this->begin_transaction();

            $_POST['organizacao_fk'] = $this->session->user['id_organizacao'];
            
            $this->org_cidade->delete_city($this->input->post());

            $this->end_transaction();

            $this->response->set_code(Response::SUCCESS);
            $this->response->send();
        }catch(MyException $e){
            handle_my_exception($e);
        } catch(Exception $e){
            handle_exception($e);
        }
    }
}

?>