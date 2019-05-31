<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'core/Response.php';
require_once APPPATH.'core/CRUD_Controller.php';
require_once APPPATH.'core/MyException.php';

class Dashboard extends CRUD_Controller 
{
    public $response; 

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->response = new Response();
    }

    private function get_tipos_servicos(){
        $this->load->model('dashboard_model', 'model');
        
        return $this->model->get_tipos_servicos_do_dia();

    }

    private function heatmap(){
        $response = array(
            'x' => [],
            'y' => [],
            'z' => []
        );

        $response['x'] = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'];

        $response['y'] = $this->get_revisores_array();
            

        $data = $this->model->get_heatmap();

        if($data == false){
            return false;
        }

        $response = $this->format_data_heatmap($data);

        return $response;
    }

    private function format_data_heatmap($data){
        $r = array(
            'x' => [],
            'y' => [],
            'z' => []
            );


        return $r;
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

    private function get_revisores_array(){
        $this->load->model('dashboard_model', 'model');
        $response = array();

        $revisores = $this->model->get_revisores_do_dia();
        foreach($revisores as $r){
            $arr = explode(' ', $r->nome);
            $response[] = $arr[0]; //pegando o primeiro nome
        }

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

            $this->session->set_flashdata('css',[
                0 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css'),
                1 => base_url('assets/vendor/icon-hover-effects/component.css'),
                2 => base_url('assets/vendor/icon-hover-effects/default.css'),
                3 => base_url('assets/css/dashboard.css')
            ]);

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

    public function get(){

        $this->load->model('funcionario_model');
        $this->load->model('Setor_model', 'setor'); 
        $this->load->model('ordem_servico_model', 'ordem_servico');
        $this->load->model('dashboard_model');     
        $this->load->model('Tipo_Servico_model', 'tipo_servico');

        $this->response->add_data('self', $this->getOrdersTable());
        $this->response->add_data('semana', $this->getOrdersByWeek());
        $this->response->add_data('semana_setores', $this->getOrdersBySector());
        $this->response->add_data('semana_tipos', $this->getOrdersByTypeService());
        $this->response->add_data('ano', $this->getOrdersByYear());
        $this->response->send();

    }

    private function getOrdersTable(){
       
        $start_week = date('Y-m-d');
        $date = strtotime($start_week);
        $d = strtotime("-7 day", $date);
        $end_week = date('Y-m-d', $d);

        $ordens_servico = $this->ordem_servico->get_home(
            $this->session->user['id_organizacao'],
            "ordens_servicos.ordem_servico_criacao BETWEEN '".$end_week."' AND '".$start_week."' AND ordens_servicos.ativo = 1"
        );
        return $ordens_servico;
    }

    private function getOrdersByWeek(){
        $organizacao = $this->session->user['id_organizacao'];  
        
        try{

            $ordens_semana_abertas = $this->dashboard_model->get_ordens_semana($organizacao, 1);
            $ordens_semana_finalizadas = $this->dashboard_model->get_ordens_semana($organizacao, 2);
            $ordens_semana_andamento = $this->dashboard_model->get_ordens_semana($organizacao, 5);
            $ordens_semana_repetidas = $this->dashboard_model->get_ordens_semana($organizacao, 4);
            $ordens_semana_nprocede = $this->dashboard_model->get_ordens_semana($organizacao, 3);

            $ordens_semana = [];

            array_push($ordens_semana,  $this->setNullasZero((array)$ordens_semana_abertas[0]));
            array_push($ordens_semana,  $this->setNullasZero((array)$ordens_semana_finalizadas[0])); 
            array_push($ordens_semana,  $this->setNullasZero((array)$ordens_semana_andamento[0]));
            array_push($ordens_semana,  $this->setNullasZero((array)$ordens_semana_repetidas[0]));
            array_push($ordens_semana,  $this->setNullasZero((array)$ordens_semana_nprocede[0]));

            return $ordens_semana;
            
        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        }
    }

    private function getOrdersBySector(){
        $organizacao = $this->session->user['id_organizacao'];   
        $data_chart = []; 
        $sector_names = [];
       
        try{
            $setores = $this->setor->get_all(
                'setores.setor_pk, setores.setor_nome',
                ['setores.organizacao_fk' => $organizacao],
                -1,
                -1
            );

            foreach($setores as $s){
                $data = $this->dashboard_model->get_ordens_setor($organizacao, $s->setor_pk);
                array_push($data_chart, $this->setNullasZero((array)$data[0]));
                array_push($sector_names, $s->setor_nome);
            }
            
            return [$sector_names, $data_chart];
            
        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        } 
    }

    private function getOrdersByTypeService(){
        $organizacao = $this->session->user['id_organizacao'];   
        
        $data_chart = []; 
        $type_services_names = [];
       
        try{
            $tipos_servicos= $this->tipo_servico->get_all(
                'tipos_servicos.tipo_servico_pk,tipos_servicos.tipo_servico_nome',
                ['departamentos.organizacao_fk' => $organizacao],
                -1,
                -1,
                [
                    ['table' => 'departamentos', 'on' => 'departamentos.departamento_pk = tipos_servicos.departamento_fk']
                ]
            );
            

            foreach($tipos_servicos as $s){
                $data = $this->dashboard_model->get_ordens_tipo_servico($organizacao, $s->tipo_servico_pk);
                array_push($data_chart, $data[0]->Total);
                array_push($type_services_names, $s->tipo_servico_nome);
            }
           
            return [$type_services_names, $data_chart];
            
        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        } 
    }

    private function getOrdersByYear(){

        $organizacao = $this->session->user['id_organizacao'];   
        $data = $this->dashboard_model->get_ordens_ano($organizacao);
        $data_chart =  $this->setNullasZero((array)$data[0]);
        
        return $data_chart;
    }

    private function setNullasZero($ordens_semana){
        $data = [];
        foreach($ordens_semana as $index => $val){
            
            if($val == NULL){
                array_push($data,0);
            }else{
                array_push($data, $val);
            }
        }
        
        return $data;
    }

    public function funcionario_administrador_antigo()
    {
        $this->load->model('funcionario_model');
        $this->load->helper('date_helper');

        $organizacao = $this->session->user['id_organizacao'];       

        $this->load->model('dashboard_model');


        $current_month = date("m");
        $current_year = date("Y");

        $ordens_por_mes = $this->dashboard_model->get_ordens_ano("01.01.".$current_year, "31.".$current_month.".".$current_year,$organizacao);

        
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