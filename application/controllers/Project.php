<?php
class Project extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $models = $this->config->item('rpt_models');
        $this->load->model( $models . 'Report_m');
        $this->load->model($models . 'Project_m');
//        $this->ion_auth->isLogin();
//        $this->ion_auth->login();
        $_SESSION['user_id'] = 1;

    }

    public function index(){
        $projects = $this->project_m->getUserProjects($_SESSION['user_id']);
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
            'projects' =>  $projects
        );
        $this->load->view( $this->config->item('rpt_template') . 'index', $data);
    }

    public function name($name_project){
        $slug = urldecode($name_project);
        $rpt_template = $this->config->item('rpt_template');
        $project = $this->Project_m->searchBySlug($slug);
// :todo: ver esta forma de validar users and projects       $this->ion_auth->validateUserProject($_SESSION['user_id'], $project->idProject);
        $this->Project_m->setProject($project->idProject);
        $template = is_null($project->template) ? $rpt_template.'index' : $project->template;
        $data = array(
            'title_page' => $project->name,
            'main_content' => $rpt_template . 'grid',
            'table' => $this->Project_m->bodyGrid(),
        );
        return $this->load->view($template, $data);
    }

    public function show($id)
    {
//        $this->ion_auth->validateUserProject($_SESSION['user_id'], $id);
        $this->Project_m->setProject($id);
        $data = $this->Project_m->dataGrid();
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(
                $data
            ));
    }
}
