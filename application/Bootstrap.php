<?php

/**
 *   File: Bootstrap.php
 *
 *   Description:
 *       Application bootstraper, called by Zend_Application in index.php
 *       Basically, initializing resources on application level
*/
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    /**
     * Autoloader for the "public" module
     * 
     * @return Zend_Application_Module_Autoloader
     */
    public function _initPublicAutoload()
    {
        $moduleLoader = new Zend_Application_Module_Autoloader(
                                array(
                                    'namespace' => 'Planet',
                                    'basePath' => APPLICATION_PATH . '/modules/public'
                                )
                            );

        // adding model resources to the autoloader
        $moduleLoader->addResourceTypes(
                array(
                'modelResources' => array(
                        'path' => 'models/resources',
                        'namespace' => 'Model_Resource'
                    )
                )
            );

        return $moduleLoader;
    }

    /**
     * Initializing action helpers
     */
    public function _initActionHelpers()
    {
        Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH .'/modules/public/controllers/helpers');
    }
    
    /**
     * Initializing the View, setting the doctype, charset, et al.
     */
    public function _initViewHelpers()
    {
        $this->bootstrap('layout');
        $this->_layout = $this->getResource('layout');
        $this->_view = $this->_layout->getView();

        $this->_view->doctype('XHTML1_STRICT');
        $this->_view->headMeta()->appendHttpEquiv('Content-type', 'text/html;charset=utf-8');
        $this->_view->headTitle('PHPplaneta.net');
        $this->_view->headTitle()->setSeparator(' / ');

        $this->_view->headScript()->appendFile(
                'http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js',
                'text/javascript'
                );

        $this->_view->addHelperPath('PPN/View/Helper','PPN_View_Helper');
    }

    public function _initAdminRoute()
    {
        $this->bootstrap('FrontController');
        $fc = $this->getResource('FrontController');

        $router = $fc->getRouter();

        $adminRoute = new Zend_Controller_Router_Route(
                    'admin/:module/:controller/:action/*',
                    array(
                        'action' => 'index',
                        'controller' => 'admin',
                        'module' => 'public',
                        'isAdmin' => true
                    )
                );

        $router->addRoute('admin', $adminRoute);

        $loginRoute = new Zend_Controller_Router_Route_Static(
                    'login',
                    array(
                        'action' => 'login',
                        'controller' => 'user',
                        'module' => 'public'
                    )
                );
        $router->addRoute('login', $loginRoute);
    }

}