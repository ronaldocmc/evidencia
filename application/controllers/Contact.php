
<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH."core/Response.php";   


class Contact extends CI_Controller
{
    /**
     * Variavel que representa a resposta do servidor ao usuário
     *
     * @var Response
     */
    public $response;
    public $kind_user;
    public $select;

    //-------------------------------------------------------------------------------

    /**
     * Construtor da Classe
     *
     * Chama o construtor da classe pai
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('America/Sao_Paulo');
    
        if($this->is_superuser()){
            $this->load->model('recuperacao_super_model', 'recuperacao_model');
            $this->load->model('super_model', 'worker_model');

            $this->kind_user = 'superusuario';
            $this->select = 'superusuario_fk as pk, superusuario_login as email';
        }else{
            $this->load->model('recuperacao_funcionario_model', 'recuperacao_model');
            $this->load->model('funcionario_model', 'worker_model');

            $this->kind_user = 'funcionario';
            $this->select = 'funcionario_fk as pk, funcionario_login as email';
        }   
        
        $this->load->model('tentativa_model');
        $this->load->model('tentativa_recuperacao_model');
        $this->load->model('funcionario_model');
        
        $this->load->helper('recaptcha');
        $this->load->helper('attempt');
        $this->load->helper('exception'); 

        $this->load->library('send_email');
        $this->load->library('form_validation');

        $this->response = new Response();
    }

    //--------------------------------------------------------------------------------

    /**
     * Método padrão da classe Contact
     *
     * @return void
     */
    public function index()
    {
        $this->load->view('contact/restore_login', null, false);
    }


    /**
     * Método chamado quando é necessário criar ou redefinir a senha para um usuário por via do e-mail
     *
     * @return void
     */


     private function verify_token($token)
     {
        if ($token !== '') 
        {
            
            $restore = [
                'recuperacao_token' => $token,
                'recuperacao_tempo >' => date('Y-m-d H:i:s', strtotime('- 1 day', strtotime(date('Y-m-d H:i:s')))),
            ];

            $restore_fetch = $this->recuperacao_model->get_all(
                $this->kind_user.'_fk as pk,
                recuperacao_token,
                recuperacao_tempo',
                $restore,
                -1,
                -1
            );

            if(!$restore_fetch){
                throw new MyException('Seu pedido expirou. Você já utilizou esse código de alteração de senha.', Response::NOT_FOUND);
                // $this->load->view('errors/padrao/home',['response' => $this->response]);
            }

            return $restore_fetch;
        }
     }

    public function reset_password($token = '', $define = NULL)
    {
        try{
            $this->verify_token($token);

            $data = [
                'token' => $token,
                'define' => $define
            ];

            $this->load->view('dashboard/commons/head.php', false);
            $this->load->view('contact/define_password', $data, false);
            $this->load->view('dashboard/commons/footer.php', false);

        } catch(MyException $e) {
            handle_my_exception($e);
        } catch(Exception $e) {
            handle_exception($e);
        }
    }


    /**
     * Método chamado pela view de definição de senha para login
     *
     * @return void
     */
    public function define_password($token)
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('new_password',
            'Senha Atual',
            'trim|required|min_length[8]|max_length[128]'
        );

        $this->form_validation->set_rules('new_password_repeat',
            'Repetir Senha',
            'trim|required|min_length[8]|max_length[128]|matches[new_password]'
        );

        $mensagem = '';

        if ($this->form_validation->run() === true) 
        {
            $this->load->model('contato_model');
            $this->load->model('recuperacao_model');
            $this->load->model('acesso_model');

            // De acordo com o token, pega a tupla da recuperação de senha
            $rec = $this->recuperacao_model->get([
                'recuperacao_token' => $token
            ]);

            if ($rec !== false) 
            {
                // Se existir, procura o contato da pessoa referenciada pela tupla da recuperação
                $contato = $this->contato_model->get([
                    'pessoa_fk' => $rec->pessoa_fk
                ]);

                if ($contato !== false) 
                {
                    // Se o contato existir, coloca o e-amil na tabela de acessos
                    $acesso['pessoa_fk'] = $rec->pessoa_fk;
                    $acesso['acesso_login'] = $contato->contato_email;
                    $acesso['acesso_senha'] = hash(ALGORITHM_HASH, $this->input->post('new_password') . SALT);

                    if ($this->acesso_model->insert($acesso) !== false) 
                    {
                        // Retira a tupla da tabela de recuperação
                        $this->recuperacao_model->delete([
                            'pessoa_fk' => $rec->pessoa_fk
                        ]);
                        
                        // Redireciona para a página inicial do site
                        redirect(base_url());
                    }
                }
                else
                {
                    $mensagem = 'Usuário não encontrado';
                }
            }
            else
            {
                $mensagem = 'Operação de definição de senha experida';
            }

        }
        else
        {
            $mensagem = 'Senha informada inválida';
        }

