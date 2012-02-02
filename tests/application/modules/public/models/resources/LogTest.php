<?php

class LogTest extends PHPUnit_Framework_TestCase {
    
    protected $_logFilePath = null;
    
    public function setup() {
        $seedLogFile = realpath(dirname(__FILE__)) . '/_files/logs_seed.xml';
        $logFile = realpath(dirname(__FILE__)) . '/_files/logs.xml';
        copy($seedLogFile, $logFile);
        
        $this->_logFilePath = $logFile;
    }
    
    public function testLogResourceReturnsExistingLogs() {
        $resource = new Planet_Model_Resource_Logs();
        $resource->setLogFilePath($this->_logFilePath);
        
        $logs = $resource->getAllLogs();
        
        $this->assertEquals(13, count($logs));
    }
    
    public function testLogResourceDeletesLogs() {
        $resource = new Planet_Model_Resource_Logs();
        $resource->setLogFilePath($this->_logFilePath);
        
        $logs = $resource->getAllLogs();
        
        $this->assertEquals(13, count($logs));
        
        $return = $resource->deleteLogs();
        
        $this->assertTrue($return);
        
        $logs = $resource->getAllLogs();
        
        $this->assertEquals(0, count($logs));
    }
    
    public function testLogResourceReturnsPaginatorInstanceWithPageParameter() {
        $resource = new Planet_Model_Resource_Logs();
        $resource->setLogFilePath($this->_logFilePath);
        
        $logs = $resource->getAllLogs(1);
        
        $this->assertInstanceOf('Zend_Paginator', $logs);
    }
    
    public function testNegativePageNumberReturnsFirstPage() {
        $resource = new Planet_Model_Resource_Logs();
        $resource->setLogFilePath($this->_logFilePath);
        
        $logs = $resource->getAllLogs(-10);
        
        $this->assertEquals(1, $logs->getCurrentPageNumber());
    }
}