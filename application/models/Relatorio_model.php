<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH . "core\MY_Model.php";

class Relatorio_model extends MY_Model
{

    const NAME = 'relatorios';
    const TABLE_NAME = 'relatorios';
    const PRI_INDEX = 'relatorio_pk';

    const FORM = array(
        'relatorio_func_responsavel',
        'relatorio_data_criacao',
        'ativo',
        'relatorio_data_entrega',
        'relatorio_criador',
        'relatorio_data_inicio_filtro',
        'relatorio_data_fim_filtro',
        'relatorio_situacao'
    );

    public function config_password_validation(){
        
        $this->CI->form_validation->set_rules(
            'senha',
            'Senha',
            'required'
        );   
    }

    public function config_form_validation()
    {

        $this->CI->form_validation->set_rules(
            'setor[]',
            'Setor',
            'required'
        );

        $this->CI->form_validation->set_rules(
            'tipo[]>',
            'Tipo_Servico',
            'required'
        );

        $this->CI->form_validation->set_rules(
            'data_inicial',
            'Data_Inicial',
            'required'
        );

        $this->CI->form_validation->set_rules(
            'data_final',
            'Data_Final',
            'required'
        );

        $this->CI->form_validation->set_rules(
            'funcionario_fk',
            'Funcionario',
            'required'
        );
    }

    public function insert_filter_data($data, $table_name)
    {

        if($this->CI->db->insert_batch($table_name, $data)){
            
            return $this->CI->db->insert_id();

        } else {
            throw new MyException('Não foi possível inserir na tabela '.$table_name, Response::SERVER_FAIL);
        }
    }

    public function insert_report_os(Array $data)
    {
        if ($this->CI->db->insert('relatorios_os', $data)) {
            return true;
        } else {
            throw new MyException('Não foi possível inserir na tabela relatorio_os', Response::SERVER_FAIL);
        }
    }

    public function get_images($relatorio_pk){
        return $this->CI->db
        ->select("imagens_os.*")
        ->from("relatorios_os")
        ->where("relatorios_os.relatorio_fk",$relatorio_pk)
        ->join("imagens_os", "relatorios_os.os_fk = imagens_os.ordem_servico_fk")
        ->get()->result();
    }

    public function get_orders_of_report($where = null, $count = FALSE)
    {
        $this->CI->db->select(
                'ordens_servicos.ordem_servico_pk,
                ordens_servicos.ordem_servico_desc, 
                ordens_servicos.ordem_servico_cod, 
                ordens_servicos.situacao_atual_fk,
                ordens_servicos.ordem_servico_comentario,
                ordens_servicos.ordem_servico_atualizacao,
                ordens_servicos.ordem_servico_criacao,
                servicos.servico_nome,
                servicos.servico_pk,
                tipos_servicos.tipo_servico_pk,
                tipos_servicos.tipo_servico_nome,
                prioridades.prioridade_pk,  
                prioridades.prioridade_nome, 
                procedencias.procedencia_nome, 
                localizacoes.localizacao_pk,
                localizacoes.localizacao_lat,
                localizacoes.localizacao_long,
                localizacoes.localizacao_rua,
                localizacoes.localizacao_num,
                localizacoes.localizacao_bairro,
                situacoes.situacao_nome,
                setores.setor_nome,
                setores.setor_pk,
                departamentos.departamento_nome,
                departamentos.departamento_pk
           ');

        $this->CI->db->from('relatorios_os');
        $this->CI->db->join('ordens_servicos', 'relatorios_os.os_fk = ordens_servicos.ordem_servico_pk');
        $this->CI->db->join('servicos','servicos.servico_pk = ordens_servicos.servico_fk');
        $this->CI->db->join('tipos_servicos', 'tipos_servicos.tipo_servico_pk = servicos.tipo_servico_fk');
        $this->CI->db->join('situacoes','situacoes.situacao_pk = ordens_servicos.situacao_atual_fk');
        $this->CI->db->join('prioridades','prioridades.prioridade_pk = ordens_servicos.prioridade_fk');
        $this->CI->db->join('procedencias','procedencias.procedencia_pk = ordens_servicos.procedencia_fk');
        $this->CI->db->join('setores','setores.setor_pk = ordens_servicos.setor_fk');
        $this->CI->db->join('localizacoes', 'localizacoes.localizacao_pk = ordens_servicos.localizacao_fk');
        $this->CI->db->join('departamentos', 'departamentos.departamento_pk = tipos_servicos.departamento_fk');
        $this->CI->db->group_by('ordens_servicos.ordem_servico_pk');

        if ($where !== NULL) {
            if (is_array($where)) {
                foreach ($where as $field => $value) {
                    $this->CI->db->where($field, $value);
                }
            } else {
                $this->CI->db->where(self::PRI_INDEX, $where);
            }
        }

        if($count == TRUE){
            return $this->CI->db->count_all_results();
        }

        $result = $this->CI->db->get()->result();
        if ($result) {
            return ($result);
        } else {
            return false;
        }
    }

    public function not_finished($relatorio_pk){
        // $where['ordens_servicos.situacao_atual_fk != 3 AND ordens_servicos.situacao_atual_fk != 4 AND ordens_servicos.situacao_atual_fk != '] = 5;

        return $this->CI->db
        ->select('relatorios_os.os_fk')
        ->from('relatorios_os')
        ->join('ordens_servicos','ordens_servicos.ordem_servico_pk = relatorios_os.os_fk')
        ->where('relatorios_os.relatorio_fk',$relatorio_pk) //todas as ordens do relatório especificado
        ->where('ordens_servicos.situacao_atual_fk', 2) //que estão em andamento
        ->get()->result();        
    }

    public function get_em_andamento_or_criado($relatorio_id = NULL)
    {
        $this->CI->db->select('*');
        $this->CI->db->from('relatorios');
        $this->CI->db->where('relatorios.relatorio_situacao', 'Em andamento');
        $this->CI->db->or_where('relatorios.relatorio_situacao', 'Criado');
        $this->CI->db->where('relatorios.ativo', '1');
        
        if ($relatorio_id !== NULL) 
        {
            $this->CI->db->where('relatorios.relatorio_pk', $relatorio_id);
        }
        
        return $this->CI->db->get()->result();
    }

}
?>