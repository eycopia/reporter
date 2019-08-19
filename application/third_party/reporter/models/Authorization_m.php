<?php

/**
 * Name: Authorization.php
 *
 * Author: Jorge Copia <eycopia@gmail.com>
 *
 * Description:
 */
class Authorization extends CI_Model
{

    /**
     * Obtiene los permisos del usuario para un proyecto
     * @param $idProject
     * @return mixed
     */
    public function getProjectPermission($idProject){
        $username = $this->report_auth->get_username();
        $q = $this->db->where('username', $username)
            ->where('idProject', $idProject)
            ->get('users_by_project');
        return $q->row();
    }

    /**
     * Obtiene los proyectos a los que pertenece un reporte
     * @param $idProject
     * @return mixed
     */
    public function getReportPermission($idProject, $idReport){
        $q = $this->db->where('idProject', $idProject)
            ->where('idReport', $idReport)
            ->get('reports_by_project');
        return $q->row();
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