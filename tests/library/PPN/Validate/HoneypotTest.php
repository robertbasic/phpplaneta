<?php

class HoneypotTest extends PHPUnit_Framework_TestCase {
        
    public function testHoneypotIsValid() {
        $validator = new PPN_Validate_Honeypot();
        
        $valid = $validator->isValid('');
        
        $this->assertTrue($valid);
    }
    
    public function testHoneypotIsInvalid() {
        $validator = new PPN_Validate_Honeypot();
        
        $valid = $validator->isValid('foo');
        
        $this->assertFalse($valid);
    }
    
}