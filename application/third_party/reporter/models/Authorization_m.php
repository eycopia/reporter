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
        $user = $this->base_user_m->find($idUser);
        if($user->permission < Permission::$ADMIN){
            $where  = " and  up.idUser = '{$idUser}'";
        }
        $join = "LEFT JOIN users_by_project as up on up.idProject = p.idProject ";
        $q = $this->db->query("SELECT p.* FROM project as p $join".
            " WHERE p.status=1 $where GROUP BY p.idProject");
        return $q->result();
    }

}