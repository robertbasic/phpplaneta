<?php

/**
 * @todo add messages to flashmessenger all over the place
 */

class NewsTagsController extends Zend_Controller_Action
{

    public function init()
    {
        $this->model = new Planet_Model_News();
        $this->loggedInUser = $this->_helper->loggedInUser();
        $this->redirector = $this->getHelper('redirector');
        $this->urlHelper = $this->getHelper('url');
        $this->fm = $this->getHelper('flashMessenger');
    }

    public function indexAction()
    {
    }

    public function adminListAction()
    {
    }
    
    public function addAction()
    {
    }

    public function ajaxAddAction()
    {
        if(!$this->loggedInUser) {
            $this->fm->addMessage(array('fm-bad' => 'Nemate pravo pristupa!'));
            return $this->redirector->gotoRoute(null, 'login');
        }
        
        if(!$this->_request->isXmlHttpRequest()
                and !$this->_request->isPost()) {
            return $this->redirector->gotoRoute(array(), 'admin', true);
        }
        
        $data = $this->_request->getPost();

        try{
            $tags['tags'] = $this->model->saveNewsTags($data);
            echo $this->_helper->json($tags);
        } catch (Exception $e) {
            $response['errors'] = $e->getMessage();
            echo $this->_helper->json($response);
        }
    }

    public function editAction()
    {
    }

    public function deleteAction()
    {
    }

}