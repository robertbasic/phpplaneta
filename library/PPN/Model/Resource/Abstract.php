<?php

/**
 * Abstract for model resources
 * All model resources extend this class
 *
 * @author robert
 */
class PPN_Model_Resource_Abstract extends Zend_Db_Table_Abstract
{
    /**
     * Prefix for table names
     *
     * @var string
     */
    protected $_prefix = null;

    /**
     * DB adapter config
     *
     * @var array
     */
    protected $_config = null;

    /**
     * Init
     */
    public function init()
    {
        // Get the table prefix and add it to the table name
        $this->_prefix = $this->getPrefix();
        $this->_name = $this->_prefix . $this->_name;
    }

    /**
     * Get the table prefix
     *
     * @return string
     */
    public function getPrefix()
    {
        if($this->_prefix === null) {
            $this->_config = $this->getConfig();
            $this->_prefix = $this->_config['prefix'];
        }

        return $this->_prefix;
    }

    /**
     * Get the db config
     *
     * @return array
     */
    public function getConfig()
    {
        if($this->_config === null) {
            $this->_config = $this->getAdapter()->getConfig();
        }

        return $this->_config;
    }

    /**
     * Return a Zend_Paginator instance based on the select passed
     *
     * @param Zend_Db_Select $select
     * @param int $page
     * @return Zend_Paginator
     */
    protected function _getPaginatorForSelect($select,$page)
    {
        $page = (int)$page;
        if($page < 1) {
            $page = 1;
        }
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage(5);
        $paginator->setCurrentPageNumber($page);

        return $paginator;
    }
}