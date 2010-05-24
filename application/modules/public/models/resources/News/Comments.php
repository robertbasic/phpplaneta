<?php

/**
 * Model resource for news sources
 * table news_sources
 *
 * @author robert
 */
class Planet_Model_Resource_News_Comments extends PPN_Model_Resource_Abstract
{
    /**
     * Name of the table
     * gets overwritten in PPN_Model_Resource_Abstract::init()
     * by adding the prefix
     *
     * @var string
     */
    protected $_name = 'news_comments';

    /**
     *
     * @var Zend_Db_Table_Row_Abstract
     */
    protected $_rowClass = 'Planet_Model_Resource_News_Comments_Item';

    public function getAllComments($page=null)
    {
        $select = $this->_getCommentSelect();

        if($page !== null) {
            return $this->_getPaginatorForSelect($select, $page);
        }

        return $this->fetchAll($select);
    }

    public function getOneCommentById($id)
    {
        $id = (int)$id;

        $select = $this->_getCommentSelect(
                    array(
                        array('comments.id = ?', $id)
                    )
                );

        return $this->fetchRow($select);
    }

    public function getCommentsForNewsById($newsId)
    {
        $newsId = (int)$newsId;

        $select = $this->_getCommentSelect(
                    array(
                        array('comments.fk_news_id = ?', $newsId),
                        array('comments.active = ?', true)
                    )
                );

        return $this->fetchAll($select);
    }

    public function insertComment($data)
    {
        try {
            $this->insert($data);
            return true;
        } catch(Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public function updateComment($data)
    {
        try {
           $this->update($data, array('id = ?' => $data['id']));
           return true;
        } catch(Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public function deleteComment($id)
    {
        $id = (int)$id;

        return $this->delete(array('id = ?' => $id));
    }

    protected function _getCommentSelect($where=null)
    {
        $select = $this->select();
        $select->setIntegrityCheck(false);

        $select->from(
                    array(
                        'comments' => $this->_name
                    ),
                    array(
                        'id', 'fk_news_id', 'name', 'email', 'url', 'comment',
                            'datetime_added', 'active'
                    )
                );

        if($where !== null and is_array($where)) {
            foreach($where as $w) {
                $select->where($w[0], $w[1]);
            }
        }

        $select->order('comments.datetime_added DESC');

        return $select;
    }

}