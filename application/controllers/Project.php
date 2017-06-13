<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Project
 * @package Reporter\Controllers
 * @author Jorge Copia Silva <eycopia@gmail.com>
 * @license https://github.com/eycopia/reporter/blob/master/LICENSE
 */
class Project extends CI_Controller
{
    /**
     * @var int $user current user id
     */
    private $user;

    public function __construct(){
        parent::__construct();
        $this->load->model('Report_m');
        $this->load->model('Project_m');
        $this->reporter_auth->isLogin();
        $this->user = $this->reporter_auth->get_user_id();
    }

    public function index(){
        $projects = $this->Project_m->getUserProjects($this->user);
        $is_pretty = $this->config->item('pretty_url');
        $totalProjects = count($projects);
        if($totalProjects == 1){
            return $this->name($projects[0]->slug);
        }else if( $totalProjects == 0) {
            $this->session->set_flashdata('message', $this->lang->line('empty_projects'));
            $this->session->set_flashdata('type_message', 'success');
        }
        $data = array(
            'main_content' => $this->config->item('rpt_views') . "projects",
            'title_page' => $this->lang->line('index_title'),
            'projects' =>  $projects,
            'is_pretty' => $is_pretty
        );
        $this->load->view( $this->config->item('rpt_base_template'), $data);
    }

    /**
     * Show a grid with the project reports
     * @param $name_project
     * @return mixed
     */
    public function name($name_project){
        $slug = urldecode($name_project);
        $rpt_template = $this->config->item('rpt_template');
        $project = $this->Project_m->searchBySlug($slug);
        $this->Project_m->validate_user($this->user, $project->idProject);
        $this->Project_m->setProject($project->idProject);
        $template = is_null($project->template) ? $rpt_template.'index' : $project->template;
        $data = array(
            'title_page' => $project->name,
            'main_content' => $rpt_template . 'grid',
            'table' => $this->Project_m->bodyGrid(),
        );
        return $this->load->view($template, $data);
    }

    /**
     * All reports of the project
     * @param $idProject
     * @return json
     */
    public function show($idProject)
    {
        $this->Project_m->validate_user($this->user, $idProject);
        $this->Project_m->setProject($idProject);
        $data = $this->Project_m->dataGrid();
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(
                $data
            ));
    }
}
