<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Relatorio_model extends CI_Model
{

    /**
     * @name string TABLE_NAME Holds the name of the table in use by this model
     */
    const TABLE_NAME = 'relatorios';

    /**
     * @name string PRI_INDEX Holds the name of the tables' primary index used in this model
     */
    const PRI_INDEX = 'relatorio_pk';

    /**
     * Retrieves record(s) from the database
     *
     * @param mixed $where Optional. Retrieves only the records matching given criteria, or all records if not given.
     *                      If associative array is given, it should fit field_name=>value pattern.
     *                      If string, value will be used to match against PRI_INDEX
     * @return mixed Single record if ID is given, or array of results
     */
    public function get($where = null)
    {
        $this->db->select('ordens_servicos.ordem_servico_pk,ordens_servicos.ordem_servico_desc, ordens_servicos.ordem_servico_cod, ordens_servicos.ordem_servico_status, servicos.servico_nome,  prioridades.prioridade_nome, procedencias.procedencia_nome, imagens_situacoes.imagem_situacao_caminho, coordenadas.coordenada_lat, coordenadas.coordenada_long, setores.setor_nome, (SELECT situacoes.situacao_nome FROM historicos_ordens JOIN situacoes ON historicos_ordens.situacao_fk = situacoes.situacao_pk WHERE historicos_ordens.ordem_servico_fk = ordens_servicos.ordem_servico_pk  ORDER BY historicos_ordens.historico_ordem_tempo DESC LIMIT 1) as situacao_atual,(SELECT situacoes.situacao_nome FROM historicos_ordens JOIN situacoes ON historicos_ordens.situacao_fk = situacoes.situacao_pk WHERE historicos_ordens.ordem_servico_fk = ordens_servicos.ordem_servico_pk  ORDER BY historicos_ordens.historico_ordem_tempo ASC LIMIT 1) as situacao_inicial,
           (SELECT historicos_ordens.historico_ordem_tempo FROM historicos_ordens WHERE historicos_ordens.ordem_servico_fk = ordens_servicos.ordem_servico_pk  ORDER BY historicos_ordens.historico_ordem_tempo ASC LIMIT 1) as data_criacao,
           tipos_servicos.tipo_servico_nome, 
           locais.local_complemento, locais.local_num, logradouros.logradouro_nome, bairros.bairro_nome, municipios.municipio_nome, municipios.estado_fk, coordenadas.local_fk, departamento_nome

           ')
        ;
        $this->db->from('relatorios_os');
        $this->db->join('ordens_servicos', 'relatorios_os.os_fk = ordens_servicos.ordem_servico_pk');
        $this->db->join('servicos','servicos.servico_pk = ordens_servicos.servico_fk');
        $this->db->join('historicos_ordens','historicos_ordens.ordem_servico_fk =  ordens_servicos.ordem_servico_pk');
        $this->db->join('imagens_situacoes', 'historicos_ordens.historico_ordem_pk = imagens_situacoes.historico_ordem_fk', 'LEFT');
        $this->db->join('tipos_servicos', 'tipos_servicos.tipo_servico_pk = servicos.tipo_servico_fk');
        $this->db->join('situacoes','situacoes.situacao_pk = historicos_ordens.situacao_fk');
        $this->db->join('prioridades','prioridades.prioridade_pk = ordens_servicos.prioridade_fk');
        $this->db->join('procedencias','procedencias.procedencia_pk = ordens_servicos.procedencia_fk');
        $this->db->join('setores','setores.setor_pk= ordens_servicos.setor_fk');
        $this->db->join('coordenadas','coordenadas.coordenada_pk = ordens_servicos.coordenada_fk');
        $this->db->join('locais', 'coordenadas.local_fk = locais.local_pk');
        $this->db->join('logradouros', 'locais.logradouro_fk = logradouros.logradouro_pk');
        $this->db->join('bairros', 'locais.bairro_fk = bairros.bairro_pk');
        $this->db->join('municipios', 'bairros.municipio_fk = municipios.municipio_pk');
        $this->db->join('departamentos', 'departamentos.departamento_pk = tipos_servicos.departamento_fk');
        // $this->db->join('ordens_servicos', 'ordens_servicos.ordem_servico_pk = relatorios_os.os_fk');
        $this->db->group_by('ordens_servicos.ordem_servico_pk');

        if ($where !== NULL) {
            if (is_array($where)) {
                foreach ($where as $field=>$value) {
                    $this->db->where($field, $value);
                }
            } else {
                $this->db->where(self::PRI_INDEX, $where);
            }
        }

        //echo $this->db->get_compiled_select(); die();

        $result = $this->db->get()->result();
        if ($result) {
            return ($result);
        } else {
            return false;
        }
    }

    public function get_relatorio($id_relatorio){
       $this->db->select('*');
       $this->db->from('relatorios');
       $this->db->where('relatorio_pk', $id_relatorio);

        $result = $this->db->get()->row();

        if ($result) {
            return ($result);
        } else {
            return false;
        }
    }

    public function get_os_nao_verificadas($id_relatorio = NULL)
    {
        $this->db->select('*');
        $this->db->from('relatorios_os');
        $this->db->join('relatorios', 'relatorios_os.relatorio_fk = relatorios.relatorio_pk');
        $this->db->where('relatorios.status', '0');
        $this->db->where('relatorios_os.os_verificada', '0');
        if($id_relatorio != NULL){
            $this->db->where('relatorios.relatorio_pk', $id_relatorio);
        }

        $result =  $this->db->get()->result();
        
        if ($result) 
        {
            return ($result);
        } 
        else 
        {
            return false;
        }
    }

    public function get_relatorios(){
        $this->db->select('*');
        $this->db->from('relatorios');
        $this->db->join('funcionarios', 'relatorios.funcionario_fk = funcionarios.funcionario_pk');
        $this->db->join('funcionarios_funcoes', 'funcionarios.funcionario_pk = funcionarios_funcoes.funcionario_fk');
        $this->db->join('funcoes', 'funcionarios_funcoes.funcao_fk = funcoes.funcao_pk');
        $this->db->join('populacao', 'funcionarios.pessoa_fk = populacao.pessoa_pk');

        $this->db->order_by('relatorios.data_criacao', 'DESC');

        $relatorios = $this->db->get()->result();

        //quero pegar agora, as ordens vinculadas a este relatório, quantas estão finalizadas, quantas estão em andamento ainda, ...
        if($relatorios)
        {
            foreach($relatorios as $relatorio)
            {

                $relatorio->quantidade_os = $this->db->where(['relatorio_fk'=>$relatorio->relatorio_pk])->from("relatorios_os")->count_all_results();

                //quantidade de  OS concluída:
                // $this->db->select('*');
                // $this->db->from('historicos_ordens');
                // $this->db->join('relatorios_os', 'relatorios_os.os_fk = historios_ordens.ordem_servico_fk');
                // $this->db->where('relatorios_os.relatorio_fk', $relatorio->relatorio_pk);

                // $historicos_ordens = $this->db->get()->result();

                // $quantidade_finalizada = 0;

                // foreach($historicos_ordens as $historico_ordem){
                //     if($historico_ordem->situacao_fk == 5){ //5 é finalizado
                //         $quantidade_finalizada++;
                //     }
                // }

                // if($relatorio->quantidade_os == 0 ){
                //     $relatorio->progresso = 100;
                // }else{
                //     $relatorio->progresso = ($quantidade_finalizada/$relatorio->quantidade_os) * 100;
                    
                // }
            }

            return $relatorios;
        }else{ //se result deu erro:
            return false;
        }

    }

    public function get_filtro_relatorio_data($id_relatorio){
        $this->db->select('*');
        $this->db->from('filtros_relatorios_data');
        $this->db->where(['relatorio_fk' => $id_relatorio]);
        $result = $this->db->get()->row();
        if ($result) {
            return ($result);
        } else {
            return false;
        }
    }

    public function get_filtro_relatorio_setores($id_relatorio){
        $this->db->select('*');
        $this->db->from('filtros_relatorios_setores');
        $this->db->join('setores', 'filtros_relatorios_setores.setor_fk = setores.setor_pk');
        $this->db->where(['relatorio_fk' => $id_relatorio]);
        $result = $this->db->get()->result();
        if ($result) {
            return ($result);
        } else {
            return false;
        }
    }

    public function get_filtro_relatorio_tipos_servicos($id_relatorio){
        $this->db->select('*');
        $this->db->from('filtros_relatorios_tipos_servicos');
        $this->db->join('tipos_servicos', 'filtros_relatorios_tipos_servicos.tipo_servico_fk = tipos_servicos.tipo_servico_pk');
        $this->db->where(['relatorio_fk' => $id_relatorio]);
        $result = $this->db->get()->result();
        if ($result) {
            return ($result);
        } else {
            return false;
        }
    }

    public function get_relatorio_do_funcionario($id_funcionario){
       $this->db->select('relatorio_pk');
       $this->db->from('relatorios');
       //$this->db->where('DAY(data_criacao) = DAY(NOW())');
       $this->db->order_by('data_criacao', 'DESC');
       $this->db->where('status = 0');
       $this->db->where('funcionario_fk', $id_funcionario);

        $result = $this->db->get()->row();
        if ($result) {
            return ($result);
        } else {
            return false;
        }
    }

    /**
     * Inserts new data into database
     *
     * @param Array $data Associative array with field_name=>value pattern to be inserted into database
     * @return mixed Inserted row ID, or false if error occured
     */
    public function insert(Array $data) {
        if ($this->db->insert(self::TABLE_NAME, $data)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function insert_relatorios_os(Array $data){
        if ($this->db->insert('relatorios_os', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function insert_filtro_data( $inicio, $fim, $relatorio_fk){
       if($this->db->insert('filtros_relatorios_data', ['filtros_relatorios_data_inicio' => date('Y-m-d', strtotime($inicio)), 'filtros_relatorios_data_fim' => date('Y-m-d', strtotime($fim)), 'relatorio_fk' => $relatorio_fk])){
        return true;
       } else {
        return false;
       }
    }

    public function insert_filtro_setor($relatorio_id, $setor_id){
        if( $this->db->insert('filtros_relatorios_setores', ['relatorio_fk' => $relatorio_id, 'setor_fk' => $setor_id]) ) {
            return true;
        } else {
            return false;
        }
    }

    public function insert_filtro_tipo($relatorio_id, $tipo_id){
        if( $this->db->insert('filtros_relatorios_tipos_servicos', ['relatorio_fk' => $relatorio_id, 'tipo_servico_fk' => $tipo_id]) ) {
            return true;
        } else {
            return false;
        }
    }

    public function insert_os_nao_concluida($os, $relatorio)
    {
        if( $this->db->insert('relatorio_os_nao_concluidas', ['relatorio_fk' => $relatorio, 'ordem_servico_fk' => $os])) 
        {
            return true;
        } 
        else 
        {
            return false;
        }
    }

    /**
     * Updates selected record in the database
     *
     * @param Array $data Associative array field_name=>value to be updated
     * @param Array $where Optional. Associative array field_name=>value, for where condition. If specified, $id is not used
     * @return int Number of affected rows by the update query
     */

    // public function update(array $data, $where = array())
    // {
    //     if (!is_array($where)) {
    //         $where = array(self::PRI_INDEX => $where);
    //     }
    //     $this->db->update(self::TABLE_NAME, $data, $where);
    //     return $this->db->affected_rows();
    // }

    public function update(Array $data, $where = array()) {
        if (!is_array($where)) {
            $where = array(self::PRI_INDEX => $where);
        }
        $this->db->update(self::TABLE_NAME, $data, $where);
        return $this->db->affected_rows();
    }


    public function update_relatorios_os_verificada($os)
    {
        $data = array('os_verificada' => '1');
        $where = array('os_fk' => $os);
        $this->db->update('relatorios_os', $data, $where);
    }


    public function delete_relatorios_os($where = array())
    {
        if (!is_array($where)) {
            $where = array('relatorio_fk' => $where);
        }
        $this->db->delete('relatorios_os', $where);
        return $this->db->affected_rows();
    }


    public function delete_filtros_data($where = array())
    {
        if (!is_array($where)) {
            $where = array('relatorio_fk' => $where);
        }
        $this->db->delete('filtros_relatorios_data', $where);
        return $this->db->affected_rows();
    }

    public function delete_filtros_setores($where = array())
    {
        if (!is_array($where)) {
            $where = array('relatorio_fk' => $where);
        }
        $this->db->delete('filtros_relatorios_setores', $where);
        return $this->db->affected_rows();
    }

    public function delete_filtros_tipos_servicos($where = array())
    {
        if (!is_array($where)) {
            $where = array('relatorio_fk' => $where);
        }
        $this->db->delete('filtros_relatorios_tipos_servicos', $where);
        return $this->db->affected_rows();
    }




    /**
     * Deletes specified record from the database
     *
     * @param Array $where Optional. Associative array field_name=>value, for where condition. If specified, $id is not used
     * @return int Number of rows affected by the delete query
     */
    public function delete($where = array())
    {
        if (!is_array($where)) {
            $where = array(self::PRI_INDEX => $where);
        }
        $this->db->delete(self::TABLE_NAME, $where);
        return $this->db->affected_rows();
    }


    public function delete_relatorio_os($os)
    {
        $where = array('os_fk' => $os);
        $this->db->delete('relatorios_os', $where);

        return $this->db->affected_rows();
    }
}
