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
        'ordem_servico_desc'
    );

    public function config_form_validation_primary_key()
    {
        $this->CI->form_validation->set_rules(
            'ordem_servico_pk',
            'Ordem Servico',
            'trim|required|is_natural'
        );
    }

    public function config_form_validation()
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

    // A localização e funcionario já devem estar setados no array
    // A organização deve ser passada pois no WS não existirá sessão
    public function insert_os($organization)
    {
        $this->generate_os_cod($organization);
        $this->insert();
    }

    private function generate_os_cod($organization)
    {
        date_default_timezone_set('America/Sao_Paulo');
        $this->CI->load->model('Organizacao_model', 'organizacao');

        $cod = $this->CI->organizacao->get_and_increase_cod($organization);
        $shortening = $this->generate_shortening();

        $ordem_servico_cod = $shortening . '-' . date('Y') . '/' . $cod;

        $this->__set('ordem_servico_cod', $ordem_servico_cod);
    }

    private function generate_shortening()
    {
        $this->CI->load->model('Servico_model', 'servico');
        $shortenings = $this->CI->servico->get_all(
            'tipos_servicos.tipo_servico_abreviacao,
             servicos.servico_abreviacao',
            ['servicos.servico_pk' => $this->__get('servico_fk')],
            -1,
            -1,
            [
                ['table' => 'tipos_servicos', 'on' => 'tipos_servicos.tipo_servico_pk = servicos.tipo_servico_fk']
            ]
        );

        return $shortenings[0]->tipo_servico_abreviacao . $shortenings[0]->servico_abreviacao;
    }

    public function insert_os_images()
    {
        $this->CI->db->insert();
    }

    private function build_images_rows($paths, $os)
    {
        $rows = [];

        foreach ($paths as $key => $p)
        {
            $rows[$key] = array(
                'ordem_servico_fk' => $os,
                'situacao_fk' => $this->__get('situacao_fk'),
                'imagem_os' => $p,
            );
        }

        return $rows;
    }
}
