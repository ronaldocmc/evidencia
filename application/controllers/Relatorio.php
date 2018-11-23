<?php 


if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__)."/Response.php");   

require_once APPPATH."core/CRUD_Controller.php"; 
require_once dirname(__FILE__) . "/Response.php";
require_once 'vendor/autoload.php';

class Relatorio extends CRUD_Controller 
{

    function __construct() 
    {
        parent::__construct();
        $this->load->library('form_validation');
    }


    function mapa()
    {
        $this->load->model('Prioridade_model', 'prioridade_model');
        $this->load->model('Situacao_model', 'situacao_model');
        $this->load->model('Departamento_model', 'departamento_model');
        $this->load->model('Servico_model', 'servico_model');
        $this->load->model('Tipo_Servico_model', 'tipo_servico_model');
        $this->load->helper('form');

        $prioridades = $this->prioridade_model->get([
            'organizacao_fk' => $this->session->user['id_organizacao']
        ]);
        $situacoes = $this->situacao_model->get([
            'organizacao_fk' => $this->session->user['id_organizacao']
        ]);
        $servicos = $this->servico_model->get([
            'situacoes.organizacao_fk' => $this->session->user['id_organizacao']
        ]);
        $departamentos = $this->departamento_model->get([
            'departamentos.organizacao_fk' => $this->session->user['id_organizacao']
        ]);
        $tipos_servicos = $this->tipo_servico_model->get([
            'departamentos.organizacao_fk' => $this->session->user['id_organizacao']
        ]);

        $this->session->set_flashdata('css',[
            0 => base_url('assets/css/modal_desativar.css'),
            1 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.css'),
            2 => base_url('assets/css/loading_input.css'),
            3 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css'),
            4 => base_url('assets/css/modal_map.css'),
            5 => base_url('assets/css/timeline.css'),
            6 => base_url('assets/css/style_card.css'),
        ]);

        $this->session->set_flashdata('scripts',[
            0 => base_url('assets/vendor/masks/jquery.mask.min.js'),
            1 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.js'),
            2 => base_url('assets/js/masks.js'),
            3 => base_url('assets/vendor/bootstrap-multistep-form/jquery.easing.min.js'),
            4 => base_url('assets/vendor/datatables/datatables.min.js'),
            5 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js'),
            6 => base_url('assets/js/utils.js'),
            7 => base_url('assets/js/constants.js'),
            8 => base_url('assets/js/jquery.noty.packaged.min.js'),
            9 => base_url('assets/js/dashboard/ordem_servico/relatorio.js'),
            10 => base_url('assets/vendor/select-input/select-input.js'),
            11 => base_url('assets/js/localizacao.js'),
        ]);

        $this->session->set_flashdata('mapa',[
            0 => true
        ]);

        load_view([
            0 => [
                'src' => 'dashboard/administrador/mapa/home',
                'params' => [
                    'prioridades' => $prioridades,
                    'situacoes' => $situacoes,
                    'servicos' => $servicos,
                    'departamentos' => $departamentos,
                    'tipos_servicos' => $tipos_servicos
                ]
            ],
            1 => [
                'src' => 'access/pre_loader',
                'params' => null,
            ]
        ],'administrador');    
    }


    public function relatorios_gerais()
    {
        $this->load->model('departamento_model');
        $this->load->model('setor_model');
        $this->load->model('situacao_model');
        $this->load->model('prioridade_model');
        $this->load->model('servico_model');

        $departamentos = $this->departamento_model->get([
            'departamentos.organizacao_fk' => $this->session->user['id_organizacao']
        ]);

        $setores = $this->setor_model->get([
            'organizacao_fk' => $this->session->user['id_organizacao']
        ]);
        
        $situacoes = $this->situacao_model->get([
            'organizacao_fk' => $this->session->user['id_organizacao']
        ]);

        $prioridades = $this->prioridade_model->get([
            'organizacao_fk' => $this->session->user['id_organizacao']
        ]);

        $servicos = $this->servico_model->get([
            'situacoes.organizacao_fk' => $this->session->user['id_organizacao']
        ]);

        $this->session->set_flashdata('scripts',[
            0 => base_url('assets/js/constants.js'),
            1 => base_url('assets/js/jquery.noty.packaged.min.js'),
            2 => base_url('assets/js/dashboard/relatorio/relatorios_gerais.js'),
        ]);

        load_view([
            0 => [
                'src' => 'dashboard/administrador/ordem_servico/relatorios_gerais',
                'params' => [
                    'prioridades' => $prioridades,
                    'situacoes' => $situacoes,
                    'servicos' => $servicos,
                    'departamentos' => $departamentos,
                    'setores' => $setores
                ]
            ]
        ],'administrador');    
    }

