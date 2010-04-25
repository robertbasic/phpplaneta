<?php

class NewsController extends Zend_Controller_Action
{

    public function init()
    {
        $this->model = new Planet_Model_News();
    }

    public function indexAction()
    {
    }

    public function adminListAction()
    {
        $this->view->news = $this->model->getAllNews();
    }

    public function addAction()
    {
        /**
         * @todo Add user's ID
         */
        $addForm = $this->model->getForm('News_Add', $this->model);

        if($this->_request->isPost()) {
            if($addForm->isValid($this->_request->getPost())) {
                if($this->model->saveNews($addForm->getValues())) {
                    return $this->_helper->redirector('admin-list');
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

        $editForm = $this->model->getForm('News_Edit', $this->model);
        $editForm->populate($this->model->getOneNewsById($id)->toArray());

        /**
         * @todo breaks for whatever reason. fix it.
         * 
         */
        if($this->_request->isPost()) {
            if($editForm->isValid($this->_request->getPost())) {
                if($this->model->saveNews($editForm->getValues())) {
                    return $this->_helper->redirector('admin-list');
                }
            }
        }

        $this->view->editForm = $editForm;
    }

    public function deleteAction()
    {
        $id = $this->_request->getParam('id', null);

        if($id === null) {
            return $this->_helper->redirector('admin-list');
        }

        $this->model->deleteNews($id);
        return $this->_helper->redirector('admin-list');
    }

}