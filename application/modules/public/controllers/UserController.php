<?php

class UserController extends Zend_Controller_Action
{

    public function init()
    {
        $this->auth = new Planet_Service_Auth();
    }

    public function indexAction()
    {
    }

    public function loginAction()
    {
        $loginForm = new Planet_Form_User_Login();
        
        if($this->_request->isPost()) {
            if($loginForm->isValid($this->_request->getPost())) {
                $data = $loginForm->getValues();
                if($this->auth->authenticate($data['email'], $data['password'])) {
                    return $this->_helper->redirector->gotoUrl('/admin');
                } else {
                    /**
                     * @todo add failed authentication message to flashmessenger
                     */
                }
            }
        }

        $this->view->loginForm = $loginForm;
    }

    public function logoutAction()
    {
        $this->auth->clear();
        /**
         * @todo add successful logout message to flashmessenger
         */
        return $this->_helper->redirector->gotoUrl('/');
    }

}