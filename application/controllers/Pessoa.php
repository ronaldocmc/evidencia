<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__) . "/Response.php";
require_once dirname(__FILE__) . "/Localizacao.php";

class Pessoa extends CI_Controller {
	public $response;
    public $CI;
    public $user_call = FALSE;

    function __construct() {
        if ($this->CI = & get_instance() === NULL)
        {
            parent::__construct();
            $this->CI = & get_instance();
            $this->user_call = TRUE; 
            $this->load->library('form_validation');
        }
        $this->response = new Response();
        $this->CI->load->model('pessoa_model');
        $this->CI->load->library('upload');
    }

    function index() {

    }

    public function profile()
    {
    	if ($this->CI->session->has_userdata('user')) {

            $where = [
                'populacao.pessoa_pk' => $this->CI->session->user['id_user'],
            ];
            $retorno['usuario'] = $this->CI->pessoa_model->get($where);

            $this->CI->session->set_flashdata('css', array(
                0 => base_url('assets/vendor/cropper/cropper.css'),
                1 => base_url('assets/vendor/input-image/input-image.css'),
                2 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.css'),
                3 => base_url('assets/css/modal_desativar.css'),
                4 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css'),
                5 => base_url('assets/css/loading_input.css')
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
                11 => base_url('assets/js/dashboard/pessoa/index.js'),
                12 => base_url('assets/vendor/select-input/select-input.js')
            ));

            load_view([
                0 => [
                    'src' => 'dashboard/commons/profile/home',
                    'params' => $retorno,
                ],
                1 => [
                    'src' => 'access/pre_loader',
                    'params' => null,
                ],
            ], $this->CI->session->user['is_superusuario']?'superusuario':'administrador');
        } else {
            $this->CI->load->view('errors/html/error_404');
        }
    }


    //Função que configura o form_validation do insert e do update
    public function check_form_validation()
    {

        $this->CI->load->library('form_validation');

        //Configurando as regras de validação do formulário
        $this->CI->form_validation->set_rules(
            'pessoa_nome',
            'Nome',
            'trim|required|min_length[4]|max_length[128]'
        );

        $this->CI->form_validation->set_rules(
            'pessoa_cpf',
            'CPF',
            'trim|required|regex_match[/[0-9].\-/]|exact_length[14]'
        );

        $this->CI->form_validation->set_rules(
            'contato_email',
            'Email',
            'trim|required|regex_match[/[a-zA-Z0-9_\-.+]+@[a-zA-Z0-9-]+/]|max_length[128]'
        );

        $this->CI->form_validation->set_rules(
            'contato_tel',
            'Telefone',
            'trim|max_length[14]'
        );

        $this->CI->form_validation->set_rules(
            'contato_cel',
            'Celular',
            'trim|max_length[15]'
        );
    }


    //Função que executa o upload da imagem de perfil do usuário
    public function upload_img()
    {

        //Definindo o nome da pasta de armazenamento do arquivo
        $path = "./assets/uploads/perfil_images/";

        //Definindo as configurações para o upload no CI
        $configUpload['upload_path'] = $path;
        $configUpload['allowed_types'] = '*';
        $configUpload['encrypt_name'] = true;
        $configUpload['file_name'] = hash(ALGORITHM_HASH, $this->CI->session->user['id_user'] . uniqid(rand(), true));

        $this->CI->upload->initialize($configUpload);

        // verificamos se o upload foi processado com sucesso
        if (!$this->CI->upload->do_upload('img')) {
            // em caso de erro retornamos os mesmos para uma variável e enviamos para a home
            // return $this->CI->upload->display_errors();
            return null;
        } else {
            //se correu tudo bem, recuperamos os dados do arquivo
            $data['dadosArquivo'] = $this->CI->upload->data();

            // definimos o path original do arquivo
            $arquivo['path'] = $path;
            $arquivo['name'] = $data['dadosArquivo']['file_name'];
            return $arquivo;
        }
    }


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
        if (!$image) 
        {
            $image = imagecreatefromstring(file_get_contents(base_url("/assets/uploads/perfil_images/" . $file['name'])));
        }

        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

