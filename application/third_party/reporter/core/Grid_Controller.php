<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Grid_Controller extends CI_Controller{
    
    /**
     * The custom model for grid
     * @param interfaceGrid $customModel
     */
    public function __construct($customModel, $aliasModel=null){
        parent::__construct();
        
        if(is_null($aliasModel)){
            $aliasModel = 'custom_grid_model';
        }
        
        $this->load->model($customModel, $aliasModel);
    }
    
    /**
     * View path to show report
     * @param int $idReport
     * @param int|null $idProject projecto to work
     * @param array $dataReplace replace the default values 
     */
    public function getGridDefinition($idReport, $idProject=null, $dataReplace=null){
       
        $table = $this->report_m->bodyGrid($idReport);
        $base = $this->config->item('rpt_base_template');
        $report = $this->report_m->getReportData($idProject);
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
            'breadcrumb' => $this->getBreadCrumb($report),
            'data_url' => isset($this->report->url) ? $this->report->url : null
        );
        
        if(!is_null($dataReplace)){
            $data = array_merge($data, $dataReplace);
        }
        
        return $data;
        
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
}
