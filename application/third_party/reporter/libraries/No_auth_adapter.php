<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(__DIR__."/interfaceAuthReporter.php");

/**
 * Name: Ion_auth_adapter.php
 *
 * Author: Jorge Copia <eycopia@gmail.com>
 *
 */
class No_auth_adapter  implements interfaceAuthReporter
{
    private $CI = null;

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    /**
     * Redirect to login page
     * @return HttpRequest
     */
    public function login()
    {
        $_SESSION['user_id'] = 1;
        $_SESSION['email'] = $this->CI->config->item('tester_email');
    }

    /**
     * @return redirect to login page
     */
    public function logout()
    {
        session_destroy();
        $this->login();
    }

    /**
     * Check user is login
     * @return boolean
     */
    public function isLogin()
    {
        if(isset($_SESSION['user_id'])){
            $this->login();
        }
    }

    /**
     * Check if current user is admin
     * @return boolean
     */
    public function isAdmin()
    {
        return true;
    }

    /**
     * Check if the current user is admin
     * @return HttpRequest
     */
    public function checkAdmin()
    {
        if(!$this->isAdmin()){
            redirect(base_url());
        }
    }

    /**
     * Return the current user id
     * @return int
     */
    public function get_user_id()
    {
        return $_SESSION['user_id'];
    }
}
