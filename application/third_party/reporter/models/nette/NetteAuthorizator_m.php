<?php 
use Nette\Security\IAuthenticator;

/**
 *
 * @author JorgeCopia
 *        
 */
class NetteAuthorizator_m extends CI_Model implements IAuthenticator
{

    /**
     */
    public function __construct()
    {}

    /**
     * (non-PHPdoc)
     *
     * @see \Nette\Security\IAuthenticator::authenticate()
     */
    public function authenticate(array $credentials)
    {}
}

