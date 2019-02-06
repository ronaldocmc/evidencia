<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ordem_Servico_model extends CI_Model {

    /**
     * @name string TABLE_NAME Holds the name of the table in use by this model
     */
    const TABLE_NAME = 'ordens_servicos';

    /**
     * @name string PRI_INDEX Holds the name of the tables' primary index used in this model
     */
    const PRI_INDEX = 'ordem_servico_pk';

    /**
     * Retrieves record(s) from the database
     *
     * @param mixed $where Optional. Retrieves only the records matching given criteria, or all records if not given.
     *                      If associative array is given, it should fit field_name=>value pattern.
     *                      If string, value will be used to match against PRI_INDEX
     * @return mixed Single record if ID is given, or array of results
     */
    public function get($where = NULL) {
        $this->db->select('*');
        $this->db->from(self::TABLE_NAME);
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
        if ($result) {
            return ($result);
        } else {
            return false;
        }
    }



    public function getHome($where = NULL) {
        $this->db->select('ordens_servicos.ordem_servico_cod,ordens_servicos.ordem_servico_pk,ordens_servicos.ordem_servico_desc, ordens_servicos.ordem_servico_status, servicos.servico_nome, servicos.servico_pk, servicos.situacao_padrao_fk, prioridades.prioridade_pk, prioridades.prioridade_nome, procedencias.procedencia_pk, procedencias.procedencia_nome, servicos.tipo_servico_fk, historicos_ordens.historico_ordem_pk, coordenadas.local_fk, coordenadas.coordenada_lat, coordenadas.coordenada_long, setores.setor_pk, locais.local_num, logradouros.logradouro_nome, 
            populacao_os.populacao_os_pk, 
            populacao.pessoa_nome, populacao.pessoa_cpf, contatos.contato_email, contatos.contato_cel, 
            contatos.contato_tel, bairros.bairro_nome, setores.setor_nome, (SELECT historicos_ordens.situacao_fk FROM historicos_ordens WHERE 
            historicos_ordens.ordem_servico_fk = ordens_servicos.ordem_servico_pk  ORDER BY 
            historicos_ordens.historico_ordem_tempo DESC LIMIT 1) as situacao_atual_pk,(SELECT 
            historicos_ordens.situacao_fk FROM historicos_ordens WHERE historicos_ordens.ordem_servico_fk = 
            ordens_servicos.ordem_servico_pk  ORDER BY historicos_ordens.historico_ordem_tempo ASC LIMIT 1) as 
            situacao_inicial_pk ,(SELECT situacoes.situacao_nome FROM historicos_ordens JOIN situacoes ON 
            historicos_ordens.situacao_fk = situacoes.situacao_pk WHERE historicos_ordens.ordem_servico_fk = 
            ordens_servicos.ordem_servico_pk  ORDER BY historicos_ordens.historico_ordem_tempo DESC LIMIT 1) as 
            situacao_nome,(SELECT historicos_ordens.historico_ordem_tempo FROM historicos_ordens WHERE 
            historicos_ordens.ordem_servico_fk = ordens_servicos.ordem_servico_pk  ORDER BY 
            historicos_ordens.historico_ordem_tempo ASC LIMIT 1) as data_criacao');
        $this->db->from(self::TABLE_NAME);
        $this->db->join('servicos','servicos.servico_pk = '.self::TABLE_NAME.'.servico_fk');
        $this->db->join('historicos_ordens','historicos_ordens.ordem_servico_fk = '.self::TABLE_NAME. '.'.self::PRI_INDEX);
        $this->db->join('situacoes','situacoes.situacao_pk = historicos_ordens.situacao_fk');
        $this->db->join('prioridades','prioridades.prioridade_pk = '
            .self::TABLE_NAME.'.prioridade_fk');
        $this->db->join('procedencias','procedencias.procedencia_pk = '
            .self::TABLE_NAME.'.procedencia_fk');
        $this->db->join('setores','setores.setor_pk= '
            .self::TABLE_NAME.'.setor_fk');
        $this->db->join('coordenadas','coordenadas.coordenada_pk = '
            .self::TABLE_NAME.'.coordenada_fk');
        $this->db->join('locais','locais.local_pk = coordenadas.local_fk');
        $this->db->join('logradouros','logradouros.logradouro_pk = locais.logradouro_fk');
        $this->db->join('bairros','bairros.bairro_pk = locais.bairro_fk');
        //Após inserção do cidadão na ordem de serviço
        $this->db->join('populacao_os','populacao_os.ordem_servico_fk = '.self::TABLE_NAME. '.'.self::PRI_INDEX, 'LEFT');
        $this->db->join('populacao','populacao.pessoa_pk = populacao_os.pessoa_fk', 'LEFT');
        $this->db->join('contatos', 'contatos.pessoa_fk = populacao_os.pessoa_fk', 'LEFT');
        $this->db->group_by(self::TABLE_NAME. '.'.self::PRI_INDEX);
        $this->db->order_by(self::TABLE_NAME. '.'.self::PRI_INDEX, 'DESC');
        $this->db->limit(500);


        if ($where !== NULL) {
            if (is_array($where)) {
                foreach ($where as $field=>$value) {
                    $this->db->where($field, $value);
                }
            } else {
                $this->db->where(self::PRI_INDEX, $where);
            }
        }
        // echo $this->db->get_compiled_select(); die();
        $result = $this->db->get()->result();
        if ($result) {
            return ($result);
        } else {
            return false;
        }
    }

    public function getJsonForMobile($where) {
        $ordens = $this->db->query("CALL getForMobile('".$where['id_organizacao']."',".$where['id_funcionario'].")");
        return $ordens->result();
    }

    public function insert_os_populacao($data){
        $this->db->insert('populacao_os', $data);
        return $this->db->insert_id();
    }


    public function getJsonForWeb($where = NULL) {

        $this->db->select(self::TABLE_NAME. '.'.self::PRI_INDEX. ' AS id,
            coordenadas.coordenada_lat AS latitude,
            coordenadas.coordenada_long AS longitude,
            tipos_servicos.departamento_fk AS departamento,
            servicos.servico_pk AS servico,
            servicos.tipo_servico_fk AS tipo_servico,
            ordens_servicos.ordem_servico_pk AS ordem_servico_pk,
            logradouros.logradouro_nome as rua,
            locais.local_num as numero,
            locais.local_referencia as ponto_referencia,
            bairros.bairro_nome as bairro,
            setores.setor_nome as setor,
            ordens_servicos.prioridade_fk AS prioridade,
            MIN(historicos_ordens.historico_ordem_tempo) AS data_criacao,
            (SELECT historicos_ordens.situacao_fk FROM historicos_ordens WHERE historicos_ordens.ordem_servico_fk = ordens_servicos.ordem_servico_pk ORDER BY historicos_ordens.historico_ordem_tempo DESC LIMIT 1) as situacao_atual_pk
            ');

        $this->db->from(self::TABLE_NAME);

        $this->db->join('coordenadas',
            'coordenadas.coordenada_pk = '.self::TABLE_NAME.'.coordenada_fk');

        $this->db->join('locais',
            'locais.local_pk = coordenadas.local_fk');

        $this->db->join('logradouros',
            'logradouros.logradouro_pk = locais.logradouro_fk');

        $this->db->join('bairros',
            'bairros.bairro_pk = locais.bairro_fk');

        $this->db->join('servicos','servicos.servico_pk = '.self::TABLE_NAME.'.servico_fk');

        $this->db->join('tipos_servicos',
            'tipos_servicos.tipo_servico_pk = servicos.tipo_servico_fk');

        $this->db->join('departamentos',
            'departamentos.departamento_pk = tipos_servicos.departamento_fk');

        $this->db->join('historicos_ordens','historicos_ordens.ordem_servico_fk = '.self::TABLE_NAME. '.'.self::PRI_INDEX);

        $this->db->join('setores', 'setores.setor_pk = '.self::TABLE_NAME.'.setor_fk');

        $this->db->group_by(self::TABLE_NAME. '.'.self::PRI_INDEX);
        $this->db->limit(100);

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

        if ($result) {
            return ($result);
        } else {
            return false;
        }
    }

    public function getEspecifico($where = NULL){
        $this->db->select('
        ordens_servicos.ordem_servico_cod AS codigo,
        ordens_servicos.ordem_servico_pk,
        ordens_servicos.ordem_servico_desc AS descricao,
        servicos.servico_nome AS servico,
        prioridades.prioridade_nome AS prioridade,
        ');
        $this->db->from(self::TABLE_NAME);

        $this->db->join('servicos','servicos.servico_pk = '.self::TABLE_NAME.'.servico_fk');

        $this->db->join('tipos_servicos',
            'tipos_servicos.tipo_servico_pk = servicos.tipo_servico_fk');

        $this->db->join('departamentos',
            'departamentos.departamento_pk = tipos_servicos.departamento_fk');

        $this->db->join('prioridades',
        'prioridades.prioridade_pk = ordens_servicos.prioridade_fk');

        $this->db->join('procedencias',
        'procedencias.procedencia_pk = ordens_servicos.procedencia_fk');

        if ($where !== NULL) {
            if (is_array($where)) {
                foreach ($where as $field=>$value) {
                    $this->db->where($field, $value);
                }
            } else {
                $this->db->where(self::PRI_INDEX, $where);
            }
        }

        // echo $this->db->get_compiled_select();die();
        $result = $this->db->get()->row_array();

        if ($result) {
            return ($result);
        } else {
            return false;
        }
    }

    public function getCidadao($where = null){
        $this->db->select('
        ordens_servicos.ordem_servico_pk,
        ordens_servicos.ordem_servico_desc,
        ordens_servicos.ordem_servico_cod,
        ordens_servicos.ordem_servico_status,
        servicos.servico_nome,
        prioridades.prioridade_nome,
        procedencias.procedencia_nome,
        coordenadas.coordenada_lat,
        coordenadas.coordenada_long,
        setores.setor_nome,
        (SELECT situacoes.situacao_pk FROM historicos_ordens JOIN situacoes ON historicos_ordens.situacao_fk = situacoes.situacao_pk WHERE historicos_ordens.ordem_servico_fk = ordens_servicos.ordem_servico_pk  ORDER BY historicos_ordens.historico_ordem_tempo DESC LIMIT 1) as situacao_atual,
        (SELECT situacoes.situacao_nome FROM historicos_ordens JOIN situacoes ON historicos_ordens.situacao_fk = situacoes.situacao_pk WHERE historicos_ordens.ordem_servico_fk = ordens_servicos.ordem_servico_pk  ORDER BY historicos_ordens.historico_ordem_tempo DESC LIMIT 1) as situacao_atual_nome,
        (SELECT situacoes.situacao_nome FROM historicos_ordens JOIN situacoes ON historicos_ordens.situacao_fk = situacoes.situacao_pk WHERE historicos_ordens.ordem_servico_fk = ordens_servicos.ordem_servico_pk  ORDER BY historicos_ordens.historico_ordem_tempo ASC LIMIT 1) as situacao_inicial,
        (SELECT historicos_ordens.historico_ordem_tempo FROM historicos_ordens WHERE historicos_ordens.ordem_servico_fk = ordens_servicos.ordem_servico_pk  ORDER BY historicos_ordens.historico_ordem_tempo ASC LIMIT 1) as data_criacao,
        tipos_servicos.tipo_servico_nome,
        locais.local_complemento,
        locais.local_num,
        logradouros.logradouro_nome,
        bairros.bairro_nome,
        municipios.municipio_nome,
        municipios.estado_fk,
        coordenadas.local_fk, departamento_nome')
        ;
        $this->db->from('ordens_servicos','LEFT');
        $this->db->join('servicos','servicos.servico_pk = ordens_servicos.servico_fk','LEFT');
        $this->db->join('historicos_ordens','historicos_ordens.ordem_servico_fk =  ordens_servicos.ordem_servico_pk','LEFT');
        //$this->db->join('imagens_situacoes', 'historicos_ordens.historico_ordem_pk = imagens_situacoes.historico_ordem_fk', 'LEFT','LEFT');
        $this->db->join('tipos_servicos', 'tipos_servicos.tipo_servico_pk = servicos.tipo_servico_fk','LEFT');
        $this->db->join('situacoes','situacoes.situacao_pk = historicos_ordens.situacao_fk','LEFT');
        $this->db->join('prioridades','prioridades.prioridade_pk = ordens_servicos.prioridade_fk','LEFT');
        $this->db->join('procedencias','procedencias.procedencia_pk = ordens_servicos.procedencia_fk','LEFT');
        $this->db->join('setores','setores.setor_pk= ordens_servicos.setor_fk','LEFT');
        $this->db->join('coordenadas','coordenadas.coordenada_pk = ordens_servicos.coordenada_fk','LEFT');
        $this->db->join('locais', 'coordenadas.local_fk = locais.local_pk','LEFT');
        $this->db->join('logradouros', 'locais.logradouro_fk = logradouros.logradouro_pk','LEFT');
        $this->db->join('bairros', 'locais.bairro_fk = bairros.bairro_pk','LEFT');
        $this->db->join('municipios', 'bairros.municipio_fk = municipios.municipio_pk','LEFT');
        $this->db->join('departamentos', 'departamentos.departamento_pk = tipos_servicos.departamento_fk','LEFT');
        $this->db->group_by('ordens_servicos.ordem_servico_pk','LEFT');

        if ($where !== NULL) {
            if (is_array($where)) {
                foreach ($where as $field=>$value) {
                    $this->db->like($field, $value);
                }
            } else {
                $this->db->like(self::PRI_INDEX, $where);
            }
        }

        // echo $this->db->get_compiled_select(); die();

        $result = $this->db->get()->row();
        if ($result) {
            return ($result);
        } else {
            return false;
        }
    }

    public function getHistorico($where = NULL){
        $this->db->select('
            populacao.pessoa_nome AS funcionario,
            historicos_ordens.historico_ordem_tempo AS data,
            situacoes.situacao_nome AS situacao,
            historicos_ordens.historico_ordem_comentario AS comentario,
            imagens_situacoes.imagem_situacao_caminho as foto,
            imagens_perfil.imagem_caminho as funcionario_foto
        ');

        $this->db->from('historicos_ordens');
        $this->db->join('funcionarios','historicos_ordens.funcionario_fk = funcionarios.funcionario_pk');
        $this->db->join('populacao', 'funcionarios.pessoa_fk = populacao.pessoa_pk');
        $this->db->join('situacoes', 'situacoes.situacao_pk = historicos_ordens.situacao_fk');
        $this->db->join('imagens_situacoes', 'imagens_situacoes.historico_ordem_fk = historicos_ordens.historico_ordem_pk','LEFT');
        $this->db->join('imagens_perfil', 'populacao.pessoa_pk = imagens_perfil.pessoa_fk','LEFT');



        if ($where !== NULL) {
            if (is_array($where)) {
                foreach ($where as $field=>$value) {
                    $this->db->where($field, $value);
                }
            } else {
                $this->db->where('historicos_ordens.ordem_servico_fk', $where);
            }
        }

        $this->db->order_by('historicos_ordens.historico_ordem_tempo', 'ASC');

         // echo $this->db->get_compiled_select();die();
        $result = $this->db->get()->result();

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

    public function insert_image(Array $data) {
        $this->db->insert('imagens_situacoes', $data);
        return $this->db->error();
    }


    public function insert_os(Array $data) {
        if ($this->db->insert(self::TABLE_NAME, $data)) {
            $return = [
                'id'=> $this->db->insert_id(),
                'db_error' =>$this->db->error() 
            ];

        } else {
            $return = [
                'id'=> NULL,
                'db_error' =>$this->db->error()
            ];
        }
        return $return;
    }

    public function get_populacao_os($where){
        $this->db->select('*');
        $this->db->from('populacao_os');
        $this->db->where($where);

        // $result = $this->db->get_compiled_select();
        // print_r($result); die();
         $result = $this->db->get()->result();
         return $result;
    }

    public function get_coordenadas(Array $where){
        $this->db->select('coordenada_pk, local_fk');
        $this->db->from('coordenadas');

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
        if ($result) {
            if ($where !== NULL) {
                return array_shift($result);
            } else {
                return $result;
            }
        } else {
            return false;
        }
    }

    public function insert_coordenada(Array $data) {
        if ($this->db->insert('coordenadas', $data)) {

            $return = [
                'id'=> $this->db->insert_id(),
                'db_error' => $this->db->error() 
            ];
        } 
        else 
        {
            $return = [
                'id'=> NULL,
                'db_error' =>$this->db->error()
            ];
        }

        return $return;
    }

    /**
     * Updates selected record in the database
     *
     * @param Array $data Associative array field_name=>value to be updated
     * @param Array $where Optional. Associative array field_name=>value, for where condition. If specified, $id is not used
     * @return int Number of affected rows by the update query
     */
    public function update_os(Array $data, $where = array()) {
        if (!is_array($where)) {
            $where = array(self::PRI_INDEX => $where);
        }
        $this->db->update(self::TABLE_NAME, $data, $where);
        return $this->db->affected_rows();
    }

    /**
     * Deletes specified record from the database
     *
     * @param Array $where Optional. Associative array field_name=>value, for where condition. If specified, $id is not used
     * @return int Number of rows affected by the delete query
     */
    public function update_status($where = array(), Array $data) {

        if (!is_array($where)) {
            $where = array(self::PRI_INDEX => $where);
        }
        $this->db->update(self::TABLE_NAME, $data, $where);
        return $this->db->affected_rows();
    }


    public function get_ids_os($organizacao_fk) {
        $this->db->select(self::TABLE_NAME.'.'.self::PRI_INDEX);

        $this->db->from(self::TABLE_NAME);
        $this->db->join('prioridades','prioridades.prioridade_pk = '.self::TABLE_NAME.'.prioridade_fk');

        $this->db->where('prioridades.organizacao_fk', $organizacao_fk);

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


    public function get_os_relatorio($where) {
        if($where['relatorio'] == 'setor')
        {
            $query = 
            $this->db->query("CALL relatorio_setor(".$where['setor'].",".$where['situacao'].")");
        }
        else if($where['relatorio'] == 'data')
        {
            $query = 
            $this->db->query("CALL relatorio_data(".$where['qtd_dias'].",".$where['situacao'].")");
        }
        else if($where['relatorio'] == 'departamento')
        {
            $query = 
            $this->db->query("CALL relatorio_departamento(".$where['departamento'].",".$where['situacao'].")");
        }
        else if($where['relatorio'] == 'servico')
        {
            $query = 
            $this->db->query("CALL relatorio_servico(".$where['servico'].",".$where['situacao'].")");
        }
        return $query->result();
    }


    public function get_cont_and_update($organizacao_fk)
    {
        $this->db->select('contador_cod_os.proximo_cod');
        $this->db->from('contador_cod_os');
        $this->db->where('contador_cod_os.organizacao_fk', $organizacao_fk);

        $proximo_cod = $this->db->get()->result();

        $this->db->update('contador_cod_os', [
            'proximo_cod' => $proximo_cod[0]->proximo_cod + 1
        ]);

        return $proximo_cod[0]->proximo_cod;
    }

    public function get_os_novo_relatorio($where){
        $query = '';

        // $query.="SELECT DISTINCT ".self::TABLE_NAME.'.'.self::PRI_INDEX;
        // $query.=" FROM ".self::TABLE_NAME;
        $query.= "SELECT ordens_servicos.ordem_servico_pk,ordens_servicos.ordem_servico_desc, ordens_servicos.ordem_servico_status, servicos.servico_nome, servicos.servico_pk, servicos.situacao_padrao_fk, prioridades.prioridade_pk, prioridades.prioridade_nome, procedencias.procedencia_pk, procedencias.procedencia_nome, servicos.tipo_servico_fk, historicos_ordens.historico_ordem_pk, coordenadas.local_fk, coordenadas.coordenada_lat, coordenadas.coordenada_long, setores.setor_pk, setores.setor_nome, 
            (SELECT historicos_ordens.situacao_fk FROM historicos_ordens WHERE historicos_ordens.ordem_servico_fk = ordens_servicos.ordem_servico_pk  ORDER BY historicos_ordens.historico_ordem_tempo DESC LIMIT 1) as situacao_atual_pk,
            (SELECT historicos_ordens.situacao_fk FROM historicos_ordens WHERE historicos_ordens.ordem_servico_fk = ordens_servicos.ordem_servico_pk  ORDER BY historicos_ordens.historico_ordem_tempo ASC LIMIT 1) as situacao_inicial_pk ,(SELECT situacoes.situacao_nome FROM historicos_ordens JOIN situacoes ON historicos_ordens.situacao_fk = situacoes.situacao_pk WHERE historicos_ordens.ordem_servico_fk = ordens_servicos.ordem_servico_pk  ORDER BY historicos_ordens.historico_ordem_tempo DESC LIMIT 1) as situacao_nome,
            (SELECT historicos_ordens.historico_ordem_tempo FROM historicos_ordens WHERE historicos_ordens.ordem_servico_fk = ordens_servicos.ordem_servico_pk  ORDER BY historicos_ordens.historico_ordem_tempo ASC LIMIT 1) as data_criacao
            FROM ordens_servicos ";

        $query.= "INNER JOIN servicos ON ordens_servicos.servico_fk = servicos.servico_pk
        INNER JOIN historicos_ordens ON historicos_ordens.ordem_servico_fk = historicos_ordens.ordem_servico_fk
        INNER JOIN situacoes ON situacoes.situacao_pk = historicos_ordens.situacao_fk
        INNER JOIN prioridades ON ordens_servicos.prioridade_fk = ordens_servicos.prioridade_fk
        INNER JOIN procedencias ON procedencias.procedencia_pk = ordens_servicos.procedencia_fk
        INNER JOIN setores ON setores.setor_pk = ordens_servicos.setor_fk
        INNER JOIN coordenadas ON coordenadas.coordenada_pk = ordens_servicos.coordenada_fk ";

        $query.="INNER JOIN tipos_servicos
            ON tipos_servicos.tipo_servico_pk = servicos.tipo_servico_fk ";

        $query.= "WHERE historico_ordem_tempo BETWEEN '".$where['data_inicial']." 00:00:01' AND '".$where['data_final']." 23:59:59'";
        $query.= " AND ordem_servico_status = 1";

        $query.= $this->fill_query('setor_fk', $where['setor']);
        $query.= $this->fill_query('tipos_servicos.tipo_servico_pk', $where['tipo']);
        $query.= "GROUP BY ordens_servicos.ordem_servico_pk";

        $return = $this->db->query($query);
        return $return->result();

    }

    public function fill_query($key_value, $array){
        $query = '';
        if(isset($array)){
           $i = 0;
           $len = count($array);
           $query .=" AND (";
           foreach($array as $item){
               
                if($i == $len - 1){ //se for o último
                    $query.=$key_value." = ".$item.") ";
                }
                else{
                    $query.=$key_value." = ".$item." OR ";
                    }
                $i++;
            }
        }
        return $query;
    }


    public function get_abreviacoes($servico)
    {
        $this->db->select(
            'servicos.servico_abreviacao,
            tipos_servicos.tipo_servico_abreviacao'
        );

        $this->db->from('tipos_servicos');
        $this->db->join('servicos', 'servicos.tipo_servico_fk = tipos_servicos.tipo_servico_pk');
        $this->db->where('servicos.servico_pk', $servico);

        $result = $this->db->get()->result();

        $abreviacao = $result[0]->tipo_servico_abreviacao . $result[0]->servico_abreviacao . "-";
        return $abreviacao;
    }



}


?>