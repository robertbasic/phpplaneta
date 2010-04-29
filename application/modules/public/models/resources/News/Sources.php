<?php

/**
 * Model resource for news sources
 * table news_sources
 *
 * @author robert
 */
class Planet_Model_Resource_News_Sources extends PPN_Model_Resource_Abstract
{
    /**
     * Name of the table
     * gets overwritten in PPN_Model_Resource_Abstract::init()
     * by adding the prefix
     *
     * @var string
     */
    protected $_name = 'news_sources';

    /**
     *
     * @var Zend_Db_Table_Row_Abstract
     */
    protected $_rowClass = 'Planet_Model_Resource_News_Sources_Item';

    public function getAllNewsSources($page=null)
    {
        $select = $this->_getSourceSelect();

        if($page !== null) {
            return $this->_getPaginatorForSelect($select, $page);
        }

        return $this->fetchAll($select);
    }

    /**
     * Get a source by id
     *
     * @param int $id
     * @return PPN_Model_Resource_Item_Abstract
     */
    public function getSourceById($id)
    {
        $id = (int)$id;

        $select = $this->_getSourceSelect(
                    array(
                        array('sources.id = ?', $id)
                    )
                );

        return $this->fetchRow($select);
    }

    public function insertSource($data)
    {
        try {
            $this->insert($data);
            return true;
        } catch(Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public function updateSource($data)
    {
        try {
           $this->update($data, array('id = ?' => $data['id']));
           return true;
        } catch(Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public function deleteSource($id)
    {
        $id = (int)$id;

        return $this->delete(array('id = ?' => $id));
    }

    protected function _getSourceSelect($where=null)
    {
        $select = $this->select();
        $select->setIntegrityCheck(false);

        $select->from(
                    array(
                        'sources' => $this->_name
                    ),
                    array(
                        'id', 'name', 'url'
                    )
                );

        if($where !== null and is_array($where)) {
            foreach($where as $w) {
                $select->where($w[0], $w[1]);
            }
        }

        $select->order('sources.name ASC');

        return $select;
    }

}