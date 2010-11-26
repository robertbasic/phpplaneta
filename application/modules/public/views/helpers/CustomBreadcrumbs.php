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
class Zend_View_Helper_CustomBreadcrumbs extends Zend_View_Helper_Abstract
{
    protected $_request = null;

    protected $_action = null;

    protected $_controller = null;

    protected $_module = null;
    
    protected $_model = null;

    protected $_view = null;

    public function customBreadcrumbs()
    {
        $this->_action = $this->_getAction();
        $this->_controller = $this->_getController();
        $this->_module = $this->_getModule();

        $page = $this->_getParam('page');

        if(($this->_module == 'public'
                and $this->_controller == 'index'
                and $this->_action == 'index'
                and ($page === null or $page == 1))
            or $this->_controller == 'error') {
            return false;
        }

        $news = $this->_getParam('slug');
        $category = $this->_getParam('category');
        $tag = $this->_getParam('tag');
        $date = $this->_getParam('date');
        $keyword = $this->_getParam('keyword');

        if($news !== null) {
            $crumb = $this->_getCrumbForNews($news);
        } elseif($category !== null) {
            $crumb = $this->_getCrumbForCategory($category, $page);
        } elseif($tag !== null) {
            $crumb = $this->_getCrumbForTag($tag, $page);
        } elseif($date !== null) {
            $crumb = $this->_getCrumbForDate($date, $page);
        } elseif($keyword !== null) {
            $crumb = $this->_getCrumbForSearch($keyword, $page);
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
            'href' => '/',
            'class' => 'breadcrumbs-level-1'
        );

        $crumb['first'] = array(
            'title' => $news->category_title,
            'href' => $this->_view->url(array(
                'action' => 'browse',
                'controller' => 'news',
                'category' => $news->category_slug
            ), 'category', true),
            'class' => 'breadcrumbs-level-2'
        );

        $crumb['second'] = array(
            'title' => $news->title,
            'href' => $this->_view->url(array(
                'action' => 'view',
                'controller' => 'news',
                'slug' => $news->slug
            ), 'news', true),
            'class' => 'breadcrumbs-level-3'
        );

        return $crumb;
    }

    protected function _getCrumbForCategory($categorySlug,$page=null)
    {
        $crumb = array();

        $category = $this->_getModel()->getOneNewsCategoryBySlug($categorySlug);

        $crumb['root'] = array(
            'title' => "Početna",
            'href' => '/',
            'class' => 'breadcrumbs-level-1'
        );

        $crumb['first'] = array(
            'title' => $category->title,
            'href' => $this->_view->url(array(
                'action' => 'browse',
                'controller' => 'news',
                'category' => $category->slug,
                'page' => 1
            ), 'category', true),
            'class' => 'breadcrumbs-level-2'
        );

        if($page !== null) {
            $crumb['second'] = array(
                'title' => 'Strana ' . $page,
                'href' => $this->_view->url(array(
                    'action' => 'browse',
                    'controller' => 'news',
                    'category' => $category->slug,
                    'page' => $page
                ), 'category', true),
                'class' => 'breadcrumbs-level-3'
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
            'href' => '/',
            'class' => 'breadcrumbs-level-1'
        );

        $crumb['first'] = array(
            'title' => $tag->title,
            'href' => $this->_view->url(array(
                'action' => 'browse',
                'controller' => 'news',
                'tag' => $tag->slug,
                'page' => 1
            ), 'tag', true),
            'class' => 'breadcrumbs-level-2'
        );

        if($page !== null) {
            $crumb['second'] = array(
                'title' => 'Strana ' . $page,
                'href' => $this->_view->url(array(
                    'action' => 'browse',
                    'controller' => 'news',
                    'tag' => $tag->slug,
                    'page' => $page
                ), 'tag', true),
                'class' => 'breadcrumbs-level-3'
            );
        }

        return $crumb;
    }

    protected function _getCrumbForDate($date,$page=null)
    {
        $crumb = array();

        $crumb['root'] = array(
            'title' => "Početna",
            'href' => '/',
            'class' => 'breadcrumbs-level-1'
        );

        $crumb['first'] = array(
            'title' => date('d.m.Y.', strtotime($date)),
            'href' => $this->_view->url(array(
                'action' => 'browse',
                'controller' => 'news',
                'date' => $date,
                'page' => 1
            ), 'date', true),
            'class' => 'breadcrumbs-level-2'
        );

        if($page !== null) {
            $crumb['second'] = array(
                'title' => 'Strana ' . $page,
                'href' => $this->_view->url(array(
                    'action' => 'browse',
                    'controller' => 'news',
                    'tag' => $tag->slug,
                    'page' => $page
                ), 'date', true),
                'class' => 'breadcrumbs-level-3'
            );
        }

        return $crumb;
    }

    protected function _getCrumbForSearch($keyword,$page=null)
    {
        $crumb = array();

        $crumb['root'] = array(
            'title' => "Početna",
            'href' => '/',
            'class' => 'breadcrumbs-level-1'
        );

        $crumb['first'] = array(
            'title' => 'Pretraga',
            'href' => $this->_view->url(array(
                'action' => 'search',
                'controller' => 'news',
                'page' => 1
            ), 'search', true) . '/?keyword=' . $keyword,
            'class' => 'breadcrumbs-level-2'
        );

        if($page !== null) {
            $crumb['second'] = array(
                'title' => 'Strana ' . $page,
                'href' => $this->_view->url(array(
                    'action' => 'search',
                    'controller' => 'news',
                    'page' => $page
                ), 'search', true) . '/?keyword=' . $keyword,
                'class' => 'breadcrumbs-level-3'
            );
        }

        return $crumb;
    }

    protected function _getCrumbForOther($page=null)
    {
        $crumb['root'] = array(
            'title' => "Početna",
            'href' => '/',
            'class' => 'breadcrumbs-level-1'
        );

        if($this->_action == 'contact') {
            $crumb['first'] = array(
                'title' => 'Kontakt',
                'href' => $this->_view->url(array(
                    'action' => 'contact',
                    'controller' => 'index'
                ), 'contact', true),
                'class' => 'breadcrumbs-level-2'
            );
        } elseif($this->_action == 'about') {
            $crumb['first'] = array(
                'title' => 'O PHP planeti',
                'href' => $this->_view->url(array(
                    'action' => 'about',
                    'controller' => 'index'
                ), 'about', true),
                'class' => 'breadcrumbs-level-2'
            );
        } else {
            $crumb['first'] = array(
                'title' => 'Strana ' . $page,
                'href' => $this->_view->url(array(
                    'action' => 'index',
                    'controller' => 'index',
                    'page' => $page
                ), '', true),
                'class' => 'breadcrumbs-level-2'
            );
        }

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