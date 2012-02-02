<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Logs
 *
 * @author robert
 */
class Planet_Model_Resource_Logs
{
    protected $_logFilePath = null;
    
    public function __construct()
    {

    }

    public function setLogFilePath($path) {
        $this->_logFilePath = $path;
    }
    
    public function getLogFilePath() {
        if($this->_logFilePath === null) {
            $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
            $this->_logFilePath = $config->settings->logs->filepath;
        }
        
        return $this->_logFilePath;
    }
    
    public function getAllLogs($page=null)
    {
        $file = $this->getLogFilePath();
        
        if (!is_file($file) or !is_readable($file)) {
            return array();
        }

        $parsedLogs = array();
        $logData = file_get_contents($file);

        preg_match_all('#<logEntry>
            <timestamp>(?<timestamp>.*)</timestamp>
            <message>(?<message>.*)</message>
            <priority>(?<priority>.*)</priority>
            <priorityName>(?<priorityName>.*)</priorityName>
            </logEntry>#xsU',$logData,$parsedLogs,PREG_SET_ORDER);

        $logs = array();

        foreach($parsedLogs as $key => $parsedLog) {
            $log = new ArrayObject();

            $log->timestamp = $parsedLog['timestamp'];
            $log->message = $parsedLog['message'];
            $log->priority = $parsedLog['priority'];
            $log->priorityName = $parsedLog['priorityName'];

            $logs[$key] = $log;
        }

        $logs = array_reverse($logs);

        if($page !== null) {
            $page = (int)$page;
            if($page < 1) {
                $page = 1;
            }
            $paginator = Zend_Paginator::factory($logs);
            $paginator->setCurrentPageNumber($page);

            return $paginator;
        }

        return $logs;
    }

    public function deleteLogs()
    {
        $file = $this->getLogFilePath();

        if(file_put_contents($file,'') !== false) {
            return true;
        } else {
            return false;
        }
    }
}