    public function relatorio_especifico()
    {
        $this->load->model('departamento_model');
        $this->load->model('setor_model');
       // $this->load->model('procedencia_model');
        $this->load->model('situacao_model');
        $this->load->model('prioridade_model');
        $this->load->model('tipo_servico_model');
        $this->load->model('servico_model');
        $this->load->model('estado_model');
        $this->load->model('municipio_model');
        $this->load->model('bairro_model');
        $this->load->helper('form');

        $departamentos = $this->departamento_model->get([
            'departamentos.organizacao_fk' => $this->session->user['id_organizacao']
        ]);

        $setores = $this->setor_model->get([
            'organizacao_fk' => $this->session->user['id_organizacao']
        ]);

        // Pegar procedencias

        $situacoes = $this->situacao_model->get([
            'organizacao_fk' => $this->session->user['id_organizacao']
        ]);

        $prioridades = $this->prioridade_model->get([
            'organizacao_fk' => $this->session->user['id_organizacao']
        ]);

        $tipos_servicos = $this->tipo_servico_model->get([
            'departamentos.organizacao_fk' => $this->session->user['id_organizacao']
        ]);

        $servicos = $this->servico_model->get([
            'situacoes.organizacao_fk' => $this->session->user['id_organizacao']
        ]);

        $estados = $this->estado_model->get();

        $municipios = $this->municipio_model->get();

        $bairros = $this->bairro_model->get();


        $this->session->set_flashdata('css',[
            0 => base_url('assets/css/modal_desativar.css'),
            1 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.css'),
            2 => base_url('assets/css/loading_input.css'),
            3 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css'),
            4 => base_url('assets/css/modal_map.css'),
            5 => base_url('assets/css/timeline.css'),
        ]);

        $this->session->set_flashdata('scripts',[
            0 => base_url('assets/vendor/masks/jquery.mask.min.js'),
            1 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.js'),
            2 => base_url('assets/js/masks.js'),
            3 => base_url('assets/vendor/bootstrap-multistep-form/jquery.easing.min.js'),
            4 => base_url('assets/vendor/datatables/datatables.min.js'),
            5 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js'),
            6 => base_url('assets/js/utils.js'),
            7 => base_url('assets/js/constants.js'),
            8 => base_url('assets/js/jquery.noty.packaged.min.js'),
            9 => base_url('assets/js/dashboard/relatorio/index.js'),
            10 => base_url('assets/vendor/select-input/select-input.js'),
            11 => base_url('assets/js/localizacao.js'),
        ]);

        load_view([
            0 => [
                'src' => 'dashboard/administrador/ordem_servico/relatorio_especifico',
                'params' => [
                    'prioridades' => $prioridades,
                    'situacoes' => $situacoes,
                    'servicos' => $servicos,
                    'departamentos' => $departamentos,
                    'setores' => $setores,
                    'tipos_servicos' => $tipos_servicos,
                    'estados' => $estados,
                    'municipios' => $municipios,
                    'bairros' => $bairros,
                    'procedencias' => null,
                    'ordens_servico' => null
                ]
            ],
            1 => [
                'src' => 'access/pre_loader',
                'params' => null,
            ]
        ],'administrador');    
    }


