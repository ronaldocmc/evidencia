<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH . "core/MY_Model.php";

class Ordem_Servico_model extends MY_Model
{

    const NAME = 'ordem_servico';
    const TABLE_NAME = 'ordens_servicos';
    const PRI_INDEX = 'ordem_servico_pk';

    const FORM = array(
        'prioridade_fk',
        'servico_fk',
        'setor_fk',
        'situacao_inicial_fk',
        'situacao_atual_fk',
        'ordem_servico_desc',
        'ordem_servico_comentario'
    );

    public function get_home($organization, $where = null, $limit = null)
    {
        $this->CI->db->select('
            ordens_servicos.ordem_servico_pk,
            ordens_servicos.ordem_servico_cod,
            ordens_servicos.ativo,
            ordens_servicos.ordem_servico_desc,
            ordens_servicos.ordem_servico_criacao,
            ordens_servicos.ordem_servico_atualizacao,
            ordens_servicos.ordem_servico_comentario,
            ordens_servicos.funcionario_fk,
            ordens_servicos.procedencia_fk,
            ordens_servicos.localizacao_fk,
            prioridades.prioridade_pk as prioridade_fk,
            prioridades.prioridade_nome,
            servicos.servico_pk as servico_fk,
            servicos.servico_nome,
            si.situacao_pk as situacao_inicial_fk,
            si.situacao_nome as situacao_inicial_nome,
            sa.situacao_pk as situacao_atual_fk,
            sa.situacao_nome as situacao_atual_nome,
            setores.setor_pk as setor_fk,
            setores.setor_nome,
            localizacoes.localizacao_municipio,
            localizacoes.localizacao_lat,
            localizacoes.localizacao_long,
            localizacoes.localizacao_rua,
            localizacoes.localizacao_num,
            localizacoes.localizacao_bairro,
            localizacoes.localizacao_ponto_referencia,
            municipios.municipio_pk,
            municipios.municipio_nome,
            funcionarios.funcionario_nome,
            funcionarios.funcionario_caminho_foto,
            procedencias.procedencia_nome
            ');
        $this->CI->db->from('ordens_servicos');

        $this->CI->db->join('prioridades', 'prioridades.prioridade_pk = ordens_servicos.prioridade_fk');
        $this->CI->db->join('procedencias', 'procedencias.procedencia_pk = ordens_servicos.procedencia_fk');
        $this->CI->db->join('servicos', 'servicos.servico_pk = ordens_servicos.servico_fk');
        $this->CI->db->join('situacoes as si', 'si.situacao_pk = ordens_servicos.situacao_inicial_fk');
        $this->CI->db->join('situacoes as sa', 'sa.situacao_pk = ordens_servicos.situacao_atual_fk');
        $this->CI->db->join('setores', 'setores.setor_pk = ordens_servicos.setor_fk');
        $this->CI->db->join('localizacoes', 'localizacoes.localizacao_pk = ordens_servicos.localizacao_fk');
        $this->CI->db->join('municipios', 'municipios.municipio_pk = localizacoes.localizacao_municipio');
        $this->CI->db->join('funcionarios', 'funcionarios.funcionario_pk = ordens_servicos.funcionario_fk');

        $this->CI->db->where('setores.organizacao_fk', $organization);

        if ($where !== null) 
        {
            if(is_array($where))
            {
                foreach ($where as $field=>$value) 
                {
                    $this->CI->db->where($field, $value);
                }
            }
            else
            {
                $this->CI->db->where($where);
            }
        }

        $this->CI->db->order_by('ordens_servicos.ordem_servico_pk', 'DESC');

        if ($limit !== null) 
        {
            $this->CI->db->limit($limit);
        }

        $result = $this->CI->db->get()->result();
        return $result;
    }

    public function get_map($where)
    {
        $this->CI->db->select('*');
        $this->CI->db->from('ordens_servicos');
        $this->CI->db->join('localizacoes', 'localizacoes.localizacao_pk = ordens_servicos.localizacao_fk');
        $this->CI->db->join('servicos', 'servicos.servico_pk = ordens_servicos.servico_fk');
        $this->CI->db->join('tipos_servicos', 'tipos_servicos.tipo_servico_pk = servicos.tipo_servico_fk');
        $this->CI->db->join('setores', 'setores.setor_pk = ordens_servicos.setor_fk');

        $dates['data_final'] = array_pop($where);
        $dates['data_inicial'] = array_pop($where);
        
        foreach ($where as $field=>$value) 
        {
            if ($value !== '') 
            {
                $this->CI->db->where($field, $value);
            }
        }

        if ($dates['data_inicial'] === '' && $dates['data_final'] === '') 
        {
            // pass
        } 
        elseif ($dates['data_inicial'] !== '' && $dates['data_final'] !== '') 
        {
            $this->CI->db->where('ordem_servico_criacao >=', $dates['data_inicial']);
            $this->CI->db->where('ordem_servico_criacao <=', $dates['data_final']);
        } 
        elseif ($dates['data_inicial'] !== '') 
        {
            $this->CI->db->where('ordem_servico_criacao >=', $dates['data_inicial']);
        }
        elseif ($dates['data_final'] !== '') 
        {
            $this->CI->db->where('ordem_servico_criacao <=', $dates['data_final']);
        }

        return $this->CI->db->get()->result();
    }

    function get_for_new_report($where, $count = FALSE){
        $this->CI->db->select('prioridades.prioridade_pk, 
                            prioridades.prioridade_nome, 
                            procedencias.procedencia_pk, 
                            procedencias.procedencia_nome,
                            servicos.servico_pk,
                            servicos.servico_nome,
                            servicos.tipo_servico_fk,
                            setores.setor_pk,
                            setores.setor_nome,
                            funcionarios.funcionario_pk,
                            funcionarios.funcionario_nome,
                            funcionarios.departamento_fk,
                            funcionarios.funcao_fk,
                            situacoes.situacao_pk,
                            situacoes.situacao_nome,
                            situacoes.situacao_descricao,
                            ordens_servicos.ordem_servico_pk,
                            ordens_servicos.ordem_servico_criacao,
                            ordens_servicos.ordem_servico_comentario,
                            ordens_servicos.ordem_servico_cod,
                            ordens_servicos.ordem_servico_desc,
                            ordens_servicos.localizacao_fk'
                        );
        $this->CI->db->from($this->getTableName());
        $this->CI->db->join('prioridades', 'prioridades.prioridade_pk = ' . $this->getTableName() . '.prioridade_fk');
        $this->CI->db->join('procedencias', 'procedencias.procedencia_pk = ' . $this->getTableName() . '.procedencia_fk');
        $this->CI->db->join('servicos', 'servicos.servico_pk = ' . $this->getTableName() . '.servico_fk');
        $this->CI->db->join('setores', 'setores.setor_pk = ' . $this->getTableName() . '.setor_fk');
        $this->CI->db->join('funcionarios', 'funcionarios.funcionario_pk = ' . $this->getTableName() . '.funcionario_fk');
        $this->CI->db->join('situacoes', 'situacoes.situacao_pk = ' . $this->getTableName() . '.situacao_inicial_fk');
        // $this->CI->db->where('funcionario_fk', $where['funcionario_fk']);
        $this->CI->db->where('situacao_atual_fk', '1');
        $this->CI->db->where('ordens_servicos.ativo', '1');
        $this->CI->db->where('ordem_servico_criacao BETWEEN '."'".$where['data_inicial']." 00:00:01'"." AND "."'".$where['data_final']." 23:59:59'");
        $this->CI->db->where_in('setor_fk', $where['setor']);
        $this->CI->db->where_in('tipo_servico_fk', $where['tipo']);

        if($count == TRUE){
            return $this->CI->db->count_all_results();
        }

        return $this->CI->db->get()->result();
    }

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

    function get_historico($id)
    {
        return $this->CI->db
        ->select("historicos_ordens.*, funcionarios.funcionario_caminho_foto, funcionarios.funcionario_nome, situacoes.situacao_nome")
        ->from("historicos_ordens")
        ->where("ordem_servico_fk", $id)
        ->join("funcionarios","historicos_ordens.funcionario_fk = funcionarios.funcionario_pk")
        ->join("situacoes","historicos_ordens.situacao_fk = situacoes.situacao_pk")
        ->get()->result();       
    }

    function get_images($organizacao, $where = null)
    {
        if($where != null){
            $this->CI->db->where($where);
        }
        
        return $this->CI->db
        ->select("*")
        ->from("imagens_os")
        ->where("imagens_os.organizacao_fk", $organizacao)
        ->join("situacoes","imagens_os.situacao_fk = situacoes.situacao_pk")
        ->get()->result();  
    }
  
    function get_images_id($id){
        return $this->CI->db
        ->select("*")
        ->from("imagens_os")
        ->where("imagens_os.ordem_servico_fk", $id)
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

    function handle_historico($id)
    {
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

    function insert_images($paths, $os, $organizacao)
    {
        $this->CI->db->insert_batch('imagens_os', $this->build_images_rows($paths, $os, $organizacao));
    }

    function build_images_rows($paths, $os, $organizacao)
    {
        $rows = [];

        if ($paths !== null) 
        {
            foreach ($paths as $key => $p) 
            {
                $rows[$key] = array(
                    'ordem_servico_fk' => $os,
                    'situacao_fk' => $this->__get('situacao_atual_fk'),
                    'imagem_os' => $p,
                    'organizacao_fk' => $organizacao
                );
            }
        }

        return $rows;
    }
}