        //o 3º argumento é a qualidade da imagem de 0 a 100
        imagejpeg($image_p, './assets/uploads/perfil_images/min/' . $file['name'], 50);
        // imagedestroy($image_p);

    }

    //Função que gera uma query para o insert, transforma um array em uma string
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

    //Função que verifica se já existe uma imagem de perfil do usuário
    public function is_imagem_set($id)
    {

        $retorno = $this->CI->pessoa_model->get_image($id);
        if ($retorno != false) {
            return true;
        } else {
            return false;
        }
    }

    //Função que realiza uma atualização de dados das pessoas já cadastradas 
    public function update()
    {

        if ($this->CI->session->has_userdata('user')) {
            $this->CI->form_validation->reset_validation();
            $this->check_form_validation();

            if ($this->CI->form_validation->run()) {
                $data_update = $this->CI->input->post();

                var_dump($data_update);
                die();


                // $local = new Localizacao();
                // $return_local = $local->insert();

                // if ($return_local->code != 200)
                // {
                //     return $return_local;
                // }
                // //Padronizando os dados para update
                // if (isset($return_local->data['id']))
                // {
                //     $data_endereco = 
                //     [
                //         'local_fk' => $return_local->data['id']
                //     ];
                // }
                // else
                // {
                //     $data_endereco = NULL;
                // }

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
                if (isset($_FILES['img'])) {
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

                        $setou = $this->CI->pessoa_model->insert_image($data_imagem);

                    }
                    $this->resize_img($file);
                }

                $retorno = $this->CI->pessoa_model->update($data_pessoa, $data_contato, $data_endereco, $file['name'], $data_update['pessoa_pk']);

                
                if( $this->CI->session->user['id_user'] == $data_update['pessoa_pk'])
                {
                	if ($file == null) 
                   {
                       $image_user = $this->CI->session->user['image_user'];
                       $image_user_min = $this->CI->session->user['image_user_min'];
                   }
                   else 
                   {
                       $image_user = base_url('/assets/uploads/perfil_images/' . $file['name']);
                       $image_user_min = base_url('/assets/uploads/perfil_images/min/' . $file['name']);
                   }

                   $userdata = [
                       'id_user' => $this->CI->session->user['id_user'],
                       'name_user' => $data_pessoa['pessoa_nome'],
                       'id_organizacao' => $this->CI->session->user['id_organizacao'],
                       'name_organizacao' => $this->CI->session->user['name_organizacao'],
                       'password_user' =>  $this->CI->session->user['password_user'],
                       'email_user' => $data_contato['contato_email'],
                       'is_superusuario' => $this->CI->session->user['is_superusuario'],
                       'image_user_min' => $image_user_min,
                       'image_user' => $image_user,
                   ];

                   $this->CI->session->set_userdata('user', $userdata);
               }

               if ($retorno['pessoa'] == true && $retorno['contato'] == true && $retorno['endereco']==true && $file['name'] == null) {
                $this->response->set_code(Response::SUCCESS);
                $this->response->set_data("Dados da Pessoa foram alterados com sucesso!");

            } else {
                    //var_dump($retorno['endereco']);
                if ($retorno['pessoa'] == true && $retorno['contato'] == true && $retorno['endereco']==true&& $retorno['imagem'] == true) {
                    $this->response->set_code(Response::SUCCESS);
                    $this->response->set_data("Dados da Pessoa foram alterados com sucesso!");
                } else {
                    $this->response->set_code(Response::DB_ERROR_INSERT);
                    $this->response->set_data("Não foi possível alterar os dados na Pessoa no sistema.");
                }
            }
        } else {
            $this->response->set_code(Response::BAD_REQUEST);
            $this->response->set_data($this->CI->form_validation->errors_array());
        }


        if ($this->user_call === TRUE)
        {
            $this->response->send();
        }
        else
        {

            return $this->response;
        }

        } else {
            redirect(base_url('access/index'));
        }
    }



    //Função que realiza a inserção de um nova pessoa 