    public function verifica_form_validation()
    {

    	$this->form_validation->set_rules(
    		'departamento_fk',
    		'departamento_fk',
    		'required'
    	);

    	$this->form_validation->set_rules(
    		'setor_fk',
    		'setor_fk',
    		'required'
    	);

    	$this->form_validation->set_rules(
    		'procedencia_fk',
    		'procedencia_fk',
    		'required'
    	);

    	$this->form_validation->set_rules(
    		'situacao_fk',
    		'situacao_fk',
    		'required'
    	);

    	$this->form_validation->set_rules(
    		'prioridade_fk',
    		'prioridade_fk',
    		'required'
    	);

    	$this->form_validation->set_rules(
    		'tipo_servico_fk',
    		'departamento_fk',
    		'required'
    	);

    	$this->form_validation->set_rules(
    		'servico_fk',
    		'departamento_fk',
    		'required'
    	);

    	$this->form_validation->set_rules(
    		'data_criacao',
    		'data_criacao',
    		'required'
    	);

    	$this->form_validation->set_rules(
    		'data_fin',
    		'data_fin',
    		'required'
    	);

    	$this->form_validation->set_rules(
    		'hr_inicial',
    		'hr_inicial',
    		'required'
    	);

    	$this->form_validation->set_rules(
    		'hr_final',
    		'hr_final',
    		'required'
    	);

    	$this->form_validation->set_rules(
    		'estado_pk',
    		'estado_pk',
    		'required'
    	);

    	$this->form_validation->set_rules(
    		'municipio_pk',
    		'municipio_pk',
    		'required'
    	);

    	$this->form_validation->set_rules(
    		'bairro_pk',
    		'bairro_pk',
    		'required'
    	);

    	if($this->form_validation->run())
    	{
    		return true;
    	}
    	else 
    	{
    		return false;
    	}
    }


    public function gera_relatorio_geral($relatorio, $filtro, $situacao) 
    {
        $this->form_validation->set_data([
            'relatorio' => $relatorio,
            'filtro' => $filtro,
            'situacao' => $situacao
        ]);

        $this->form_validation->set_rules(
            'relatorio',
            'relatorio',
            'trim|required|alpha'
        );

        $this->form_validation->set_rules(
            'filtro',
            'filtro',
            'trim|required'
        );

        $this->form_validation->set_rules(
            'situacao',
            'situacao',
            'trim|required'
        );


        if (true)
        {
            $this->load->model('ordem_servico_model');

            $ordens_servicos = $this->ordem_servico_model->get_ids_os($this->session->user['id_organizacao']);

            if(!$ordens_servicos)
            {
                $view = "<h4>Não há ordens de serviços em sua empresa</h4>";
                return;
            }

            $where['relatorio'] = $relatorio;
            $where['situacao'] = $situacao;

            switch ($where['relatorio']) 
            {
                case 'data':
                $where['qtd_dias'] = $filtro;
                break;

                case 'setor':
                $where['setor'] = $filtro;
                break;

                case 'departamento':
                $where['departamento'] = $filtro;
                break;

                case 'servico':
                $where['servico'] = $filtro;
                break;
            }

            $data['ordens'] = $this->ordem_servico_model->get_os_relatorio($where);

            $data['data'] = date('d/m/Y');
            $data['empresa'] = 'Prudenco';

            $view = $this->load->view('pdf/relatorio_os',$data,true);
        }
        else
        {  
            $view = "<h4>Erro ao processar o relatório, informe a equipe técnica o código 48</h4>";
        }

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($view);
        $mpdf->Output();
    }



    public function novo_relatorio()
    {
        $this->load->model('Servico_model', 'servico_model');
        $this->load->model('Tipo_Servico_model', 'tipo_servico_model');
        $this->load->model('Setor_model', 'setor_model');
        $this->load->model('Funcionario_model', 'funcionario_model');

        $this->session->flashdata('css', [
            0 => base_url('assets/css/modal_desativar.css')
        ]);

        $this->session->set_flashdata('scripts',[
            0 => base_url('assets/js/constants.js'),
            1 => base_url('assets/js/jquery.noty.packaged.min.js'),
            2 => base_url('assets/js/dashboard/relatorio/relatorios_gerais.js'),
            3 => base_url('assets/js/utils.js')
        ]);

        load_view([
            0 => [
                'src' => 'dashboard/administrador/relatorio/novo_relatorio',
                'params' => [
                    'setores' => $this->setor_model->get(),
                    'tipos_servicos' => $this->tipo_servico_model->get(),
                    'servicos' => $this->servico_model->get(),
                    //pegamos os motoristas de caminhão
                    'motoristas_de_caminhao' => $this->funcionario_model->get(['funcionarios_funcoes.funcao_fk'=>'6']),
                    'message' => $this->session->flashdata('error'),
                ]
            ]
        ],'administrador');    
    } 

