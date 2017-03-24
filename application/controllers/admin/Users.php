<?php

/**
 * Class Users
 *
 * @package \\${NAMESPACE}
 */
class Users extends CI_Controller{

    public function __construct()
    {
        parent::__construct();

        $this->ion_auth->validateAdminUser();
        $this->load->library('grocery_CRUD');
        $this->ion_auth->isLogin();
    }

    /**
     * CRUD Configure for table project
     * @return html
     */
    public function index()
    {
        try{
            $crud = new grocery_CRUD();
            $crud->unset_delete();//grid
            $crud->set_table('users');
            $crud->set_subject('Users');
            $crud->set_relation_n_n('groups', 'users_groups', 'groups', 'user_id', 'group_id', 'name');
            $crud->set_relation_n_n('projects', 'user_projects', 'project', 'user_id', 'idProject', 'name');
            $crud->fields('username','full_name', 'status', 'groups', 'projects');
            $crud->required_fields('username','full_name');
            $crud->edit_fields('full_name', 'status', 'groups', 'projects','updated');
            $crud->callback_before_insert(array($this, 'addCentralLogin'));
            $crud->callback_before_update(array($this, 'editUpdated'));
            $output = $crud->render();
            $output->title_page = 'Users for ' . APP_NAME ;
            $output->main_content =  'admin';
            $this->load->view('template/index',$output);
        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    public function reset($idUser){
        $q = $this->db->query("SELECT * FROM users WHERE id = $idUser");
        $user = $q->row();
        if(count($user)){
            $this->db->query("UPDATE central_login.users
              SET password = null, special_creation=1, appID=6
              WHERE username = '{$user->username}'");
            $this->session->set_flashdata('message', "Se quito el antiguo password para el usuario: {$user->username}");
            $this->session->set_flashdata('type_message', "success");
            redirect(site_url('admin/users/'));
        }else{
            $this->session->set_flashdata('message', "No existe este usuario");
            $this->session->set_flashdata('type_message', "danger");
        }
    }

    public function addCentralLogin($post)
    {
        $user = trim($post['username']);
        $data = $this->findUserCentral($user);
        if(count($data)>0){
            $this->agregarPermiso($user);
        }else{
            $this->nuevoUsuarioCentral($user);
        }
    }

    private function nuevoUsuarioCentral($user){
        $this->db->query("INSERT INTO central_login.users(username, special_creation, appID)
          values('$user',1,6)");
        $this->agregarPermiso($user);
    }

    private function agregarPermiso($username)
    {
        $user = $this->findUserCentral($username);
        $this->db->query("INSERT IGNORE INTO central_login.user_apps(userID, appID, status)
            VALUES ({$user->id}, 6, 1)");
    }

    public function editUpdated($user){
        $user['updated'] = date('Y-m-d H:i:s');
        return $user;
    }

    private function findUserCentral($user)
    {
        $q = $this->db->query("SELECT * FROM central_login.users where username = '{$user}'");
        return $q->row();
    }
}
