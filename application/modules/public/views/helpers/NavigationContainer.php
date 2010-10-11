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
class Zend_View_Helper_NavigationContainer extends Zend_View_Helper_Abstract
{
    protected $_request = null;

    protected $_action = null;
    
    protected $_model = null;

    protected $_view = null;

    public function navigationContainer()
    {
        $model = $this->_getModel();

        $categories = $model->getAllNewsCategoriesWithPosts();

        $subpages = array();

        foreach($categories as $category) {
            if($category->slug != 'vesti') {
                $subpages[] = array(
                    'label' => $category->title,
                    'action' => 'browse',
                    'controller' => 'news',
                    'route' => 'category',
                    'params' => array(
                        'category' => $category->slug,
                    )
                );
            }
        }

        $this->_action = $this->_getAction();
        $newsActive = false;
        if($this->_action == 'view') {
            $newsActive = true;
        }

        $pages = array(
            array(
                'label' => "Početna",
                'uri' => '/'
            ),
            array(
                'label' => 'Vesti',
                'action' => 'browse',
                'controller' => 'news',
                'active' => $newsActive,
                'route' => 'category',
                'params' => array(
                    'category' => 'vesti',
                ),
                'pages' => $subpages
            ),
            array(
                'label' => "O planeti",
                'action' => 'about',
                'controller' => 'index',
                'route' => 'about'
            ),
            array(
                'label' => "Kontakt",
                'action' => 'contact',
                'controller' => 'index',
                'route' => 'contact'
            ),
        );

        $container = new Zend_Navigation($pages);

        return $container;
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

}