    public function insert_novo_relatorio()
    {
        $this->load->model('relatorio_model');
        $response = new Response();


        //VERIFICANDO SE EXISTE UM RELATÓRIO PARA O FUNCIONÁRIO QUE ESTÁ EM ANDAMENTO:
        $relatorios_em_andamento = $this->relatorio_model->get_objects(
            ['status' => 0, 'funcionario_fk' => $this->input->post('funcionario_fk')]);
        //ou seja, se existir relatórios em andamento:
        if(count($relatorios_em_andamento) > 0){ 
            $response->set_code(Response::BAD_REQUEST);
            $response->set_data(['message' => 'Há um relatório em andamento para este funcionário que necessita ser finalizado.']); 
            $response->send();
            die();
        }

        //pegamos os filtros que o usuário marcou na página anterior: qual(is) os setores, qual(is) os tipos de serviço e qual o funcionário responsável.
        $filtro = $this->input->post();

        $message = $this->valida_filtro($filtro);
        if($message == "true"){
        //criamos o relatório e suas respectivas ordens de serviço, de acordo com o filtro.
            $resposta = $this->create_relatorio($filtro);  

        //$response recebe o ID do relatório se deu tudo certo, ou a mensagem do erro.
            if(is_int($resposta)){
                $id_relatorio = $resposta;
                //redirect('relatorio/detalhes_relatorio/'.$id_relatorio); 
                $response->set_code(Response::SUCCESS);
                $response->set_data([
                    'id' => $id_relatorio, 
                    'message' => 'Relatório criado com sucesso!<br>Aguarde enquanto estamos recarregando a página ...'
                ]); 
            }else{
                //$this->session->set_flashdata('error', $resposta);
                //redirect('relatorio/novo_relatorio');
                $response->set_code(Response::BAD_REQUEST);
                $response->set_data(['message' => $resposta]); 
            }

        }else{
            //$this->session->set_flashdata('error', $message);
            //redirect('relatorio/novo_relatorio');
            $response->set_code(Response::BAD_REQUEST);
            $response->set_data(['message' => $message]); 
        }

        $response->send();


    }

    public function get_string_filtro_data($filtro_data){
        return 'Relatório contendo as ordens de serviço emitidas do dia '.date('d/m/Y', strtotime($filtro_data->filtros_relatorios_data_inicio)).' até '.date('d/m/Y', strtotime($filtro_data->filtros_relatorios_data_fim));
    }

