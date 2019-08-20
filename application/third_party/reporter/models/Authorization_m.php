<?php

/**
 * Name: Authorization.php
 *
 * Author: Jorge Copia <eycopia@gmail.com>
 *
 * Description:
 */
class Authorization_m extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Obtiene los permisos del usuario para un proyecto
     * @param $idProject
     * @return mixed
     */
    public function getUserProject($idProject){
        $idUser = $this->reporter_auth->get_user_id();
        $q = $this->db->where('idUser', $idUser)
            ->where('idProject', $idProject)
            ->get('users_by_project');
        return $q->row();
    }

    /**
     * Obtiene los proyectos a los que pertenece un reporte
     * @param $idProject
     * @return mixed
     */
    public function getReportProject($idProject, $idReport){
        $q = $this->db->where('idProject', $idProject)
            ->where('idReport', $idReport)
            ->get('reports_by_project');
        return $q->row();
    }

    /**
     * Get all active projects for the current user
     *
     * @param $user_id
     *
     * @return mixed
     */
    public function getUserProjects($idUser){
        $this->load->model('base_user_m');
        $where = '';
        $user = $this->base_user_m->findByUsername($idUser);
        if($user->permission < Permission::$ADMIN){
            $where  = " and  up.idUser = '{$idUser}'";
        }
        $join = "LEFT JOIN users_by_project as up on up.idProject = p.idProject ";
        $q = $this->db->query("SELECT p.* FROM project as p $join".
            " WHERE p.status=1 $where");
        return $q->result();
    }


//    /**
//     * Check if the current user is authorized for the project
//     * @param $idUser int user to evaluate
//     * @param $idProject int project id
//     */
//    public function validate_user($idUser, $idProject)
//    {
//        $project = $this->Project_m->find($idProject);
//        $is_admin = $this->reporter_auth->isAdmin();
//        if (!is_cli() && !$is_admin && !is_null($project) && !$this->Project_m->hasPermission($idUser, $idProject)) {
//            $this->session->set_flashdata('type_message', 'danger');
//            $message = $this->lang->line('unauthorized_project')
//                . ": {$project->name}";
//            $this->session->set_flashdata('message', $message);
//            redirect(site_url());
//        }
//    }
}