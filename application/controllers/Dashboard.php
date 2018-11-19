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

    public function funcionario_administrador() {
        //$this->load->model('funcionario_model');
        $this->load->helper('date_helper');

        $organizacao = $this->session->user['id_organizacao'];       

        $this->load->model('dashboard_model');
        $dados['primeiro_nome'] = $this->primeiro_nome($this->session->user['name_user']);

        $dados['cards'] = $this->get_cards();
        $dados['charts'] = $this->get_charts();
        $dados['ordens_em_execucao'] = $this->get_ordens_em_execucao();
        $dados['funcionarios'] = $this->get_funcionarios();

        $this->session->set_flashdata('css',[
            0 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css'),
            1 => base_url('assets/vendor/icon-hover-effects/component.css'),
            2 => base_url('assets/vendor/icon-hover-effects/default.css'),
        ]);


        $this->session->set_flashdata('scripts',[
            0 => base_url('assets/js/dashboard/dashboard/dashboard.js'),
            1 => base_url('assets/vendor/masks/jquery.mask.min.js'),
            2 => base_url('assets/vendor/datatables/datatables.min.js'),
            3 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js'),
            4 => base_url('assets/js/utils.js'),
            5 => base_url('assets/js/constants.js'),

        ]);
        load_view([
            0 => [
                'src'=>'dashboard/administrador/principal/dashboard',
                'params' =>  $dados
            ]
        ],'administrador');
    }

    private function contains($string, $substring){
        return strpos($string, $substring) !== false;
    }

    private function get_funcionarios(){
        $this->load->model('dashboard_model', 'model');
        $funcionarios = array();


        $res = $this->model->get_funcionarios();

        foreach($res as $f){
            $id_relatorio = $f->relatorio_id;

            $setores = $this->model->get_setores_do_relatorio($id_relatorio);
            $servicos = $this->model->get_servicos_do_relatorio($id_relatorio);


            $string_setores  = $this->get_string($setores, 'explode', ' ', 1);
            $string_servicos = $this->get_string($servicos);


            $performance = $this->get_performance($id_relatorio);

            $ultima_ordem = $this->get_ultima_ordem($id_relatorio);

            $status = $this->get_status($performance);

            $funcionarios[] = array(
                'nome'        => $f->nome,
                'performance' => $performance,
                'setores'     => $string_setores,
                'servicos'    => $string_servicos,
                'ultima_ordem'=> $ultima_ordem,
                'status'      => $status

            );
        }

        return $funcionarios;
    }

    private function get_status($performance){

       $status = array();

       if ($this->contains($performance['label'], '100')){
            $status = array(
                'label' => 'Disponível',
                'class' => 'status--process'
            );
        } else {
            $status = array(
                'label' => 'Ocupado',
                'class' => 'status--denied'
            );
        }

        return $status;
    }

    private function get_ultima_ordem($id_relatorio){
        $this->load->model('dashboard_model', 'model');

        $ultima_ordem = array();

        $data = $this->model->get_data_ultima_ordem($id_relatorio);
        if($data == false){
            return array(
                'tooltip' => '',
                'label'   => '---'
            );
        }


        
        $date_dif = strtotime(date('Y-m-d H:i:s')) - strtotime($data);

        $date_dif = $this->model->date_dif($data);

            if($date_dif > 60){ //é em horas
                $value = round($date_dif/60);
                $format = 'H:i';
                $time = 'horas';
                if($value > 24){ //está em dias
                    $value = round($value/24);
                    $format = 'd/m/Y H:i';
                    $time = 'dias';
                }
            }else { //é em minutos
                $value = $date_dif;
                 $format = 'H:i';
                $time = 'minutos';
            }

            $tooltip = date($format, strtotime($data));

            $ultima_ordem    = array(
                'tooltip' => $tooltip,
                'label'   => $value.' '.$time.' atrás'
            );

            return $ultima_ordem;

    }

    private function get_performance($id_relatorio){
        $this->load->model('dashboard_model', 'model');
        $performance = array();


        $porcentagem = 0;
        $total_ordens = $this->model->get_ordens($id_relatorio);
        $ordens_concluidas = $this->model->get_ordens_concluidas($id_relatorio);

        $porcentagem = $this->porcentagem($ordens_concluidas, $total_ordens).' %';
        $tooltip = $ordens_concluidas.'/'.$total_ordens.' (concluídas/total)';

        $performance = array(
            'tooltip' => $tooltip,
            'label'   => $porcentagem
        );

        return $performance;
    }


    private function get_ordens_em_execucao(){
        $this->load->model('dashboard_model', 'model');

        return $this->model->get_ordens_em_execucao();
    }

    private function get_charts(){
        $this->load->model('dashboard_model', 'model');

        $charts = array();
        $labels = array();
        $charts['doughnut'] = array();

        $em_andamento = count($this->model->get_ordens_em_execucao());
        $finalizadas  = $this->model->get_ordens_hoje_finalizadas();


        $labels[] = array(
            'color' => 'blue',
            'label' => 'em andamento',
            'data'  => ''.($em_andamento - $finalizadas)
        );
        $labels[] = array(
            'color' => 'red',
            'label' => 'concluidas',
            'data'  => ''.$finalizadas
        );
        
        $charts['doughnut'] = array(
         'title'   => 'Concluídas %',
         'percent' => $this->get_ordens_hoje(),
         'label'   => $labels,
         'data'    => $this->get_data($labels),
         'labels'  => $this->get_labels($labels)
     );

        return $charts;
    }

    private function get_cards() {

        $cards = array();
        $ordens_concluidas = $this->get_ordens_concluidas();
        $revisores = $this->get_revisores();
        $setores = $this->get_setores();

        $cards[] = array(
            'color' => 'green',
            'title' => $this->get_ordens_hoje(),
            'label' => 'novas',
            'icon'  => 'fa-plus'
        );

        $cards[] = array(
            'color' => 'orange',
            'title' => $ordens_concluidas['porcentagem'],
            'label' => 'concluídas',
            'icon'  => 'fa-tasks',
            'tooltip' => $ordens_concluidas['text']
        );

        $cards[] = array(
            'color' => 'red',
            'title' => $revisores['quantidade'],
            'label' => 'revisores',
            'icon'  => 'fa-users',
            'tooltip' => $revisores['nomes']
        );

        $cards[] = array(
            'color' => 'blue',
            'title' => $setores['quantidade'],
            'label' => 'setores',
            'icon'  => 'fa-map-marker-alt',
            'tooltip' => $setores['nomes']
        );

        return $cards;

    }


    private function primeiro_nome($nome){
        $array = explode(' ', $nome);
        return $array[0];
    }

    private function get_data($labels){
        $data = '';
        for($i = 0; $i < count($labels); $i++){
            if($i == count($labels) -1){ //é o último
                $data.= $labels[$i]['data'];
            }else { // não é o último
                $data.= $labels[$i]['data'].',';
            }
        }
        return $data;
    }

    private function get_labels($labels){
        $data = '';
        for($i = 0; $i < count($labels); $i++){
            if($i == count($labels) -1){ //é o último
                $data.= $labels[$i]['label'];
            }else { // não é o último
                $data.= $labels[$i]['label'].',';
            }
        }
        return $data;
    }


    private function get_setores() {
        $this->load->model('dashboard_model', 'model');
        $response = array(
            'quantidade' => '',
            'nomes' => ''
        );

        $setores = $this->model->get_setores_do_dia();
        $response['quantidade'] = count($setores);
        $response['nomes'] = $this->get_string($setores, 'explode', ' ', 1);

        return $response;
    }

    private function get_revisores() {
        $this->load->model('dashboard_model', 'model');

        $response = array(
            'quantidade' => '',
            'nomes' => ''
        );

        $revisores = $this->model->get_revisores_do_dia();

        $response['quantidade'] = count($revisores);
        $response['nomes'] = $this->get_string($revisores, 'explode');

        return $response;
    }

    private function get_string($array, $method = NULL, $condition = ' ', $piece = 0){
     $string = '';

     for($i = 0; $i < count($array); $i++){
        if($i == 0){ //se for o primeiro registro
        }else if($i == count($array) -1){ //se for o ultimo registro:
            $string.= ' e ';
        }else{ // se tiver no meio
            $string.= ', ';
        }

        if($method == NULL){
            $string.= $array[$i]->nome;
        } else {
            if($method == 'explode') {
             $explode_array = explode($condition, $array[$i]->nome);

             $string.= $explode_array[$piece]; 
         }
     }
 }

 return $string;
}

