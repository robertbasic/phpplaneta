<?php

require_once APPLICATION_PATH . '/modules/public/models/News.php';

class News_Model_NewsTest extends PHPUnit_Framework_TestCase
{
    protected $_resource = null;

    public function setup()
    {
        $this->_resource = $this->_getCleanMock('Planet_Model_Resource_News');
    }

    public function testTestsAreRuning()
    {
        $i = 1;
        $this->assertEquals(1, $i);
    }

    protected function _getCleanMock($className)
    {
        $class = new ReflectionClass($className);
        $methods = $class->getMethods();
        $stubMethods = array();

        foreach($methods as $method) {
            if($method->isPublic()
                    or ($method->isProtected() and $method->isAbstract())) {
                $stubMethods[] = $method->getName();
            }
        }

        $mocked = $this->getMock(
                    $className,
                    $stubMethods,
                    array(),
                    $className . '_AuthorMapperTestMock_' . uniqid(),
                    false
                );

        return $mocked;
    }
}