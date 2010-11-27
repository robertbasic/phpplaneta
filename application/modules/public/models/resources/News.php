<?php

/**
 * Model resource for news
 * table news
 *
 * @author robert
 * @todo throw specific exceptions
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
    protected $_rowClass = 'Planet_Model_Resource_News_Item';

    /**
     * Get all active news
     *
     * @param int $page
     * @return Zend_Paginator | Zend_Db_Table_Rowset
     */
    public function getAllActiveNews($page=null)
    {
        $select = $this->_getAllNewsSelect(
                    array(
                        array('news.active = ?', true),
                        array('author.active = ?', true)
                    )
                );

        if($page !== null) {
            return $this->_getPaginatorForSelect($select, $page);
        }

        return $this->fetchAll($select);

    }

    public function searchActiveNews($keyword,$page=null)
    {
        $keyword = trim(strip_tags((string)$keyword));

        $select = $this->_getAllNewsSelect(
                    array(
                        array('news.title LIKE ? OR news.text LIKE ?', '%' . $keyword . '%'),
                        array('news.active = ?', true),
                        array('author.active = ?', true)
                    )
                );
        
        if($page !== null) {
            return $this->_getPaginatorForSelect($select, $page);
        }

        return $this->fetchAll($select);
    }

    public function getAllNews($page=null)
    {
        $select = $this->_getAllNewsSelect();

        if($page !== null) {
            return $this->_getPaginatorForSelect($select, $page);
        }

        return $this->fetchAll($select);
    }

    /**
     * Get all active news from a category, by category slug
     *
     * @param int $page
     * @return Zend_Paginator | Zend_Db_Table_Rowset
     */
    public function getAllActiveNewsFromCategoryBySlug($slug,$page=null)
    {
        $slug = (string)$slug;
        $select = $this->_getAllNewsSelect(
                    array(
                        array('news.active = ?', true),
                        array('author.active = ?', true),
                        array('category.slug = ?', $slug)
                    )
                );

        if($page !== null) {
            return $this->_getPaginatorForSelect($select, $page);
        }

        return $this->fetchAll($select);
    }

    /**
     * Get all active news from a category, by category id
     *
     * @param int $page
     * @return Zend_Paginator | Zend_Db_Table_Rowset
     */
    public function getAllActiveNewsFromCategoryById($id,$page=null)
    {
        $id = (int)$id;
        $select = $this->_getAllNewsSelect(
                    array(
                        array('news.active = ?', true),
                        array('author.active = ?', true),
                        array('news.fk_news_category_id = ?', $id)
                    )
                );

        if($page !== null) {
            return $this->_getPaginatorForSelect($select, $page);
        }

        return $this->fetchAll($select);
    }

    /**
     * Get one active news by it's slug
     *
     * @param string $slug
     * @return PPN_Model_Resource_Item_Abstract
     */
    public function getOneActiveNewsBySlug($slug)
    {
        $slug = (string)$slug;
        $select = $this->_getAllNewsSelect(
                    array(
                        array('news.active = ?', true),
                        array('author.active = ?', true),
                        array('news.slug = ?', $slug)
                    )
                );

        return $this->fetchRow($select);
    }

    /**
     * Get one active news by it's id
     *
     * @param int $id
     * @return PPN_Model_Resource_Item_Abstract
     */
    public function getOneActiveNewsById($id)
    {
        $id = (int)$id;
        $select = $this->_getAllNewsSelect(
                    array(
                        array('news.active = ?', true),
                        array('author.active = ?', true),
                        array('news.id = ?', $id)
                    )
                );

        return $this->fetchRow($select);
    }

    /**
     * Get one news by it's id
     *
     * @param int $id
     * @return PPN_Model_Resource_Item_Abstract
     */
    public function getOneNewsById($id)
    {
        $id = (int)$id;
        $select = $this->_getAllNewsSelect(
                    array(
                        array('news.id = ?', $id)
                    )
                );

        return $this->fetchRow($select);
    }

    public function getNewsByTagId($tagId,$page=null)
    {
        $tagId = (int)$tagId;

        $select = $this->select();
        $select->setIntegrityCheck(false);

        $select->from(
                    array(
                        'news' => $this->_name
                    ),
                    array(
                        'id', 'fk_news_category_id',
                        'fk_user_id', 'title', 'slug', 'text',
                        'datetime_added', 'active', 'comments_enabled'
                    )
                )
                ->join(
                    array(
                        'relations' => $this->getPrefix() . 'news_tags_relations'
                    ),
                    'relations.fk_news_id = news.id',
                    array(
                        ''
                    )
                )
                ->join(
                    array(
                        'category' => $this->getPrefix() . 'news_categories'
                    ),
                    'category.id = news.fk_news_category_id',
                    array(
                        'category_title' => 'title', 'category_slug' => 'slug'
                    )
                )
                ->join(
                    array(
                        'author' => $this->getPrefix() . 'users'
                    ),
                    'author.id = news.fk_user_id',
                    array(
                        'firstname', 'lastname'
                    )
                )
                ->where('relations.fk_news_tag_id = ?', $tagId)
                ->where('news.active = ?', true)
                ->order('news.datetime_added DESC');

        if($page !== null) {
            return $this->_getPaginatorForSelect($select, $page);
        }

        return $this->fetchAll($select);
    }

    public function getNewsByDate($date, $page=null)
    {
        $select = $this->_getAllNewsSelect(
                    array(
                        array('news.active = ?', true),
                        array('author.active = ?', true),
                        array('news.datetime_added LIKE ?', $date . "%")
                    )
                );

        if($page !== null) {
            return $this->_getPaginatorForSelect($select, $page);
        }

        return $this->fetchAll($select);
    }

    public function insertNews($data)
    {
        try {
            $this->insert($data);
            return true;
        } catch(Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public function updateNews($data)
    {
        try {
           $this->update($data, array('id = ?' => $data['id']));
           return true;
        } catch(Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public function deleteNews($id)
    {
        $id = (int)$id;

        return $this->delete(array('id = ?' => $id));
    }

    public function deleteNewsFromCategory($categoryId)
    {
        $categoryId = (int)$categoryId;

        return $this->delete(array('fk_news_category_id = ?' => $categoryId));
    }

    /**
     * Build the select object for news
     * optionally pass in an array for the where part
     * without the where it will return all the news
     *
     * @param array $where
     * @return Zend_Db_Select
     */
    protected function _getAllNewsSelect($where=null)
    {
        $select = $this->select();
        $select->setIntegrityCheck(false);

        $select->from(
                    array(
                        'news' => $this->_name
                    ),
                    array(
                        'id', 'fk_news_category_id',
                            'fk_user_id', 'title', 'slug', 'text',
                            'datetime_added', 'active', 'comments_enabled'
                    )
                )
                ->join(
                    array(
                        'category' => $this->getPrefix() . 'news_categories'
                    ),
                    'category.id = news.fk_news_category_id',
                    array(
                        'category_title' => 'title', 'category_slug' => 'slug'
                    )
                )
                ->join(
                    array(
                        'author' => $this->getPrefix() . 'users'
                    ),
                    'author.id = news.fk_user_id',
                    array(
                        'firstname', 'lastname'
                    )
                );

        if($where !== null and is_array($where)) {
            foreach($where as $w) {
                $select->where($w[0], $w[1]);
            }
        }

        $select->order('news.datetime_added DESC');
        
        return $select;
    }
}