<?php   

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once dirname(_FILE_) . "\Response.php";

require_once APPPATH."core\CRUD_Controller.php";

require_once APPPATH."models\Ordem_Servico_model.php";

//TODO mudar para CRUD_Controller
class Ordem_Servico extends CI_Controller {
    public $response;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ordem_servico_model', 'ordem_servico');
    }

    private function load()
    {
        $this->load->library('form_validation');
        $this->load->model('localizacao_model', 'localizacao');
        $this->load->helper('exception');
        $this->response = new Response();
        
        $this->localizacao->config_form_validation();
        $this->ordem_servico->config_form_validation();
    }

    public function index()
    {
        $this->load->model('organizacao_model');
        $organizacoes = $this->organizacao_model->get();

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
                'params' => ['organizacoes' => $organizacoes]
            ],
            1 => [
                'src' => 'access/pre_loader',
                'params' => []
            ]
        ],'superusuario');

    }

    public function save()
    {
        try
        {
            $this->load();
            // $organizacao_pk = $this->session->user['id_organizacao'];

            if($this->is_superuser())
            {
                $this->add_password_to_form_validation();
            }
            
            $this->ordem_servico->fill();
            $this->localizacao->fill();
            //TODO
            //form validation tem que estar dentro dos métodos -> insert tem a validação do organizacao_pk também
            $this->organizacao->run_form_validation();

            $this->begin_transaction();

            if(isset($organizacao_pk))
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
        $this->localizacao->update();
        $this->organizacao->update();
    }

    public function deactivate()
    {
        //TODO
    }

    public function activate()
    {
        //TODO
    }

    private function is_superuser()
    {
        return $this->session->user['is_superusuario'];
    }

    private function add_password_to_form_validation()
    {
        $this->form_validation->set_rules(
            'senha', 
            'senha', 
            'trim|required|min_length[8]'
        );
    }

    public function begin_transaction(){
        $this->db->trans_start();
    }

    public function end_transaction(){
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            if(is_array($this->db->error())){
                throw new MyException('Erro ao realizar operação.<br>'.implode('<br>',$this->db->error()), Response::SERVER_FAIL);
            } else {
                throw new MyException('Erro ao realizar operação.<br>'.$this->db->error(), Response::SERVER_FAIL);
            }
        }
        else
        {
            $this->db->trans_commit();
        }
    }
}

?>