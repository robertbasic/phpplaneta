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

    public function adminListAction()
    {
        if(!$this->loggedInUser) {
            $this->fm->addMessage(array('fm-bad' => 'Nemate pravo pristupa!'));
            return $this->redirector->gotoRoute(array(), 'login');
        }

        $page = $this->_getParam('page', 1);

        $this->view->tags = $this->model->getAllNewsTags($page);

        $this->view->pageTitle = 'Administracija oznaka vesti';
    }

    public function addAction()
    {
        if(!$this->loggedInUser) {
            $this->fm->addMessage(array('fm-bad' => 'Nemate pravo pristupa!'));
            return $this->redirector->gotoRoute(array(), 'login');
        }

        $addForm = $this->model->getForm('News_Tags');
        $addForm->setAction($this->urlHelper->url(array(
                                                    'action' => 'add',
                                                    'controller' => 'news-tags'
                                                ),
                                                'admin', true
                                            ));

        if($this->_request->isPost()) {
            if($addForm->isValid($this->_request->getPost())) {
                try {
                   $this->model->saveNewsTags($addForm->getValues());

                   $this->fm->addMessage(array('fm-good' => 'Oznaka uspešno dodata!'));

                   return $this->redirector->gotoRoute(
                           array('action' => 'admin-list', 'controller' => 'news-tags'),
                           'admin', true
                           );
                } catch (Exception $e) {
                    $this->fm->addMessage(array('fm-bad' => $e->getMessage()));
                }
            }
        }

        $this->view->addForm = $addForm;

        $this->view->pageTitle = 'Dodavanje oznake vesti';
    }

    public function ajaxLoadAction()
    {
        if(!$this->loggedInUser) {
            $this->fm->addMessage(array('fm-bad' => 'Nemate pravo pristupa!'));
            return $this->redirector->gotoRoute(array(), 'login');
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
            return $this->redirector->gotoRoute(array(), 'login');
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
        if(!$this->loggedInUser) {
            $this->fm->addMessage(array('fm-bad' => 'Nemate pravo pristupa!'));
            return $this->redirector->gotoRoute(array(), 'login');
        }

        // @todo move this ID checking to the model
        // and just throw an exception from there
        $id = $this->_request->getParam('id', null);

        if($id === null) {
            return $this->redirector->gotoRoute(
                           array('action' => 'admin-list', 'controller' => 'news-tags'),
                           'admin', true
                           );
        }

        $editForm = $this->model->getForm('News_Tags_Edit');
        $editForm->setAction($this->urlHelper->url(array(
                                                    'action' => 'edit',
                                                    'controller' => 'news-tags'
                                                ),
                                                'admin', true
                                            ));
        $editForm->populate($this->model->getOneNewsTagById($id)->toArray());
        $editForm->setSlugValidator();

        if($this->_request->isPost()) {
            if($editForm->isValid($this->_request->getPost())) {
                try {
                   $this->model->saveNewsTags($editForm->getValues());

                   $this->fm->addMessage(array('fm-good' => 'Oznaka vesti uspešno promenjena!'));

                   return $this->redirector->gotoRoute(
                           array('action' => 'admin-list', 'controller' => 'news-tags'),
                           'admin', true
                           );
                } catch (Exception $e) {
                    $this->fm->addMessage(array('fm-bad' => $e->getMessage()));
                }
            }
        }

        $this->view->editForm = $editForm;

        $this->view->pageTitle = 'Izmena oznake vesti';
    }

    public function deleteAction()
    {
        if(!$this->loggedInUser) {
            $this->fm->addMessage(array('fm-bad' => 'Nemate pravo pristupa!'));
            return $this->redirector->gotoRoute(array(), 'login');
        }

        // @todo move this ID checking to the model
        // and just throw an exception from there
        $id = $this->_request->getParam('id', null);

        if($id === null) {
            return $this->redirector->gotoRoute(
                           array('action' => 'admin-list', 'controller' => 'news-tags'),
                           'admin', true
                           );
        }

        try {
            $this->model->deleteNewsTag($id);

            $this->fm->addMessage(array('fm-good' => 'Oznaka vesti uspešno obrisana!'));
        } catch (Exception $e) {
            $this->fm->addMessage(array('fm-bad' => $e->getMessage()));
        }
        return $this->redirector->gotoRoute(
                           array('action' => 'admin-list', 'controller' => 'news-tags'),
                           'admin', true
                           );
    }

}