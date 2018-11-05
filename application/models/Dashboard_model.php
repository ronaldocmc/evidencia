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
        //         SELECT ordem_servico_fk from historicos_ordens WHERE DAY(historico_ordem_tempo) = DAY(CURDATE()) 
        // AND situacao_fk = 2;
        $this->db->select('count(ordem_servico_fk) as count');
        $this->db->from('historicos_ordens');
        $this->db->where('DAY(historico_ordem_tempo) = DAY(CURDATE())');
        $this->db->where('situacao_fk', ID_SITUACAO_FINALIZADO);

        $result = $this->db->get()->result();
        if ($result) {
            return ($result);
        } else {
            return false;
        }
    }

    // public function get_ordens_hoje() {
    //     //         SELECT ordem_servico_fk from historicos_ordens WHERE DAY(historico_ordem_tempo) = DAY(CURDATE()) 
    //     // AND situacao_fk = 2;
    //     $this->db->select('count(ordem_servico_fk) as count');
    //     $this->db->from('historicos_ordens');
    //     $this->db->where('DAY(historico_ordem_tempo) = DAY(CURDATE())');
    //     $this->db->where('situacao_fk', ID_SITUACAO_ABERTA);

    //     $result = $this->db->get()->result();
    //     if ($result) {
    //         return ($result);
    //     } else {
    //         return false;
    //     }
    // }

    public function get_ordens_hoje(){
        // $this->db->query('SELECT count(*) as quantidade FROM (select ordem_servico_fk, historico_ordem_pk,
        //     min(historico_ordem_tempo) as data_criacao 
        //     from evidencia.historicos_ordens 
        //     group by ordem_servico_fk) as t WHERE DAY(t.data_criacao) = DAY(curdate());');
        $this->db->select('count(*) as quantidade');
        $this->db->from('(select ordem_servico_fk, historico_ordem_pk,
            min(historico_ordem_tempo) as data_criacao 
            from evidencia.historicos_ordens 
            group by ordem_servico_fk) as t');
        $this->db->where('DAY(t.data_criacao) = DAY(curdate())');
        $result = $this->db->get()->row();
        if ($result) {
            return ($result->quantidade);
        } else {
            return false;
        }
    }



}