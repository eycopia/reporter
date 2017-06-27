<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class No_auth_adapter
 * Moc para evitar la autenticación
 * @package Reporter\Libraries
 * @author Jorge Copia Silva <eycopia@gmail.com>
 * @license https://github.com/eycopia/reporter/blob/master/LICENSE
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
            $this->CI->session->set_flashdata('message', $this->CI->lang->line('unauthorized_resource'));
            $this->CI->session->set_flashdata('type_message', 'danger');
            redirect(base_url());
        }
    }

    /**
     * Return the current user id
     * @return int
     */
    public function get_user_id()
    {
        if(!isset($_SESSION['user_id'])){
            $this->login();
        }
        return $_SESSION['user_id'];
    }
}
