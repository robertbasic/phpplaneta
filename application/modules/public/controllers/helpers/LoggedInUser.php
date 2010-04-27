<?php

/**
 * Description of LoggedInUser
 *
 * @author robert
 */
class Zend_Controller_Action_Helper_LoggedInUser
        extends Zend_Controller_Action_Helper_Abstract
{
    protected $_auth = null;

    public function direct()
    {
        $auth = $this->_getAuth();
        return $auth->getIdentity();
    }

    protected function _getAuth()
    {
        if($this->_auth === null) {
            $this->_auth = new Planet_Service_Auth();
        }

        return $this->_auth;
    }
}