<?php

class PPN_Plugin_AdminContext extends Zend_Controller_Plugin_Abstract
{
    protected $_auth = null;

    public function preDispatch(Zend_Controller_Request_Abstract $request) 
    {
        if($request->getParam('isAdmin')
                or $request->getControllerName() === 'admin') {

            $auth = $this->_getAuth();

            if(!$auth->getIdentity()) {
                $request->setModuleName('public')
                        ->setControllerName('user')
                        ->setActionName('login');
                return;
            }

            $layout = Zend_Layout::getMvcInstance();
            $layout->setLayout('admin');
            $view = $layout->getView();
            $view->headTitle()->prepend('Admin panel');
        }
    }

    protected function _getAuth()
    {
        if($this->_auth === null) {
            $this->_auth = new Planet_Service_Auth();
        }

        return $this->_auth;
    }
}