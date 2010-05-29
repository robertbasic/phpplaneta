<?php

class UserController extends Zend_Controller_Action
{

    public function init()
    {
        $this->auth = new Planet_Service_Auth();
        
        $this->fm = $this->getHelper('flashMessenger');
    }

    public function indexAction()
    {
    }

    public function loginAction()
    {
        $this->_helper->layout->setLayout('login');
        
        $loginForm = new Planet_Form_User_Login();
        
        if($this->_request->isPost()) {
            if($loginForm->isValid($this->_request->getPost())) {
                $data = $loginForm->getValues();
                if($this->auth->authenticate($data['email'], $data['password'])) {
                    $this->fm->addMessage(array('fm-good' => 'Uspešno ste se prijavili!'));
                    return $this->_helper->redirector->gotoUrl('/admin');
                } else {
                    $this->fm->addMessage(array('fm-bad' => 'Pogrešno korisniško ime i/ili lozinka!'));
                }
            }
        }

        $this->view->loginForm = $loginForm;
    }

    public function logoutAction()
    {
        $this->auth->clear();

        $this->fm->addMessage(array('fm-good' => 'Uspešno ste se odjavili!'));

        return $this->_helper->redirector->gotoUrl('/');
    }

}