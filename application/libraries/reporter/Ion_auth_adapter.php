<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(__DIR__."/interfaceAuthReporter.php");

/**
 * Name: Ion_auth_adapter.php
 *
 * Author: Jorge Copia <eycopia@gmail.com>
 *
 */
class Ion_auth_adapter  implements interfaceAuthReporter
{
    private $CI = null;
    
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->library('ion_auth');
    }

    /**
     * Redirect to login page
     * @return HttpRequest
     */
    public function login()
    {
        redirect($this->CI->config->item('rpt_login'));
    }

    /**
     * @return redirect to login page
     */
    public function logout()
    {
        $this->CI->ion_auth->logout();
        redirect($this->CI->config->item('rpt_login'));
    }

    /**
     * Check user is login
     * @return boolean
     */
    public function isLogin()
    {
        if(!$this->CI->ion_auth->logged_in()){
            $this->login();
        }
    }

    /**
     * Check if current user is admin
     * @return boolean
     */
    public function isAdmin()
    {
        // TODO: Implement isAdmin() method.
    }
}