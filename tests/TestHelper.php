<?php

/**
 * Set the include path
 */
$root  = realpath(dirname(__FILE__) . '/../');
$paths = array(
    "$root/library",
    "$root/tests",
    "$root/application",
    "/usr/share/php"
);
set_include_path(implode(PATH_SEPARATOR, $paths));

/**
 * Set error reporting level
 */
error_reporting( E_ALL | E_STRICT );

/**
 * Default timezone
 */
date_default_timezone_set('Europe/Belgrade');

defined('APPLICATION_PATH')
    or define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

/**
 * Autoloader helpers
 */
function _App_Autloader_SetUp() {
    require_once 'Zend/Loader/Autoloader.php';
    $loader = Zend_Loader_Autoloader::getInstance();
    $loader->registerNamespace('PPN_');
}

function _App_Autloader_TearDown() {
    Zend_Loader_Autoloader::resetInstance();
    $loader = Zend_Loader_Autoloader::getInstance();
    $loader->registerNamespace('PPN_');
}

/**
 * Init autoloader
 */
_App_Autloader_SetUp();

/**
 * Start session now!
 */
Zend_Session::$_unitTestEnabled = true;
Zend_Session::start();

/**
 * Ignore folders from code coverage etc
 */
PHP_CodeCoverage_Filter::getInstance()->addDirectoryToBlacklist("$root/tests");
PHP_CodeCoverage_Filter::getInstance()->addDirectoryToBlacklist("$root/library/Zend");