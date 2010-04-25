<?php

class NewsController extends Zend_Controller_Action
{

    public function init()
    {
        $this->model = new Planet_Model_News();
    }

    public function indexAction()
    {
    }

    public function adminListAction()
    {
        
    }

}