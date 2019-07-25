<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH.'core/MY_Model.php';
require_once APPPATH.'core/MyException.php';

class Funcionario_model extends MY_Model
{
    const NAME = 'funcionário';
    const TABLE_NAME = 'funcionarios';
    const PRI_INDEX = 'funcionario_pk';

    const FORM = array(
        'funcionario_login',
        'funcionario_nome',
        'funcionario_cpf',
        'funcao_fk',
        'departamento_fk',
        'funcionario_caminho_foto',
    );

    public function config_form_validation()
    {
        $this->CI->form_validation->set_rules(
            'funcionario_login',
            'Login',
            'trim|required|valid_email'
        );

        $this->CI->form_validation->set_rules(
            'funcionario_nome',
            'Nome',
            'trim|required'
        );

        $this->CI->form_validation->set_rules(
            'funcionario_cpf',
            'CPF',
            'trim|required'
        );

        $this->CI->form_validation->set_rules(
            'organizacao_fk',
            'Organizacao',
            'trim|required'
        );
    }

    // @override
    public function get_or_404()
    {
        $this->CI->db->select('*');
        $this->CI->db->from(self::TABLE_NAME);
        $this->CI->db->join('organizacoes', 'organizacoes.organizacao_pk = '.self::TABLE_NAME.'.organizacao_fk');
        $this->CI->db->join('funcoes', 'funcoes.funcao_pk = funcionarios.funcao_fk');

        foreach ($this->object as $k => $v) {
            $this->CI->db->where($k, $this->object[$k]);
        }

        //echo $this->CI->db->get_compiled_select();
        //echo $this->CI->db->get();
        $res = $this->CI->db->get();
        var_dump($res);

        if ($res == null || !$res) {
            throw new MyException('Usuário e/ou senha inválidos.', Response::NOT_FOUND);
        } else {
            return $res;
        }
    }

    public function get($select = '*', $where = null)
    {
        $this->CI->db->select($select);
        $this->CI->db->from(self::TABLE_NAME);
        $this->CI->db->join('funcionarios_setores', 'funcionarios_setores.funcionario_fk = '.self::TABLE_NAME.'.'.self::PRI_INDEX, 'left');
        $this->CI->db->join('organizacoes', 'organizacoes.organizacao_pk = '.self::TABLE_NAME.'.organizacao_fk');
        $this->CI->db->join('funcoes', 'funcoes.funcao_pk = funcionarios.funcao_fk');
        $this->CI->db->group_by('funcionarios.funcionario_pk');

        if ($where !== null) {
            if (is_array($where)) {
                foreach ($where as $field => $value) {
                    $this->CI->db->where($field, $value);
                }
            } else {
                $this->CI->db->where(self::PRI_INDEX, $where);
            }
        }
        // $this->CI->db->where(self::TABLE_NAME . '.ativo', 1);

        // var_dump($this->CI->db->get_compiled_select());die();
        // echo $this->CI->db->get_compiled_select(); die();

        return $this->CI->db->get()->result();
        if ($result) {
            if ($where !== null) {
                return array_shift($result);
            } else {
                return $result;
            }
        } else {
            return false;
        }
    }

    public function get_setores($where)
    {
        $this->CI->db->select('setores.*, funcionarios_setores.*');
        $this->CI->db->from('funcionarios_setores');
        $this->CI->db->join('setores', 'funcionarios_setores.setor_fk = setores.setor_pk');
        $this->CI->db->join('funcionarios', 'funcionarios_setores.funcionario_fk = funcionarios.funcionario_pk');
        foreach ($where as $field => $value) {
            $this->CI->db->where($field, $value);
        }
        // var_dump($this->CI->db->get_compiled_select());die();
        return $this->CI->db->get()->result();
    }

    public function count($where = null)
    {
        $this->CI->db->select('count(*) as total');
        $this->CI->db->from(self::TABLE_NAME);
        if ($where !== null) {
            if (is_array($where)) {
                foreach ($where as $field => $value) {
                    $this->CI->db->where($field, $value);
                }
            } else {
                $this->CI->db->where(self::PRI_INDEX, $where);
            }
        }
        $result = $this->CI->db->get()->row()->total;
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    public function insert_funcionario($data_setores = null)
    {
        $id = $this->insert();

        if ($data_setores !== null) {
            $insert_setores = $this->explode_setores($data_setores, $id);
            $this->CI->db->insert_batch('funcionarios_setores', $insert_setores);
        }
        // var_dump($insert_setores);die();
        return $id;
    }

    public function update_funcionario($id, $data_setores = null)
    {
        $insert_setores = [];

        $this->update();

        if ($data_setores != '' && $data_setores != null) {
            $insert_setores = $this->explode_setores($data_setores, $id);
        }

        $this->CI->db->delete('funcionarios_setores', ['funcionario_fk' => $id]);

        if ($insert_setores != []) {
            $this->CI->db->insert_batch('funcionarios_setores', $insert_setores);
        }
    }

    public function update_image($path, $id)
    {
        $this->CI->db->where('funcionario_pk', $id)
        ->update(self::TABLE_NAME, ['funcionario_caminho_foto' => $path]);
    }

    public function get_image_path($id)
    {
        $this->CI->db->select('funcionario_caminho_foto');
        $this->CI->db->from('funcionarios');
        $this->CI->db->where('funcionario_pk', $id);

        return $this->CI->db->get()->result();
    }

    public function explode_setores($data_setores, $id)
    {
        if (!is_array($data_setores)) {
            $data_setores = explode(',', $data_setores);
        }

        foreach ($data_setores as $key => $set):
            $insert_setores[$key] = array(
                'setor_fk' => $set,
                'funcionario_fk' => $id,
            );
        endforeach;

        return $insert_setores;
    }

    public function get_dependents($funcao)
    {
        $this->CI->db->select('funcionario_nome as name');
        $this->CI->db->from('funcionarios');
        $this->CI->db->where('funcao_fk', $funcao);

        return $this->CI->db->get()->result();
    }

    public function get_revisores($select, $organizacao)
    {
        $this->CI->db->select($select);
        $this->CI->db->from('funcionarios');

        $this->CI->db->join('funcoes_permissoes', 'funcoes_permissoes.funcao_fk = funcionarios.funcao_fk');
        $this->CI->db->join('permissoes', 'permissoes.permissao_pk = funcoes_permissoes.permissao_fk');

        $this->CI->db->where('funcionarios.organizacao_fk', $organizacao);
        $this->CI->db->where('permissoes.acao_fk', 27);

        return $this->CI->db->get()->result();
    }
}