    public function get_string_filtro_setores($filtro_setores){
       $string = '';

       for($i = 0; $i < count($filtro_setores); $i++){
        if($i == 0){ //se for o primeiro registro
        }else if($i == count($filtro_setores) -1){ //se for o ultimo registro:
            $string.= ' e ';
        }else{ // se tiver no meio
            $string.= ', ';
        }
        $string.= $filtro_setores[$i]->setor_nome;
    }

    return $string;
}

public function get_string_filtro_tipos_servicos($filtro_tipos_servicos){
   $string = '';

   for($i = 0; $i < count($filtro_tipos_servicos); $i++){
        if($i == 0){ //se for o primeiro registro
        }else if($i == count($filtro_tipos_servicos) -1){ //se for o ultimo registro:
            $string.= ' e ';
        }else{ // se tiver no meio
            $string.= ', ';
        }
        $string.= $filtro_tipos_servicos[$i]->tipo_servico_nome;
    }

    return $string;
}


public function detalhes_relatorio($id_relatorio)
{
    $this->load->model('funcionario_model');
    $this->load->model('relatorio_model');

    $relatorio = $this->relatorio_model->get_relatorio($id_relatorio);
    if($relatorio)
    {

        //pegamos o funcionário:
        $funcionario = $this->funcionario_model->get(['funcionario_pk' => $relatorio->funcionario_fk])[0];
        $funcionarios = $this->funcionario_model->get(['funcao_fk' => $funcionario->funcao_pk]);

        for($i = 0 ; $i < count($funcionarios); $i++){
            if($funcionarios[$i]->funcionario_pk == $funcionario->funcionario_pk){
                array_splice($funcionarios, $i, 1);
                //unset($funcionarios[$i]);
            }
        }


        $ordens_servicos = $this->get_ordens_relatorio($id_relatorio);

        //var_dump($ordens_servicos); die();

        //arrumando a data:
        if($ordens_servicos != false){
            foreach($ordens_servicos as $os){
                $os->data_criacao = date('d/m/Y H:i:s', strtotime($os->data_criacao));

                //Se não tiver sido entregue, vamos mostrar: Não Finalizado, se tiver, vamos printar a situação atual
                if($os->status_os == 2){
                    $os->status_os_string = 'Não Finalizado';
                }else{
                    $os->status_os_string = $os->situacao_atual;
                }
            }
        }


        $filtro_data = $this->relatorio_model->get_filtro_relatorio_data($id_relatorio);
        $filtro_setores = $this->relatorio_model->get_filtro_relatorio_setores($id_relatorio);
        $filtro_tipos_servicos = $this->relatorio_model->get_filtro_relatorio_tipos_servicos($id_relatorio);

        //criar o método que preenche o filtro data
        $string_filtros['data'] = $this->get_string_filtro_data($filtro_data);

        //criar um método para preecnher os filtros:
        $string_filtros['setor'] = $this->get_string_filtro_setores($filtro_setores);

        $string_filtros['tipos_servicos'] = $this->get_string_filtro_tipos_servicos($filtro_tipos_servicos);

        $this->session->set_flashdata('css', [

            0 => base_url('assets/css/modal_desativar.css'),

            1 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.css'),

            2 => base_url('assets/css/loading_input.css'),

            3 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css'),

            4 => base_url('assets/css/modal_map.css'),

        ]);

        $this->session->set_flashdata('scripts', [

            0 => base_url('assets/vendor/masks/jquery.mask.min.js'),

            1 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.js'),

            2 => base_url('assets/js/masks.js'),

            3 => base_url('assets/vendor/bootstrap-multistep-form/jquery.easing.min.js'),

            4 => base_url('assets/vendor/datatables/datatables.min.js'),

            5 => base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js'),

            6 => base_url('assets/js/utils.js'),

            7 => base_url('assets/js/constants.js'),

            8 => base_url('assets/js/jquery.noty.packaged.min.js'),

            9 => base_url('assets/vendor/select-input/select-input.js'),

            10 => base_url('assets/js/dashboard/relatorio/detalhe-relatorio.js'),

        ]);

        $this->session->set_flashdata('mapa',[
            0 => true
        ]);

        load_view([
            0 => [
               'src' => 'dashboard/administrador/relatorio/detalhe_relatorio',
               'params' => [
                'ordens_servicos' => $ordens_servicos,
                'funcionario' => $funcionario,
                'funcionarios' => $funcionarios,
                'relatorio' => $relatorio,
                'filtros' => $string_filtros
            ]
        ]
    ],'administrador');    

    }
    else
    {
        $response = new Response();
        $response->set_code(Response::NOT_FOUND);
        $response->set_data("Relatório não encontrado."); 
        $response->send();

    } 
}

private function validateDate($date) {
  $tempDate = explode('-', $date);
  // checkdate(month, day, year)
  return checkdate($tempDate[1], $tempDate[2], $tempDate[0]);
}

private function valida_filtro($filtro){

    if(isset($filtro['setor'])){
        if(isset($filtro['tipo'])){
            if($this->validateDate($filtro['data_inicial'])){
                if($this->validateDate($filtro['data_final'])){

                    $initial_time = strtotime($filtro['data_inicial']);
                    $final_time = strtotime($filtro['data_final']);

                    if($initial_time <= $final_time){
                        $message = "true";
                    }else{
                        $message = "A data inicial deve ser menor ou igual a data final.";  
                    }
                }else{
                    $message = "Data final inválida.";
                }
            }else{
                $message = "Data inicial inválida.";
            }   

        }else{
            $message = "Marque pelo menos um tipo de serviço";
        }
    }else{
        $message = 'Marque pelo menos um setor.';
    }


    return $message;
}


public function count_os()
{   
    $response = new Response();
    $filtro = $this->input->post();
    $message = $this->valida_filtro($filtro);

    if($message == "true")
    {
        $this->load->model('Ordem_Servico_model', 'ordem_servico_model');

        $ordens_servicos = $this->ordem_servico_model->get_os_novo_relatorio($filtro);
        $ordens_servicos_em_aberto = [];
        foreach($ordens_servicos as $os)
        {
            if($os->situacao_atual_pk == '1')
            {
                $ordens_servicos_em_aberto[] = $os;
            }

        }
        $ordens_servicos = $ordens_servicos_em_aberto;

        $response->set_data(count($ordens_servicos));
    }
    else
    {
        $response->set_code(Response::BAD_REQUEST);
        $response->set_data(['message' => $message]); 
    }
    $response->send();
    return;
}


private function create_relatorio($filtro)
{

    set_time_limit(180);

    $this->load->model('Ordem_Servico_model', 'ordem_servico_model');
    $this->load->model('Relatorio_model', 'relatorio_model');
    $this->load->model('Funcionario_model', 'funcionario_model');
    $this->load->model('Historico_model', 'historico_model');

    $resposta = $this->valida_filtro($filtro);

    // vamos filtrar as OS que pertencerão a este relatório.
    $ordens_servicos = $this->ordem_servico_model->get_os_novo_relatorio($filtro);
    $ordens_servicos_em_aberto = [];
    foreach($ordens_servicos as $os)
    {
        if($os->situacao_atual_pk == '1')
        {
            $ordens_servicos_em_aberto[] = $os;
        }

    }
    $ordens_servicos = $ordens_servicos_em_aberto;

    //vamos bloquear a criação de relatórios sem ordens de serviço.
    if(count($ordens_servicos) == 0)
    {
        return "Não é possível criar um relatório sem ordens de serviço.";
    }

    $id_relatorio = $this->relatorio_model->insert(['funcionario_fk' => $filtro['funcionario_fk']]);

    //Vamos inserir os filtros escolhidos pelo usuário:
    $this->relatorio_model->insert_filtro_data($filtro['data_inicial'], $filtro['data_final'], $id_relatorio);

    foreach($filtro['setor'] as $setor)
    {
        $this->relatorio_model->insert_filtro_setor($id_relatorio, $setor);
    }

    foreach($filtro['tipo'] as $tipo)
    {
        $this->relatorio_model->insert_filtro_tipo($id_relatorio, $tipo);
    }

    $funcionario = $this->funcionario_model->get(['funcionario_pk' => $filtro['funcionario_fk']])[0];

    // vamos atribuir apenas as ordens de serviço que estiverem com a situação atual == 1, ou seja, que estão em ABERTO.
    foreach($ordens_servicos as $os)
    {
        $tem_imagem = false;

        //UMA ORDEM DEVE PERTENCER A APENAS UM RELATÓRIO DO MESMO DIA.
        //TEMOS QUE VERIFICAR SE A ORDEM JÁ NÃO ESTÁ ATRIBUÍDA A UM RELATÓRIO HOJE.
        $this->relatorio_model->insert_relatorios_os(
            array(
                'relatorio_fk' => $id_relatorio,
                'os_fk' => $os->ordem_servico_pk
            )
        );

        // Pegamos todos os históricos para ver qual deles tem a última foto
        $historicos = $this->historico_model->get_all_historico_decrescente($os->ordem_servico_pk);

        foreach ($historicos as $h)
        {
            if($h->imagem_situacao_caminho != null)
            {
                $tem_imagem = true;
                break;
            }
        }

        $return = $this->historico_model->insert(
            array(
                'ordem_servico_fk' => $os->ordem_servico_pk,
                'funcionario_fk' => $funcionario->funcionario_pk,
                    'situacao_fk' => 2, //EM ANDAMENTO
                    'historico_ordem_comentario' => "Atribuída a(o) ".$funcionario->pessoa_nome,
                )
        );

        if ($tem_imagem)
        {
            $this->historico_model->insert_imagem([
                'historico_ordem_fk' => $return['id'], 
                'imagem_situacao_caminho' => $h->imagem_situacao_caminho
            ]);
        }

    }
    if($id_relatorio)
    {
        return $id_relatorio;
    }
    else
    {
        return false;
    }
}

