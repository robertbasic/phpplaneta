<?php

/**
 *   File: NewsController.php
 *
 *   Description:
 *      Both the front-end and admin for all news related stuff going
 *      through this controller, as we're using "pseudo" module for the
 *      admin panel
*/
class LogsController extends Zend_Controller_Action
{

    public function init()
    {
        $this->model = new Planet_Model_Logs();
        $this->loggedInUser = $this->_helper->loggedInUser();
        $this->redirector = $this->getHelper('redirector');
        $this->urlHelper = $this->getHelper('url');
        $this->fm = $this->getHelper('flashMessenger');
    }

    public function indexAction()
    {
        return $this->redirector->gotoRoute(
                           array('action' => 'admin-list', 'controller' => 'logs'),
                           'admin', true
                           );
    }


    /**
     * List logs, paginated, for the admin panel
     */
    public function adminListAction()
    {
        if(!$this->loggedInUser) {
            $this->fm->addMessage(array('fm-bad' => 'Nemate pravo pristupa!'));
            return $this->redirector->gotoRoute(array(), 'login');
        }

        $page = $this->_getParam('page', 1);

        $this->view->logs = $this->model->getAllLogs($page);

        $this->view->pageTitle = 'Pregled logova';
    }

    public function adminDeleteLogsAction()
    {
        if(!$this->loggedInUser) {
            $this->fm->addMessage(array('fm-bad' => 'Nemate pravo pristupa!'));
            return $this->redirector->gotoRoute(array(), 'login');
        }

        if($this->model->deleteLogs()) {
            $this->fm->addMessage(array('fm-good' => 'Logovi uspešno obrisani!'));
        } else {
            $this->fm->addMessage(array('fm-bad' => 'Neuspelo brisanje logova!'));
        }

        return $this->redirector->gotoRoute(
                           array('action' => 'admin-list', 'controller' => 'logs'),
                           'admin', true
                           );
    }
}