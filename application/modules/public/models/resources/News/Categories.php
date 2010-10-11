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

    public function getAllNewsCategories($page=null)
    {
        $select = $this->_getCategorySelect();

        if($page !== null) {
            return $this->_getPaginatorForSelect($select, $page);
        }

        return $this->fetchAll($select);
    }

    public function getAllNewsCategoriesWithPosts()
    {
        $select = $this->_getCategorySelect();
        $select->distinct()
                ->join(
                    array(
                        'news' => $this->getPrefix() . 'news'
                    ),
                    'categories.id = news.fk_news_category_id',
                    array()
                )
                ->where('news.active = ?', true);

        return $this->fetchAll($select);
    }

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

    public function insertCategory($data)
    {
        try {
            $this->insert($data);
            return true;
        } catch(Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public function updateCategory($data)
    {
        try {
           $this->update($data, array('id = ?' => $data['id']));
           return true;
        } catch(Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public function deleteCategory($id)
    {
        $id = (int)$id;

        return $this->delete(array('id = ?' => $id));
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