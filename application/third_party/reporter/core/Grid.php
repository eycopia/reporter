<?php

/**
 * CREATE DYNAMIC GRIDS WITH JQUERY DATATABLES
 * @package Reporter\Core
 * @author Jorge Copia Silva <eycopia@gmail.com>
 * @license https://github.com/eycopia/reporter/blob/master/LICENSE
 */
class Grid extends CI_Model
{

    /**
     * @var string Sql procesado
     */
    private $sqlFiltered = "";

    /**
     * The columns for grid
     * @var array
     */
    protected $columns = array();

    /**
     * Define los botones de descarga y la cantidad filas a mostrar
     * @var null
     */
    protected $utilities = null;

    /**
     * Connexion a la base de datos local o remoto
     * @var DB
     */
    protected $db_connection;

    /**
     * El enlace que sera utilizado para cargar los datos en la grilla
     * @var null
     */
    protected $data_url = null;

    /**
     * @var array types of vars
     */
    protected $varTypes = array();

    private $sqlReport = '';
    /**
     * @var \interfaceAccessDB|null
     */
    private $model = null;

    private $filterGrid;

    private $pagination = true;

    private $avoid_basic_filter =false;
    
    /**
     * 
     * @var iGestorDB
     */
    private $gestor;

    public function __construct(interfaceAccessDB $model){
        $this->model = $model;
        $this->load->model('server_m');
        $this->load->model('report_m');
    }


    public function loadReport($idReport, $idProject=null){
        $this->load->model("admin/AdminReport_m");
        $this->report = $this->report_m->find($idReport);
        if(!is_null($idProject)){
            $this->report->idProject = $idProject;
        }
    }

    public function getReportData($idProject){
        if(!is_null($idProject)){
            $this->report->moreReports = $this->report_m->getReportsByProject($idProject);
        }
        return $this->report;
    }

    /**
     * Retorna la estructura de la grilla
     * @param array $table
     * @return array
     * @throws
     */
    public function prepare($table){
        $this->db_connection = $this->getDbConnection();
        $this->initFilterGrid($table['filters']);
        $this->model->setDbConnection($this->db_connection);
//        print_r($table);Exit;
        if(isset($table['sql'])){
            $this->sqlReport = $table['sql'];
        }else{
            throw new Exception('The item sql, with the sql '
                . 'for the report is required');
        }
        if(isset($table['columns']) && count($table['columns']) > 0){
            $this->columns = $table['columns'];
        }
        
        $this->data_url = $table['data_url'];
        $this->utilities = isset($table['utilities']) ? $table['utilities'] : null;
        $this->pagination = isset($table['pagination']) ? $table['pagination'] : true;
        $this->avoid_basic_filter = isset($table['avoid_basic_filter']) ? $table['avoid_basic_filter'] : false;
    }

    
    /**
     * Devuelve la conexion a la base de datos que usara el reporte
     */
    public function getDbConnection()
    {
        if(property_exists($this, "report")){
            $con = $this->server_m->getDbConnection($this->report->idServerConnection);
            $server = $this->server_m->find($this->report->idServerConnection);
            $driver = $this->server_m->getDriver($server->idDriver);
            $nameGestor =  ucfirst($driver->config_name);
            $param = $this->report;
        }else{
            $con = $this->db; 
            $nameGestor =  ucfirst($this->config->item('db_default_driver'));
            $param = null;
        }
        $this->gestor = new $nameGestor($param);
        return $con;  
    }
    
    private function  initFilterGrid($filters){
        if(isset($filters)){
            $this->load->model('VarTypes_m'); //todo: necesito quitar esto
            $varTypes = $this->VarTypes_m->getTypes();
            $this->filterGrid = new Filter();
            $this->filterGrid->init($varTypes, $filters);
        }
    }

    /**
     * Retorna la estructura de la grilla
     * @return array
     * @throws
     */
    public function bodyGrid(){
        $table = $this->gridDefinition();
        $this->prepare($table);
        $rs =  array(
            "columns" => $this->makeColumns(),
            "filters" => $this->filterGrid->getFilters(),
            "viewsFilters" => $this->filterGrid->getNameViews(),
            "data_url" => $this->data_url,
            "utilities" => $this->utilities,
            'avoid_basic_filter' => $this->avoid_basic_filter,
            "draw" => isset ( $_REQUEST['draw'] ) ? intval( $_REQUEST['draw'] ) : 0
        );
        if(isset($table['links'])){
            $rs['links'] = $table['links'];
        }
        return $rs;
    }

