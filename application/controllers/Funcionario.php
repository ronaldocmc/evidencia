<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . "/Response.php";
require_once dirname(__FILE__) . "/Pessoa.php";
date_default_timezone_set('America/Sao_Paulo');
require_once APPPATH."core/CRUD_Controller.php";


class Funcionario extends CRUD_Controller {

    public $CI;
    public $response;
    public $pessoa; 

    function __construct() {
        if(ENVIRONMENT == 'testing'){
            CIPHPUnitTest::createCodeIgniterInstance();
        }
        if ($this->CI = & get_instance() === NULL)
        {
            parent::__construct();
            $this->CI = & get_instance();
        }

        //parent::__construct();
        $this->CI->load->model('funcionario_model','model');
        $this->CI->load->model('pessoa_model');
        $this->CI->load->library('form_validation');
        $this->CI->load->library('upload');
        $this->response = new Response();
        $this->pessoa = new Pessoa();
    }


    function index() 
    {

        $this->CI->load->model('funcionario_setor_model');
        $this->CI->load->model('funcao_model');
        $this->CI->load->model('departamento_model');
        $this->CI->load->model('setor_model');

        $this->CI->session->set_flashdata('css', array(
            0 => base_url('assets/vendor/cropper/cropper.css'),
            1 => base_url('assets/vendor/input-image/input-image.css'),
            2 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.css'),
            3 => base_url('assets/css/modal_desativar.css'),
            4 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css'),
            5 => base_url('assets/css/user_guide.css')
        ));

        $this->CI->session->set_flashdata('scripts', array(
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
            13 => base_url('assets/vendor/select-input/select-input.js')
        ));

        $funcionarios = $this->CI->model->get([
            'funcionarios.organizacao_fk' => $this->CI->session->user['id_organizacao'],
            'funcionarios.pessoa_fk !=' => $this->CI->session->user['id_user'],
        ]);

        $func_sets = $this->CI->funcionario_setor_model->get([
            'organizacao_fk' => $this->CI->session->user['id_organizacao']
        ]);

        // Percorre pelos funcionarios e pelos func_sets vendo os matches
        // e colocando nos funcionarios os setores dele

        if($funcionarios == false){
            $funcionarios = [];
        }

        foreach ($funcionarios as $func) 
        {
            // Variável auxiliar para guardar os setores do funcionário
            $setor_aux = [];

            foreach ($func_sets as $fc) 
            {
                if ($fc->funcionario_fk == $func->funcionario_pk) 
                {
                    // Se a tupla de func_set tem o funcionário, adiciona um campo no vetor
                    $setor_aux[] = $fc->setor_fk;
                }
            }

            // Caso ele tenha setores
            if (!empty($setor_aux)) 
            {
                $func->setor_fk = $setor_aux;
            }
        }


        $funcoes = $this->CI->funcao_model->get();
        foreach ($funcoes as $key => $f) {
            $funcoes_drop[$f->funcao_pk] = $f->funcao_nome; 
        }

        $departamentos = $this->CI->departamento_model->get([
            'organizacao_fk' => $this->session->user['id_organizacao'],
            'departamentos.ativo' => '1'
        ]);

        if($departamentos !== FALSE)
        {
            foreach ($departamentos as $key => $d) 
            {
               $departamentos_drop[$d->departamento_pk] = $d->departamento_nome; 
           }
        }
        else
        {
            $departamentos_drop = [];
        }

        $setores_drop[NULL] = "Nenhum Setor";
        $setores = $this->setor_model->get(['organizacao_fk' => $this->CI->session->user['id_organizacao']]);
        if($setores !== FALSE)
        {
           foreach ($setores as $key => $s) {
               $setores_drop[$s->setor_pk] = $s->setor_nome; 
           }
        }

       $this->CI->load->helper('form');
       load_view([
        0 => [
            'src' => 'dashboard/administrador/funcionario/home',
            'params' => [
                'departamentos' => $departamentos_drop,
                'funcionarios' => $funcionarios,
                'funcoes' => $funcoes_drop,
                'setores' => $setores_drop
            ],
        ],
        1 => [
            'src' => 'access/pre_loader',
            'params' => null,
        ]
        ], 'administrador');
    }


public function config_form_validation()
{

        //Configurando as regras de validação do formulário
    $this->form_validation->set_rules(
        'funcao_fk',
        'Funcao',
        'required|trim|is_natural_no_zero'
    );

    $this->form_validation->set_rules(
        'departamento_fk',
        'Departamento',
        'trim|is_natural_no_zero'
    );

    if($this->session->user['is_superusuario'])
    {
        $this->form_validation->set_rules(
            'senha',
            'Senha',
            'trim|required|min_length[8]|max_length[128]'
        );
    }

    $this->form_validation->set_rules(
        'logradouro_nome',
        'logradouro_nome'
        // 'required'
    );

    $this->form_validation->set_rules(
        'local_num',
        'local_num'
        //'required'
    );

    $this->form_validation->set_rules(
        'bairro',
        'bairro'
        //'required'
    );

    $this->form_validation->set_rules(
        'municipio_pk',
        'cidade'
        //'required'
    );

    $this->form_validation->set_rules(
        'local_complemento',
        'local_complemento'
    );

    $this->form_validation->set_rules(
        'estado_pk',
        'estado_pk',
        'trim|max_length[2]'
    );
}


