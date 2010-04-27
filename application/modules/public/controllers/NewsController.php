<?php

/**
 * @todo add messages to flashmessenger all over the place
 */

class NewsController extends Zend_Controller_Action
{

    public function init()
    {
        $this->model = new Planet_Model_News();
        $this->loggedInUser = $this->_helper->loggedInUser();
        $this->redirector = $this->getHelper('redirector');
    }

    public function indexAction()
    {
    }

    public function adminListAction()
    {
        $page = $this->_getParam('page', 1);

        $this->view->news = $this->model->getAllNews($page);
    }

    public function addAction()
    {
        $addForm = $this->model->getForm('News_Add');
        $addForm->getElement('fk_user_id')->setValue($this->loggedInUser->id);

        if($this->_request->isPost()) {
            if($addForm->isValid($this->_request->getPost())) {
                try {
                   $this->model->saveNews($addForm->getValues());
                   return $this->redirector->gotoRoute(
                           array('action' => 'admin-list', 'controller' => 'news'),
                           'admin', true
                           );
                } catch (Exception $e) {
                }
            }
        }

        $this->view->addForm = $addForm;
    }

    public function editAction()
    {
        $id = $this->_request->getParam('id', null);

        if($id === null) {
            return $this->_helper->redirector('admin-list');
        }

        $editForm = $this->model->getForm('News_Edit');
        $editForm->populate($this->model->getOneNewsById($id)->toArray());

        /**
         * @todo breaks cause of the csrf element. fix it.
         */
        if($this->_request->isPost()) {
            if($editForm->isValid($this->_request->getPost())) {
                try {
                   $this->model->saveNews($editForm->getValues());
                   return $this->redirector->gotoRoute(
                           array('action' => 'admin-list', 'controller' => 'news'),
                           'admin', true
                           );
                } catch (Exception $e) {
                }
            }
        }

        $this->view->editForm = $editForm;
    }

    public function deleteAction()
    {
        $id = $this->_request->getParam('id', null);

        if($id === null) {
            return $this->redirector->gotoRoute(
                           array('action' => 'admin-list', 'controller' => 'news'),
                           'admin', true
                           );
        }

        try {
            $this->model->deleteNews($id);
        } catch (Exception $e) {
        }
        return $this->redirector->gotoRoute(
                           array('action' => 'admin-list', 'controller' => 'news'),
                           'admin', true
                           );
    }

}