    /**
    Retorna o relatório que está em aberto do funcionário
    **/
    public function get_relatorio_do_funcionario($id_funcionario){
        $this->load->model('relatorio_model');

        //precisamos descobrir o id do relatório que está em aberto do funcionário:
        $relatorio = $this->relatorio_model->get_relatorio_do_funcionario($id_funcionario);
        
        //se o relatório existir:
        if($relatorio)
        {
            //vamos pegar todas as ordens de serviços que pertence a este relatório:

            // $ordens_servicos = $this->relatorio_model->get(['relatorio_fk' => $relatorio->relatorio_pk]);
            // return $ordens_servicos;

            return $this->get_ordens_relatorio($relatorio->relatorio_pk);
        }
        else
        {
            return false;
        }
    }


    public function get_ordens_relatorio($id_relatorio)
    {
        $this->load->model('relatorio_model');

        $ordens_servicos = $this->relatorio_model->get(['relatorio_fk' => $id_relatorio]);

        return $ordens_servicos;
    }

    /**
    * Vamos verificar se o relatório já foi iniciado se o funcionário
    * pegar o relatório através do celular.
    **/
    public function verifica_se_relatorio_ja_foi_iniciado($id_relatorio)
    {
        $relatorio = $this->relatorio_model->get_relatorio($id_relatorio);
        if($relatorio->pegou_no_celular == 1)
        {
            return true; //já foi iniciado
        }else
        {
            return false; //não foi iniciado
        }
    }


