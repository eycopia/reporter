<?php
require_once APPPATH . "models/reporter/core/interfaceGrid.php";
require_once APPPATH . "models/reporter/core/Grid.php";
require_once APPPATH . "models/reporter/core/ModelReporter.php";

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
//        if(true) {//todo: mejora esta forma de validar al administrador !$this->ion_auth->is_admin()){
//            $where  = " and  u.user_id = $user_id";
//            $join = "LEFT JOIN user_projects as u on u.idProject = p.idProject ";
//        }

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

    public function setProject($idProject){
        $this->idProject = $idProject;
    }

    public function gridDefinition(){
        return array(
            'sql' => "SELECT r.idReport, r.sql, r.url, r.title, r.description, r.created, p.name as project
            FROM {$this->table} as p
            join report as r on r.idProject = p.idProject
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
                "formatter" => function($d, $row) {
                    $url = site_url('report/grid/'.$d);
                    if(strlen($row['url']) > 0 ){
                        $url = site_url($row['url']);
                    }
                    return "<a class='' href='$url'>
                    ".$row['title']."</a>";
            }),
            array('dt' => 'Description', 'db' => 'description', 'type' => 'column'),
            array('dt' => '', 'db' => 'idReport', 'type' => 'column',
                "formatter" => function($d, $row) use ($that){
                    $url = site_url('report/grid/'.$d);
                    if(strlen($row['url']) > 0 ){
                        $url = site_url($row['url']);
                    }
                    return "<a class='btn btn-success' href='$url'>
                    <i class='fa fa-eye'></i> {$that->lang->line('show')}</a>";
                }),
        );
    }
}
