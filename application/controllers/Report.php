<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Report
 * @package Reporter\Controllers
 * @author Jorge Copia Silva <eycopia@gmail.com>
 * @license https://github.com/eycopia/reporter/blob/master/LICENSE
 */
require_once "AdminGrid.php";

class Report extends AdminGrid{

	/**
	 * Format for JSON response
	 * @var array
	 */
	private $response =  array(
		'status' => true,
		'msg' => 'The report was saved',
		'errors' => ''
	);

	/**
	 * @var string Index view
	 */
	protected $index_view = null;

	/**
	 * @var string Title page
	 */
	protected $title_page = null;

	/**
	 * Rules for the report form
	 * @var array
	 */
	private $rules = array(
		array(
			'field' => 'project',
			'label' => 'Project',
			'rules' => 'required'
		),
		array(
			'field' => 'connection',
			'label' => 'Server Connection',
			'rules' => 'required'
		));

	public function __construct(){
		parent::__construct();
		$this->reporter_auth->isLogin();
		$this->reporter_auth->checkAdmin();
		$this->load->model( 'admin/AdminReport_m', 'model');
		$this->load->model('VarReport_m');
		$this->load->model('admin/NotifyReport_m');
		$this->title_page = $this->lang->line('admin_report_title');
		$this->index_view = $this->config->item('rpt_template') . 'grid';
	}

	public function add(){
		$this->title_page = $this->lang->line('title_new_report');
		return $this->prepareForm();
	}

	public function edit($idReport){
		return $this->prepareForm($idReport);
	}

	public function del($idReport){
		$this->model->delete($idReport);
		redirect(site_url('admin/report'));
	}

	/**
	 * Get all data for form
	 * @param int $idReport
	 * @return mixed
	 */
	private function prepareForm($idReport=null){
		$view = $this->config->item('rpt_views');
		$this->load->model('server_m');
		$this->load->model('project_m');
		$this->load->model('VarTypes_m');
		$assets = $this->config->item('rpt_assets');
		$custom_js_files = array(
			base_url('assets/grocery_crud/texteditor/ckeditor/ckeditor.js'),
			base_url('assets/libs/select2/js/select2.min.js'),
			base_url('assets/libs/ace/ace.js'),
			base_url($assets . 'js/add.js')
		);
		$data = array(
			'main_content' =>$view . 'admin/frmReport',
			'js_files' => $custom_js_files,
			'projects' => $this->project_m->getProjects(),
			'servers' => $this->server_m->getServers(),
			'type_vars' => $this->VarTypes_m->getTypes()
		);
		if(!is_null($idReport)){
			$data['report'] = $this->model->find($idReport);
			$data['vars'] = $this->VarReport_m->getVarsReport($idReport);
			$data['people'] = $this->NotifyReport_m->getPeopleToNotify($idReport);
			$this->title_page = 'Edit Report: '.$data['report']->title;
		}
		$data['title_page'] = $this->title_page;
		$data['breadcrumb'] = array( array(
			'title'=> $this->lang->line('home'),
			'link'=> site_url()
		),array(
			'title'=> $this->lang->line('menu_report'),
			'link'=> site_url('admin/report')));
		$data['columns'] = $this->getColumns($data);
		return $this->load->view($view . "template/index", $data);
	}