    /**
     * Retorna la información en el formato que espera el
     * plugin Jquery Datatables
     * @return array
     */
    public function dataGrid(){
        $this->prepare($this->gridDefinition());
        if (isset($_REQUEST['vars'])){
            $_REQUEST['vars'] = $this->security->xss_clean($_REQUEST['vars']);
        }
        if(isset($_REQUEST['columns'])){
            $_REQUEST['columns'] = $this->security->xss_clean($_REQUEST['columns']);
        }
        $sqlData = $this->getData();
        $keyColumns = isset($sqlData[0]) ? array_keys($sqlData[0]) : array();
        $this->columns = $this->makeColumns($keyColumns);
        $data = DatatablesSSP::data_output($this->columns, $sqlData);
        $total = ($this->pagination) ? $this->getTotal() : 1000000;
        $result =  array(
            "columns" => $_REQUEST['columns'],
            "draw" => isset ( $_REQUEST['draw'] ) ? intval( $_REQUEST['draw'] ) : 0,
            "data" => $data,
            "filters" => $this->filterGrid->getFilters(),
            "recordsTotal" => $total,
            "recordsFiltered" => $total
        );

        if(ENVIRONMENT == 'development'){
            $result["sql"] = $this->sqlFiltered;
        }
        return $result;
    }

    public function getData(){
        $sql = $this->applyFilters($this->sqlReport);
        return $this->model->result_array($sql);
    }

    public function getTotal(){
        $sql = $this->getSqlFiltered();
        $sintax = new SyntaxAnalyze($sql);
        $positions = $sintax->getPositions();
        if(isset($positions['GROUP BY'])){
            $sqlTotal = $this->getSqlCount($sintax, $positions, $sql);
        }else{
            if(isset($positions['ORDER BY'])){
                $sintax->setSQL(substr($sql,0, $positions['ORDER BY']));
            }
            $sqlTotal = $sintax->replace("SELECT", "COUNT(*) AS TOTAL");
        }
        $count  = $this->model->row($sqlTotal);
        if(!$count){
            $message = "Sql utilizado: $sqlTotal";
            show_error($message, 1, $heading = 'No se puede encontrar el total de registros');
        }
        return $count->TOTAL;
    }


    /**
     * Retorna el sql base mas los filtro aplicados por
     * el plugin Datatables
     * @return string
     *
     */
    protected function getSqlFiltered(){
        return $this->sqlFiltered;
    }

    /**
     * Aplica al SQL los filtros del plugin Jquery Datatables
     * y devuelve un sql con paginacion
     * @param $sql
     * @return string
     */
    protected function applyFilters($sql){
        $sql = $this->applyCustomFilters($sql);
        $columns = $this->columns;
        $sql = DatatablesSSP::getQuery($_REQUEST, $sql, $columns);
        $this->sqlFiltered = DatatablesSSP::getSqlOrder($_REQUEST, $sql, $columns);
        return $this->gestor->getSqlPaginate($_REQUEST, $columns, $this->sqlFiltered);
    }

    public function applyCustomFilters($sql){
       return $this->filterGrid->applyFilterOnSql($sql);
    }

    protected function getSqlCount($sintax, $positions, $sql){
        $posGroup = $positions['GROUP BY'] + strlen('GROUP BY');
        if(isset($positions['ORDER BY'])){
            $len = $positions['ORDER BY'] - $posGroup;
            $fields = substr(trim($sql), $posGroup, $len);
        }else{
            $fields = substr($sql, $posGroup, strlen($sql));
        }
        $sql = $sintax->replace('SELECT', $fields);
        return "SELECT count(*) as TOTAL FROM ($sql) temp";
    }

    private function makeColumns($columnsSql=null)
    {
        if(count($this->columns)!=0){
            for($i= 0; $i<count($this->columns);$i++){
                $this->columns[$i]['dt'] = $this->formatColumn($this->columns[$i]['dt']);
            }
            $columns =  $this->columns;
        }else{
            $columns = $this->makeColumnsFromSql($columnsSql);
        }
        return $columns;
    }

    public function makeColumnsFromSql($keys=null){
        if(is_null($keys)){
            $keys = $this->getDefaultColumns();
        }
        $columns = array();
        foreach($keys as $field){
            $columns[] =  array(
                'dt' => $this->formatColumn($field),
                'db' => $field,
                'show' => true,
                'table' => ''
            );
        }
        return $columns;
    }

    private function formatColumn($field){
        $formated = ucfirst(strtolower(
            str_replace(array('_', '-', '.'), array(' ', ' ', ''), $field)
        ));
        return $formated;
    }

    
    private function getDefaultColumns(){
        $sql = $this->applyCustomFilters($this->sqlReport);
        $sql = $this->gestor->getLimitForColumns($sql);
        $data = $this->model->row($sql);
        return array_keys((array)$data);
    }
    
    
}
