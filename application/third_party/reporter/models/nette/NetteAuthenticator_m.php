<?php

use Nette\Security\IAuthenticator;

/**
 *
 * @author JorgeCopia
 *        
 */
class Authenticator extends CI_Model implements IAuthenticator
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
    {
        
    }
}

