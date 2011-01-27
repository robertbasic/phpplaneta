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

        if($this->loggedInUser) {
            $this->view->headScript()->appendFile('/static/ckeditor/ckeditor.js');
            $this->view->headScript()->appendFile('/static/ckeditor/adapters/jquery.js');
            $this->view->headScript()->appendFile('/static/js/ckeditor.js');
            $this->view->headScript()->appendFile('/static/js/tags.js');
        }
    }

    public function indexAction()
    {
        
    }

    public function viewAction()
    {
        $slug = $this->_getParam('slug', null);

        $news = $this->model->getOneActiveNewsBySlug($slug);

        $this->view->news = $news;

        $commentsForm = $this->model->getForm('News_Comments_Add');
        $commentsForm->setAction($this->urlHelper->url(array(
                                                    'action' => 'view',
                                                    'controller' => 'news',
                                                    'slug' => $slug
                                                ),
                                                'news', true
                                            ));
        $commentsForm->getElement('fk_news_id')->setValue($news->id);

        if($this->_request->isPost()) {
            if($commentsForm->isValid($this->_request->getPost())) {
                try {
                   $this->model->saveComment($commentsForm->getValues());

                   $this->fm->addMessage(array('fm-good' => 'Komentar uspešno dodat!'));

                   return $this->redirector->gotoRoute(
                           array('action' => 'view', 'controller' => 'news', 'slug' => $slug),
                           'news', true
                           );
                } catch (Exception $e) {
                    $this->fm->addMessage(array('fm-bad' => $e->getMessage()));
                }
            }
        }

        $this->view->commentsForm = $commentsForm;
    }

    public function browseAction()
    {
        $page = $this->_getParam('page', 1);
        $category = $this->_getParam('category', null);
        $tag = $this->_getParam('tag', null);
        $date = $this->_getParam('date', null);

        $news = null;

        if($category !== null) {
            $news = $this->model->getAllActiveNewsFromCategoryBySlug($category, $page);
        } elseif($tag !== null) {
            $news = $this->model->getAllActiveNewsByTagSlug($tag, $page);
        } elseif($date !== null) {
            $news = $this->model->getAllActiveNewsByDate($date, $page);
            $this->view->headScript('SCRIPT', '
                        var setCalendarDate = "'.date('d/m/Y', strtotime($date)).'";
                    ');
        }

        $this->view->news = $news;
    }

    public function searchAction()
    {
        $page = $this->_getParam('page', 1);
        $keyword = trim(strip_tags($this->_getParam('keyword', null)));

        $this->view->news = $this->model->searchActiveNews($keyword,$page);
        $this->view->keyword = $keyword;
    }

    public function rssAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $news = $this->model->getAllActiveNews();

        $url = $this->view->serverUrl();

        $feed = new Zend_Feed_Writer_Feed();
        $feed->setTitle('PHPPlaneta');
        $feed->setLink($url.'/');
        $feed->setDescription('PHPPlaneta');
        $feed->setFeedLink($url.'/news/rss', 'rss');
        $feed->setDateModified(time());

        $entry = null;

        foreach($news as $n) {
            $entry = $feed->createEntry();
            $entry->setTitle($n->title);
            $newsUrl = $url . $this->urlHelper->url(array(
                                                    'action' => 'view',
                                                    'controller' => 'news',
                                                    'slug' => $n->slug
                                                ),
                                                'news', true
                                            );
            $entry->setLink($newsUrl);
            $entry->setDateCreated(strtotime($n->datetime_added));
            $entry->setContent($n->text);
            $feed->addEntry($entry);
        }

        $out = $feed->export('rss');
        $this->getResponse()->setHeader('Content-Type', 'text/xml; charset=utf-8');
        echo $out;
    }

    public function ajaxLoadDatesAction()
    {
        if(!$this->_request->isXmlHttpRequest()) {
            return $this->redirector->gotoRoute(array(), '', true);
        }

        $year = $this->_request->getQuery('year', null);
        $month = $this->_request->getQuery('month', null);
        
        $response = $this->model->getNewsForYearAndMonth($year,$month);

        echo $this->_helper->json($response);
    }

    /**
     * List news, paginated, for the admin panel
     */
    public function adminListAction()
    {
        if(!$this->loggedInUser) {
            $this->fm->addMessage(array('fm-bad' => 'Nemate pravo pristupa!'));
            return $this->redirector->gotoRoute(array(), 'login');
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
            return $this->redirector->gotoRoute(array(), 'login');
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
            return $this->redirector->gotoRoute(array(), 'login');
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
            return $this->redirector->gotoRoute(array(), 'login');
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