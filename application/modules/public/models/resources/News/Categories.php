<?php

/**
 * Model resource for news categories
 * table news_categories
 *
 * @author robert
 */
class Planet_Model_Resource_News_Categories extends PPN_Model_Resource_Abstract
{
    /**
     * Name of the table
     * gets overwritten in PPN_Model_Resource_Abstract::init()
     * by adding the prefix
     *
     * @var string
     */
    protected $_name = 'news_categories';

    /**
     *
     * @var Zend_Db_Table_Row_Abstract
     */
    protected $_rowClass = 'Planet_Model_Resource_News_Categories_Item';

    /**
     * Get a category by slug
     *
     * @param string $slug
     * @return PPN_Model_Resource_Item_Abstract
     */
    public function getCategoryBySlug($slug)
    {
        $slug = (string)$slug;

        $select = $this->_getCategorySelect(
                    array(
                        array('categories.slug = ?', $slug)
                    )
                );
        
        return $this->fetchRow($select);
    }

    /**
     * Get a category by id
     *
     * @param int $id
     * @return PPN_Model_Resource_Item_Abstract
     */
    public function getCategoryById($id)
    {
        $id = (int)$id;

        $select = $this->_getCategorySelect(
                    array(
                        array('categories.id = ?', $id)
                    )
                );

        return $this->fetchRow($select);
    }

    protected function _getCategorySelect($where=null)
    {
        $select = $this->select();
        $select->setIntegrityCheck(false);

        $select->from(
                    array(
                        'categories' => $this->_name
                    ),
                    array(
                        'id', 'title', 'slug'
                    )
                );

        if($where !== null and is_array($where)) {
            foreach($where as $w) {
                $select->where($w[0], $w[1]);
            }
        }

        $select->order('categories.title ASC');

        return $select;
    }

}