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

    public function _initLogger()
    {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);

        if($config->settings->logs->enabled) {
            $writer = new Zend_Log_Writer_Stream(realpath(APPLICATION_PATH . '/../data/logs') . '/logs.xml');

            $formatter = new Zend_Log_Formatter_Xml();
            $writer->setFormatter($formatter);
        } else {
            $writer = new Zend_Log_Writer_Null();
        }
        
        $logger = new Zend_Log();
        $logger->addWriter($writer);

        Zend_Registry::set('logger', $logger);
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

        $this->_view->addHelperPath('PPN/View/Helper','PPN_View_Helper');
        $this->_view->addHelperPath('ZendX/JQuery/View/Helper','ZendX_JQuery_View_Helper');

        $this->_view->jQuery()
                        ->addStylesheet('/static/css/smoothness/jquery-ui-1.8.1.custom.css')
//                        ->setVersion('1.4.2')
                        ->enable()
//                        ->setUiVersion('1.8.1')
                        ->setLocalPath('/static/js/jquery-1.4.2.min.js')
                        ->setUiLocalPath('/static/js/jquery-ui-1.8.1.custom.min.js')
                        ->uiEnable();
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

    public function _initFullPageCache()
    {
        $this->bootstrap('FrontController');
        $fc = $this->getResource('FrontController');
        $fc->setParam('disableOutputBuffering', true);

        if(APPLICATION_ENV == 'development') {
            $debugHeader = true;
        } else {
            $debugHeader = false;
        }

        $frontendOptions = array(
            'lifetime' => 1800,
            'debug_header' => $debugHeader,
            'default_options' => array(
                'cache' => false
            ),
            'regexps' => array(
                '^/$' => array(
                    'cache' => true,
                    'cache_with_get_variables' => true,
                    'cache_with_cookie_variables' => true
                ),
                '^/index/index/' => array(
                    'cache' => true,
                    'cache_with_get_variables' => true,
                    'cache_with_cookie_variables' => true
                ),
                '^/news/view/' => array(
                    'cache' => true,
                    'cache_with_get_variables' => true,
                    'cache_with_cookie_variables' => true
                ),
                '^/news/browse/' => array(
                    'cache' => true,
                    'cache_with_get_variables' => true,
                    'cache_with_cookie_variables' => true
                ),
                '^/news/ajax-load-dates/' => array(
                    'cache' => true,
                    'cache_with_get_variables' => true,
                    'cache_with_cookie_variables' => true
                )
            )
        );

        $backendOptions = array(
            'cache_dir' => realpath(APPLICATION_PATH . '/../data/cache/page/')
        );

        $cache = Zend_Cache::factory(
                    'Page',
                    'File',
                    $frontendOptions,
                    $backendOptions
                );

        $cache->start();
    }

    public function _initDbCache()
    {
        $frontendOptions = array(
            'automatic_serialization' => true
        );

        $backendOptions = array(
            'cache_dir' => realpath(APPLICATION_PATH . '/../data/cache/db/')
        );

        $cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);

        Zend_Db_Table_Abstract::setDefaultMetadataCache($cache);
    }

}