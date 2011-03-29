<?php

class AdminController extends Zend_Controller_Action
{

    public function init()
    {
    }

    public function indexAction()
    {
    }

    public function settingsAction()
    {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
        
        $this->view->environment = APPLICATION_ENV;
        $this->view->fullpageCacheSetting = $config->settings->cache->fullpage->enabled;
        $this->view->dbCacheSetting = $config->settings->cache->db->enabled;
        $this->view->logSetting = $config->settings->logs->enabled;
    }

}