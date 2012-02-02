<?php

require_once TEST_PATH . '/ControllerTestCase.php';

class UserControllerTest extends ControllerTestCase {
    
    public function testDisplayLoginPage() {
        $this->dispatch('/login');
        
        $this->assertNotController('error');
        $this->assertNotAction('error');
        
        $this->assertModule('public');
        $this->assertController('user');
        $this->assertAction('login');
        $this->assertResponseCode(200);
    }
    
    public function testValidUserCanLogin() {
        
    }
    
    public function testInvalidUserCannotLogin() {
        
    }
    
    public function testLogoutUser() {
        
    }
    
}