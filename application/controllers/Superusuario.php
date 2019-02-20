<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once dirname(__FILE__) . "/Response.php";
require_once dirname(__FILE__) . "/Contact.php";
require_once APPPATH . "core/CRUD_Controller.php";
class Superusuario extends CRUD_Controller
{

    public $response_super;

    public function __construct()
    {
        parent::__construct();

        date_default_timezone_set('America/Sao_Paulo');
        $this->response_super = new Response();
        $this->load->library('upload');
        $this->load->model('Super_model', 'model');
        $this->load->helper('Password');

    }

    //Dashboard principal do superusuário (index)

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
        $superusuarios = $this->model->get_all_superusers_without_me($pk);

        load_view([
            0 => [
                'src' => 'dashboard/superusuario/superusuario/home',
                'params' => ['superusuarios' => $superusuarios],
            ]
        ], 'superusuario');
    }

    /**
     * Função que gera uma query para o insert, transforma um array em uma string
     *
     * @param Array com os dados para inserir um superusuario
     * @return String com os parametros separados por virgula
     */
    public function array_to_string($data)
    {
        $query = "";
        foreach ($data as $value) {
            $query .= '"' . $value . '",';
        }
        $size = strlen($query);
        $query = substr($query, 0, $size - 1);

        return $query;

    }

    /**
     * Método responsável por redimensionar a imagem para 50 x 50
     *
     * @param String com o nome do arquivo
     * @return null
     */
    public function resize_img($file)
    {
        ini_set("gd.jpeg_ignore_warning", 1);

        // pegando as dimensoes reais da imagem, largura e altura
        list($width, $height) = getimagesize(base_url('/assets/uploads/perfil_images/' . $file['name']));

        //setando a largura da miniatura
        $new_width = 50;
        //setando a altura da miniatura
        $new_height = 50;

        //gerando a a miniatura da imagem
        $image_p = imagecreatetruecolor($new_width, $new_height);
        // "/assets/uploads/perfil_images/".$file['name'].'.jpeg'
        $image = @ImageCreateFromJpeg(base_url("/assets/uploads/perfil_images/" . $file['name']));
        if (!$image) {
            $image = imagecreatefromstring(file_get_contents(base_url("/assets/uploads/perfil_images/" . $file['name'])));
        }

        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

        //o 3º argumento é a qualidade da imagem de 0 a 100
        imagejpeg($image_p, './assets/uploads/perfil_images/min/' . $file['name'], 50);
        imagedestroy($image_p);

    }

    /**
     * Função que executa o upload da imagem de perfil do usuário
     *
     * @return null ou o caminho do arquivo
     */
    public function upload_img()
    {

        //Definindo o nome da pasta de armazenamento do arquivo
        $path = "./assets/uploads/perfil_images/";

        //Definindo as configurações para o upload no CI
        $configUpload['upload_path'] = $path;
        $configUpload['allowed_types'] = '*';
        $configUpload['encrypt_name'] = true;
        $configUpload['file_name'] = hash(ALGORITHM_HASH, $this->session->user['id_user'] . uniqid(rand(), true));

        $this->upload->initialize($configUpload);

        // verificamos se o upload foi processado com sucesso
        if (!$this->upload->do_upload('img_su')) {
            // em caso de erro retornamos os mesmos para uma variável e enviamos para a home
            // return $this->upload->display_errors();
            return null;
        } else {
            //se correu tudo bem, recuperamos os dados do arquivo
            $data['dadosArquivo'] = $this->upload->data();

            // definimos o path original do arquivo
            $arquivo['path'] = $path;
            $arquivo['name'] = $data['dadosArquivo']['file_name'];
            return $arquivo;
        }
    }

    public function donwload_img($download_url)
    {
        // recuperamos o terceiro segmento da url, que é o nome do arquivo
        $arquivo = $this->uri->segment(3);
        // recuperamos o segundo segmento da url, que é o diretório
        $diretorio = $this->uri->segment(2);
        // definimos original path do arquivo
        $arquivoPath = './uploads/' . $diretorio . "/" . $arquivo;
        // forçamos o download no browser
        // passando como parâmetro o path original do arquivo
        force_download($arquivoPath, null);
    }

    //Função que configura o form_validation do insert e do update
    public function check_form_validation()
    {

        $this->load->library('form_validation');

        //Configurando as regras de validação do formulário
        $this->form_validation->set_rules(
            'pessoa_nome',
            'Nome',
            'trim|required|min_length[4]|max_length[128]'
        );

        $this->form_validation->set_rules(
            'pessoa_cpf',
            'CPF',
            'trim|required|regex_match[/^\d{3}\.\d{3}\.\d{3}\-\d{2}$/]|exact_length[14]|valid_cpf'
        );

        $this->form_validation->set_rules(
            'contato_email',
            'Email',
            'trim|required|regex_match[/[a-zA-Z0-9_\-.+]+@[a-zA-Z0-9-]+/]|max_length[128]'
        );

        $this->form_validation->set_rules(
            'contato_tel',
            'Telefone',
            'trim|max_length[14]'
        );

        $this->form_validation->set_rules(
            'contato_cel',
            'Celular',
            'trim|max_length[15]'
        );

        $this->form_validation->set_rules(
            'senha',
            'Senha',
            'trim|required|min_length[8]|max_length[128]'
        );
    }

    /**
     * Função que realiza a inserção de um novo superusuario
     *
     * @param Requisição GET com os dados de insert
     * @return Objeto response contendo o código e a mensagem
     */
    public function insert()
    {
        if ($this->session->has_userdata('user')) {
            //Iniciando a configuração do form_validation dos campos
            $this->check_form_validation();

            if ($this->form_validation->run() === true) {

                //Recebendo os dados enviados pelo super usuário para cadastro de novo super
                $new_user = $this->input->post();
                //Funçao que valida a operação do usuário
                $sucess = authenticate_operation($new_user['senha'], $this->session->user['password_user']);

                //Se o usuário tiver informado a senha correta a inserção pode ser executada
                if ($sucess !== false) {
                    //Efetuando o upload da imagem, retorna NULL se mal sucedido ou retorna o endereço a ser armazenado
                    if (isset($_FILES['img_su'])) 
                    {
                        //Se ele enviou, então realizamos o upload. Caso os dados estejam duplicados esses dados são removidos em unlick
                        $file = $this->upload_img();
                    } else {
                        //Se o usuário não enviou nenhuma imagem por opção própria
                        $file['path'] = "";
                        $file['name'] = "";
                    }

                    if ($file !== null) {

                        //Padronizando os dados para inserção (Caso deseja-se não trabalhar mais com a procedure está pronto)
                        if($file['path'] !== "" && $file['name'] !== "")
                        {
                            $this->resize_img($file);
                        }

                        $data_pessoa = array(
                            'pessoa_nome' => $new_user['pessoa_nome'],
                            'pessoa_cpf' => $new_user['pessoa_cpf'],
                            'pessoas_status' => 1,
                            'contato_cel' => $new_user['contato_cel'],
                            'contato_email' => $new_user['contato_email'],
                            'contato_tel' => $new_user['contato_tel'],
                            'imagem_caminho' => $file['name'],
                        );

                        //Transformando array numa string que será a query (TRABALHANDO COM PROCEDURES)
                        $data_query = $this->array_to_string($data_pessoa);

                        //Enviando a query para a inserção (CHAMADA A PROCEDURE)
                        $result = $this->model->insert_super($data_query);

                        //Se a inserção no banco foi bem sucedida
                        if ($result['code'] == 0) {

                            //Se a inserção foi feita com sucesso então enviaremos um e-mail para o novo super. Para isso padronizamos os dados da pk do novo com um token que foi gerado
                            $pessoa_pk = $this->model->get_pessoa_pk($data_pessoa['pessoa_cpf']);
                            $token = hash(ALGORITHM_HASH, (date("Y-m-d H:i:s") . $pessoa_pk . SALT));

                            $data_recuperacao = array(
                                'pessoa_fk' => $pessoa_pk,
                                'recuperacao_token' => $token,
                            );

                            //Realizamos o insert na tabela recuperacao_senha para que o novo usuário
                            $this->load->model('recuperacao_model');

                            if ($this->recuperacao_model->insert($data_recuperacao)) {
                                $email = $this->new_superusuario_email($data_recuperacao['recuperacao_token'], $data_pessoa['contato_email']);

                                if ($email) {
                                    $this->response_super->set_code(Response::SUCCESS);
                                    $this->response_super->set_data(
                                        array(
                                            'mensagem' => "Um e-mail foi enviado para " . $data_pessoa['contato_email'] . ", nele será possível configurar as informações de acesso do novo Superusuário.",
                                            'pessoa_fk' => $pessoa_pk,
                                        )
                                    );
                                } else {
                                    $this->response_super->set_code(Response::SERVER_FAIL);
                                    $this->response_super->set_data($email);
                                    $this->model->reset($pessoa_pk);
                                }
                            } else {
                                $this->response_super->set_code(Response::DB_ERROR_INSERT);
                                $this->response_super->set_data("Não foi possível inserir o novo Superusuário no sistema.");
                            }

                        } else //Se não ocorreram alguns dos seguintes erros: Dados duplicados, erro desconhecido, upload falhou
                        {
                            if ($result['code'] == 1062) {

                                $this->response_super->set_code(Response::DB_DUPLICATE_ENTRY);
                                $this->response_super->set_data($result['message']);
                                //Caso dados já estejam no sistema, remove o upload
                                if ($data_pessoa['imagem_caminho'] !== "") {
                                    unlink($file['path'] . $file['name']);
                                }
                            } //Se não a inserção no banco de dados apresentou um erro desconhecido
                            else {
                                $this->response_super->set_code(Response::DB_ERROR_INSERT);
                                $this->response_super->set_data($result['message']);
                            }
                        }
                        //Se não imagem sem upload no servidor
                    } else {
                        $this->response_super->set_code(Response::BAD_REQUEST);
                        $this->response_super->set_data("Não foi possível cadastrar a imagem inserida.");
                    }
                    //Senão a senha de autenticação está incorreta
                } else {
                    $this->response_super->set_code(Response::UNAUTHORIZED);
                    $this->response_super->set_data("Operação não autorizada! Senha informada incorreta.");
                }
            } else {
                $this->response_super->set_code(Response::BAD_REQUEST);
                $this->response_super->set_data($this->form_validation->errors_array());
            }
            //Enviando resposta da requisição AJAX
            $this->response_super->send();
        } else {
            redirect(base_url('access/index'));
        }
    }

    /**
     * Método que envia um e-mail para o email cadastrado para um novo usuário, esse email contém acesso a um token
     *
     * @param String com o token, @param String com o email do destinatário
     * @return String contendo os erros do envio ou true
     */
    public function new_superusuario_email($token, $email_user)
    {
        $this->load->library('send_email');
        return $this->send_email->send_email('email/first_login.php', 'Criar acesso - Evidencia', base_url() . 'contact/first_login/' . $token, $email_user);
    }

    //Função que verifica se já existe uma imagem de perfil do usuário
    public function is_imagem_set($id)
    {

        $retorno = $this->model->get_image($id);
        if ($retorno != false) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Função que realiza uma atualização de dados dos superusuarios já cadastrados
     *
     * @param Requisição GET com os dados do update
     * @return Objeto Response contendo o código e a mensagem
     */
    public function update()
    {
        if ($this->session->has_userdata('user')) {
            $this->check_form_validation();

            if ($this->form_validation->run()) {
                $data_update = $this->input->post();
                


                //Funçao que valida a operação do usuário
                $sucess = authenticate_operation($this->input->post('senha'), $this->session->user['password_user']);



                if ($sucess) {
                    //Padronizando os dados para update
                    $data_pessoa = array(
                        'pessoa_nome' => $data_update['pessoa_nome'],
                        'pessoa_cpf' => $data_update['pessoa_cpf'],
                        'pessoas_status' => 1,
                    );

                    $data_contato = array(
                        'contato_cel' => $data_update['contato_cel'],
                        'contato_email' => $data_update['contato_email'],
                        'contato_tel' => $data_update['contato_tel'],
                    );

                    $file = null;

                    //Efetuando o upload da imagem, retorna NULL se mal sucedido ou retorna o endereço a ser armazenado
                    if (isset($_FILES['img_su'])) {
                        //Se ele enviou, então realizamos o upload. Caso os dados estejam duplicados esses dados são removidos
                        $file = $this->upload_img();

                        //Verificando se já existe alguma imagem, caso não existe a operação a ser feita é um insert não update
                        $set = $this->is_imagem_set($data_update['pessoa_pk']);
                        if (!$set) {
                            //Padronizando os dados para efetuar o insert
                            $data_imagem = array(
                                'imagem_caminho' => $file['name'],
                                'pessoa_fk' => $data_update['pessoa_pk'],
                            );

                            //Chamando a função do model que faz o insert, retorna se deu certo ou não

                            $setou = $this->model->insert_image($data_imagem);
                        }
                        $this->resize_img($file);
                    }

                    $retorno = $this->model->update($data_pessoa, $data_contato, $file['name'], $data_update['pessoa_pk']);

                    // if($data_update['pessoa_pk'] == $this->session->user['id_user'])
                    // {
                    //     if ($file == null) {
                    //         $image_user = $this->session->user['image_user'];
                    //         $image_user_min = $this->session->user['image_user_min'];
                    //     } else {
                    //         $image_user = base_url('/assets/uploads/perfil_images/' . $file['name']);
                    //         $image_user_min = base_url('/assets/uploads/perfil_images/min/' . $file['name']);
                    //     }

                    // $userdata = [
                    //     'id_user' => $this->session->user['id_user'],
                    //     'password_user' => $this->session->user['password_user'],
                    //     'name_user' => $data_pessoa['pessoa_nome'],
                    //     'id_organizacao' => $this->session->user['id_organizacao'],
                    //     'name_organizacao' => $this->session->user['name_organizacao'],
                    //     'email_user' => $data_contato['contato_email'],
                    //     'is_superusuario' => true,
                    //     'image_user_min' => $image_user_min,
                    //     'image_user' => $image_user,
                    // ];

                    // $this->session->set_userdata('user', $userdata);

                    if ($retorno['pessoa'] == true && $retorno['contato'] == true && $file['name'] == null) {
                        $this->response_super->set_code(Response::SUCCESS);
                        $this->response_super->set_data("Dados do Superusuário foram alterados com sucesso!");

                    } else {
                        if ($retorno['pessoa'] == true && $retorno['contato'] == true && $retorno['imagem'] == true) {
                            $this->response_super->set_code(Response::SUCCESS);
                            $this->response_super->set_data("Dados do Superusuário foram alterados com sucesso!");
                        } else {
                            $this->response_super->set_code(Response::DB_ERROR_INSERT);
                            $this->response_super->set_data("Não foi possível alterar os dados Superusuário no sistema.");
                        }
                    }
                 
                // else {
                //     $this->response_super->set_code(Response::UNAUTHORIZED);
                //     $this->response_super->set_data("Operação não autorizada! Senha informada incorreta.");
                // }
            } else {
                $this->response_super->set_code(Response::BAD_REQUEST);
                $this->response_super->set_data($this->form_validation->errors_array());
            }

            $this->response_super->send();
        } else {
            redirect(base_url('access/index'));
        }
    }
}

    /**
     * Função que atualiza a senha de um superusuario logado
     *
     * @param Requisição com os dados para alterar a senha
     * @return Objeto Response contendo o código e a mensagem
     */
    public function update_password()
    {
        if ($this->session->has_userdata('user')) {
            $this->load->library('form_validation');

            $this->form_validation->set_rules(
                'old_password',
                'Senha Antiga',
                'trim|required|min_length[8]|max_length[128]'
            );

            $this->form_validation->set_rules(
                'new_password',
                'Nova Senha',
                'trim|required|min_length[8]|max_length[128]'
            );

            $this->form_validation->set_rules(
                'confirm_new_password',
                'Confirmar Nova Senha',
                'trim|required|min_length[8]|max_length[128]|matches[new_password]'
            );

            if ($this->form_validation->run()) {
                $data_password = $this->input->post();
                $success = authenticate_operation($data_password['old_password'], $this->session->user['password_user']);

                if ($success) {

                    $where = [
                        'pessoa_fk' => $this->session->user['id_user'],
                    ];

                    if ($this->model->new_password($where, ['acesso_senha' => hash(ALGORITHM_HASH, $data_password['new_password'] . SALT)])) {
                        $new_session = $this->session->user;
                        $new_session['password_user'] = hash(ALGORITHM_HASH, $data_password['new_password'] . SALT);
                        $this->session->set_userdata('user', $new_session);

                        $this->response_super->set_code(Response::SUCCESS);
                        $this->response_super->set_data("Senha alterada com sucesso!");
                    } else {
                        $this->response_super->set_code(Response::DB_ERROR_INSERT);
                        $this->response_super->set_data("Não foi possível aterar a senha");
                    }

                } else {
                    $this->response_super->set_code(Response::UNAUTHORIZED);
                    $this->response_super->set_data("Operação não autorizada! Senha de autenticação informada está incorreta.");
                }
            } else {
                $this->response_super->set_code(Response::BAD_REQUEST);
                $this->response_super->set_data($this->form_validation->errors_array());
            }

            $this->response_super->send();
        } else {
            redirect(base_url('access/index'));
        }
    }

    /**
     * Função que desativa um superusuario cadastrado no sistema
     *
     * @param Requisição com o id do usuario que sera desativado
     * @return Objeto Response contendo o código e a mensagem
     */
    public function deactivate()
    {
        if ($this->session->has_userdata('user')) {

            $sucess = authenticate_operation($this->input->post('senha'), $this->session->user['password_user']);

            if ($sucess !== false) {
                //Recebendo o id do super usuário a ser desativado
                $id = $this->input->post('pessoa_pk');

                $usuario_logado_pk = $this->session->userdata['user']['id_user'];

                //se a pessoa estiver tentando enviar uma requisição para desativar o próprio usuário logado:
                if($id == $usuario_logado_pk){
                    $this->response_super->set_code(Response::UNAUTHORIZED);
                    $this->response_super->set_data("Operação não autorizada!Não é possível desativar o próprio usuário logado.");
                }
                else{

                        //Padronizando os dados para fazer o update
                        $data_super = array(
                            'usuario_status' => 0,
                        );
                        //Chamando a função no model que realiza a desativação do usuáio (update status)
                        $result = $this->model->update_super($data_super, $id);

                        //Se a operação foi bem sucedida
                        if ($result == 1) {
                            $this->response_super->set_code(Response::SUCCESS);
                            $this->response_super->set_data("Superusuário foi desativado com sucesso!");
                        } else {
                            $this->response_super->set_code(Response::DB_ERROR_INSERT);
                            $this->response_super->set_data("Não foi possível desativar Superusuário no sistema.");
                        }
               
                    
                } //fecha o else se o usuário que está desativando é diferente do usuário desativado:

            } //fecha o se o success diferente de false

            $this->response_super->send();
        } else {
            redirect(base_url('access/index'));
        }
    }

    /**
     * Função que ativa um superusuario cadastrado no sistema
     *
     * @param Requisição com o id do usuario que sera desativado
     * @return Objeto Response contendo o código e a mensagem
     */
    public function activate()
    {
        if ($this->session->has_userdata('user')) {
            $sucess = authenticate_operation($this->input->post('senha'), $this->session->user['password_user']);

            if ($sucess !== false) {
                //Carregabndo o model para efetuar operação

                //Recebendo o id do super usuário a ser desativado
                $id = $this->input->post('pessoa_pk');

                //Padronizando os dados para fazer o update
                $data_super = array(
                    'usuario_status' => 1,
                );
                //Chamando a função no model que realiza a desativação do usuáio (update status)
                $result = $this->model->update_super($data_super, $id);

                //Se a operação foi bem sucedida
                if ($result == 1) {
                    $this->response_super->set_code(Response::SUCCESS);
                    $this->response_super->set_data("Superusuário foi ativado com sucesso!");
                } else //Caso contrário
                {
                    $this->response_super->set_code(Response::DB_ERROR_INSERT);
                    $this->response_super->set_data("Não foi possível ativar Superusuário no sistema.");
                }
            } else {
                $this->response_super->set_code(Response::UNAUTHORIZED);
                $this->response_super->set_data("Operação não autorizada! Senha informada incorreta.");
            }
            $this->response_super->send();
        } else {
            redirect(base_url('access/index'));
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
            'trim|required|min_length[8]|max_length[128]|is_unique[acessos.acesso_login]'
        );

        $this->form_validation->set_rules(
            'confirme-senha',
            'Senha2',
            'trim|required|min_length[8]|max_length[128]|matches[acesso_senha]'
        );

        if ($this->form_validation->run() === true) {
            //Realizando o load do model recuperacao_mmodel
            $this->load->model('recuperacao_model', 'rmodel');

            $where = array(
                'recuperacao_token' => $this->input->post('token'),
            );

            //Chamando função get para realizar a operação e encontrar o usuário especifico do acesso
            $retorno = $this->rmodel->get($where);
            if ($retorno) //Se o token existir então inserimos o novo acesso
            {
                //Padronizando dados recebidos via post para inserção
                $data_insert = array(
                    'pessoa_fk' => $this->input->post('pessoa_fk'),
                    'acesso_login' => $this->input->post('acesso_login'),
                    'acesso_senha' => hash(ALGORITHM_HASH, $this->input->post('acesso_senha') . SALT),
                );

                if ($this->rmodel->insert_acesso($data_insert)) {
                    $row = $this->rmodel->delete_token($where);

                    if ($row == 1) {
                        //Se a inserção foi bem sucedida então response é positiva e a remoção feita
                        $this->response_super->set_code(Response::SUCCESS);
                        $this->response_super->set_data("Novo acesso foi cadastrado com sucesso!");
                    } else {
                        $this->response_super->set_code(Response::DB_ERROR_INSERT);
                        $this->response_super->set_data("Usuário foi cadastrado no entanto o token não foi apagado");
                    }
                } else {
                    $this->response_super->set_code(Response::DB_ERROR_INSERT);
                    $this->response_super->set_data("Não foi possível inserir novo acesso do superusuário");
                }
            } else {
                $this->load->view('errors/html/error_404');
            }
        } else //Se os formulários não foram preenchidos corretamente (form_validation)
        {
            $this->response_super->set_code(Response::BAD_REQUEST);
            $this->response_super->set_data($this->form_validation->errors_array());
        }

        $this->response_super->send();
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
                    'src' => 'dashboard/superusuario/superusuario/modal_first_login',
                    'params' => $retorno,
                ],
                1 => [
                    'src' => 'access/pre_loader',
                    'params' => null,
                ],
            ], 'superusuario', false);
        } else //Se não carregamos a view de erro.
        {
            $this->load->view('errors/html/error_404');
        }
    }

    //Função que atualiza os dados cadastrais do superusuário atual logado no sistema.
    public function profile()
    {

        if ($this->session->has_userdata('user')) {
            $where = [
                'acessos.pessoa_fk' => $this->session->user['id_user'],
            ];
            $retorno['usuario'] = $this->model->get_login($where);

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
                11 => base_url('assets/js/dashboard/superusuario/profile.js'),
            ));

            load_view([
                0 => [
                    'src' => 'dashboard/superusuario/superusuario/profile',
                    'params' => $retorno,
                ]
            ], 'superusuario');
        } else {
            $this->load->view('errors/html/error_404');
        }

    }

}