private function get_ordens_concluidas(){
    $this->load->model('dashboard_model', 'model');

    $response = array(
            'porcentagem' => '', //será retornado como string
            'text' => ''
        );
    $ordens_em_execucao = $this->model->get_ordens_em_execucao();
    if($ordens_em_execucao != false){
        $em_andamento = count($this->model->get_ordens_em_execucao()); 
        $finalizadas  = $this->model->get_ordens_hoje_finalizadas();
    } else {
        $em_andamento = 0;
        $finalizadas = 0;
    }




    $response['porcentagem'] = $this->porcentagem($finalizadas,$em_andamento).' %';
    $response['text'] = $finalizadas.'/'.$em_andamento.' (concluídas/total)';

    return $response;
}

private function porcentagem($dividendo, $divisor) {
    if($divisor == 0){
        return 0;
    } else {
            $porcentagem = $dividendo/$divisor; //intervalo de 0 a 1

            $porcentagem = round($porcentagem, 4);

            $porcentagem = $porcentagem * 100;
            
            $porcentagem = $porcentagem.'';
            $porcentagem = str_replace('.', ',', $porcentagem);

            return $porcentagem;
        }
    }

    private function get_ordens_hoje(){
        $this->load->model('dashboard_model', 'model');

        return $this->model->get_ordens_hoje();
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


    public function funcionario_administrador_antigo()
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
        $quantidade_de_ordens_finalizadas = $ordens_finalizadas;


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