    /**
    Recebe por parâmetro o id do relatório
    **/
    public function change_employee($id)
    {
        $this->load->model('relatorio_model');

        $response = new Response();

        if(!$this->verifica_se_relatorio_ja_foi_iniciado($id))
        {
            $resposta = $this->relatorio_model->update($this->input->post(), ['relatorio_pk' => $id]);

            if($resposta){
                $response->set_code(Response::SUCCESS);
                $response->set_data("Funcionário alterado com sucesso."); 
            }else{
                $response->set_code(Response::DB_ERROR_UPDATE);
                $response->set_data("Ocorreu um erro com o banco de dados.");
            }
        }else{
            $response->set_code(Response::UNAUTHORIZED);
            $response->set_data("O funcionário já recebeu o relatório no celular, portanto não é possível trocá-lo.");
        }

        $response->send();
    }

    /**
    Recebe por parâmetro o id do relatório.
    Destruir um relatório gera as seguintes operações:
    1 - As ordens de serviço vinculadas a ele recebem um novo historico_ordem com o estado Aberto;
    2 - Devemos apagar os relatorios_os vinculados ao ID do relatorio;
    3 - Por fim destruimos o relatório.
    **/
    public function destroy($id)
    {
        $this->load->model('relatorio_model');
        $this->load->model('historico_model');

        $response = new Response();

        $relatorio = $this->relatorio_model->get_relatorio($id);

        if($relatorio){
            if(!$this->verifica_se_relatorio_ja_foi_iniciado($id))
            {
                $ordens_servicos = $this->relatorio_model->get(['relatorio_fk' => $relatorio->relatorio_pk]);
                if($ordens_servicos)
                {
                    foreach($ordens_servicos as $os)
                    {
                        $this->historico_model->insert(
                            array(
                                'ordem_servico_fk' => $os->ordem_servico_pk,
                                'funcionario_fk' => $this->session->user['id_funcionario'],
                                'situacao_fk' => 1, //ABERTO
                                'historico_ordem_comentario' => "Relatório destruído.",
                            )
                        );
                    }

                }

                $this->relatorio_model->delete_relatorios_os(['relatorio_fk' => $relatorio->relatorio_pk]);

                $this->relatorio_model->disable(['relatorio_pk' => $relatorio->relatorio_pk]);
                $response->set_code(Response::SUCCESS);
                $response->set_data("Relatório deletado com sucesso.");
            }
            else
            {
               $response->set_code(Response::UNAUTHORIZED);
               $response->set_data("O funcionário já recebeu o relatório no celular, portanto não é possível destruí-lo."); 
            }

        }
        else
        {
            $response->set_code(Response::NOT_FOUND);
            $response->set_data("Relatório não encontrado.");
        }
        $response->send();
    }

