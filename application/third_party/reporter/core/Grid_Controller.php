<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Grid_Controller extends CI_Controller{
    
    /**
     * The custom model for grid
     * @param interfaceGrid $customModel
     */
    public function __construct($customModel=null){
        parent::__construct();

        if(!is_null($customModel)){
            $this->load->model($customModel, 'grid_report_m');
        }
        $this->reporter_auth->isLogin();
        $this->load->model('grid_report_m');
    }
    
    /**
     * View path to show report
     * @param int $idReport
     * @param int|null $idProject projecto to work
     * @param array $dataReplace replace the default values
     * @return array
     */
    public function getGridDefinition($idReport, $idProject=null, $dataReplace=null){
        $this->grid_report_m->loadReport($idReport, $idProject);
        $table = $this->grid_report_m->bodyGrid();
        $base = $this->config->item('rpt_base_template');
        $report = $this->grid_report_m->getReportData($idProject);
        $template = null;
		if(!is_null($idProject)){
		    $this->load->model('project_m');
		    $report->current_project = $this->project_m->find($idProject);
		    $template = $report->current_project->template;
		}
        $template = is_null($template) ? $base : $template;
                
        $data =  array(
            'template' => $template,
            'title_page' => $report->title,
            'main_content' => $this->config->item('rpt_template') . 'grid',
            'table' => $table,
            'report' => $report,
            'breadcrumb' => $this->getBreadCrumb($report)
        );
        
        if(!is_null($dataReplace)){
            $data = array_merge($data, $dataReplace);
        }
        
        return $data;
        
    }

    public function show($idReport, $idProject=null){
        session_write_close();
        $this->grid_report_m->loadReport($idReport);
        $data = $this->grid_report_m->dataGrid($idReport);
        $json = json_encode($this->utf8ize( $data ));
		return $this->output
			->set_content_type('application/json')
			->set_output($json);
    }

    public function download($idReport, $idProject=null)
    {
        $this->grid_report_m->loadReport($idReport);
        session_write_close();
        $params = array('model' => $this->grid_report_m, 'idReport' => $idReport);
        $this->load->library('Large_Download', $params);
        return $this->large_download->download();
    }

    protected function getBreadCrumb($report){
        return array(
            array(
                'title'=> $this->lang->line('home'),
                'link'=> site_url()
            ),array(
                'title'=> $report->current_project->name,
                'link'=> site_url('project/name/'.url_title($report->current_project->slug))
            ), array(
                'title' => $report->title
            ));
    }

    private function utf8ize( $mixed ) {
        if (is_array($mixed)) {
            foreach ($mixed as $key => $value) {
                $mixed[$key] = $this->utf8ize($value);
            }
        } elseif (is_string($mixed)) {
            return mb_convert_encoding($mixed, "UTF-8", "UTF-8");
        }
        return $mixed;
    }
}
