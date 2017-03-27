<?php
/**
 * Permite generar un archivo csv a partir de una sentencia sql
 * Sin importar la cantidad de datos a procesar.
 */

class Large_Download {

    private $model = null;
    private $grid = null;
    private $con = null;
    private $fp = null;
    private $filename = null;

    public function __construct($params){
        if(isset($_GET['datatable'])){
            foreach(json_decode($_GET['datatable'], true) as $key =>  $param ){
                $_REQUEST[$key] =  $param;
            }
        }
        $_REQUEST['length']  = 1000;
        $_REQUEST['start'] = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
        $this->model = $params['model'];
        $this->grid = $this->model->gridDefinition();
        $this->con = isset($this->grid['db_connection']) ? $this->grid['db_connection'] : $this->model->db;
        $this->model->prepare($this->grid);
    }


    public function save(){
        $this->make();
        ob_start();
        readfile($this->filename);
        $data = ob_get_clean();
        unlink($this->filename);
        return $data;
    }

    public function download(){
        $this->make();
        $fecha = date('Ymdhms');
        header("Content-type: application/csv; charset=UTF-8");
        header("Content-Disposition: attachment; filename=report-{$fecha}.csv");
        header("Pragma: no-cache");
        header("Expires: 0");
        readfile( $this->filename );
        unlink($this->filename);
    }

    private function make(){
        $this->filename = tempnam('/temp', 'report-');
        if($this->filename){
            $this->fp = fopen($this->filename, "w");
            $this->fillData();
        }else{
            throw new Exception('Imposible crear archivo');
        }
    }

    private function fillData(){
        $first = true;
        $data = $this->getData();
        //permite codificar correctamente en excel
        fputs($this->fp, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
        while(count($data) > 0){
             if($first){
                 fputcsv($this->fp, $this->getFields(array_keys($data[0])));
                 $first = false;
             }
            foreach($data as $row){
                fputcsv($this->fp, $row);
            }
            $data = $this->getData();
        }
        fclose($this->fp);
    }

    public function getData(){
        $data = $this->model->getData();
        $_REQUEST['start'] = $_REQUEST['start'] + $_REQUEST['length'];
        return $data;
    }

    /**
     * Formatea las cabeceras
     * @param  array $fields cabeceras de la tabla
     * @return array
     */
    public function getFields($fields){
        $newFields = array();
        foreach ($fields as $v) {
            array_push($newFields, ucwords(str_replace('_', ' ', $v)));
        }
        return $newFields;
    }
}