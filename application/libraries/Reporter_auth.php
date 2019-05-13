<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH."third_party/reporter/libraries/interfaceAuthReporter.php";




class Reporter_auth implements interfaceAuthReporter
{
    /**
     * @var CI instance
     */
    private $CI = null;

    /**
     * @var interfaceAuthReporter
     */
    public $adapter;

    public function __construct()
    {
        $this->CI = &get_instance();
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
     * @return HttpRequest
     */
    public function login()
    {
        $this->adapter->login();
    }

    /**
     * @return redirect to login page
     */
    public function logout()
    {
        $this->adapter->logout();
    }

    /**
     * Check user is login
     * @return boolean
     */
    public function isLogin()
    {
        $this->adapter->isLogin();
    }

    /**
     * Check if current user is admin
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->adapter->isAdmin();
    }

    /**
     * Check if the current user is admin
     * @return HttpRequest
     */
    public function checkAdmin(){
        $this->adapter->checkAdmin();
    }

    public function get_user_id()
    {
        return $this->adapter->get_user_id();
    }
}
