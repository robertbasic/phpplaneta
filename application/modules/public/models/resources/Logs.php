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
    public function __construct()
    {

    }

    public function getAllLogs($page)
    {
        $file = realpath(APPLICATION_PATH . '/../data/logs') . '/logs.xml';

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
        $file = realpath(APPLICATION_PATH . '/../data/logs') . '/logs.xml';

        if(file_put_contents($file,'') !== false) {
            return true;
        } else {
            return false;
        }
    }
}