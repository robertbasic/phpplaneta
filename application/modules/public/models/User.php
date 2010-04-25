<?php

/**
 * User model
 *
 * @author robert
 */
class Planet_Model_User extends PPN_Model_Abstract
{
    public function __construct()
    {
        
    }

    public function getUserByEmail($email)
    {
        return $this->getResource('User')->getUserByEmail($email);
    }

}