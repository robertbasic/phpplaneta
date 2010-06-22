<?php

/**
 * Logs model
 *
 * @author robert
 */
class Planet_Model_Logs extends PPN_Model_Abstract
{
    public function __construct()
    {
    }

    public function getAllLogs($page=null)
    {
        return $this->getResource('Logs')->getAllLogs($page);
    }

    public function deleteLogs()
    {
        return $this->getResource('Logs')->deleteLogs();
    }
}