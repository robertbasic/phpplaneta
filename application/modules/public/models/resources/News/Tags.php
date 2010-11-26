<?php

/**
 * Model resource for news tags
 * table news_tags
 *
 * @author robert
 */
class Planet_Model_Resource_News_Tags extends PPN_Model_Resource_Abstract
{
    /**
     * Name of the table
     * gets overwritten in PPN_Model_Resource_Abstract::init()
     * by adding the prefix
     *
     * @var string
     */
    protected $_name = 'news_tags';

    /**
     *
     * @var Zend_Db_Table_Row_Abstract
     */
    protected $_rowClass = 'Planet_Model_Resource_News_Tags_Item';

    public function getAllNewsTags($page=null)
    {
        $select = $this->_getTagSelect();

        if($page !== null) {
            return $this->_getPaginatorForSelect($select, $page);
        }

        return $this->fetchAll($select);
    }

    /**
     * Get a tag by slug
     *
     * @param string $slug
     * @return PPN_Model_Resource_Item_Abstract
     */
    public function getTagBySlug($slug)
    {
        $slug = (string)$slug;

        $select = $this->_getTagSelect(
                    array(
                        array('tags.slug = ?', $slug)
                    )
                );

        return $this->fetchRow($select);
    }

    /**
     * Get a tag by id
     *
     * @param int $id
     * @return PPN_Model_Resource_Item_Abstract
     */
    public function getTagById($id)
    {
        $id = (int)$id;

        $select = $this->_getTagSelect(
                    array(
                        array('tags.id = ?', $id)
                    )
                );
        
        return $this->fetchRow($select);
    }

    public function insertTags($tags)
    {
        $existingTags = array();
        $existingTag = null;
        $newTags = array();
        $newTag = null;
        $returnTags = array();

        foreach($tags as $tag) {
            $existingTag = $this->getTagBySlug($tag['slug']);
            if($existingTag === null) {
                try {
                    $this->insertTag($tag);
                    $id = $this->getAdapter()->lastInsertId();

                    $newTag['id'] = $id;
                    $newTag['title'] = $tag['title'];
                    $newTag['slug'] = $tag['slug'];

                    $newTags[] = $newTag;

                } catch(Exception $e) {
                    throw new Exception($e->getMessage(), $e->getCode());
                }
            } else {
                $existingTags[] = $existingTag->toArray();
            }
        }

        $returnTags = array_merge($existingTags, $newTags);

        return $returnTags;
    }

    public function insertTag($data)
    {
        try {
            $this->insert($data);
            return true;
        } catch(Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public function updateTag($data)
    {
        try {
           $this->update($data, array('id = ?' => $data['id']));
           return true;
        } catch(Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public function deleteTag($id)
    {
        $id = (int)$id;

        return $this->delete(array('id = ?' => $id));
    }

    protected function _getTagSelect($where=null)
    {
        $select = $this->select();
        $select->setIntegrityCheck(false);

        $select->from(
                    array(
                        'tags' => $this->_name
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

        $select->order('tags.title ASC');

        return $select;
    }

}