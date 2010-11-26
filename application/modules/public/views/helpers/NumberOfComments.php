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
class Zend_View_Helper_NumberOfComments extends Zend_View_Helper_Abstract
{
    protected $_model = null;

    public function numberOfComments($newsId)
    {
        $return = '';
        $comments = $this->_getModel()->getCommentsForNews($newsId);

        $numberOfComments = count($comments);

        if($numberOfComments == 1) {
            $return = '1 komentar';
        } elseif($numberOfComments > 1) {
            $return = $numberOfComments . ' komentara';
        } else {
            $return = 'Nema komentara';
        }

        return $return;
    }

    protected function _getModel()
    {
        if($this->_model === null) {
            $this->_model = new Planet_Model_News();
        }

        return $this->_model;
    }

}