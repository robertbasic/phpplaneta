<?php

class UsertTest extends PHPUnit_Framework_TestCase {
    
    protected $_model = null;
    
    public function setup() {
        $this->_model = new Planet_Model_User();
    }
    
    public function testGetExistingUserByEmail() {
        $testUser = array(
            'id' => 1,
            'email' => 'admin@phpplaneta.net',
            'firstname' => 'Admin',
            'lastname' => 'Admin',
            'datetime_added' => '2010-05-09 20:08:36',
            'role' => 'administrator',
            'active' => 1
        );
        
        $user = $this->_model->getUserByEmail('admin@phpplaneta.net');
        $user = $user->toArray();
        
        $this->assertEquals($testUser, $user);
    }
    
    /**
     * @expectedException PPN_Exception_NotFound
     */
    public function testGetNonExistingUserByEmail() {
        $user = $this->_model->getUserByEmail('nosuchuser@example.com');
    }
    
}