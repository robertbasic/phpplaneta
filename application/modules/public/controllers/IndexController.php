<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->model = new Planet_Model_News();
    }

    public function indexAction()
    {
        $this->view->news = $this->model->getAllActiveNews(1);
    }

}