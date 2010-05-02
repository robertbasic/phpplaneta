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
        $this->urlHelper = $this->getHelper('url');
        $this->fm = $this->getHelper('flashMessenger');

        $this->view->headScript()->appendFile('/static/ckeditor/ckeditor.js');
        $this->view->headScript()->appendFile('/static/ckeditor/adapters/jquery.js');
        $this->view->headScript()->appendFile('/static/js/ckeditor.js');

        $this->view->headScript()->appendScript("
            $(function(){
                var tagsform = $('#tagsform');
                if(tagsform.length > 0) {
                    tagsform.remove();
                    $('#right').append(tagsform);
                    tagsform = $('#tagsform');
                    tagsform.submit(function(){
                        var tags = $('#tags').val();
                        $.post(
                            '/admin/public/news-tags/ajax-add',
                            {
                                tags: tags
                            },
                            function(data) {
                                console.log(data);
                            },
                            'json'
                        );
                        return false;
                    });
                }
            });
        ");

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

        $this->view->news = $this->model->getAllNews($page);

        $this->view->pageTitle = 'Administracija vesti';
    }

    public function addAction()
    {
        if(!$this->loggedInUser) {
            $this->fm->addMessage(array('fm-bad' => 'Nemate pravo pristupa!'));
            return $this->redirector->gotoRoute(null, 'login');
        }

        if(null == $this->model->getAllNewsCategories()->toArray()) {
            $this->fm->addMessage(array('fm-bad' => 'Mora postojati najmanje 1 kategorija vesti!'));
            return $this->redirector->gotoRoute(
                           array('action' => 'admin-list', 'controller' => 'news'),
                           'admin', true
                           );
        }

        $addForm = $this->model->getForm('News_Add');
        $addForm->setAction($this->urlHelper->url(array(
                                                    'action' => 'add',
                                                    'controller' => 'news'
                                                ),
                                                'admin', true
                                            ));
        $addForm->setSlugValidator();
        $addForm->getElement('fk_user_id')->setValue($this->loggedInUser->id);

        if($this->_request->isPost()) {
            if($addForm->isValid($this->_request->getPost())) {
                try {
                   $this->model->saveNews($addForm->getValues());

                   $this->fm->addMessage(array('fm-good' => 'Vest uspešno dodata!'));

                   return $this->redirector->gotoRoute(
                           array('action' => 'admin-list', 'controller' => 'news'),
                           'admin', true
                           );
                } catch (Exception $e) {
                    $this->fm->addMessage(array('fm-bad' => $e->getMessage()));
                }
            }
        }

        $this->view->addForm = $addForm;

        $this->view->tagsForm = new Planet_Form_News_Tags();

        $this->view->pageTitle = 'Dodavanje vesti';
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
                           array('action' => 'admin-list', 'controller' => 'news'),
                           'admin', true
                           );
        }

        $editForm = $this->model->getForm('News_Edit');
        $editForm->setAction($this->urlHelper->url(array(
                                                    'action' => 'edit',
                                                    'controller' => 'news'
                                                ),
                                                'admin', true
                                            ));
        $editForm->populate($this->model->getOneNewsById($id)->toArray())
                ->setSlugValidator();

        if($this->_request->isPost()) {
            if($editForm->isValid($this->_request->getPost())) {
                try {
                   $this->model->saveNews($editForm->getValues());

                   $this->fm->addMessage(array('fm-good' => 'Vest uspešno promenjena!'));

                   return $this->redirector->gotoRoute(
                           array('action' => 'admin-list', 'controller' => 'news'),
                           'admin', true
                           );
                } catch (Exception $e) {
                    $this->fm->addMessage(array('fm-bad' => $e->getMessage()));
                }
            }
        }

        $this->view->editForm = $editForm;

        $this->view->pageTitle = 'Izmena vesti';
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
                           array('action' => 'admin-list', 'controller' => 'news'),
                           'admin', true
                           );
        }

        try {
            $this->model->deleteNews($id);

            $this->fm->addMessage(array('fm-good' => 'Vest uspešno obrisana!'));
        } catch (Exception $e) {
            $this->fm->addMessage(array('fm-bad' => $e->getMessage()));
        }
        return $this->redirector->gotoRoute(
                           array('action' => 'admin-list', 'controller' => 'news'),
                           'admin', true
                           );
    }

}