<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH."third_party/reporter/autoload_reporter.php";

class Report_m extends Grid implements interfaceGrid {

	private $table = "report";

	protected $con;

	public $columns = array();

    private $report = '';

	public function __construct()
    {
        parent::__construct( new ModelReporter() );
        $this->load->model('component_m');
        $this->load->model('project_m');
        $this->load->model('server_m');
    }

    public function search($q){
        $query = "FROM report
            WHERE lower(title) like '".strtolower(trim($q))."%' and status = 1";
        $rs = $this->db->query("SELECT * ".$query);
        $qTotal = $this->db->query("SELECT count(*) as total ".$query);
        $count = $qTotal->row();
        $data = array('recordsTotal' => $count->total, 'data' => $rs->result() );
        return $data;
    }

    /**
     * Retorna el sql filtrado
     * @param $id
     * @return array
     */
    public function getReportSql($id){
        $this->loadReport($id);
        $this->applyFilters($this->report->sql);
        return $this->getSqlFiltered();
    }

    public function find($id){
        $q = $this->db->query("SELECT * FROM {$this->table} WHERE idReport = $id");
        return $q->row();
    }

    public function getReportData(){
        $this->report->moreReports = $this->getProjectReports($this->report->idProject);
        return $this->report;
    }

    public function loadReport($id){
        $query = $this->db->query("SELECT r.*, p.name as project, p.template, p.slug, s.oracle
			FROM {$this->table} as r
			JOIN project as p on p.idProject = r.idProject
			JOIN server_connection as s on s.idServerConnection = r.idServerConnection
			WHERE idReport = $id and p.status = 1");
        $this->report = $query->row_object();
    }

    public function getReportsPerProject(){
        $projects = $this->project_m->getProjects();
        foreach( $projects as $project){
            $project->reports = $this->getProjectReports($project->idProject);
        }
        return $projects;
    }

    public function getProjectReports($idProject){
        $q = $this->db->query("SELECT idReport, idProject,
            idServerConnection, title, description
            FROM {$this->table}
            WHERE idProject = {$idProject} and status = 1");
        return $q->result();
    }

    public function gridDefinition(){
        $vars = $this->declareVars();
        $dbColumns = json_decode($this->report->columns, true);
        $project = array(
                'name' => $this->report->project,
                'idProject' =>$this->report->idProject);
        $sql = trim($this->report->sql);
        $database = is_null($this->report->oracle) ? 'mysql' : 'oracle';
        return array(
            'title' => $this->report->title,
            'description' => $this->report->details,
            'project' => $project,
            'data_url' => $this->getReportDataUrl(),
            'filters' => (count($vars) > 0) ? $vars : 'basic',
            'columns' => (count($dbColumns)>0) ? $dbColumns : array(),
            'db_connection' => $this->getDbConnection(),
            'pagination' => $this->report->pagination,
            'database' => $database,
            'sql' => $sql,
            'utilities' => array(
                'auto_reload' => $this->report->auto_reload,
                'items_per_page' => $this->report->items_per_page,
                'download_all' => $this->getDownloadUrl(),
                'donwload_view' => true,
                'show_columns' => true
            )
        );
    }


    public function declareVars()
    {
        $sql = "SELECT vr.name as 'name', vr.label, vt.name  as 'type', vr.default, vt.frontendClass
            FROM var_report as vr
            JOIN var_type as vt on vr.idVarType = vt.idVarType
            WHERE vr.idReport = {$this->report->idReport} and vr.status = 1";
        $vars = $this->db->query($sql);
        return $vars->result_array();
    }

    private function getDownloadUrl(){
        $component = $this->component_m->getComponentDownload($this->report->idReport);
        if(isset($component)){
                          $url = site_url('component/download/'.$component->idComponent);
        }else{
            $url = site_url('report/download/'.$this->report->idReport);
        }
        return $url;
    }

    /**
     * Devuelve la conexion a la base de datos que usara el reporte
     */
    public function getDbConnection()
    {
        return  $this->server_m->getDbConnection($this->report->idServerConnection);
    }


    /**
     * Devuelve la url donde esta el report
     * @return string
     */
    public function getReportDataUrl()
    {
        $data_url = site_url('report/show/'.$this->report->idReport);
        if(!empty($this->report->url)){
            $data_url = site_url($this->report->url."/show");
        }
        return $data_url;
    }
}
