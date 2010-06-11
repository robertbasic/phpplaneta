<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->model = new Planet_Model_News();
    }

    public function indexAction()
    {
        $page = $this->_getParam('page', 1);

        $this->view->news = $this->model->getAllActiveNews($page);
    }

    public function contactAction()
    {
        $contactForm = new Planet_Form_Contact();

        if($this->_request->isPost()) {
            if($contactForm->isValid($this->_request->getPost())) {
                try {
                    $contactService = new Planet_Service_Contact($contactForm->getValues());
                    $contactService->sendMail();
                } catch (Exception $e) {
                    
                }
            }
        }

        $this->view->contactForm = $contactForm;
    }

}