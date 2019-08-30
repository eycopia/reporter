<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH."third_party/reporter/libraries/interfaceAuthReporter.php";
require_once APPPATH."third_party/reporter/core/Permission.php";




class Reporter_auth implements interfaceAuthReporter
{
    /**
     * @var CI instance
     */
    private $CI = null;

    private $keyUsername = 'reporter_user_loggin';

    /**
     * @var object BaseUser
     */
    private $user;

    /**
     * @var interfaceAuthReporter
     */
    public $adapter;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('authorization_m');
        $this->CI->load->model('base_user_m');
        $this->loadAdapter();
    }

    private function loadAdapter()
    {
        $adapter = $this->CI->config->item('rpt_auth_adapter');
        $this->CI->load->library($adapter, null, 'auth_adapter');
        $this->adapter = $this->checkAdapter($this->CI->auth_adapter);
    }

    private function checkAdapter(interfaceAuthReporter $adapter){
        return $adapter;
    }


    /**
     * Redirect to login page
     * @return array [success=>BOOLEAN, msg=>STRING, userid => INT]
     */
    public function login()
    {
        $rs = $this->adapter->login();
        if($rs['success']){
            $this->newSession($rs['userid']);
            $default = $this->CI->lang->line('login_successful') . $this->CI->config->item('app_name');
            $rs['msg'] = (strlen($rs['msg']) > 0  ) ? $rs['msg'] : $default; 
        }else{
            $rs['msg'] = (strlen($rs['msg']) > 0  ) ? $rs['msg'] : $this->CI->lang->line('login_failed');
        }
        return $rs;
    }

    public function newSession($idUser){
        $this->CI->session->set_userdata($this->keyUsername, $idUser);
    }

    public function deleteSession(){
        if($this->CI->session->has_userdata($this->keyUsername)){
            $this->CI->session->unset_userdata($this->keyUsername);
        }
    }

    /**
     * Valida si el usuario tiene permisos para ingresar a un proyecto
     * @param $idProject
      @return HttpRequest|void
     */
    public function checkProjectAccess($idProject){
        $project = $this->CI->authorization_m->getUserProject($idProject);
        $permission = isset($project->idProject) ? Permission::$READER : 0;
        $rs = $this->validPermission($permission, Permission::$READER);
        if($rs === FALSE){
            redirect(site_url());
        }


    }

    /**
     * Valida si el usuario tiene permiso para ingresar a un reporte y que tipo de permiso tiene
     * @param $idReport
     * @param string $type
     * @return HttpRequest|void
     */
    public function checkReportAccess($idProject, $idReport, $type=null){
        if(is_null($type)){
            $type = Permission::$READER;
        }
        $rs = false;
        $report = $this->CI->authorization_m->getReportProject($idProject, $idReport);
        if($report->idReport == $idReport){
            $project = $this->CI->authorization_m->getUserProject($idProject);
            $permission =  isset($project->permission) ? $project->permission : 0;
            $rs = $this->validPermission($permission, $type);
        }

        if($rs===FALSE){
            redirect(site_url());
        }
    }

    /**
     * @param int $level Permission required
     *
     */
    public function checkUserAccess($level){
        $this->dataUser();
        $rs = $this->validPermission($this->user->permission, $level);
        if(!$rs){
            $this->CI->session->set_flashdata('message', $this->CI->lang->line('unauthorized_resource'));
            $this->CI->session->set_flashdata('type_message', 'error');
            redirect(site_url());
        }
    }

    /**
     * @param int $permission user permission
     * @param int $type permission required
     * @return bool
     * @throws Exception
     */
    public function validPermission($permission, $type){

        if( $permission > Permission::$DEVELOPER || $type > Permission::$DEVELOPER) {
            throw new Exception("No existe el permiso que busca validar");
        }

        $this->dataUser();

        if($this->user->permission > Permission::$ADMIN && $permission < $this->user->permission){
            $permission = $this->user->permission;
        }
        return ($permission >= $type) ? TRUE : FALSE;
    }

    /**
     * @return redirect to login page
     */
    public function logout()
    {
        $this->deleteSession();
        $this->adapter->logout();        
    }

    /**
     * Check user is login
     * @return boolean
     */
    public function isLogin()
    {
        if( ! $this->CI->session->has_userdata($this->keyUsername) ) {
            redirect($this->CI->config->item('rpt_login'));
        }
    }

    private function dataUser(){
        if(is_null($this->user)){
            $this->user = $this->CI->base_user_m->find($this->get_user_id());
        }
    }

    /**
     * Check if current user is admin
     * @return boolean
     */
    public function isAdmin()
    {
        $this->dataUser();
        return $this->user->permission >= Permission::$ADMIN ? TRUE : FALSE;
    }

    /**
     * Check if current user is a developer
     * @return boolean
     */
    public function isDeveloper(){
        $this->dataUser();
        return $this->user->permission == Permission::$DEVELOPER ? TRUE : FALSE;
    }


    public function get_user_id()
    {
        return $this->CI->session->userdata($this->keyUsername);
    }

}
