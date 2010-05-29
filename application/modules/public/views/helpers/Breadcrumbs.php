<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * File: BaseUrl
 * Ver: 1.0
 * Created: Dec 28, 2009
 * Created by: Robert Basic
 * E-mail: robert.basic@online.rs
 * Description:
 *
 */
class Zend_View_Helper_Breadcrumbs extends Zend_View_Helper_Abstract
{
    protected $_request = null;

    protected $_action = null;

    protected $_controller = null;

    protected $_module = null;
    
    protected $_model = null;

    protected $_view = null;

    public function breadcrumbs()
    {
        $this->_action = $this->_getAction();
        $this->_controller = $this->_getController();
        $this->_module = $this->_getModule();

        $page = $this->_getParam('page');

        if($this->_module == 'public'
                and $this->_controller == 'index'
                and $this->_action == 'index'
                and ($page === null or $page == 1)) {
            return false;
        }

        $news = $this->_getParam('slug');
        $category = $this->_getParam('category');
        $tag = $this->_getParam('tag');

        if($news !== null) {
            $crumb = $this->_getCrumbForNews($news);
        } elseif($category !== null) {
            $crumb = $this->_getCrumbForCategory($category, $page);
        } elseif($tag !== null) {
            $crumb = $this->_getCrumbForTag($tag, $page);
        } else {
            $crumb = $this->_getCrumbForOther($page);
        }

        return $this->_view->partial('_breadcrumbs.phtml', array('crumb' => $crumb));
    }

    protected function _getCrumbForNews($newsSlug)
    {
        $crumb = array();
        
        $news = $this->_getModel()->getOneActiveNewsBySlug($newsSlug);

        $crumb['root'] = array(
            'title' => "Početna",
            'href' => '/'
        );

        $crumb['first'] = array(
            'title' => $news->category_title,
            'href' => $this->_view->url(array(
                'action' => 'browse',
                'controller' => 'news',
                'category' => $news->category_slug
            ), '', true)
        );

        $crumb['second'] = array(
            'title' => $news->title,
            'href' => $this->_view->url(array(
                'action' => 'view',
                'controller' => 'news',
                'slug' => $news->slug
            ), '', true)
        );

        return $crumb;
    }

    protected function _getCrumbForCategory($categorySlug,$page=null)
    {
        $crumb = array();

        $category = $this->_getModel()->getOneNewsCategoryBySlug($categorySlug);

        $crumb['root'] = array(
            'title' => "Početna",
            'href' => '/'
        );

        $crumb['first'] = array(
            'title' => $category->title,
            'href' => $this->_view->url(array(
                'action' => 'browse',
                'controller' => 'news',
                'category' => $category->slug
            ), '', true)
        );

        if($page !== null) {
            $crumb['second'] = array(
                'title' => 'Strana ' . $page,
                'href' => $this->_view->url(array(
                    'action' => 'browse',
                    'controller' => 'news',
                    'category' => $category->slug,
                    'page' => $page
                ), '', true)
            );
        }

        return $crumb;
    }

    protected function _getCrumbForTag($tagSlug,$page=null)
    {
        $crumb = array();

        $tag = $this->_getModel()->getOneNewsTagBySlug($tagSlug);

        $crumb['root'] = array(
            'title' => "Početna",
            'href' => '/'
        );

        $crumb['first'] = array(
            'title' => $tag->title,
            'href' => $this->_view->url(array(
                'action' => 'browse',
                'controller' => 'news',
                'tag' => $tag->slug
            ), '', true)
        );

        if($page !== null) {
            $crumb['second'] = array(
                'title' => 'Strana ' . $page,
                'href' => $this->_view->url(array(
                    'action' => 'browse',
                    'controller' => 'news',
                    'tag' => $tag->slug,
                    'page' => $page
                ), '', true)
            );
        }

        return $crumb;
    }

    protected function _getCrumbForOther($page=null)
    {
        $crumb['root'] = array(
            'title' => "Početna",
            'href' => '/'
        );

        $crumb['first'] = array(
            'title' => 'Strana ' . $page,
            'href' => $this->_view->url(array(
                'action' => 'index',
                'controller' => 'index',
                'page' => $page
            ), '', true)
        );

        return $crumb;
    }

    public function setView(Zend_View_Interface $view)
    {
        if($this->_view === null) {
            $this->_view = $view;
        }

        return $this->_view;
    }

    protected function _getModel()
    {
        if($this->_model === null) {
            $this->_model = new Planet_Model_News();
        }

        return $this->_model;
    }

    protected function _getRequest()
    {
        if($this->_request === null) {
            $this->_request = Zend_Controller_Front::getInstance()->getRequest();
        }

        return $this->_request;
    }

    protected function _getAction()
    {
        $this->_request = $this->_getRequest();

        if($this->_action === null) {
            $this->_action = $this->_request->getActionName();
        }

        return $this->_action;
    }

    protected function _getController()
    {
        $this->_request = $this->_getRequest();

        if($this->_controller === null) {
            $this->_controller = $this->_request->getControllerName();
        }

        return $this->_controller;
    }

    protected function _getModule()
    {
        $this->_request = $this->_getRequest();

        if($this->_module === null) {
            $this->_module = $this->_request->getModuleName();
        }

        return $this->_module;
    }

    protected function _getParam($param)
    {
        $this->_request = $this->_getRequest();

        return $this->_request->getParam($param, null);
    }

}