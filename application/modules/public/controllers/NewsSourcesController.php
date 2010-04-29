<?php

/**
 * @todo add messages to flashmessenger all over the place
 */

class NewsSourcesController extends Zend_Controller_Action
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
            return $this->redirector->gotoRoute(null, 'login');
        }

        $page = $this->_getParam('page', 1);

        $this->view->sources = $this->model->getAllNewsSources($page);

        $this->view->pageTitle = 'Administracija izvora vesti';
    }
    
    public function addAction()
    {
        if(!$this->loggedInUser) {
            $this->fm->addMessage(array('fm-bad' => 'Nemate pravo pristupa!'));
            return $this->redirector->gotoRoute(null, 'login');
        }

        $addForm = $this->model->getForm('News_Sources_Add');
        $addForm->setAction($this->urlHelper->url(array(
                                                    'action' => 'add',
                                                    'controller' => 'news-sources'
                                                ),
                                                'admin', true
                                            ));

        if($this->_request->isPost()) {
            if($addForm->isValid($this->_request->getPost())) {
                try {
                   $this->model->saveNewsSource($addForm->getValues());

                   $this->fm->addMessage(array('fm-good' => 'Izvor uspešno dodat!'));

                   return $this->redirector->gotoRoute(
                           array('action' => 'admin-list', 'controller' => 'news-sources'),
                           'admin', true
                           );
                } catch (Exception $e) {
                    $this->fm->addMessage(array('fm-bad' => $e->getMessage()));
                }
            }
        }

        $this->view->addForm = $addForm;

        $this->view->pageTitle = 'Dodavanje izvora vesti';
    }

    public function editAction()
    {
        if(!$this->loggedInUser) {
            $this->fm->addMessage(array('fm-bad' => 'Nemate pravo pristupa!'));
            return $this->redirector->gotoRoute(null, 'login');
        }

        $id = $this->_request->getParam('id', null);

        if($id === null) {
            return $this->redirector->gotoRoute(
                           array('action' => 'admin-list', 'controller' => 'news-sources'),
                           'admin', true
                           );
        }

        $editForm = $this->model->getForm('News_Sources_Edit');
        $editForm->setAction($this->urlHelper->url(array(
                                                    'action' => 'edit',
                                                    'controller' => 'news-sources'
                                                ),
                                                'admin', true
                                            ));
        $editForm->populate($this->model->getOneNewsSourceById($id)->toArray());

        if($this->_request->isPost()) {
            if($editForm->isValid($this->_request->getPost())) {
                try {
                   $this->model->saveNewsSource($editForm->getValues());

                   $this->fm->addMessage(array('fm-good' => 'Izvor vesti uspešno promenjen!'));

                   return $this->redirector->gotoRoute(
                           array('action' => 'admin-list', 'controller' => 'news-sources'),
                           'admin', true
                           );
                } catch (Exception $e) {
                    $this->fm->addMessage(array('fm-bad' => $e->getMessage()));
                }
            }
        }

        $this->view->editForm = $editForm;

        $this->view->pageTitle = 'Izmena izvora vesti';
    }

    public function deleteAction()
    {
        if(!$this->loggedInUser) {
            $this->fm->addMessage(array('fm-bad' => 'Nemate pravo pristupa!'));
            return $this->redirector->gotoRoute(null, 'login');
        }
        
        $id = $this->_request->getParam('id', null);

        if($id === null) {
            return $this->redirector->gotoRoute(
                           array('action' => 'admin-list', 'controller' => 'news-sources'),
                           'admin', true
                           );
        }

        try {
            $this->model->deleteNewsSource($id);

            $this->fm->addMessage(array('fm-good' => 'Izvor vesti uspešno obrisan!'));
        } catch (Exception $e) {
            $this->fm->addMessage(array('fm-bad' => $e->getMessage()));
        }
        return $this->redirector->gotoRoute(
                           array('action' => 'admin-list', 'controller' => 'news-sources'),
                           'admin', true
                           );
    }

}