<?php

/**
 * Name: Auth_test.php
 *
 * Author: Jorge Copia <eycopia@gmail.com>
 *
 * Description:
 */
class Auth_test extends TestCase
{

    public function setUp()
    {
        $this->CI =& get_instance();
        $sql = "truncate table users_by_project";
        $sql2 = "DELETE FROM base_user WHERE idBaseUser > 1 ";
        $sql3 = "DELETE FROM project WHERE name like 'project_test_%'";
        $sql4 = "delete rp from reports_by_project as rp 
                    join report as r on r.idReport = rp.idReport
                    where title like 'test_report_%'";
        $sql5 = "DELETE FROM report WHERE title like 'test_report_%'";
        $this->CI->db->query($sql);
        $this->CI->db->query($sql2);
        $this->CI->db->query($sql3);
        $this->CI->db->query($sql4);
        $this->CI->db->query($sql5);
        if ($this->CI->session->has_userdata('reporter_user_loggin')) {
            $this->CI->session->unset_userdata('reporter_user_loggin');
        }
    }

    private function load(){
        $this->CI->load->model('base_user_m');
        $this->CI->load->model('project_m');
    }

    private function addProject($name)
    {
        $this->CI->db->insert('project', ['name' => $name, 'slug' => $name]);
        return $this->CI->db->insert_id();
    }

    private function addUser()
    {
        $user = ['idUser' => 3, 'username'=> 'user_test_1@test.com'];
        $this->CI->base_user_m->add($user);
        return $user['idUser'];
    }


    public function mockSession($idUser){
        $this->CI->reporter_auth->deleteSession();
        $this->CI->reporter_auth->newSession($idUser);
    }

    public function test_redirect_for_login(){
        $this->CI->reporter_auth->deleteSession();
        $uri = $this->CI->config->item('rpt_login');
        $output = $this->request('GET', 'project');
        $this->assertRedirect($uri);
    }


    public function test_user_can_access_anything(){
        $this->load();
        $idUser = $this->addUser();
        $this->mockSession($idUser);
        $line = $this->CI->lang->line('empty_projects');
        $output = $this->request('GET', "project/index");
        $this->assertContains($line, $output);
    }

    public function test_user_can_access_project(){
        $this->load();
        $idUser = $this->addUser();
        $project = 'project_test_1';
        $idProject = $this->addProject($project);
        $this->load();
        $this->CI->project_m->addUser($idProject, $idUser);
        $this->mockSession($idUser);
        $output = $this->request('GET', "project/name/{$project}");
        $this->assertContains($project, $output);
    }

    public function test_user_can_not_access_project(){
        $this->load();
        $idUser = $this->addUser();
        $project = 'project_test_1';
        $idProject = $this->addProject($project);
        $this->mockSession($idUser);
        $output = $this->request('GET', "project/name/{$project}");
        $this->assertRedirect('/');
    }

    public function test_user_cannot_access_report_because_user_not_have_permission_to_project(){
        $this->load();
        $report = ['user_id' => 1, 'connection' => 1, 'title' => 'test_report_1',
        'description' => 'test', 'url' =>'', 'sql'=>''];
        $this->CI->load->model('admin/AdminReport_m', 'admin_report_m');
        $idReport = $this->CI->admin_report_m->add($report);
        $idProject = $this->addProject('project_test_1');
        $this->CI->admin_report_m->addProjects($idReport, [$idProject]);
        $idUser = $this->addUser();
        $this->mockSession($idUser);
        $output = $this->request('GET', "report/grid/{$idReport}/{$idProject}");
        $this->assertRedirect('/');
    }

    public function test_user_access_report(){
        $this->load();
        $report = ['user_id' => 1, 'connection' => 1, 'title' => 'test_report_1',
            'description' => 'test', 'url' =>'', 'sql'=>''];
        $this->CI->load->model('admin/AdminReport_m', 'admin_report_m');
        $idProject = $this->addProject('project_test_1');
        $idReport = $this->CI->admin_report_m->add($report);
        $idUser = $this->addUser();
        $this->CI->project_m->addUser($idProject, $idUser);
        $this->CI->admin_report_m->addProjects($idReport, [$idProject]);
        $this->mockSession($idUser);
        $output = $this->request('GET', "report/grid/{$idReport}/{$idProject}");
        $this->assertContains($report['title'], $output);
    }

//-----------------------------

    //test permiso de escritura
//
//    public function test_admin_can_access_everyware_but_nut_server_connection(){ //user_developer
//
//    }
//
//    public function test_admin_can_not_create_server_connection(){ //user_admin
//
//    }
//
//    public function test_user_can_access_anything(){
//
//    }
//
//    public function test_developer_can_access_everyware(){
//
//    }
}