    /**
    Index será responsável pela listagem dos relatórios
    **/
    public function index()
    {   
        $this->load->model('relatorio_model');
        $relatorios = $this->relatorio_model->get_relatorios();

        if ($relatorios !== false)
        {
            //arrumar a data:
            foreach($relatorios as $relatorio)
            {
                $relatorio->data_criacao = date('d/m/Y H:i:s', strtotime($relatorio->data_criacao));
                if($relatorio->status == 0)
                {
                    $relatorio->status_string = 'Em Andamento';
                }
                else if($relatorio->status == 1)
                {
                    $relatorio->status_string = 'Entregue';
                }

                if ($relatorio->data_entrega == null) 
                {
                    $relatorio->data_entrega = '-- --';
                }
                else
                {
                    $relatorio->data_entrega = date('d/m/Y H:i:s', strtotime($relatorio->data_entrega));
                }
            }
        }

        $this->session->set_flashdata('css', array(
            0 => base_url('assets/vendor/cropper/cropper.css'),
            1 => base_url('assets/vendor/input-image/input-image.css'),
            2 => base_url('assets/vendor/bootstrap-multistep-form/bootstrap.multistep.css'),
            3 => base_url('assets/css/modal_desativar.css')
        ));

        $this->session->set_flashdata('scripts', array(
            0 => base_url('assets/vendor/masks/jquery.mask.min.js'),
            1 => base_url('assets/vendor/bootstrap-multistep-form/jquery.easing.min.js'),
            2 => base_url('assets/js/utils.js'),
            3 => base_url('assets/js/constants.js'),
            4 => base_url('assets/js/jquery.noty.packaged.min.js'),
            5 =>base_url('assets/js/dashboard/relatorio/home.js')
        ));

        $this->load->helper('form');
        load_view([
            0 => [
                'src' => 'dashboard/administrador/relatorio/home',
                'params' => [
                    'relatorios' => $relatorios,
                ],
            ],
            1 => [
                'src' => 'access/pre_loader',
                'params' => null,
            ]
        ], 'administrador');
    }

    
    public function restaurar_os($id_relatorio = NULL)
    {
        $this->load->model('Relatorio_model', 'relatorio_model');
        $response = new Response();

        $this->form_validation->set_rules(
            'senha',
            'senha',
            'required'
        );

        $this->load->model('Acesso_model', 'acesso_model');

        // Verifica a senha do funcionário
        $acesso = $this->acesso_model->get([
            'pessoa_fk' => $this->session->user['id_user'],
            'acesso_senha' => hash(ALGORITHM_HASH,$this->input->post('senha').SALT)
        ]); 

        if ($acesso === false)
        {
            $response->set_code(Response::UNAUTHORIZED);
            $response->send();
            return;
        }
        else
        {
            if ($id_relatorio === NULL) 
            {
                $relatorios = $this->relatorio_model->get_objects(['relatorios.status' => 0]);

                if ($relatorios !== false) 
                {
                    foreach ($relatorios as $r) 
                    {
                        $this->restaurar_os_relatorio($r->relatorio_pk);
                    }
                }
            }
            else
            {
                $this->restaurar_os_relatorio($id_relatorio);
            }
            $response->set_code(Response::SUCCESS);
        }
        $response->send();
    }


    private function restaurar_os_relatorio($id_relatorio)
    {
        $this->load->model('Relatorio_model', 'relatorio_model');
        $ordens_servico = $this->relatorio_model->get_os_nao_verificadas($id_relatorio);

        // Verifica se há OS não finalizadas
        if ($ordens_servico !== false)
        {
            $this->load->model('Historico_model', 'historico_model');
            $completo = true;

            foreach ($ordens_servico as $os) 
            {
                // Para cada OS, é pego o último registro do histórico
                $hist = $this->historico_model->get_max_data_os($os->os_fk);

                // Se for em andamento, não foi finalizada, logo, deve ser inserido um novo histórico
                // a colocando em aberto novamente, informando que não foi finalizada no relatório
                if ($hist[0]->situacao_fk == '2') //2 é EM ANDAMENTO
                {
                   $return =  $this->historico_model->insert([
                        'ordem_servico_fk' => $os->os_fk,
                        'funcionario_fk' => $this->session->user['id_funcionario'],
                        'situacao_fk' => '1',
                        'historico_ordem_comentario' => 'Não foi feito no relatório'
                    ]);

                    //2 significa que não foi concluída.
                    $this->relatorio_model->update_relatorios_os_verificada(
                        ['os_fk' => $os->os_fk, 'os_verificada' => 0], 2
                    ); 

                    $completo = false;
                }
                // Caso esteja finalizada, seu status na tabela de relatorio_os é alterado para verificado
                else
                {
                    $this->relatorio_model->update_relatorios_os_verificada(
                        ['os_fk' => $os->os_fk, 'os_verificada' => 0], 1
                    );
                }   
            }
            //após verificar todas as ordens, setamos o status do relatório para 1 ou 2 para indicar que o relatório foi entregue completo ou incompleto.
            if ($completo) 
            {
                // Completo
                $this->relatorio_model->update(['status' => 1], ['relatorio_pk' => $id_relatorio]);
            }
            else
            {
                // Incompleto
                $this->relatorio_model->update(['status' => 2], ['relatorio_pk' => $id_relatorio]);
            }

            $this->relatorio_model->set_data_entrega($id_relatorio);
        }
        else
        {
            return;
        }
    }
}

?>