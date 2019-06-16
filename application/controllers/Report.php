<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class Report
 * @package Reporter\Controllers
 * @author Jorge Copia Silva <eycopia@gmail.com>
 * @license https://github.com/eycopia/reporter/blob/master/LICENSE
 */
class Report extends Grid_Controller {

    public $idReport;

    public $idProject;

	public function __construct(){
		parent::__construct();
	}

	public function grid($idReport, $idProject=null){
	    $data = $this->checkCustomModel($idReport, $idProject);

 	    if($data['report']->resource == 'model'){
            $dataModel = $this->getGridDefinition($idReport, $idProject);
            $data['table'] = $this->margeReportInfo($data['table'], $dataModel['table']);
        }

 	    $this->load->view($data['template'], $data);
	}

	private function margeReportInfo($reportTable, $customTable){
        foreach($reportTable as $key => $values){
            if(is_array($customTable[$key]) && count($customTable[$key]) > 0){
                $reportTable[$key] = $customTable[$key];
            }

            if(!is_array($customTable[$key]) && !empty($customTable[$key])){
                $reportTable[$key] = $customTable[$key];
            }
        }

        return $reportTable;
    }

	public function show($idReport, $idProject=null)
    {
        $this->checkCustomModel($idReport, $idProject);
        parent::show($idReport, $idProject);
    }


    public function download($idReport, $idProject=null)
    {
        $this->checkCustomModel($idReport, $idProject);
        parent::download($idReport, $idProject);
    }


    private function checkCustomModel($idReport, $idProject){
        $data = $this->getGridDefinition($idReport, $idProject);

        if ($data['report']->resource == 'model') {
            $this->grid_report_m = null;
            $this->load->model($data['report']->url, 'grid_custom_m');
            $this->grid_report_m = $this->grid_custom_m;
        }
        return $data;
    }
}
