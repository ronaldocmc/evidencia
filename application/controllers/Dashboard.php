<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . "/Response.php";
require_once APPPATH."core/CRUD_Controller.php";


class Dashboard extends CRUD_Controller 
{

    function __construct() {
        parent::__construct();
        $this->load->library('session');
    }

    public function index() {
        $this->load->model('funcionario_model');
        $this->load->helper('date_helper');

        $organizacao = $this->session->user['id_organizacao'];       

        $this->load->model('dashboard_model');
        $dados['primeiro_nome'] = $this->primeiro_nome($this->session->user['name_user']);


        $this->session->set_flashdata('scripts',[
            0 => base_url('assets/js/dashboard/dashboard/index.js'),
        ]);
        load_view([
            0 => [
                'src'=>'dashboard/administrador/principal/dashboard',
                'params' =>  $dados
            ]
        ],'administrador');
    }

    private function primeiro_nome($nome){
        $array = explode(' ', $nome);
        return $array[0];
    }


    public function superusuario()
    {
        if ($this->session->user['id_user'] !== NULL)
        {

            // Atualizando a sessão, caso ele esteja vindo de uma organização
            if($this->session->user['id_organizacao'] !== 'admin')
            {
                $array_session = $this->session->user;
                $this->session->unset_userdata('user');
                
                $array_session['id_organizacao'] = 'admin';
                $array_session['name_organizacao'] = 'Administração';

                $this->session->set_userdata('user',$array_session);
            }


            load_view([
                0 => [
                    'src'=>'dashboard/superusuario/principal/home',
                    'params' =>  NULL
                ]
            ],'superusuario');
        }
        else
        {
            redirect('access','refresh');
        }
    }


    public function funcionario_administrador()
    {
        $this->load->model('funcionario_model');
        $this->load->helper('date_helper');

        $organizacao = $this->session->user['id_organizacao'];       

        $this->load->model('dashboard_model');


        $current_month = date("m");
        $current_year = date("Y");

        $ordens_por_mes = $this->dashboard_model->get_ordens_ano("01.".$current_month.".".$current_year, "31.".$current_month.".".$current_year,$organizacao);

        
        $ordens_por_semana = $this->dashboard_model->get_ordens_semana($organizacao);
        
        $ordens_por_setor_semana = $this->dashboard_model->get_ordens_setor_semana($organizacao);
        
        $ordens_por_bairro_ano = $this->dashboard_model->get_ordens_bairro_ano($organizacao);

        $ordens_por_tipo_semana = $this->dashboard_model->get_ordens_tipo_semana($organizacao);

        
        $ordens_finalizadas = $this->dashboard_model->get_ordens_hoje_finalizadas();
        $quantidade_de_ordens_finalizadas = $ordens_finalizadas[0]->count;


        $hoje = $this->dashboard_model->get_ordens_hoje();
        $quantidade_de_ordens_de_hoje = $hoje;

        //$quantidade_de_ordens_abertas = $quantidade_de_ordens_de_hoje - $quantidade_de_ordens_finalizadas;
        $quantidade_de_ordens_abertas = $this->dashboard_model->get_ordens_hoje();
        
        // $ultimas_ordens = $this->dashboard_model->get_ultimas_ordens($organizacao);
        
        $quantidade_ordens_mes = $ordens_por_mes['total'];
        $quantidade_ordens_semana = $ordens_por_semana['total'];

        unset($ordens_por_mes['total']);
        unset($ordens_por_semana['total']);
        
        $dia_da_semana = date('w');
        $mes = date("n");

        $quantidade_ordens_bairro = [];
        $quantidade_ordens_tipo = [];

        // echo "<pre>";
        // var_dump($ordens_por_bairro_ano);die();

        for($i = 0; $i < count($ordens_por_bairro_ano); $i++){
            array_push($quantidade_ordens_bairro,[
                'bairro' => $ordens_por_bairro_ano[$i]['bairro_nome'],
                'quantidade' =>$ordens_por_bairro_ano[$i]['total']
                ]);
        }

        usort($quantidade_ordens_bairro,function( $a, $b ) {
                 if( $a['quantidade'] == $b['quantidade'] ) return 0;
                 return ( ( $a['quantidade'] > $b['quantidade'] ) ? -1 : 1 );
        });

        for($i = 0; $i < count($ordens_por_tipo_semana); $i++){
            $quantidade_ordens_tipo[$ordens_por_tipo_semana[$i]['tipo_servico_nome']] = $ordens_por_tipo_semana[$i]['total'];
        }
            
        //Ordenando para o último dado do gráfico ser o do dia atual
        $fim = array_slice($ordens_por_semana,$dia_da_semana + 1);
        $inicio = array_slice($ordens_por_semana, 0,$dia_da_semana + 1);

        $ordens_por_semana = array_merge($fim,$inicio);

        $dados['quantidade_ordens_mes'] = $quantidade_ordens_mes;
        $dados['quantidade_ordens_semana'] = $quantidade_ordens_semana;
        $dados['ordens_servico_meses'] = $ordens_por_mes; 
        $dados['ordens_servico_semanas'] = $ordens_por_semana; 
        $dados['ordens_por_setor_semana'] = $ordens_por_setor_semana;
        $dados['quantidade_ordens_bairro'] = $quantidade_ordens_bairro;
        $dados['quantidade_ordens_tipo'] = $quantidade_ordens_tipo;

        $dados['hoje'] = array(
            "finalizados" => 0,
            "novas" => $quantidade_de_ordens_abertas
        );
        
        // Carrega a view principal do administrador, passando os dados obtidos


        $this->session->set_flashdata('scripts',[
            0 => base_url('assets/js/dashboard/dashboard/index.js'),
        ]);
        load_view([
            0 => [
                'src'=>'dashboard/administrador/principal/home',
                'params' =>  $dados
            ]
        ],'administrador');
    }
}


?>