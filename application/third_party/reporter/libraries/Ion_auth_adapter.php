<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Ion_auth_adapter
 * Adaptador de Ion_Auth para el Reportador
 *
 * @package Reporter\Libraries
 * @author Jorge Copia Silva <eycopia@gmail.com>
 * @license https://github.com/eycopia/reporter/blob/master/LICENSE
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
     * Check login user, if not loggin redirect
     */
    public function check(){
        if(!$this->isLogin()){
            $this->login();
        }
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
        return $this->CI->ion_auth->logged_in();
    }

    /**
     * Check if current user is admin
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->CI->ion_auth->is_admin();
    }

    /**
     * Check if the current user is admin
     * @return HttpRequest
     */
    public function checkAdmin()
    {
        if(!$this->isAdmin()){
            $this->CI->session->set_flashdata('message', $this->CI->lang->line('unauthorized_resource'));
            $this->CI->session->set_flashdata('type_message', 'danger');
            redirect(base_url());
        }
    }

    public function get_user_id()
    {
        return $this->CI->ion_auth->get_user_id();
    }
}