public function insert()
{
    if ($this->CI->session->has_userdata('user')) {
        $this->check_form_validation();

        if ($this->CI->form_validation->run()) {

            $data_insert = $this->CI->input->post();

            $local = new Localizacao();
            $return_local = $local->insert();
            

            if ($return_local->code != 200)
            {
                return $return_local;
            }

                //Padronizando os dados para update
            if (isset($return_local->data['id']))
            {
                $data_endereco = 
                [
                    'local_fk' => $return_local->data['id']
                ];
            }
            else
            {
                $data_endereco = NULL;
            }
            $data_pessoa = array(
                'pessoa_nome' => $data_insert['pessoa_nome'],
                'pessoa_cpf' => $data_insert['pessoa_cpf'],
                'pessoas_status' => 1,
            );

            $data_contato = array(
                'contato_cel' => $data_insert['contato_cel'],
                'contato_email' => $data_insert['contato_email'],
                'contato_tel' => $data_insert['contato_tel'],
            );

            $file = null;

                //Efetuando o upload da imagem, retorna NULL se mal sucedido ou retorna o endereço a ser armazenado
            if (isset($_FILES['img'])) 
            {
                $file = $this->upload_img();
                if ($file !== null) 
                {
                    $this->resize_img($file);
                }
            }

            $retorno = $this->CI->pessoa_model->insert($data_pessoa, $data_contato,$data_endereco, $file['name']);

            if ($retorno['pessoa'] == true && $retorno['contato'] == true && $retorno['endereco']==true && $file['name'] == null) {
                $this->response->set_code(Response::SUCCESS);
                $this->response->set_data(['id' => $retorno['pessoa_pk']]);

            } else {
                if ($retorno['pessoa'] == true && $retorno['contato'] == true && $retorno['endereco']==true && $retorno['imagem'] == true) {
                    $this->response->set_code(Response::SUCCESS);
                    $this->response->set_data(['id' => $retorno['pessoa_pk']]);
                } else {
                    $this->response->set_code(Response::DB_ERROR_INSERT);
                    $this->response->set_data("Não foi possível inserir os dados da Pessoa no sistema.");
                }
            }
        } else {
            $this->response->set_code(Response::BAD_REQUEST);
            $this->response->set_data($this->CI->form_validation->errors_array());
        }


        if ($this->user_call === TRUE)
        {
            $this->response->send();
        }
        else
        {
            return $this->response;
        }

    } else {
        redirect(base_url('access/index'));
    }
}


    //Função que atualiza a senha de uma pessoa logado
public function update_password()
{

    $this->load->helper('password');
    if ($this->session->has_userdata('user')) {
        $this->load->library('form_validation');

        $this->form_validation->set_rules(
            'new_password',
            'Senha',
            'trim|required|min_length[8]|max_length[128]'
        );

        $this->form_validation->set_rules(
            'old_password',
            'Login',
            'trim|required|min_length[8]|max_length[128]'
        );

        $this->form_validation->set_rules(
            'confirm_new_password',
            'Senha2',
            'trim|required|min_length[8]|max_length[128]|matches[new_password]'
        );

        if ($this->form_validation->run()) {
            $data_password = $this->input->post();
            $success = authenticate_operation($data_password['old_password'],$this->session->user['password_user']);

            if ($success) {

                $where = [
                    'pessoa_fk' => $this->session->user['id_user'],
                ];
                $new_password = hash(ALGORITHM_HASH, $data_password['new_password'] . SALT);
                if ($this->pessoa_model->new_password($where, ['acesso_senha' => $new_password])) {

                    //atualizando senha da sessão
                    $user = $this->session->user;
                    $this->session->unset_userdata('user');
                    $user['password_user'] = $new_password;
                    $this->session->set_userdata('user', $user);

                    $this->response->set_code(Response::SUCCESS);
                    $this->response->set_data("Senha alterada com sucesso!");
                } else {
                    $this->response->set_code(Response::DB_ERROR_INSERT);
                    $this->response->set_data("Não foi possível aterar a senha");
                }

            } else {
                $this->response->set_code(Response::UNAUTHORIZED);
                $this->response->set_data("Operação não autorizada! Senha de autenticação informada está incorreta.");
            }
        } else {
            $this->response->set_code(Response::BAD_REQUEST);
            $this->response->set_data($this->form_validation->errors_array());
        }

        $this->response->send();
    } else {
        redirect(base_url('access/index'));
    }
}
}


?>