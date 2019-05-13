<?php

use Nette\Security\IUserStorage;
use Nette\Security\IIdentity;

/**
 *
 * @author JorgeCopia
 *        
 */
class NetteStorage_m extends CI_Model implements IUserStorage
{

    /**
     */
    public function __construct()
    {}

    /**
     * (non-PHPdoc)
     *
     * @see \Nette\Security\IUserStorage::getIdentity()
     */
    public function getIdentity()
    {}

    /**
     * (non-PHPdoc)
     *
     * @see \Nette\Security\IUserStorage::setAuthenticated()
     */
    public function setAuthenticated($state)
    {}

    /**
     * (non-PHPdoc)
     *
     * @see \Nette\Security\IUserStorage::getLogoutReason()
     */
    public function getLogoutReason()
    {}

    /**
     * (non-PHPdoc)
     *
     * @see \Nette\Security\IUserStorage::isAuthenticated()
     */
    public function isAuthenticated()
    {}

    /**
     * (non-PHPdoc)
     *
     * @see \Nette\Security\IUserStorage::setIdentity()
     */
    public function setIdentity(IIdentity $identity = null)
    {}

    /**
     * (non-PHPdoc)
     *
     * @see \Nette\Security\IUserStorage::setExpiration()
     */
    public function setExpiration($time, $flags = 0)
    {}
}

