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
class Zend_View_Helper_Tags extends Zend_View_Helper_Abstract
{
    protected $_model = null;

    protected $_view = null;

    public function tags($newsId)
    {
        $return = '';
        
        $tags = $this->_getModel()->getTagsForNews($newsId);

        if(count($tags) > 0) {
            foreach($tags as $tag) {
                $return .= "<a href='" . $this->_view->url(array(
                    'action' => 'browse',
                    'controller' => 'news',
                    'tag' => $tag->slug,
                    'page' => 1
                ), 'tag', true) . "'>";
                $return .= $tag->title;
                $return .= "</a>, ";
            }
            $return = substr($return, 0, -2);
        } else {
            $return = 'Nema oznaka';
        }

        return $return;
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

}