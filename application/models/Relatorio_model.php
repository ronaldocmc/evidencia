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
        'pegou_no_celular',
        'relatorio_data_entrega',
        'relatorio_criador',
        'relatorio_data_inicio_filtro',
        'relatorio_data_fim_filtro',
    );

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

        return $this->CI->db->get()->result();
    }

}
    /*
    //SUBSTITUIDA PELA GET ONE

    // public function get_relatorio($id_relatorio){
    //    $this->db->select('*');
    //    $this->db->from('relatorios');
    //    $this->db->where('relatorio_pk', $id_relatorio);

    //     $result = $this->db->get()->row();

    //     if ($result) {
    //         return ($result);
    //     } else {
    //         return false;
    //     }
    // }

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


    /* /SUBSTITUÍDA PELA GET_ALL 
    public function get_relatorios(){
        $this->db->select('*');
        $this->db->from('relatorios');
        $this->db->join('funcionarios', 'relatorios.funcionario_fk = funcionarios.funcionario_pk');
        $this->db->join('funcionarios_funcoes', 'funcionarios.funcionario_pk = funcionarios_funcoes.funcionario_fk');
        $this->db->join('funcoes', 'funcionarios_funcoes.funcao_fk = funcoes.funcao_pk');
        $this->db->join('populacao', 'funcionarios.pessoa_fk = populacao.pessoa_pk');

        $this->db->order_by('relatorios.data_criacao', 'DESC');

        $this->db->where('relatorios.status != 2');

        $relatorios = $this->db->get()->result();

        //quero pegar agora, as ordens vinculadas a este relatório, quantas estão finalizadas, quantas estão em andamento ainda, ...
        if($relatorios)
        {
            foreach($relatorios as $relatorio)
            {
                $relatorio->quantidade_os = $this->db->where(['relatorio_fk'=>$relatorio->relatorio_pk])->from("relatorios_os")->count_all_results();
            }

            return $relatorios;
        }else{ //se result deu erro:
            return false;
        }

    }


    public function get_objects($where = NULL)
    {
        $this->db->select("*");
        $this->db->from('relatorios');
        if ($where !== NULL) {
            if (is_array($where)) {
                foreach ($where as $field=>$value) {
                    $this->db->where($field, $value);
                }
            } else {
                $this->db->where(self::PRI_INDEX, $where);
            }
        }
        $result = $this->db->get()->result();
        return $result;


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
     
    public function insert(Array $data) {
        if ($this->db->insert(self::TABLE_NAME, $data)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function insert_report_os(Array $data){
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

    /**
     * Updates selected record in the database
     *
     * @param Array $data Associative array field_name=>value to be updated
     * @param Array $where Optional. Associative array field_name=>value, for where condition. If specified, $id is not used
     * @return int Number of affected rows by the update query
     

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


    public function update_relatorios_os_verificada($where = array(), $status)
    {
        $data = array('os_verificada' => $status);
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

    public function disable($relatorio_pk)
    {
        $this->db->update(self::TABLE_NAME, ['status' => 2], $relatorio_pk);
        return $this->db->affected_rows();
    }

    public function set_data_entrega($relatorio_pk)
    {
        $this->db->query('UPDATE relatorios SET data_entrega = NOW() WHERE relatorio_pk = ' . $relatorio_pk);
    }

    /**
     * Deletes specified record from the database
     *
     * @param Array $where Optional. Associative array field_name=>value, for where condition. If specified, $id is not used
     * @return int Number of rows affected by the delete query
     
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
    */


?>
