<?php

require_once TEST_PATH . '/ControllerTestCase.php';

class IndexControllerTest extends ControllerTestCase {
    
    public function testDisplayHomePage() {
        $this->dispatch('/');
        
        $this->assertNotController('error');
        $this->assertNotAction('error');
        
        $this->assertModule('public');
        $this->assertController('index');
        $this->assertAction('index');
        $this->assertResponseCode(200);
    }
    
    public function testDisplayAboutPage() {
        $this->dispatch('/o-php-planeti');
        
        $this->assertNotController('error');
        $this->assertNotAction('error');
        
        $this->assertModule('public');
        $this->assertController('index');
        $this->assertAction('about');
        $this->assertResponseCode(200);
    }
    
}