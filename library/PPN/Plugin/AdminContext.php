<?php

class PPN_Plugin_AdminContext extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request) 
    {
        if($request->getParam('isAdmin')
                or $request->getControllerName() === 'admin') {
            $layout = Zend_Layout::getMvcInstance();
            $layout->setLayout('admin');
            $view = $layout->getView();
            $view->headTitle()->prepend('Admin panel');
        }
    }
}