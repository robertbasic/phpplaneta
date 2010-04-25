<?php

/**
 * Model resource for news
 * table news
 *
 * @author robert
 */
class Planet_Model_Resource_News extends PPN_Model_Resource_Abstract
{
    /**
     * Name of the table
     * gets overwritten in PPN_Model_Resource_Abstract::init()
     * by adding the prefix
     *
     * @var string
     */
    protected $_name = 'news';

    /**
     *
     * @var Zend_Db_Table_Row_Abstract
     */
    protected $_rowClass = 'Public_Model_Resource_Item_News';
}