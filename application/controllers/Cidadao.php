<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."core/Response.php";   	
require_once APPPATH . "core/MyException.php";
/**
 * Acess Class
 *
 * @package     Evidencia
 * @category    Controller
 * @author      Pedro Cerdeirinha & Matheus Palmeira 
 */
class Cidadao extends CI_Controller {
	/**
	 * Construtor da Classe
	 * 
	 * Chama o construtor da classe pai
	 *
	 * @return void
	 */
	function __construct() 
	{
		parent::__construct();
		$this->response = new Response();
    }
    //--------------------------------------------------------------------------------

    /**
	 * Método padrão da classe Access
	 * 
	 * @return void
	 */
    public function index()
    {
    	$this->load->view("cidadao/consulta");
    }

	//--------------------------------------------------------------------------------


    public function getOs()
    {
        try {
            $this->response = new Response();
            $this->load->helper('exception');
            $this->load->model('Ordem_Servico_model', 'ordem_servico');
            
            $os = $this->ordem_servico->get_all(
                '*',
                ['ordem_servico_cod' => $_GET['protocol']],
                -1,
                -1,
                [
                    ['table' => 'servicos', 'on' => 'servicos.servico_pk = ordens_servicos.servico_fk'],
                    ['table' => 'prioridades', 'on' => 'prioridades.prioridade_pk = ordens_servicos.prioridade_fk'],
                    ['table' => 'procedencias', 'on' => 'procedencias.procedencia_pk = ordens_servicos.procedencia_fk'],
                    ['table' => 'situacoes as si', 'on' => 'si.situacao_pk = ordens_servicos.situacao_inicial_fk'],
                    ['table' => 'situacoes as sa', 'on' => 'sa.situacao_pk = ordens_servicos.situacao_atual_fk'],
                    ['table' => 'localizacoes', 'on' => 'localizacoes.localizacao_pk = ordens_servicos.localizacao_fk'],
                    ['table' => 'municipios', 'on' => 'municipios.municipio_pk = localizacoes.localizacao_municipio']
                ]
            );

            if (count($os) === 0) 
            {
                throw new MyException('Ordem de Serviço não encontrada.', 404);   
            }

            $this->response->add_data('os', $os);
            $this->response->add_data(
                'historico',
                $this->ordem_servico->get_historico($os[0]->ordem_servico_pk)
            );
            $this->response->add_data(
                'imagens',
                $this->ordem_servico->get_images_id($os[0]->ordem_servico_pk)
            );

            $this->response->send();
        } catch (MyException $e) {
            handle_my_exception($e);
        }
    }
}
