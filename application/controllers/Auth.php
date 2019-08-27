<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 */
class Auth extends CI_Controller
{
	public $data = [];

	public function __construct()
	{
	    parent::__construct();
	    $this->load->model('usuarios_m');
	}

	/**
	 * Log the user in
	 */
	public function login()
	{
	    if(count($_POST) > 0){
	        $rs = $this->reporter_auth->login();
	        $redir = ($rs['success']) ? site_url('/') : site_url('auth/login');
	        $type = ($rs['success']) ? 'success' : 'error';
	        $this->session->set_flashdata('message', $rs['msg']);
	        $this->session->set_flashdata('type_message', $type);
	        redirect($redir);
	    }
	    $data =  ['main_content' =>  $this->config->item('rpt_template') . "login"];
	    $this->load->view($this->config->item('rpt_base_template'), $data);	    
	}
	
	

	/**
	 * Log the user out
	 */
	public function logout()
	{
        $this->reporter_auth->logout();
	}

}