	/**
	 * Procesa la información del formulario y determina
	 * si debe editar o crear un nuevo registro
	 * @return HttpRequest
	 */
	public function save(){
		$data = $this->cleanInputs();
		if($this->validate($data['report'])){
			isset($data['report']['idReport']) ? $this->editReport($data) : $this->newReport($data);
		}else{
			$this->response['status'] = false;
			$this->response['msg'] = 'There are some problems';
			$this->response['errors'] = validation_errors();
		}
		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode($this->response));
	}

	/**
	 * Create a new report
	 * @param array $data
	 */
	private function newReport($data){
		try {
			$idReport =  $this->model->add($data['report']);
			if(isset($data['vars']) && !empty($data['vars'])){
				$this->VarReport_m->save($data['vars'], $idReport);
			}
			$this->response['redirect_to'] = site_url('admin/report/edit/'.$idReport);
		}catch (Exception $e){
			$this->response['status'] = false;
			$this->response['msg'] = "Can't add the report, error message ".$e;
		}
	}

	/**
	 * Update the report data
	 * @param array $data
	 */
	private function editReport($data){
		$idReport = $data['report']['idReport'];
		try {
			$this->model->edit($data['report']);
			if(isset($data['vars']) && !empty($data['vars'])){
				$this->VarReport_m->save($data['vars'], $idReport);
			}
			if(empty($data['report']['url'])){
				$this->getGridColumns($idReport);
				$data['report']['columns'] =  $this->mergeColumns($data['report']['columns']);
				$this->model->edit($data['report']);
			}
			$this->NotifyReport_m->save($data['emails'], $data['report']['idReport']);
			$this->response['redirect_to'] = site_url('admin/report/edit/'.$data['report']['idReport']);
			$this->session->set_flashdata('type_message', 'success');
			$this->session->set_flashdata('message', 'The changes were made successfully');
		}catch (Exception $e){
			$this->response['status'] = false;
			$this->response['msg'] = "Can't add the report, error message ".$e;
		}
	}

	public function mergeColumns($columns){
		$newColumns = array();
		$lastColumns = array();
		foreach(json_decode($columns) as $col){
			$lastColumns[$col->db] = $col;
		}
		foreach($this->grid->makeColumnsFromSql() as $col){
			if(isset($lastColumns[$col['db']])){
				$newColumns[] = array(
					'dt' => $lastColumns[$col['db']]->dt,
					'db' => $lastColumns[$col['db']]->db,
					'table' => $lastColumns[$col['db']]->table,
					'show' => isset($lastColumns[$col['db']]->show) ? 1 : 0);
			}else{
				$newColumns[] = $col;
			}
		}
		return  json_encode($newColumns);
	}


	/**
	 * Get data from json string
	 * @return array
	 */
	private function cleanInputs(){
		$data = json_decode($this->input->post('data'));
		$reportValues = array();
		$emails = array();
		foreach($data as $element){
			if(isset($element->name)){
				if($element->name == 'emails'){
					if(!isset($reportValues[$element->name])){
						$reportValues[$element->name] = array();
					}
					$reportValues[$element->name][] = $element->value;
				}else{
					$reportValues[$element->name] = $element->value;
				}
			}else if(isset($element->filters)){
				$reportValues['vars'] = $this->getComplementsValues($element->filters);
			}else if(isset($element->columns)){
				$reportValues['columns'] = json_encode($element->columns);

			}
		}
		$report = array(
			'title' => $reportValues['title'],
			'project' => $reportValues['project'],
			'description' => $reportValues['description'],
			'details' => $reportValues['details'],
			'columns' => (isset($reportValues['columns'])) ? $reportValues['columns'] : '',
			'url' => $reportValues['url'],
			'sql' => $reportValues['sql'],
			'items' => $reportValues['items'],
			'reload' => isset($reportValues['reload']) ? $reportValues['reload'] : null,
			'format_notify' => $reportValues['format_notify']
		);
		if(is_numeric($reportValues['connection'])){
			$report['connection'] = $reportValues['connection'];
		}
		if(isset($reportValues['report'])){
			$report['idReport'] = $reportValues['report'];
		}
		if(isset($reportValues['emails'])){
			$emails = $reportValues['emails'];
		}
		return array('report' => $report,
			'vars' => $reportValues['vars'],
			'emails' => $emails
		);
	}


	/**
	 * Get Inputs for Vars
	 * @param $elements
	 * @return array
	 */
	private function getComplementsValues($elements){
		$vars = array();
		foreach($elements as $element){
			if(isset($element->varName)){
				$name = ucwords(str_replace(array('-', '_'), ' ', $element->varName));
				$vars[] = array(
					'name'  =>  $element->varName,
					'label' => $name,
					'default' => isset($element->varDefault) ? $element->varDefault : null,
					'idVarType' => $element->varType
				);

			}
		}
		return  $vars;
	}

	/**
	 * Valid input data
	 * @param $data array
	 * @return boolean
	 */
	private function validate($data){
		$this->load->library('form_validation');
		$this->form_validation->set_rules($this->rules);
		$this->form_validation->set_data($data);
		return $this->form_validation->run();
	}

	private function getColumns($data)
	{
		$gridColumns = array();
		if(isset($data['report']) and empty($data['report']->url) and !empty($data['report']->sql)){
			$dbColumns = json_decode($data['report']->columns, true);
			if(count($dbColumns) > 0){
				$gridColumns =  $dbColumns;
			}else{
				$gridColumns = $this->getGridColumns($data['report']->idReport);
			}
		}
		return $gridColumns;
	}

	private function getGridColumns($idReport){
		$columns = array();
		$this->load->model('Report_m', 'grid');
		$this->grid->loadReport($idReport);
		$grid = $this->grid->bodyGrid();
		if (is_array($grid['columns']) && count($grid['columns']) > 0){
			$columns = $grid['columns'];
		}
		return $columns;
	}
}
