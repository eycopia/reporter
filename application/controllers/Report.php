<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(1);
/**
 * Class Report
 * @package Reporter\Controllers
 * @author Jorge Copia Silva <eycopia@gmail.com>
 * @license https://github.com/eycopia/reporter/blob/master/LICENSE
 */
class Report extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->model('report_m');
		$this->validateUserReport();
		$this->reporter_auth->isLogin();
	}

	public function show($id){
		$this->report_m->loadReport($id);
		session_write_close();
		$data = $this->report_m->dataGrid($id);
		$json = json_encode($this->utf8ize( $data ));
		return $this->output
			->set_content_type('application/json')
			->set_output($json);
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

	public function grid($id, $idProject=null){
	    $this->report_m->loadReport($id);
		
		$table = $this->report_m->bodyGrid($id);
		
		$report = $this->report_m->getReportData($idProject);
		if(!is_null($idProject)){
		    $this->load->model('project_m');
		    $report->current_project = $this->project_m->find($idProject);
		}
		$breadcrumb = $this->getBreadCrumb($report);
		$views = $this->config->item('rpt_template');
		$base = $this->config->item('rpt_base_template');
		$template = is_null($report->template) ? $base : $report->template;
		$data = array(
			'title_page' => $report->title,
			'main_content' => $views . 'grid',
            'table' => $table,
			'report' => $report,		    
            'breadcrumb' => $breadcrumb,
            'data_url' => site_url('report/show/'.$id)
		);
		$this->load->view($template, $data);
	}


    public function download($id){
		$this->report_m->loadReport($id);
		session_write_close();
        $params = array('model' => $this->report_m, 'idReport' => $id);
        $this->load->library('Large_Download', $params);
        return $this->large_download->download();
    }

    private function validateUserReport(){
        $this->load->model('Project_m');
        $this->load->library('CI_URI');
        $idReport = $this->uri->segment(3, null);
        $report = $this->report_m->find($idReport);
        $this->Project_m->validate_user($this->reporter_auth->get_user_id(), $report->idProject);
    }

	private function getBreadCrumb($report){
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
