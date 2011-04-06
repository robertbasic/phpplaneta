<?php

class NumberOfCommentsTest extends PHPUnit_Framework_TestCase {
    
    protected $_modelStub = null;
    
    public function setup() {
        $this->_modelStub = $this->getMock('Planet_Model_News');
    }
    
    public function testZeroCommentsReturnsNoComments() {
        $this->_modelStub->expects($this->once())
                        ->method('getCommentsForNews')
                        ->with($this->equalTo(1))
                        ->will($this->returnValue(array()));
        
        $numberOfCommentsHelper = new Zend_View_Helper_NumberOfComments();
        $numberOfCommentsHelper->setModel($this->_modelStub);
        
        $numberOfComments = $numberOfCommentsHelper->numberOfComments(1);
        
        $this->assertEquals('Nema komentara', $numberOfComments);
    }
    
    public function testOneCommentReturnsOneComment() {
        $this->_modelStub->expects($this->once())
                        ->method('getCommentsForNews')
                        ->with($this->equalTo(1))
                        ->will($this->returnValue(array('foo')));
        
        $numberOfCommentsHelper = new Zend_View_Helper_NumberOfComments();
        $numberOfCommentsHelper->setModel($this->_modelStub);
        
        $numberOfComments = $numberOfCommentsHelper->numberOfComments(1);
        
        $this->assertEquals('1 komentar', $numberOfComments);
    }
    
    public function testMultipleCommentsReturnsMultipleComments() {
        $this->_modelStub->expects($this->once())
                        ->method('getCommentsForNews')
                        ->with($this->equalTo(1))
                        ->will($this->returnValue(array('foo', 'bar', 'baz')));
        
        $numberOfCommentsHelper = new Zend_View_Helper_NumberOfComments();
        $numberOfCommentsHelper->setModel($this->_modelStub);
        
        $numberOfComments = $numberOfCommentsHelper->numberOfComments(1);
        
        $this->assertEquals('3 komentara', $numberOfComments);
    }
    
}