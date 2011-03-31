<?php

abstract class ControllerTestCase extends Zend_Test_PHPUnit_ControllerTestCase {
    
    protected function setUp() {
        Zend_Session::$_unitTestEnabled = true;
    }
    
}