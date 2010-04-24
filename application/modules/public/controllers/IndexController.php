<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->model = new Public_Model_News();
    }

    public function indexAction()
    {
    }

}