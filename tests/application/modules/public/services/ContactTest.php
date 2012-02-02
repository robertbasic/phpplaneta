<?php

class ContactTest extends PHPUnit_Framework_TestCase {

    protected $_mailerStub = null;
    
    protected $_contactService = null;
    
    public function setup() {
        $this->_mailerStub = $this->getMock('Zend_Mail');
        
        $this->_contactService = new Planet_Service_Contact();
    }

    public static function invalidMailData() {
        return array(
            array(
                array(
                    'name' => '',
                    'email' => 'foo@email.com',
                    'subject' => 'Subject',
                    'message' => 'Message'
                )
            ),
            array(
                array(
                    'name' => 'Foo',
                    'email' => '',
                    'subject' => 'Subject',
                    'message' => 'Message'
                )
            ),
            array(
                array(
                    'name' => 'Foo',
                    'email' => 'foo@email.com',
                    'subject' => '',
                    'message' => 'Message'
                )
            ),
            array(
                array(
                    'name' => 'Foo',
                    'email' => 'foo@email.com',
                    'subject' => 'Subject',
                    'message' => ''
                )
            )
        );
    }
    
    public static function validMailData() {
        return array(
            array(
                array(
                    'name' => 'Foo',
                    'email' => 'foo@email.com',
                    'subject' => 'Subject',
                    'message' => 'Message'
                )
            )
        );
    }
    
    /**
     * @dataProvider invalidMailData
     * @expectedException PPN_Exception_Runtime
     */
    public function testAllMailDataMustBeSet($mailData) {
        $this->_contactService->setMailData($mailData);
    }
    
    /**
     * @dataProvider validMailData
     */
    public function testAllMailDataIsSetMailIsSent($mailData) {
        $this->_contactService->setMailer($this->_mailerStub);
        $this->_contactService->setMailData($mailData);
        $sent = $this->_contactService->sendMail();
        $this->assertTrue($sent);
    }
}