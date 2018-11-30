<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once dirname(__FILE__) . "\Response.php";
require_once APPPATH . "core\MY_Controller.php";

class ContactWS extends MY_Controller
{

    /**
     * Objeto responsável por monstar a resposta da requisição
     *
     * @var Response
     */
    private $response;

    public function __construct()
    {
        date_default_timezone_set('America/Sao_Paulo');
        parent::__construct();
        exit();
    }

    public function restore_password()
    {
        $this->response = new Response();
        $this->load->library('form_validation');
        $this->load->library('send_email');
        $this->load->helper('attempt');

        $obj = json_decode(file_get_contents('php://input'));
        $data = [
            'email' => $obj->email,
        ];
        $this->form_validation->set_data($data);
        $this->form_validation->set_rules('email',
            'Email',
            'trim|required|valid_email|max_length[128]'
        );

        if ($this->form_validation->run() === true) {

            $attempt_response = verify_attempt_restore($this->input->ip_address(), $obj->email);

            if ($attempt_response === true) {
                $this->load->model('contato_model');

                $contact = [
                    'contato_email' => $obj->email,
                ];
                $contact_fetch = $this->contato_model->get($contact);

                if ($contact_fetch !== false) {
                    $this->load->model('recuperacao_model');
                    $this->recuperacao_model->delete($contact_fetch->pessoa_fk);
                    $restore = [
                        'pessoa_fk' => $contact_fetch->pessoa_fk,
                        'recuperacao_token' => hash(ALGORITHM_HASH, date('Y/m/d H:i:s') . SALT . $contact_fetch->pessoa_fk),
                        'recuperacao_tempo' => date('Y/m/d H:i:s'),
                    ];

                    if ($this->recuperacao_model->insert($restore) !== false) {
                        $attempt = [
                            'tentativa_ip' => $this->input->ip_address(),
                            'tentativa_tempo' => date('Y/m/d H:i:s'),
                        ];
                        $this->tentativa_model->insert($attempt);

                        $this->send_email->send_email('email/restore_password', 'Recuperação de Senha - Evidencia', base_url() . 'contact/reset_password/' . $restore['recuperacao_token'], $contact_fetch->contato_email);
                        $this->response->set_code(Response::SUCCESS);
                    } else {
						$this->response->set_code(Response::DB_ERROR_INSERT);
                    }
                } else {
                    $this->response->set_code(Response::NOT_FOUND);
                    $this->response->set_message('Contato não encontrado');
                }
            } else {
                $this->response->set_code(Response::FORBIDDEN);
                $this->response->set_message($attempt_response);
            }
        } else {
            $this->response->set_code(Response::BAD_REQUEST);
            $this->response->set_message(implode('<br>', $this->form_validation->errors_array()));
        }
        $this->response->send();
    }
}