        $error = array('heading' => 'Erro.', 'message' => $mensagem);
        $this->load->view('errors/html/error_general.php', $error);
        
    }

    private function clear_attempts($worker){
        $this->recuperacao_model->__set($this->kind_user . '_fk', $worker->pk);
        $this->recuperacao_model->__set('recuperacao_token', $token);


        $this->recuperacao_model->delete();

        $this->tentativa_recuperacao_model->__set('tentativa_ip', $this->input->ip_address());
        $this->tentativa_recuperacao_model->__set('tentativa_email', $worker->email);

        $this->tentativa_recuperacao_model->delete();
    }

    private function save_new_password($worker){

        $this->worker_model->__set('funcionario_pk', $worker->pk);
        $this->worker_model->__set('funcionario_senha', hash(ALGORITHM_HASH, $this->input->post('new_password') . SALT));
         
        $this->begin_transaction();
        $this->worker_model->update();
        $this->clear_attempts($worker);
        $this->end_transaction();

        $this->response->set_code(Response :: SUCCESS);

    }
    public function new_password($token)
    {

        $this->recuperacao_model->config_password_form_validation($token);
        $this->recuperacao_model->run_form_validation();
        $restore_fetch = $this->verify_token($token);
        $worker = $this->fetch_contact($restore_fetch[0]->pk);
        $this->save_new_password($worker[0]);
        redirect(base_url());
                

    }

    public function is_superuser()
    {
        return $this->session->user['is_superusuario'];
    }

    private function fetch_contact_by_email(){
        
        $contact_fetch = $this->funcionario_model->get(
            $this->select,
            [$this->kind_user.'_login' => $this->input->post('email')],
            -1,
            -1
        );

        if (empty($contact_fetch)) 
        {
            throw new MyException('O e-mail inserido não foi encontrado. Por favor, recupere a senha com o e-mail cadastrado no sistema.', Response :: NOT_FOUND);
        }
        
        return $contact_fetch;
    }

    private function fetch_contact($pk){
        
        $contact_fetch = $this->funcionario_model->get(
            $this->select,
            [$this->kind_user.'_pk' => $pk],
            -1,
            -1
        );

        if (empty($contact_fetch)) 
        {
            throw new MyException('Usuário não foi encontrado no sistema.', Response :: NOT_FOUND);
        }
        
        return $contact_fetch;
    }

    private function auth_restore(){
        
        $ip = $this->input->ip_address();
        $email = $this->input->post('email');

        if(!verify_attempt_restore($ip, $email)){
            
            throw new MyException('Acesso bloqueado.', Response::FORBIDDEN);
        }
    }

    private function save($worker){

        $this->recuperacao_model->__set($this->kind_user.'_fk', $worker->pk);
        $this->recuperacao_model->delete();

        $token = hash(ALGORITHM_HASH, date('Y/m/d H:i:s') . SALT . $worker->pk); 
        
        $this->recuperacao_model->__set($this->kind_user . '_fk', $worker->pk);
        $this->recuperacao_model->__set('recuperacao_token', $token);
        $this->recuperacao_model->__set('recuperacao_tempo', date('Y/m/d H:i:s'));

        $this->recuperacao_model->insert();

        $this->tentativa_recuperacao_model->__set('tentativa_ip', $this->input->ip_address());
        $this->tentativa_recuperacao_model->__set('tentativa_tempo', date('Y/m/d H:i:s'));
        $this->tentativa_recuperacao_model->__set('tentativa_email', $worker->email);

        $this->tentativa_recuperacao_model->insert();
    
        // $status = $this->send_email->send_email('email/restore_password', 'Recuperação de Senha - Evidencia', base_url() . 'contact/reset_password/' . $token, $worker->email);

        $this->response->set_code(200);
        // $this->response->set_data('Enviado com sucesso!');
    }

    public function restore_password()
    {   
        try{


            $this->recuperacao_model->config_form_validation(); 
            $this->recuperacao_model->run_form_validation(); 

            $this->auth_restore();
            $worker = $this->fetch_contact_by_email();
            
            $this->begin_transaction();
            $this->save($worker[0]);
            $this->end_transaction();
            
            $this->response->send();

        } catch(MyException $e) {
            handle_my_exception($e);
        } catch(Exception $e) {
            handle_exception($e);
        }
    }

    public function begin_transaction()
    {
        $this->db->trans_start();
    }


    public function end_transaction()
    {
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

    //Função que cria um acesso para um novo usuário, ou seja, cria um token de primeiro acesso para recuperação de senha
    public function create_access()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules(
            'acesso_senha',
            'Senha',
            'trim|required|min_length[8]|max_length[128]'
        );

        $this->form_validation->set_rules(
            'acesso_login',
            'Login',
            'trim|required|min_length[3]|max_length[128]|is_unique[acessos.acesso_login]'
        );

        $this->form_validation->set_rules(
            'confirme-senha',
            'Senha2',
            'trim|required|min_length[8]|max_length[128]|matches[acesso_senha]'
        );

        if ($this->form_validation->run() === true) 
        {
            //Realizando o load do model recuperacao_mmodel
            $this->load->model('recuperacao_model', 'rmodel');

            $where = array(
                'recuperacao_token' => $this->input->post('token'),
            );

            //Chamando função get para realizar a operação e encontrar o usuário especifico do acesso
            // $retorno = $this->rmodel->get($where);
            $retorno = $this->rmodel->get_one(
                '*',
                $where
            );
            if ($retorno) //Se o token existir então inserimos o novo acesso
            {
                //Padronizando dados recebidos via post para inserção
                $data_insert = array(
                    'pessoa_fk' => $this->input->post('pessoa_fk'),
                    'acesso_login' => $this->input->post('acesso_login'),
                    'acesso_senha' => hash(ALGORITHM_HASH, $this->input->post('acesso_senha') . SALT),
                );

                if ($this->rmodel->insert_acesso($data_insert)) 
                {
                    $row = $this->rmodel->delete_token($where);

                    if ($row == 1) 
                    {
                        //Se a inserção foi bem sucedida então response é positiva e a remoção feita
                        $this->response->set_code(Response::SUCCESS);
                        $this->response->set_data("Novo acesso foi cadastrado com sucesso!");
                    } 
                    else 
                    {
                        $this->response->set_code(Response::DB_ERROR_INSERT);
                        $this->response->set_data("Usuário foi cadastrado no entanto o token não foi apagado");
                    }
                } 
                else 
                {
                    $this->response->set_code(Response::DB_ERROR_INSERT);
                    $this->response->set_data("Não foi possível inserir novo acesso do superusuário");
                }
            } 
            else 
            {
                $this->load->view('errors/html/error_404');
            }
        } 
        else //Se os formulários não foram preenchidos corretamente (form_validation)
        {
            $this->response->set_code(Response::BAD_REQUEST);
            $this->response->set_data($this->form_validation->errors_array());
        }

        $this->response->send();
    }

    //Função que cadastra uma nova senha e um login para um novo usuário que acessou o token de recuperação
    public function first_login($token)
    {
        $this->load->model('recuperacao_model', 'rmodel');



        //Padronizando os dados para select
        $where = array(
            'recuperacao_token' => $token,
        );

        //Chamando função get para realizar a operação e encontrar o usuário especifico do acesso
        $retorno = $this->rmodel->get($where);

        
        if ($retorno) //Se o token existir então exibimos a view para preenchimento de senha e login
        {
            $super = $this->super_model->get([
                'superusuario_pk' => $retorno->superusuario_fk,
                'ativo' => 0
            ]);

            if ($super===FALSE)
            {
                $func = $this->funcionario_model->get([
                    'funcionarios.pessoa_fk' => $retorno->pessoa_fk,
                    'funcionarios.funcionario_status' => 1
                ]);

                $retorno->organizacao_fk = $func!==FALSE?$func[count($func)-1]->organizacao_fk:"admin";
            }
            else
            {
                $retorno->organizacao_fk = "admin";
            }

            $this->session->set_flashdata('css', array(
                0 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.css'),
                1 => base_url('assets/css/modal_desativar.css'),
            ));

            $this->session->set_flashdata('scripts', array(
                0 => base_url('assets/vendor/masks/jquery.mask.min.js'),
                1 => base_url('assets/vendor/bootstrap-multistep-form/jquery.easing.min.js'),
                2 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.js'),
                3 => base_url('assets/js/utils.js'),
                4 => base_url('assets/js/constants.js'),
                5 => base_url('assets/js/jquery.noty.packaged.min.js'),
                6 => base_url('assets/js/dashboard/first-login/index.js'),
            ));

            load_view([
                0 => [
                    'src' => 'dashboard/commons/first-login/home',
                    'params' => $retorno,
                ],
                1 => [
                    'src' => 'access/pre_loader',
                    'params' => null,
                ],
            ], 'administrador', false);
        } 
        else //Se não, carregamos a view de erro.
        {
            $this->load->view('errors/html/error_404', 
                    [
                        'heading' =>  "Não foi possível exibir a página", 
                        'message' => "Token expirado ou usário não encontrado."
                    ]
                );
        }
    }

}
