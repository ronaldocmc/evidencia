
<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require 'vendor/autoload.php';
require_once APPPATH . "core\CRUD_Controller.php";
require_once dirname(__FILE__) . "/Response.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class Export extends CRUD_Controller
{
    const EXCEL_FORMAT = ".xls";
    public $response;

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('America/Sao_Paulo');
        $this->load->helper('exception');
        $this->response = new Response();
    }

    public function execute()
    {   
        try{
            $spreadsheet = new Spreadsheet();
            $spreadsheet->setActiveSheetIndex(0);
    
            $writer = new Xls($spreadsheet);
    
            $this->_fillXls($spreadsheet);
            $this->_setHeader();
            
            ob_end_clean();
            
            $writer->save('php://output', 'xls');

        } catch (MyException $e) {
            handle_my_exception($e);
        } catch (Exception $e) {
            handle_exception($e);
        }
    }

    private function _setHeading($row, $activeSheet)
    {
        $keys = array_keys($row);

        $index = 0;
        foreach($keys as $k)
        {
            $letter = chr(65 + $index);
            $activeSheet->setCellValue($letter.'1', $k);
            $index++;
        }
    }

    private function _fillXls($spreadsheet)
    {
        $activeSheet = $spreadsheet->getActiveSheet();

        $data = $this->_getData();
        
        $heading = false;

        $index = 2;
        
        foreach($data as $row)
        {
            $row = (array) $row;

            if(!$heading)
            {
                $this->_setHeading($row, $activeSheet);
                $heading = true;
            }

            $i = 0;
            foreach($row as $v)
            {   
                $letter = chr(65 + $i);
                $activeSheet->setCellValue(''.$letter.($index), $v);
                $i++;
            }
            
            $index++;
        }

    }

    private function _getData()
    {
        print_r($_GET);

        if(!isset($_GET['data_inicial']) || !isset($_GET['data_final'])){
            throw new MyException('Data inicial e data final devem ser preenchidos.', 
                Response::NOT_FOUND);
        }


        $this->load->model('Ordem_Servico_model', 'ordem_servico');

        $ordens_servicos = $this->ordem_servico->get_all(
            'ordens_servicos.ordem_servico_cod as codigo, 
             ordens_servicos.ativo, 
             ordens_servicos.ordem_servico_desc as descricao, 
             ordens_servicos.ordem_servico_criacao as data_criacao,
             ordens_servicos.ordem_servico_finalizacao as data_finalizacao,
             ordens_servicos.ordem_servico_comentario as comentario,
             prioridades.prioridade_nome as prioridade,
             procedencias.procedencia_nome as procedencia,
             servicos.servico_nome as servico,
             setores.setor_nome as setor,
             funcionarios.funcionario_nome as funcionario,
             situacoes.situacao_nome as situacao_atual', 
            [
                'ordem_servico_criacao >=' => $_GET['data_inicial'].' 00:00:01',
                'ordem_servico_criacao <=' => $_GET['data_final'].' 23:59:59'
            ], 
            -1, 
            -1,
            [
                ['table' => 'prioridades', 'on' => 'ordens_servicos.prioridade_fk = prioridades.prioridade_pk'],
                ['table' => 'procedencias', 'on' => 'ordens_servicos.procedencia_fk = procedencias.procedencia_pk'],
                ['table' => 'servicos', 'on' => 'ordens_servicos.servico_fk = servicos.servico_pk'],
                ['table' => 'setores', 'on' => 'ordens_servicos.setor_fk = setores.setor_pk'],
                ['table' => 'funcionarios', 'on' => 'ordens_servicos.funcionario_fk = funcionarios.funcionario_pk'],
                ['table' => 'situacoes', 'on' => 'ordens_servicos.situacao_atual_fk = situacoes.situacao_pk'],                
            ]
        );

        foreach($ordens_servicos as $os)
        {
            $os->ativo = ($os->ativo == 1) ? 'Sim' : 'Não';
            $os->data_criacao = $this->_formatDate($os->data_criacao);
            $os->data_finalizacao = (!empty($os->data_finalizacao))? $this->_formatDate($os->data_finalizacao): '';
        }

        return $ordens_servicos;
    }

    private function _formatDate($date){
        return date('d/m/Y H:i:s', strtotime($date));
    }

    private function _setHeader()
    {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $this->_getFile() .'"');
        header('Cache-Control: max-age=0');
    }

    private function _getFile($name = NULL)
    {
        if($name == NULL)
        {
            $name = "relatorio geral - ".date('d-m-Y H-i-s');
        }

        return $name . self::EXCEL_FORMAT;
    }
}