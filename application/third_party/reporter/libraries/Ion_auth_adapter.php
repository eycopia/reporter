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
        $this->CI->load->library('form_validation');
        $this->CI->form_validation->set_error_delimiters($this->CI->config->item('error_start_delimiter', 'ion_auth'), $this->CI->config->item('error_end_delimiter', 'ion_auth'));
        
        $this->CI->lang->load('auth');
    }

    /**
     * Redirect to login page
     * @return HttpRequest
     */
    public function login()
    {
        // validate form input
        $this->CI->form_validation->set_rules('username', str_replace(':', '', $this->CI->lang->line('login_identity_label')), 'required');
        $this->CI->form_validation->set_rules('password', str_replace(':', '', $this->CI->lang->line('login_password_label')), 'required');
        
        $rs = ['success' => FALSE, 'msg' => ''];
        if ($this->CI->form_validation->run() === TRUE)
        {
            // check to see if the user is logging in
            // check for "remember me"
            $remember = (bool)$this->CI->input->post('remember');
            
            $rs['success']= $this->CI->ion_auth->login($this->CI->input->post('username'), $this->CI->input->post('password'), $remember);
            $rs['userid'] = $this->CI->ion_auth->get_user_id();
        }
        return $rs;
           
    }

    /**
     * @return redirect to login page
     */
    public function logout()
    {
        $this->CI->ion_auth->logout();
        redirect($this->CI->config->item('rpt_login'));
    }

}
