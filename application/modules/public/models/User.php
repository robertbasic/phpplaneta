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

    /**
     *
     * @param string $email
     * @return Planet_Model_Resource_User_Item
     * @throws PPN_Exception_NotFound
     */
    public function getUserByEmail($email)
    {
        $user = $this->getResource('User')->getUserByEmail($email);
        
        if($user === null) {
            throw new PPN_Exception_NotFound();
        }
        
        return $user;
    }

}