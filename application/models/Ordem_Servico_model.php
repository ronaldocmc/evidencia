<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH . "core\MY_Model.php";

class Ordem_Servico_model extends MY_Model
{

    const NAME = 'ordem_servico';
    const TABLE_NAME = 'ordens_servicos';
    const PRI_INDEX = 'ordem_servico_pk';

    const FORM = array(
        'prioridade_fk',
        'procedencia_fk',
        'servico_fk',
        'setor_fk',
        'situacao_inicial_fk',
        'situacao_atual_fk',
        'ordem_servico_desc',
    );

    function config_form_validation_primary_key()
    {
        $this->CI->form_validation->set_rules(
            'ordem_servico_pk',
            'Ordem Servico',
            'trim|required|is_natural'
        );
    }

    function config_form_validation()
    {

        $this->CI->form_validation->set_rules(
            'prioridade_fk',
            'Prioridade',
            'trim|required|is_natural'
        );

        $this->CI->form_validation->set_rules(
            'procedencia_fk',
            'Procedência',
            'trim|required|is_natural'
        );

        $this->CI->form_validation->set_rules(
            'servico_fk',
            'Serviço',
            'trim|required|is_natural'
        );

        $this->CI->form_validation->set_rules(
            'setor_fk',
            'Setor',
            'trim|required|is_natural'
        );

        $this->CI->form_validation->set_rules(
            'situacao_inicial_fk',
            'Situacao Inicial',
            'trim|required|is_natural'
        );

        $this->CI->form_validation->set_rules(
            'situacao_atual_fk',
            'Situacao Atual',
            'trim|required|is_natural'
        );

        $this->CI->form_validation->set_rules(
            'ordem_servico_desc',
            'Descricao',
            'trim|required'
        );
    }

    function get_historico($id){
        return $this->CI->db
        ->select("historicos_ordens.*, funcionarios.funcionario_caminho_foto, funcionarios.funcionario_nome, situacoes.situacao_nome")
        ->from("historicos_ordens")
        ->where("ordem_servico_fk", $id)
        ->join("funcionarios","historicos_ordens.funcionario_fk = funcionarios.funcionario_pk")
        ->join("situacoes","historicos_ordens.situacao_fk = situacoes.situacao_pk")
        ->get()->result();       
    }

    function get_images($organizacao){
        return $this->CI->db
        ->select("*")
        ->from("imagens_os")
        ->where("situacoes.organizacao_fk", $organizacao)
        ->join("situacoes","imagens_os.situacao_fk = situacoes.situacao_pk")
        ->get()->result();  
    }

    // A localização e funcionario já devem estar setados no array
    // A organização deve ser passada pois no WS não existirá sessão
    function insert_os($organization)
    {
        $this->generate_os_cod($organization);
        return $this->insert();
    }

    function handle_historico($id){
        $os = $this->CI->db
        ->select("
        ordem_servico_pk as ordem_servico_fk, 
        funcionario_fk, 
        situacao_atual_fk as situacao_fk, 
        ordem_servico_atualizacao as historico_ordem_tempo, 
        ordem_servico_comentario as historico_ordem_comentario
        ")
        ->from(self::TABLE_NAME)
        ->where("ordem_servico_pk",$id)
        ->get()->row_array();

        // $os = get_object_vars($os);
        
        $this->CI->db->insert("historicos_ordens", $os);
    }

    function generate_os_cod($organization)
    {
        date_default_timezone_set('America/Sao_Paulo');
        $this->CI->load->model('Organizacao_model', 'organizacao');

        $cod = $this->CI->organizacao->get_and_increase_cod($organization);
        $shortening = $this->generate_shortening();

        $ordem_servico_cod = $shortening . '-' . date('Y') . '/' . $cod;

        $this->__set('ordem_servico_cod', $ordem_servico_cod);
    }

    function generate_shortening()
    {
        $this->CI->load->model('Servico_model', 'servico');
        $shortenings = $this->CI->servico->get_all(
            'tipos_servicos.tipo_servico_abreviacao,
             servicos.servico_abreviacao',
            ['servicos.servico_pk' => $this->__get('servico_fk')],
            -1,
            -1,
            [
                ['table' => 'tipos_servicos', 'on' => 'tipos_servicos.tipo_servico_pk = servicos.tipo_servico_fk'],
            ]
        );

        return $shortenings[0]->tipo_servico_abreviacao . $shortenings[0]->servico_abreviacao;
    }

    function insert_images($paths, $os)
    {
        $this->CI->db->insert_batch('imagens_os', $this->build_images_rows($paths, $os));
    }

    function build_images_rows($paths, $os)
    {
        $rows = [];

        foreach ($paths as $key => $p) {
            $rows[$key] = array(
                'ordem_servico_fk' => $os,
                'situacao_fk' => $this->__get('situacao_atual_fk'),
                'imagem_os' => $p,
            );
        }

        return $rows;
    }
}
