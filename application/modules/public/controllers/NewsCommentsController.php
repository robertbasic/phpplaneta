<?php

/**
 *   File: NewsSourcesController.php
 *
 *   Description:
 *      For working with news sources
*/

class NewsCommentsController extends Zend_Controller_Action
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
     * List sources, paginated, for the admin panel
     */
    public function adminListAction()
    {
        if(!$this->loggedInUser) {
            $this->fm->addMessage(array('fm-bad' => 'Nemate pravo pristupa!'));
            return $this->redirector->gotoRoute(array(), 'login');
        }

        $page = $this->_getParam('page', 1);

        $this->view->comments = $this->model->getAllComments($page);

        $this->view->pageTitle = 'Administracija komentara';
    }

    /**
     * Edit a comment
     * @todo GET shouldn't edit
     */
    public function editAction()
    {
        if(!$this->loggedInUser) {
            $this->fm->addMessage(array('fm-bad' => 'Nemate pravo pristupa!'));
            return $this->redirector->gotoRoute(array(), 'login');
        }

        // @todo move this ID checking to the model (getOneNewsSourceById)
        // and just throw an exception from there
        $id = $this->_request->getParam('id', null);

        if($id === null) {
            return $this->redirector->gotoRoute(
                           array('action' => 'admin-list', 'controller' => 'news-comments'),
                           'admin', true
                           );
        }

        $editForm = $this->model->getForm('News_Comments_Edit');
        $editForm->setAction($this->urlHelper->url(array(
                                                    'action' => 'edit',
                                                    'controller' => 'news-comments'
                                                ),
                                                'admin', true
                                            ));
        $editForm->populate($this->model->getOneCommentById($id)->toArray());

        if($this->_request->isPost()) {
            if($editForm->isValid($this->_request->getPost())) {
                try {
                   $this->model->saveComment($editForm->getValues());

                   $this->fm->addMessage(array('fm-good' => 'Komentar uspešno promenjen!'));

                   return $this->redirector->gotoRoute(
                           array('action' => 'admin-list', 'controller' => 'news-comments'),
                           'admin', true
                           );
                } catch (Exception $e) {
                    $this->fm->addMessage(array('fm-bad' => $e->getMessage()));
                }
            }
        }

        $this->view->editForm = $editForm;

        $this->view->pageTitle = 'Izmena komentara';
    }

    /**
     * Delete a comment
     * @todo GET shouldn't delete
     */
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
                           array('action' => 'admin-list', 'controller' => 'news-comments'),
                           'admin', true
                           );
        }

        try {
            $this->model->deleteComment($id);

            $this->fm->addMessage(array('fm-good' => 'Komentar uspešno obrisan!'));
        } catch (Exception $e) {
            $this->fm->addMessage(array('fm-bad' => $e->getMessage()));
        }
        return $this->redirector->gotoRoute(
                           array('action' => 'admin-list', 'controller' => 'news-comments'),
                           'admin', true
                           );
    }

}