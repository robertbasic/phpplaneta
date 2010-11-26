<?php

/**
 *   File: NewsSourcesController.php
 *
 *   Description:
 *      For working with news sources
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

        $this->view->sources = $this->model->getAllNewsSources($page);

        $this->view->pageTitle = 'Administracija izvora vesti';
    }

    /**
     * Add a source
     */
    public function addAction()
    {
        if(!$this->loggedInUser) {
            $this->fm->addMessage(array('fm-bad' => 'Nemate pravo pristupa!'));
            return $this->redirector->gotoRoute(array(), 'login');
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

    /**
     * Edit a source
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

    /**
     * Delete a source
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