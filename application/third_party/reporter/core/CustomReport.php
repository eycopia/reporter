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

    public function __construct($idReport)
    {
        parent::__construct();
        $CI = &get_instance();
        $this->load->model('report_m');
        $this->idReport = $idReport;
        $this->report_m->loadReport($idReport);
        $this->report = $this->report_m->gridDefinition();
        $CI->remoteDb = $this->report_m->getDbConnection();
        $this->reportUrl = $this->report_m->getReportDataUrl();
        $idProject = $this->report['project']['idProject'];
        $this->ion_auth->isLogin();
        $this->ion_auth->validateUserProject($_SESSION['user_id'], $idProject);
    }

    public function index()
    {
        $data = array(
            'custom_js_files' => array(base_url('assets/js/report/main.js')),
            'main_content' => 'report/grid',
            'title_page' => $this->report['title'],
            'data_url' => $this->report['data_url'],
            'table' => $this->model->bodyGrid()
        );
        $this->load->view('template/index', $data);
    }

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
}
