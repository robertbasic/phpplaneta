<?php

/**
 * Service for authenticating users
 *
 * @author robert
 */
class Planet_Service_Auth
{

    /**
     *
     * @var Zend_Auth
     */
    protected $_auth = null;

    /**
     *
     * @var Zend_Auth_Adapter_DbTable
     */
    protected $_authAdapter = null;

    public function __construct()
    {
    }

    /**
     * Try to authenticate a user
     *
     * @param string $identity
     * @param string $credential
     * @return bool
     */
    public function authenticate($identity, $credential)
    {
        $authAdapter = $this->_getAuthAdapter($identity, $credential);
        $auth = $this->getAuth();
        $authResult = $auth->authenticate($authAdapter);

        if(!$authResult->isValid()) {
            return false;
        }

        // Get the user's info and write it to session
        // everything but his password
        $user = $authAdapter->getResultRowObject(null, 'password');

        $auth->getStorage()->write($user);

        return true;
    }

    /**
     * Get the logged in user's (if any) identity
     *
     * @return bool | stdClass
     */
    public function getIdentity()
    {
        $auth = $this->getAuth();
        if($auth->hasIdentity()) {
            return $auth->getIdentity();
        }

        return false;
    }

    /**
     * Clear the identity (logout)
     */
    public function clear()
    {
        $this->getAuth()->clearIdentity();
    }

    public function getAuth()
    {
        if($this->_auth === null) {
            $this->_auth = Zend_Auth::getInstance();
        }

        return $this->_auth;
    }

    protected function _getAuthAdapter($identity, $credential)
    {
        if($this->_authAdapter === null) {
            $dbAdapter = Zend_Db_Table_Abstract::getDefaultAdapter();
            $dbConfig = $dbAdapter->getConfig();

            $this->_authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);

            $this->_authAdapter->setTableName($dbConfig['prefix'] . 'users')
                                ->setIdentityColumn('email')
                                ->setCredentialColumn('password')
                                ->setCredentialTreatment('MD5(?)')
                                ->getDbSelect()
                                ->where('active = ?', true);

            $this->_authAdapter->setIdentity($identity)
                                ->setCredential($credential);
        }

        return $this->_authAdapter;
    }

}