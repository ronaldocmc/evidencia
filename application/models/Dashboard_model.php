<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard_model extends CI_Model {


    public function get_ordens_ano($inicio,$fim,$organizacao_fk){
        $query = $this->db->query("CALL get_ordens_ano('".$inicio."','".$fim."','".$organizacao_fk."');");
        // var_dump($this->db->error());die();
        $r = $query->row_array();
        $this->db->close();
        return $r;
    }

    public function get_ordens_tipo_semana($organizacao_fk){
        $query = $this->db->query("CALL get_ordens_tipo_semana('".$organizacao_fk."');");
        // var_dump($this->db->error());die();
        $r = $query->result_array();
        $this->db->close();
        return $r;
    }

    public function get_ordens_semana($organizacao_fk){
        $query = $this->db->query("CALL get_ordens_semana('".$organizacao_fk."');");
        // var_dump($this->db->error());die();
        $r = $query->row_array();
        $this->db->close();
        return $r;
    }

    public function get_ordens_setor_semana($organizacao_fk){
        $query = $this->db->query("CALL get_ordens_setores_semana('".$organizacao_fk."');");
        // var_dump($this->db->error());die();
        $r = $query->result_array();
        $this->db->close();
        return $r;
    }

    public function get_ordens_bairro_ano($organizacao_fk){
        $query = $this->db->query("CALL get_ordens_bairros_ano('".$organizacao_fk."');");
        // var_dump($this->db->error());die();
        $r = $query->result_array();
        $this->db->close();
        return $r;
    }

    public function get_ordens_hoje_finalizadas() {
        $this->db->select('count(*) as quantidade');
        $this->db->from('historicos_ordens');
        $this->db->join('relatorios_os', 'relatorios_os.os_fk = historicos_ordens.ordem_servico_fk');
        $this->db->join('relatorios', 'relatorios.relatorio_pk = relatorios_os.relatorio_fk');

        $this->db->where('DAY(historico_ordem_tempo) = DAY(curdate())');
        $this->db->where('historicos_ordens.situacao_fk', 5); //5 = FINALIZADO
        $this->db->order_by('historicos_ordens.historico_ordem_tempo', 'DESC');
        $this->db->limit(1);


        $result = $this->db->get()->row();
        if ($result) {
            return ($result->quantidade);
        } else {
            return 0;
        }
    }

    public function get_ordens_ultima_situacao_em_andamento() {
        $this->db->select('count(*) as quantidade');
        $this->db->from('historicos_ordens');
        $this->db->join('relatorios_os', 'relatorios_os.os_fk = historicos_ordens.ordem_servico_fk');
        $this->db->join('relatorios', 'relatorios.relatorio_pk = relatorios_os.relatorio_fk');
        $this->db->where('DAY(historico_ordem_tempo) = DAY(curdate())');
        $this->db->where('relatorios.status', 0);
        $this->db->where('historicos_ordens.situacao_fk', 2); 
        $this->db->order_by('historicos_ordens.historico_ordem_tempo', 'DESC');
        $this->db->limit(1);

        //echo $this->db->get_compiled_select(); die();

        $result = $this->db->get()->row();
        if ($result) {
            return ($result->quantidade);
        } else {
            return false;
        }
    }

    public function get_revisores_do_dia() {
        $this->db->select('pessoa_nome as nome');
        $this->db->from('relatorios');
        $this->db->join('funcionarios', 'relatorios.funcionario_fk = funcionarios.funcionario_pk');
        $this->db->join('populacao', 'funcionarios.pessoa_fk = populacao.pessoa_pk');
        $this->db->where('relatorios.status', 0); //0 é em andamento
        $this->db->where('DAY(relatorios.data_criacao) = DAY(CURDATE())');

        //echo $this->db->get_compiled_select(); die();

        return $this->db->get()->result();
      

    }

    public function get_setores_do_dia() {
        $this->db->select('distinct(setor_nome) as nome');
        $this->db->from('relatorios');
        $this->db->join('relatorios_os', 'relatorios.relatorio_pk = relatorios_os.relatorio_fk');
        $this->db->join('ordens_servicos', 'relatorios_os.os_fk = ordens_servicos.ordem_servico_pk');
        $this->db->join('setores', 'ordens_servicos.setor_fk = setores.setor_pk');
        $this->db->where('relatorios.status', 0);
        $this->db->where('DAY(relatorios.data_criacao) = DAY(CURDATE())');

        //echo $this->db->get_compiled_select(); die();

        return $this->db->get()->result();
        
    }

    public function get_ordens_hoje(){
        $this->db->select('count(*) as quantidade');
        $this->db->from('(select ordem_servico_fk, historico_ordem_pk,
            min(historico_ordem_tempo) as data_criacao 
            from historicos_ordens 
            group by ordem_servico_fk) as t');
        $this->db->where('DAY(t.data_criacao) = DAY(curdate())');
        $result = $this->db->get()->row();
        if ($result) {
            return ($result->quantidade);
        } else {
            return false;
        }
    }

    public function get_ordens_em_execucao(){
        $this->db->select('ordem_servico_cod as codigo, prioridade_nome as prioridade, servico_nome as servico, pessoa_nome as funcionario, situacao_nome as situacao');
        $this->db->from('historicos_ordens');
        $this->db->join('relatorios_os', 'relatorios_os.os_fk = historicos_ordens.ordem_servico_fk');
        $this->db->join('ordens_servicos', 'historicos_ordens.ordem_servico_fk = ordens_servicos.ordem_servico_pk');
        $this->db->join('prioridades', 'ordens_servicos.prioridade_fk = prioridades.prioridade_pk');
        $this->db->join('servicos', 'ordens_servicos.servico_fk = servicos.servico_pk');
        $this->db->join('funcionarios', 'historicos_ordens.funcionario_fk = funcionarios.funcionario_pk');
        $this->db->join('populacao', 'funcionarios.pessoa_fk = populacao.pessoa_pk');
        $this->db->join('situacoes', 'historicos_ordens.situacao_fk = situacoes.situacao_pk');
        $this->db->join('relatorios', 'relatorios.relatorio_pk = relatorios_os.relatorio_fk');
        $this->db->where('DAY(historico_ordem_tempo) = DAY(curdate())');
        $this->db->where('relatorios.status', 0);
        $this->db->where('historicos_ordens.situacao_fk != 1'); 
        $this->db->order_by('historicos_ordens.historico_ordem_tempo', 'DESC');
        $this->db->group_by('ordem_servico_cod');

        //echo $this->db->get_compiled_select(); die();

        return $this->db->get()->result();
        
    }


    public function get_funcionarios() {
        $this->db->select('pessoa_nome as nome, relatorios.relatorio_pk as relatorio_id');
        $this->db->from('relatorios');
        $this->db->join('funcionarios', 'relatorios.funcionario_fk = funcionarios.funcionario_pk');
        $this->db->join('populacao', 'funcionarios.pessoa_fk = populacao.pessoa_pk');
        $this->db->where('relatorios.status', 0); //em andamento

        //echo $this->db->get_compiled_select(); die();

        $result = $this->db->get()->result();
        return $result;
    }

    public function get_setores_do_relatorio($id_relatorio){
        $this->db->select('distinct(setor_nome) as nome');
        $this->db->from('relatorios');
        $this->db->join('relatorios_os', 'relatorios_os.relatorio_fk = relatorios.relatorio_pk');
        $this->db->join('ordens_servicos', 'relatorios_os.os_fk = ordens_servicos.ordem_servico_pk');
        $this->db->join('setores', 'ordens_servicos.setor_fk = setores.setor_pk');
        $this->db->where('relatorios.relatorio_pk', $id_relatorio);

        //echo $this->db->get_compiled_select(); die();

        $result = $this->db->get()->result();
        if ($result) {
            return ($result);
        } else {
            return false;
        }
    }

    public function get_servicos_do_relatorio($id_relatorio){
        $this->db->select('distinct(servico_nome) as nome');
        $this->db->from('relatorios');
        $this->db->join('relatorios_os', 'relatorios_os.relatorio_fk = relatorios.relatorio_pk');
        $this->db->join('ordens_servicos', 'relatorios_os.os_fk = ordens_servicos.ordem_servico_pk');
        $this->db->join('servicos', 'ordens_servicos.servico_fk = servicos.servico_pk');

        $this->db->where('relatorios.relatorio_pk', $id_relatorio);

        //echo $this->db->get_compiled_select(); die();

        $result = $this->db->get()->result();
        if ($result) {
            return ($result);
        } else {
            return false;
        }
    }


    // public function get_ordens_concluidas($id_relatorio){
    //     $this->db->select('count(os_fk) as quantidade');
    //     $this->db->from('relatorios_os');
    //     $this->db->join('historicos_ordens', 'relatorios_os.os_fk = historicos_ordens.ordem_servico_fk');
    //     $this->db->where('relatorios_os.relatorio_fk', $id_relatorio);
    //     $this->db->where('historicos_ordens.')
    // }

    public function get_ordens_concluidas($id_relatorio) {
        $this->db->select('count(*) as quantidade');
        $this->db->from('historicos_ordens');
        $this->db->join('relatorios_os', 'relatorios_os.os_fk = historicos_ordens.ordem_servico_fk');
        $this->db->where('historicos_ordens.situacao_fk', 5); //5 = FINALIZADO
        $this->db->where('relatorios_os.relatorio_fk', $id_relatorio);
        $this->db->order_by('historicos_ordens.historico_ordem_tempo', 'DESC');
        $this->db->limit(1);


        $result = $this->db->get()->row();
        if ($result) {
            return ($result->quantidade);
        } else {
            return false;
        }
    }

    public function get_ordens($id_relatorio){
        $this->db->select('count(*) as quantidade');
        $this->db->from('relatorios_os');
        $this->db->where('relatorios_os.relatorio_fk', $id_relatorio);

        $result = $this->db->get()->row();
        if ($result) {
            return ($result->quantidade);
        } else {
            return false;
        }
    }

    // public function get_ultima_ordem($id_relatorio){
    //     $this->db->select('historico_ordem_tempo as data');
    //     $this->db->from('relatorios_os');
    //     $this->db->join('historicos_ordens', 'historicos_ordens.ordem_servico_fk = relatorios_os.os_fk');
    //     $this->db->where('relatorios_os.relatorio_fk', $id_relatorio);
    // }

    public function get_data_ultima_ordem($id_relatorio){
         $this->db->select('historico_ordem_tempo as data');
        $this->db->from('historicos_ordens');
        $this->db->join('relatorios_os', 'relatorios_os.os_fk = historicos_ordens.ordem_servico_fk');
        $this->db->where('relatorios_os.relatorio_fk', $id_relatorio); 
        $this->db->where('historicos_ordens.situacao_fk = 5'); //finalizado
        $this->db->order_by('historicos_ordens.historico_ordem_tempo', 'DESC');
        $this->db->limit(1);


        //echo $this->db->get_compiled_select(); die();

        $result = $this->db->get()->row();
        if ($result) {
            return ($result->data);
        } else {
            return false;
        }
    }

    public function date_dif($data){
        $this->db->select('ROUND(time_to_sec((TIMEDIFF(NOW(), historico_ordem_tempo))) / 60) as dif');
        $this->db->from('historicos_ordens');
        $this->db->where('historico_ordem_tempo', $data);

        $result = $this->db->get()->row();
        if ($result) {
            return ($result->dif);
        } else {
            return false;
        }
    }

    public function get_heatmap(){
        $sql = "SELECT
                populacao.pessoa_nome,
                SUM(TIME(historico_ordem_tempo) BETWEEN '00:00:00' AND '07:59:59') as `até às 8`,
                SUM(HOUR(historico_ordem_tempo) BETWEEN '08:00:00' AND '08:59:59') as `8 às 9`,
                SUM(HOUR(historico_ordem_tempo) BETWEEN '09:00:00' AND '09:59:59') as `9 às 10`,
                SUM(HOUR(historico_ordem_tempo) BETWEEN '10:00:00' AND '10:59:59') as `10 às 11`,
                SUM(HOUR(historico_ordem_tempo) BETWEEN '11:00:00' AND '11:59:59') as `11 às 12`,
                SUM(HOUR(historico_ordem_tempo) BETWEEN '12:00:00' AND '12:59:59') as `12 às 13`,
                SUM(HOUR(historico_ordem_tempo) BETWEEN '13:00:00' AND '13:59:59') as `13 às 14`,
                SUM(HOUR(historico_ordem_tempo) BETWEEN '14:00:00' AND '14:59:59') as `14 às 15`,
                SUM(HOUR(historico_ordem_tempo) BETWEEN '15:00:00' AND '15:59:59') as `15 às 16`,
                SUM(HOUR(historico_ordem_tempo) BETWEEN '16:00:00' AND '16:59:59') as `16 às 17`,
                SUM(HOUR(historico_ordem_tempo) BETWEEN '17:00:00' AND '17:59:59') as `17 às 18`
                FROM historicos_ordens
                INNER JOIN relatorios_os ON relatorios_os.os_fk = historicos_ordens.ordem_servico_fk
                INNER JOIN relatorios ON relatorios_os.relatorio_fk = relatorios.relatorio_pk
                INNER JOIN funcionarios ON relatorios.funcionario_fk = funcionarios.funcionario_pk
                INNER JOIN populacao ON populacao.pessoa_pk = funcionarios.pessoa_fk
                WHERE historicos_ordens.situacao_fk = 5
                AND DAY(relatorios.data_criacao) = DAY(CURDATE())";

        $query = $this->db->query($sql);
        $result = $query->result_array();

        foreach($result as $r){
            if($r['pessoa_nome'] == null){
                return false;
            } 
        }

        return $r;

    }


    public function get_tipos_servicos_do_dia(){
        $this->db->select('tipos_servicos.tipo_servico_nome as nome, count(*) as quantidade');
        $this->db->from('relatorios_os');
        $this->db->join('ordens_servicos', 'relatorios_os.os_fk = ordens_servicos.ordem_servico_pk');
        $this->db->join('servicos', 'ordens_servicos.servico_fk = servicos.servico_pk');
        $this->db->join('tipos_servicos', 'servicos.tipo_servico_fk = tipos_servicos.tipo_servico_pk');
        $this->db->join('relatorios', 'relatorios.relatorio_pk = relatorios_os.relatorio_fk');
        $this->db->where('DAY(relatorios.data_criacao) = DAY(CURDATE())');
        $this->db->group_by('tipos_servicos.tipo_servico_nome');

        //echo $this->db->get_compiled_select(); die();

        $result = $this->db->get()->result();
        if ($result) {
            return ($result);
        } else {
            return false;
        }
    }

}