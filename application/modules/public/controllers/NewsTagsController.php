<?php

/**
 *   File: NewsTagsController.php
 *
 *   Description:
 *      For working with news tags. This can turn out a bit ugly,
 *      as the tags are in most cases added via ajax from the news controller,
 *      add action.
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

    /**
     * @todo implement
     */
    public function adminListAction()
    {
    }

    /**
     * @todo implement
     */
    public function addAction()
    {
    }

    public function ajaxLoadAction()
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

        $tags['tags'] = $this->model->getTagsForNews($data)->toArray();
        echo $this->_helper->json($tags);
    }

    /**
     * Adding one or more tags via ajax
     * The check for already existsing tags is done in the model
     */
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

    /**
     * @todo implement
     */
    public function editAction()
    {
    }

    /**
     * @todo implement
     */
    public function deleteAction()
    {
    }

}