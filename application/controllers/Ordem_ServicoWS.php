<?php

/**
 * AccessWS.
 *
 * @author      Pietro
 */
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH.'core/Response.php';
require_once dirname(__FILE__).'/Localizacao.php';
require_once APPPATH.'core/MY_Controller.php';
require_once APPPATH.'core/MyException.php';

class Ordem_ServicoWS extends MY_Controller
{
    private $response;

    public function __construct()
    {
        $this->response = new Response();
        parent::__construct($this->response);

        date_default_timezone_set('America/Sao_Paulo');
        exit();
    }

    /**
     * Destrutor da classe.
     */
    public function __destruct()
    {
    }

    public function index()
    {
    }

    /**
     * Método responsável por receber os dados de uma ordem de serviço e fazer a inserção.
     */
    public function post()
    {
        $this->load->model('Ordem_Servico_model', 'ordem_servico');
        $this->load->model('Servico_model', 'servico_model');
        $this->load->model('Funcionario_model', 'funcionario_model');
        $this->load->model('Localizacao_model', 'localizacao');

        $this->load->helper('exception');
        $this->load->helper('token_helper');
        $this->load->helper('images');
        $this->load->library('form_validation');

        try {
            $obj = json_decode(file_get_contents('php://input'));
            $this->load->model('Localizacao_model', 'localizacao');

            $headers = apache_request_headers();

            $_POST = get_object_vars($obj);
            $_POST['img'] = isset($obj->img) ? $obj->img : null;

            $this->ordem_servico->fill();

            $this->localizacao->add_lat_long(
                $this->input->post('localizacao_lat'),
                $this->input->post('localizacao_long')
            );
            $this->localizacao->fill();

            $this->ordem_servico->config_form_validation();
            $this->localizacao->config_form_validation();

            $token_decodificado = json_decode(token_decrypt($headers[TOKEN]));

            $this->begin_transaction();

            $city = $this->localizacao->get_cities([
                'municipio_nome' => $this->input->post('localizacao_municipio'),
            ]);
            if (count($city) === 0) {
                throw new MyException('Cidade inválida. Consulte o suporte em sua organização.', 400);
            }
            $this->localizacao->__set('localizacao_municipio', $city[0]->municipio_pk);

            $this->ordem_servico->__set('localizacao_fk', $this->localizacao->insert());
            $this->ordem_servico->__set('funcionario_fk', $token_decodificado->id_funcionario);

            $id = $this->ordem_servico->insert_os($token_decodificado->id_empresa);

            $paths = upload_img(
                [
                    'id' => $id,
                    'path' => 'PATH_OS',
                    'is_os' => true,
                    'situation' => $this->ordem_servico->__get('situacao_atual_fk'),
                ],
                [0 => $this->input->post('img')]//talvez seja interessante a view já mandar no formato de array mesmo quando é uma.
            );

            $this->ordem_servico->insert_images($paths, $id, $token_decodificado->id_empresa);

            $this->end_transaction();

            $this->response->set_code(Response::SUCCESS);
            $this->response->send();
        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        }
    }

    public function get()
    {
        // Destrói a sessão que ele cria automaticamente, pois tava dando erro
        $this->session->sess_destroy();

        try {
            isset($_GET['id']) ? $id = $_GET['id'] : $id = null;

            $this->load->model('Ordem_Servico_model', 'ordem_servico');

            $obj = apache_request_headers();

            // Decripta o token
            $empresa = get('id_empresa', $obj[TOKEN]);

            if ($id != null) {
                $where['ordens_servicos.ordem_servico_pk'] = $id;

                $historico = $this->ordem_servico->get_historico($id);
                $imagens = $this->ordem_servico->get_images_id($id);

                $this->response->add_data('historico', $historico);
                $this->response->add_data('imagens', $imagens);
            }

            $where['ordens_servicos.ativo'] = 1;

            $ordens_servico = $this->ordem_servico->get_home($empresa, $where, 50);

            $this->response->add_data('ordens', $ordens_servico);

            $this->response->set_code(Response::SUCCESS);
            $this->response->send();
        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        }
    }

    public function put()
    {
        $this->load->model('Ordem_Servico_model', 'ordem_servico');

        $this->load->helper('exception');
        $this->load->helper('token_helper');
        $this->load->helper('images');
        $this->load->library('form_validation');

        try {
            $obj = json_decode(file_get_contents('php://input'));
            $headers = apache_request_headers();

            $token_decodificado = json_decode(token_decrypt($headers[TOKEN]));
            $_POST = get_object_vars($obj);
            $_POST['img'] = isset($obj->img) ? $obj->img : null;
            $_POST['ordem_servico_comentario'] = isset($obj->ordem_servico_comentario) ? $obj->ordem_servico_comentario : 'Nenhum comentário adicionado.';

            $this->ordem_servico->__set('ordem_servico_comentario', $_POST['ordem_servico_comentario']);
            $this->ordem_servico->__set('situacao_atual_fk', $_POST['situacao_atual_fk']);
            $this->ordem_servico->__set('ordem_servico_pk', $_POST['ordem_servico_pk']);

            $paths = upload_img(
                [
                    'id' => $_POST['ordem_servico_pk'],
                    'path' => 'PATH_OS',
                    'is_os' => true,
                    'situation' => $this->ordem_servico->__get('situacao_atual_fk'),
                ],
                [0 => $this->input->post('img')]
            );

            $this->begin_transaction();

            $this->ordem_servico->handle_historico($_POST['ordem_servico_pk']);

            $this->ordem_servico->update();

            if ($paths !== null && !empty($paths)) {
                $this->ordem_servico->insert_images($paths, $_POST['ordem_servico_pk'], $token_decodificado->id_empresa);
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
}
