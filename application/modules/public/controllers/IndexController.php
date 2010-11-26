<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->model = new Planet_Model_News();
        $this->fm = $this->getHelper('flashMessenger');
        $this->redirector = $this->getHelper('redirector');
    }

    public function indexAction()
    {
        $page = $this->_getParam('page', 1);

        $this->view->news = $this->model->getAllActiveNews($page);
    }

    public function aboutAction()
    {
    }

    public function contactAction()
    {
        $contactForm = new Planet_Form_Contact();

        if($this->_request->isPost()) {
            if($contactForm->isValid($this->_request->getPost())) {
                try {
                    $contactService = new Planet_Service_Contact($contactForm->getValues());
                    $contactService->sendMail();
                    
                    $this->fm->addMessage(array('fm-good' => 'E-mail uspešno poslat!'));

                    return $this->redirector->gotoRoute(
                           array('action' => 'contact', 'controller' => 'index'),
                           '', true
                           );
                    
                } catch (Exception $e) {
                    $this->fm->addMessage(array('fm-good' => 'Greška prilikom slanja E-maila! Molim Vas pokušajte ponovo!'));
                    try {
                        $logger = Zend_Registry::get('logger');
                        $logger->log($e->getMessage(),2);
                    } catch (Exception $e) {
                    }
                }
            }
        }

        $this->view->contactForm = $contactForm;
    }

}