    //Função que executa o upload da imagem de perfil do usuário

public function already_func($cpf){

    $where = [
        'populacao.pessoa_cpf' => $cpf
    ];

    return $this->model->get($where);
}

/**
Nesta função, vamos verificar se $id_dpto é vazio também, pois preencher o departamento é algo opcional.
**/
public function has_departamento($id_dpto){
    if($id_dpto == "")
    {
        return TRUE;
    }
    else
    {
        $where = [
            'departamento_pk' => $id_dpto
        ];

        return $this->model->get_dpto($where);
    }
}

public function has_funcao($id_funcao){
    $where = [
        'funcao_pk' => $id_funcao
    ];

    return $this->model->get_funcao($where);
}

public function insert(){
   //as operações só serão possíveis se o usuário estiver logado 
    if($this->session->has_userdata('user'))
    {   
        $this->config_form_validation();

        //Se o usuário inseriu os dados no form corretamente
        if($this->form_validation->run())
        {
            //Pego os dados via post (AJAX)
            $data = $this->input->post();

            //Verificando se existe o departamento e a funcao selecionada (Segurança máxima)
            if($this->has_departamento($data['departamento_fk']) && $this->has_funcao($data['funcao_fk']))
            {    
                //Inserimos os dados referente a pessoa (email, cpf, contatos em geral e imagem)
                $return = $this->pessoa->insert();

                //Padronizando os dados para inserção
                $data_funcionario = array(
                    'organizacao_fk' => $this->session->user['id_organizacao'],
                    'funcionario_status' => 1
                );


                //Se a inserção de pessoa foi correta então inserimos funcionário
                if($return->code == 200)
                {

                        $data_funcionario['pessoa_fk'] = $return->data['id']; //return->data contém o a PK de pessoa

                        //Chamando a função insert do model_funcionario para efetuar a inserção
                        $result = $this->model->insert($data_funcionario, $data['funcao_fk']); 

                        //A função retorna o db->error()  se 0 então a operação foi efetuada c/ sucesso
                        if($result['db_error']['code'] == 0)
                        {
                            if($data['departamento_fk'] !== "")
                            {
                                $data_departamento = array(
                                    'funcionario_fk' => $result['funcionario_fk'],
                                    'departamento_fk' => $data['departamento_fk']
                                );
                                $success = $this->model->insert_departamento($data_departamento);
                                if(!$success)
                                {
                                    $this->response->set_code(Response::DB_ERROR_INSERT);
                                    $this->response->set_data("Não foi possivel inserir departamento do novo funcionário!");
                                }
                            }

                            if($data['funcao_fk'] == 3)
                            {
                                if ($data['setor_fk']!=="")
                                {
                                    if (strpos($data['setor_fk'], ',') === false)
                                    {
                                        $data_setor = array(
                                            'funcionario_fk' => $result['funcionario_fk'],
                                            'setor_fk' => $data['setor_fk']
                                        );

                                        $success = $this->model->insert_setor($data_setor);
                                    }
                                    else
                                    {
                                        $setores = explode(',', $data['setor_fk']);
                                        foreach ($setores as $s) 
                                        {
                                            $data_setor = array(
                                                'funcionario_fk' => $result['funcionario_fk'],
                                                'setor_fk' => $s
                                            );
                                            $this->model->insert_setor($data_setor);
                                        }
                                        $success = TRUE;
                                    }

                                }
                                else
                                {
                                 $success = TRUE;
                            }

                            if(!$success)
                            {
                                $this->response->set_code(Response::DB_ERROR_INSERT);
                                $this->response->set_data("Não foi possivel inserir setor(es) do novo funcionário!");
                            }
                            else
                            {
                                $this->generate_recuperation($return->data['id'], $data['contato_email']);  
                            }
                        }
                        else
                        {
                            $this->generate_recuperation($return->data['id'], $data['contato_email']);
                        }
                }
                else
                {   
                    $this->response->set_code(Response::DB_ERROR_INSERT);
                    $this->response->set_data("Não foi possivel inserir novo funcionário.");
                }
            }
            else
            {
                //Se 1062 há duplicação dos dados, então é necessário verificar se o funcionário já pertence a organização ou não
                if($return->code == 503)
                {   
                    //Pegando os dados do funcionário, pois já sabemos que ele existe no banco
                    $func = $this->already_func($data['pessoa_cpf']);
                    $is_func = false;

                    //Verificando se ele já pertence a organização atual
                    if ($func !== FALSE)
                    {
                        foreach ($func as $f) {
                            if($f->organizacao_fk == $this->session->user['id_organizacao']) 
                                $is_func = true;
                        }
                    }
                    else
                    {
                        $func[0] = $this->pessoa_model->get(['pessoa_cpf' => $data['pessoa_cpf']]);
                    }

                    //Se não é funcionário dessa organização, então ele poderá ser de outra organização, isto é, ser o Julius e ter 2 empregos. 
                    if(!$is_func)
                    {
                        $data_funcionario['pessoa_fk'] = $func[0]->pessoa_pk; 

                        //Realiando o insert para outra organização
                        $this->model->insert($data_funcionario,$data['funcao_fk']);

                        $this->response->set_code(Response::SUCCESS);
                        $this->response->set_data("Funcionário foi cadastrado com sucesso!"); 
                    }
                    else
                    {
                        //Caso ele já pertença a organizaçao atual, basta que o usuário altere os seus dados cadastrais, então informamos-o. 
                        $this->response->set_code(Response::BAD_REQUEST);
                        $this->response->set_data("Não é possível cadastrar um usuário já cadastrado! Favor verifique o CPF, ative ou desative o funcionário"); 
                    }  
                }
                else
                {
                    $this->response = $return;
                }
            }
        }
        else
        {
            $this->response->set_code(Response::BAD_REQUEST);
            $this->response->set_data("Departamento ou função selecionadas não existem!");  
        }
        
    }
        else //Caso o usuário tenha inserido os dodos no formulário incorretamente
        {
            $this->response->set_code(Response::BAD_REQUEST);
            $this->response->set_data($this->form_validation->errors_array()); 
        }
        $this->response->send();
    }  
        //Enviando resposta do servidor para a view
    else
    {
        redirect(base_url('access/index'));
    }
}   

public function new_email($token, $email_user)
{
    $this->load->library('send_email');
    $url = base_url() . 'Contact/reset_password/' . $token . '/define';

    return $this->send_email->send_email('email/create_password.php', 'Definir Senha de Acesso', $url, $email_user);
}


public function generate_recuperation($id_user, $contato_email){
    $token = hash(ALGORITHM_HASH, (date("Y-m-d H:i:s") . $id_user. SALT));

    $data_recuperacao = array(
        'pessoa_fk' => $id_user,
        'recuperacao_token' => $token
    );

    //Realizamos o insert na tabela recuperacao_senha para que o novo usuário
    $this->load->model('recuperacao_model');

    if ($this->recuperacao_model->insert($data_recuperacao))
    {
        $email = $this->new_email($data_recuperacao['recuperacao_token'], $contato_email);

        if ($email) 
        {
            $this->response->set_code(Response::SUCCESS);
            $this->response->set_data(
                array(
                    'mensagem' => "Um e-mail foi enviado para " . $contato_email,
                    'pessoa_fk' => $id_user,
                )
            );
        } 
        else 
        {
            $this->response->set_code(Response::SERVER_FAIL);
            $this->response->set_data($email);
            $this->model->reset($id_user);
        }
    }
}

public function update()
{
    if ($this->session->has_userdata('user')) 
    {
        $this->config_form_validation();

        //Se o usuário inseriu os dados no form corretamente
        if($this->form_validation->run())
        {
            //Pego os dados via post (AJAX)
            $data = $this->input->post();

            //Premissa que o usuário comum está logado, ele não precisa inserir a senha para realizar operações
            $accepted = TRUE;

            if ($accepted) 
            {
                $return = $this->pessoa->update();

                if($return->code == 200)
                {
                    $data_funcionario = array(
                        'organizacao_fk' => $this->session->user['id_organizacao'],
                        'funcionario_status' => 1
                    );

                    $data_funcao = [
                        'funcao_fk' => $data['funcao_fk']
                    ];

                    $where = [
                        'pessoa_fk' => $data['pessoa_pk'],
                        'organizacao_fk' => $this->session->user['id_organizacao']
                    ];

                    $return_funcionario = $this->model->update($data_funcionario, $where);
                    if ($return_funcionario === FALSE)
                    {
                        $this->response->set_code(Response::DB_ERROR_INSERT);
                        $this->response->set_data("Não foi possível alterar os dados do funcionário no sistema.");
                        return;
                    }

                    $return_funcao = $this->model->update_funcao($data_funcao, ['funcionario_fk' => $return_funcionario]);
                    if ($return_funcao === FALSE)
                    {
                        $this->response->set_code(Response::DB_ERROR_INSERT);
                        $this->response->set_data("Não foi possível alterar os dados da função do funcionario no sistema.");
                        return;
                    }


                    $data_departamento = [
                        'departamento_fk' => $data['departamento_fk']
                    ];

                    //Buscaremos o setor do funcionário selecionado para alteração de dados
                    $where = [
                        'funcionario_fk' => $return_funcionario
                    ];

                    $func_depto_exists = $this->model->get_funcionarios_departamentos(['funcionario_fk' => $return_funcionario]);
                    if($func_depto_exists)
                    { 
                        $this->model->update_departamento($data_departamento, ['funcionario_fk' => $return_funcionario]);
                    }
                    else
                    {
                        if($data['departamento_fk'] != '')
                        {
                            $this->model->insert_departamento([
                                'funcionario_fk' => $return_funcionario, 
                                'departamento_fk' => $data['departamento_fk']
                            ]);
                       }
                    }

                    //Retorna o funcionário selecionado e seus dados de setor
                    $funcionario = $this->model->get_setor($where);

                    // Se o funcionário tiver setores
                    $this->CI->load->model('Funcionario_setor_model', 'funcionario_setor_model');
                    if($funcionario !== FALSE)
                    {  
                        $this->funcionario_setor_model->delete([
                            'funcionario_fk' => $return_funcionario
                        ]);
                    }
                    
                    //Verificando se o funcionario é fiscal, caso seja então deve-se alterar os setores
                    if($data['funcao_fk'] == '3')
                    {

                        if ($data['setor_fk'] == '')
                        {
                            $this->response->set_code(Response::BAD_REQUEST);
                            $this->response->set_data('Fiscal deve ter setor(es) associado(s) a ele');
                            $this->response->send();
                            return;
                        }

                        if (strpos($data['setor_fk'], ',') === false)
                        {
                            $data_setor = array(
                                'funcionario_fk' => $return_funcionario,
                                'setor_fk' => $data['setor_fk']
                            );

                            $success = $this->model->insert_setor($data_setor);
                        }
                        else
                        {
                            $novos_setores = explode(',', $data['setor_fk']);

                            foreach ($novos_setores as $s) 
                            {
                                $this->funcionario_setor_model->insert([
                                    'funcionario_fk' => $return_funcionario,
                                    'setor_fk' => $s
                                ]);
                            } 
                        }
                        
                    }

                    $this->response->set_code(Response::SUCCESS);
                    $this->response->set_data('Dados alterados com sucesso!');
                }
                else
                {
                    $return->send();
                    return;
                }
            } 
            else 
            {
                $this->response->set_code(Response::UNAUTHORIZED);
                $this->response->set_data("Operação não autorizada! Senha informada incorreta.");
            }
        } 
        else 
        {
            $this->response->set_code(Response::BAD_REQUEST);
            $this->response->set_data($this->form_validation->errors_array());
        }

        $this->response->send();
    } 
    else 
    {
        redirect(base_url('access/index'));
    }
}

public function activate(){
    if($this->session->has_userdata('user'))
    {
            //Pego os dados via post (AJAX)
        $data = $this->input->post();

            //Premissa que o usuário comum está logado
        $accepted = TRUE;

        if($this->session->user['is_superusuario'])
        {
                 //Utilizando o helper de autenticação de senha para realiar o insert
            $this->load->helper('Password_helper');
            $accepted = authenticate_operation($data['senha'],$this->session->user['password_user']);
        }

        if ($accepted) 
        {       
            $data_deactivate = array(
                'funcionario_status' => 1,
            );

            $where = array(
                'pessoa_fk' => $data['pessoa_pk'],
                'organizacao_fk' => $this->session->user['id_organizacao']
            );

            $result = $this->model->update_status($data_deactivate, $where);

            if($result){
                $this->response->set_code(Response::SUCCESS);
                $this->response->set_data("Funcionário foi ativado com sucesso!");
            }
            else
            {
                $this->response->set_code(Response::DB_ERROR_INSERT);
                $this->response->set_data("Não foi possível ativar o funcionário no sistema");  
            }
        } 
        else 
        {
            $this->response->set_code(Response::UNAUTHORIZED);
            $this->response->set_data("Operação não autorizada! Senha informada incorreta.");
        }

        $this->response->send();
    } 
    else 
    {
        redirect(base_url('access/index'));

    }

}

public function deactivate(){
    if($this->session->has_userdata('user'))
    {
        //Pego os dados via post (AJAX)
        $data = $this->input->post();

            //Premissa que o usuário comum está logado
        $accepted = TRUE;

        $usuario_logado_pk = $this->session->user['id_user'];

        if($usuario_logado_pk != $data['pessoa_pk'])
        {
            if($this->session->user['is_superusuario'])
            {
                     //Utilizando o helper de autenticação de senha para realiar o insert
                $this->load->helper('Password_helper');
                $accepted = authenticate_operation($data['senha'],$this->session->user['password_user']);
            }

            if ($accepted) 
            {       
                $data_activate = array(
                    'funcionario_status' => 0,
                );

                $where = array(
                    'pessoa_fk' => $data['pessoa_pk'],
                    'organizacao_fk' => $this->session->user['id_organizacao']
                );

                $result = $this->model->update_status($data_activate, $where);

                if($result){
                    $this->response->set_code(Response::SUCCESS);
                    $this->response->set_data("Funcionário foi desativado com sucesso!");
                }
                else
                {
                    $this->response->set_code(Response::DB_ERROR_INSERT);
                    $this->response->set_data("Não foi possível desativar o funcionário no sistema");  
                }
            } 
            else 
            {
                $this->response->set_code(Response::UNAUTHORIZED);
                $this->response->set_data("Operação não autorizada! Senha informada incorreta.");
            }
        }
        else //se o usuario logado estiver tentando se desativar
        {
            $this->response->set_code(Response::UNAUTHORIZED);
            $this->response->set_data("Operação não autorizada!Não é possível desativar o próprio usuário logado.");
        }

        $this->response->send();
    } 
    else //se não tiver session de user 
    {
        redirect(base_url('access/index'));

    }
}

public function change_password(){
    if($this->session->has_userdata('user'))
    {
        //Pego os dados via post (AJAX)
        $data = $this->input->post();

        //Premissa que o usuário comum está logado
        $accepted = TRUE;

        if($this->session->user['is_superusuario'])
        {
                 //Utilizando o helper de autenticação de senha para realiar o insert
            $this->load->helper('Password_helper');
            $accepted = authenticate_operation($data['senha'],$this->session->user['password_user']);
        }

        if ($accepted) 
        {       
            $where = [
                'pessoa_fk' => $this->input->post('pessoa_fk'),
            ];

            $this->form_validation->set_rules(
                'new_password',
                'Senha',
                'trim|required|min_length[8]|max_length[128]'
            );


            if (!$this->form_validation->run()) {
                
                $new_password = hash(ALGORITHM_HASH, $this->input->post('new_password') . SALT);
                $this->response->set_code(Response::DB_ERROR_UPDATE);
                $this->response->set_data("A senha deve possuir 8 ou mais caracteres!");  

            }else{   
                if($this->pessoa_model->new_password($where, ['acesso_senha' => $new_password])) {
                    $this->response->set_code(Response::SUCCESS);
                    $this->response->set_data("Senha alterada com sucesso!");
                }
                else
                {
                    $this->response->set_code(Response::DB_ERROR_UPDATE);
                    $this->response->set_data("Não foi possível alterar a senha do funcionário no sistema");  
                }
            }
        } 
        else 
        {
            $this->response->set_code(Response::UNAUTHORIZED);
            $this->response->set_data("Operação não autorizada! Senha informada incorreta.");
        }

        $this->response->send();
    } 
    else 
    {
        redirect(base_url('access/index'));

    }

}



}
?>