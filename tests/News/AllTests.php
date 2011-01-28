<?php

if(!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'News_Model_AllTests::main');
}

require_once '../TestHelper.php';

require_once 'News/NewsTest.php';

class News_Model_AllTests
{
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('News suite: Models');

        $suite->addTestSuite('News_Model_NewsTest');

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'News_Model_AllTests::main') {
    News_Model_AllTests::main();
}