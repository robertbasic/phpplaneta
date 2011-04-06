<?php

class AdminContextTest extends PHPUnit_Framework_TestCase {

    protected $_requestStub = null;
    
    protected $_authStub = null;
    
    public function setup() {
        $this->_requestStub = $this->getMock('Zend_Controller_Request_Http');
        
        $this->_authStub = $this->getMock('Planet_Service_Auth');
    }
    
    public function testRequestAdminAreaNotLoggedIn() {
        // had to add these "method" calls so that the method chaining
        // doesn't brake in the plugin because of the stubbed request
        $this->_requestStub->expects($this->at(0))
                            ->method('getParam')
                            ->with($this->equalTo('isAdmin'))
                            ->will($this->returnValue(true));
        $this->_requestStub->expects($this->at(1))
                            ->method('setModuleName')
                            ->will($this->returnValue($this->_requestStub));
        $this->_requestStub->expects($this->at(2))
                            ->method('setControllerName')
                            ->will($this->returnValue($this->_requestStub));
        
        $this->_authStub->expects($this->once())
                            ->method('getIdentity')
                            ->will($this->returnValue(false));
        
        $plugin = new PPN_Plugin_AdminContext();
        $plugin->setAuth($this->_authStub);
        
        $return = $plugin->preDispatch($this->_requestStub);
        
        $this->assertFalse($return);
    }
    
    public function testRequestAdminAreaLoggedIn() {
        $this->_requestStub->expects($this->once())
                            ->method('getParam')
                            ->with($this->equalTo('isAdmin'))
                            ->will($this->returnValue(true));
        
        $this->_authStub->expects($this->once())
                            ->method('getIdentity')
                            ->will($this->returnValue(true));
        
        $plugin = new PPN_Plugin_AdminContext();
        $plugin->setAuth($this->_authStub);
        
        $return = $plugin->preDispatch($this->_requestStub);
        
        $this->assertTrue($return);
    }
}