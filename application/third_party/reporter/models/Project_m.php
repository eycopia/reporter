<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Class Project_m
 *
 * @package Reporter\Models
 * @author Jorge Copia Silva <eycopia@gmail.com>
 * @license https://github.com/eycopia/reporter/blob/master/LICENSE
 */
class Project_m extends Grid implements interfaceGrid{

    private $table = "project";
    
    private $idProject = null;
    
    
    public function __construct()
    {
        parent::__construct( new ModelReporter() );
    }

    /**
     * Get all active projects
     */
    public function getProjects()
    {
        $q = $this->db->query("SELECT * FROM $this->table WHERE status=1");
        return $q->result();
    }
    /**
     * Get all active projects for the current user
     *
     * @param $user_id
     *
     * @return mixed
     */
    public function getUserProjects($user_id){
        $join = $where = '';
        if(!$this->reporter_auth->isAdmin()) {
            $where  = " and  u.user_id = $user_id";
            $join = "LEFT JOIN user_projects as u on u.idProject = p.idProject ";
        }

        $q = $this->db->query("SELECT * FROM $this->table as p $join".
            " WHERE p.status=1 $where");
        return $q->result();
    }

    /**
     * Search a project
     * @param $idProject
     *
     * @return mixed
     */
    public function find($idProject){
        $q = $this->db->where('status', 1)
            ->where('idProject', $idProject)
            ->get($this->table);
        return $q->row();
    }

    public function searchBySlug($slug){
        $q = $this->db->where('status', 1)
            ->where('slug', $slug)
            ->get($this->table);
        return $q->row();
    }

    public function search($field, $value){
        $q = $this->db->where('status', 1)
            ->like($field, $value)
            ->get($this->table);
        return $q->result();
    }

    public function hasPermission($user_id, $idProject){
        $q = $this->db->where('user_id', $user_id)
            ->where('idProject', $idProject)
            ->get('user_projects');
        $data = $q->row();
        return isset($data->idProject) ? true : false;
    }

    /**
     * Check if the current user is authorized for the project
     * @param $idUser int user to evaluate
     * @param $idProject int project id
     */
    public function validate_user($idUser, $idProject)
    {
        $project = $this->Project_m->find($idProject);
        $is_admin = $this->reporter_auth->isAdmin();
        if (!is_cli() && !$is_admin && !is_null($project) && !$this->Project_m->hasPermission($idUser, $idProject)) {
            $this->session->set_flashdata('type_message', 'danger');
            $message = $this->lang->line('unauthorized_project')
                . ": {$project->name}";
            $this->session->set_flashdata('message', $message);
            redirect(site_url());
        }
    }

    public function setProject($idProject){
        $this->idProject = $idProject;
    }

    public function gridDefinition(){
        return array(
            'sql' => "SELECT r.idReport, r.sql, r.url, r.title, r.description,
            p.idProject, r.created, p.name as project
            FROM {$this->table} as p
            join reports_by_project as rp on p.idProject = rp.idProject 
            join report as r on rp.idReport = r.idReport
            WHERE p.status = 1 and r.status = 1 and p.idProject = {$this->idProject}
            ORDER BY r.idReport asc",
            'description' => '',
            'data_url' => site_url('project/show/'.$this->idProject),
            'columns' => $this->getColumns(),
            'filters' => 'basic'
        );
    }

    public function getColumns(){
        $that = $this;
        return array(
            array('dt' => 'Reports', 'db' => 'idReport', 'table' => 'r',
                "formatter" => function($d, $row) use ($that ) {
                    $url = $that->generarRuta($d, $row);
                    return "<a class='' $url>
                    ".$row['title']."</a>";
                }),
            array('dt' => 'Description', 'db' => 'description', 'type' => 'column'),
            array('dt' => '', 'db' => 'idReport', 'type' => 'column',
                "formatter" => function($d, $row) use ($that){
                    $url = $that->generarRuta($d, $row);
                    return "<a class='btn btn-success' $url>
                    <i class='fa fa-eye'></i> {$that->lang->line('show')}</a>";
                }),
        );
    }

    public function generarRuta($d, $row){
        $text = trim($row['url']);
        $pattern = "/\b((www|https?)[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|\/)))/";
        $target = '';
        if(strlen($text) > 0 && preg_match($pattern, $text)){
            $url = $text;
            $target = 'target="_blank"';
        }else if(strlen($text) > 0 ){
            $url = site_url($row['url']);
        }else{
            $url = site_url("report/grid/$d/{$row['idProject']}");
        }
        return "href='$url' target = '$target'";
    }
}
