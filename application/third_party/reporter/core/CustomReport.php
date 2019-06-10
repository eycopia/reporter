<?php

/**
 * Class CustomReport
 * Shortcut para crear una grilla personalizada
 * @package Reporter\Core
 * @author Jorge Copia Silva <eycopia@gmail.com>
 * @license https://github.com/eycopia/reporter/blob/master/LICENSE
 */
class CustomReport extends CI_Controller
{
    
    /**
     * The report information
     * @var array
     */
    protected $report;
    private $idReport;
    
    public function __construct($idReport=null, $idProject)
    {
        parent::__construct();
        //         $CI = &get_instance();
        $this->load->model('report_m');
        $this->load->model('Project_m');
        $this->idReport = $idReport;
        $this->idProject = null;
        
        $this->report = $this->report_m->getReportData(null);
        //         $CI->remoteDb = $this->report_m->getDbConnection();
        //         $this->reportUrl = $this->report_m->getReportDataUrl();
        $this->reporter_auth->isLogin();
        //         $this->Project_m->validate_user(
        //             $this->reporter_auth->get_user_id(),
        //             $this->report->idProject
        //         );
    }
    
    public function index($dataReplace=null){
        $this->report_m->loadReport($this->idReport);
        $table = $this->report_m->bodyGrid($this->idReport);
        $base = $this->config->item('rpt_base_template');
        $report = $this->report_m->getReportData($this->idProject);
        $template = null;
        if(!is_null($this->idProject)){
            $this->load->model('project_m');
            $report->current_project = $this->project_m->find($this->idProject);
            $template = $report->current_project->template;
        }
        $template = is_null($template) ? $base : $template;
        
        $data =  array(
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
        $this->load->view($template, $data);
    }
    
    //     index()
    //     {
    //         $data = array(
    //             'custom_js_files' => array(base_url('assets/js/report/main.js')),
    //             'main_content' => $this->config->item('rpt_template') .'grid',
    //             'title_page' => $this->report->title,
    //             'data_url' => isset($this->report->data_url) ? $this->report->data_url :  '',
    //             'table' => $this->model->bodyGrid(),
    //             'breadcrumb' => $this->getBreadCrumb()
    //         );
    //         $this->load->view($this->config->item('rpt_base_template'), $data);
    //     }
    
    public function show(){
        session_write_close();
        $data = $this->model->dataGrid($this->idReport);
        return $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }
    
    public function download()
    {
        $this->report_m->loadReport($this->idReport);
        session_write_close();
        $params = array('model' => $this->model, 'idReport' => $this->idReport);
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
}
