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
class Zend_View_Helper_ActionToolbar extends Zend_View_Helper_Abstract
{
    protected $_request = null;

    protected $_action = null;

    protected $_controller = null;

    protected $_module = null;

    protected $_view = null;

    public function actionToolbar()
    {
        $actionToolbar = $this->_getToolbar();

        return $actionToolbar;
    }

    public function setView(Zend_View_Interface $view)
    {
        if($this->_view === null) {
            $this->_view = $view;
        }

        return $this->_view;
    }

    protected function _getToolbar()
    {
        $this->_action = $this->_getAction();
        $this->_controller = $this->_getController();
        $this->_module = $this->_getModule();

        $toolbar = null;

        if($this->_module == 'public') {
            if($this->_controller == 'news') {
                if($this->_action == 'add'
                        or $this->_action == 'edit') {
                    $toolbar = " <a href='" . $this->_view->url(array(
                                                'action' => 'admin-list',
                                                'controller' => 'news'),
                                                'admin', true) . "'>Sve vesti</a> ";
                } else {
                    $toolbar = " <a href='" . $this->_view->url(array(
                                                'action' => 'add',
                                                'controller' => 'news'),
                                                'admin', true) . "'>Dodaj vest</a> ";
                    $toolbar .= " <a href='" . $this->_view->url(array(
                                                'action' => 'admin-list',
                                                'controller' => 'news-sources'),
                                                'admin', true) . "'>Izvori vesti</a> ";
                    $toolbar .= " <a href='" . $this->_view->url(array(
                                                'action' => 'admin-list',
                                                'controller' => 'news-categories'),
                                                'admin', true) . "'>Kategorije vesti</a> ";
                }
            } elseif($this->_controller == 'news-sources') {
                if($this->_action == 'add'
                        or $this->_action == 'edit') {
                    $toolbar = " <a href='" . $this->_view->url(array(
                                                'action' => 'admin-list',
                                                'controller' => 'news-sources'),
                                                'admin', true) . "'>Svi izvori</a> ";
                } else {
                    $toolbar = " <a href='" . $this->_view->url(array(
                                                'action' => 'add',
                                                'controller' => 'news-sources'),
                                                'admin', true) . "'>Dodaj izvor</a> ";
                    $toolbar .= " <a href='" . $this->_view->url(array(
                                                'action' => 'admin-list',
                                                'controller' => 'news'),
                                                'admin', true) . "'>Sve vesti</a> ";
                    $toolbar .= " <a href='" . $this->_view->url(array(
                                                'action' => 'admin-list',
                                                'controller' => 'news-categories'),
                                                'admin', true) . "'>Kategorije vesti</a> ";
                }
            } elseif($this->_controller == 'news-categories') {
                if($this->_action == 'add'
                        or $this->_action == 'edit') {
                    $toolbar = " <a href='" . $this->_view->url(array(
                                                'action' => 'admin-list',
                                                'controller' => 'news-categories'),
                                                'admin', true) . "'>Sve kategorije</a> ";
                } else {
                    $toolbar = " <a href='" . $this->_view->url(array(
                                                'action' => 'add',
                                                'controller' => 'news-categories'),
                                                'admin', true) . "'>Dodaj kategoriju</a> ";
                    $toolbar .= " <a href='" . $this->_view->url(array(
                                                'action' => 'admin-list',
                                                'controller' => 'news'),
                                                'admin', true) . "'>Sve vesti</a> ";
                    $toolbar .= " <a href='" . $this->_view->url(array(
                                                'action' => 'admin-list',
                                                'controller' => 'news-source'),
                                                'admin', true) . "'>Izvori vesti</a> ";
                }
            }
        }

        return $toolbar;
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

}