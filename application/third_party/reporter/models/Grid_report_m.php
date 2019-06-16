<?php

/**
 * Name: Grid_report_m.php
 *
 * Author: Jorge Copia <eycopia@gmail.com>
 *
 * Description:
 */
class Grid_report_m extends Grid implements interfaceGrid
{

    protected $con;

    public $columns = array();

    protected $report = '';

    public function __construct()
    {
        parent::__construct( new ModelReporter() );
        $this->load->model('component_m');
        $this->load->model('project_m');
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

    public function gridDefinition(){
        $vars = $this->report_m->declareVars($this->report->idReport);
        $dbColumns = json_decode($this->report->columns, true);
        $sql = trim($this->report->sql);
        return array(
            'title' => $this->report->title,
            'description' => $this->report->details,
            'data_url' => $this->getReportDataUrl(),
            'filters' => (count($vars) > 0) ? $vars : 'basic',
            'columns' => (count($dbColumns)>0) ? $dbColumns : array(),
            'pagination' => $this->report->pagination,
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


    private function getDownloadUrl(){
        $component = $this->component_m->getComponentDownload($this->report->idReport);
        if(isset($component)){
            $url = site_url('component/download/'.$component->idComponent);
        }else{
            $url = site_url('report/download/'.$this->report->idReport);
        }
        return $url;
    }
}