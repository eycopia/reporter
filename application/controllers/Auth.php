<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 * @author Jorge Copia
 *        
 */
class Auth extends CI_Controller
{

    /**
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    
    public function logout()
    {
        $this->reporter_auth->logout();        
    }
    
    
    public function autenticate(){
        
    }
}

