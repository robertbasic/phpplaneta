<?php

/**
 *   File: NewsController.php
 *
 *   Description:
 *      Both the front-end and admin for all news related stuff going
 *      through this controller, as we're using "pseudo" module for the
 *      admin panel
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

        // some JS madness for working with the tags
        // eventually will be moved out to a separate JS file
        $this->view->headScript()->appendScript("
            $(function(){
                var tagsform = $('#tagsform');
                if(tagsform.length > 0) {
                    tagsform.remove();
                    $('#right').append(tagsform);
                    tagsform = $('#tagsform');
                    tagsform.submit(function(){
                        var tags = $('#tags');
                        var tagsVal = tags.val();
                        $.post(
                            '/admin/public/news-tags/ajax-add',
                            {
                                tags: tagsVal
                            },
                            function(data) {
                                if('tags' in data) {
                                    appendTags(data);
                                } else if('errors' in data) {
                                    console.log(data);
                                }
                                tags.val('');
                            },
                            'json'
                        );
                        return false;
                    });
                }

                $('.ul_tags li a').live('click', function(){
                    var liTag = $(this).parent();
                    var tagId = liTag.attr('rel').replace(/tag_id_/,'');
                    removeTag(tagId);
                    liTag.remove();
                    return false;
                });

            });

            function appendTags(tags) {
                tags = tags['tags'];
                if($('.ul_tags').length == 0) {
                    createUlTags();
                }
                var ulTags = $('.ul_tags');
                var liTags = '';
                var newsTag = $('#news_tag');

                for(key in tags) {
                    var tagId = tags[key]['id'];
                    var rel = 'tag_id_' + tagId;
                    if($('li[rel='+rel+']').length == 0) {

                        var newsTagVal = newsTag.val();
                        newsTag.val(newsTagVal + '#' + tagId + '#');

                        liTags += '<li rel=\'' + rel + '\'>';
                        liTags += tags[key]['title'];
                        liTags += ' <a href=\'#\'>&otimes;</a>';
                        liTags += '</li>';
                    }
                }
                ulTags.append(liTags);
            }

            function removeTag(tagId) {
                var newsTag = $('#news_tag');
                var newsTagVal = newsTag.val();
                newsTagVal = newsTagVal.replace('#'+tagId+'#','');
                newsTag.val(newsTagVal);
            }

            function createUlTags()
            {
                $('#right').append('<ul class=\'ul_tags\'></ul>');
            }

        ");

    }

    public function indexAction()
    {
    }

    /**
     * List news, paginated, for the admin panel
     */
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

    /**
     * Adding news. There must be at least one news category,
     * before a news can be added.
     */
    public function addAction()
    {
        if(!$this->loggedInUser) {
            $this->fm->addMessage(array('fm-bad' => 'Nemate pravo pristupa!'));
            return $this->redirector->gotoRoute(null, 'login');
        }

        // checking for existing categories
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

        $this->view->tagsForm = $this->model->getForm('News_Tags');

        $this->view->pageTitle = 'Dodavanje vesti';
    }

    /**
     * Plain ol' editing
     * @todo GET shouldn't edit
     */
    public function editAction()
    {
        if(!$this->loggedInUser) {
            $this->fm->addMessage(array('fm-bad' => 'Nemate pravo pristupa!'));
            return $this->redirector->gotoRoute(null, 'login');
        }

        // @todo move this ID checking to the model
        // and just throw an exception from there
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

        $this->view->tagsForm = $this->model->getForm('News_Tags');

        $this->view->pageTitle = 'Izmena vesti';
    }

    /**
     * Delete one news
     * @todo GET shouldn't delete
     */
    public function deleteAction()
    {
        if(!$this->loggedInUser) {
            $this->fm->addMessage(array('fm-bad' => 'Nemate pravo pristupa!'));
            return $this->redirector->gotoRoute(null, 'login');
        }

        // @todo move this ID checking to the model
        // and just throw an exception from there
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