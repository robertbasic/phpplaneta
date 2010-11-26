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
class Zend_View_Helper_MostUsedTags extends Zend_View_Helper_Abstract
{
    protected $_model = null;

    protected $_view = null;

    public function mostUsedTags()
    {
        $tags = $this->_getModel()->getMostUsedTags();

        if(count($tags) < 1) {
            return false;
        }

        $cloud = new Zend_Tag_Cloud(array(
            'cloudDecorator' => new PPN_View_Helper_DivCloud(),
            'tagDecorator' => new PPN_View_Helper_SpanTag()
        ));

        foreach($tags as $tag) {
            $cloud->appendTag(array(
                'title' => $tag->title,
                'weight' => $tag->num,
                'params' => array(
                    'url' => '/oznaka/' . $tag->slug . '/strana/1'
                )
            ));
        }

        return $cloud;
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