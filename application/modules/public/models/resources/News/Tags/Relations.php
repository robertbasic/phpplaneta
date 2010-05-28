<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Relations
 *
 * @author robert
 */
class Planet_Model_Resource_News_Tags_Relations extends PPN_Model_Resource_Abstract
{
    protected $_name = 'news_tags_relations';

    /**
     *
     * @var Zend_Db_Table_Row_Abstract
     */
    protected $_rowClass = 'Planet_Model_Resource_News_Tags_Relations_Item';

    public function getTagsForNewsById($newsId)
    {
        $newsId = (int)$newsId;

        $select = $this->select();
        $select->setIntegrityCheck(false);

        $select->from(
                    array(
                        'relations' => $this->_name
                    ),
                    ''
                )
                ->join(
                    array(
                        'tags' => $this->getPrefix() . 'news_tags'
                    ),
                    'relations.fk_news_tag_id = tags.id',
                    array(
                        'id', 'title', 'slug'
                    )
                )
                ->where('relations.fk_news_id = ?', $newsId)
                ->order('tags.title ASC');

        return $this->fetchAll($select);
    }

    public function getMostUsedTags($limit=20)
    {
        $select = $this->select();
        $select->setIntegrityCheck(false);

        $select->from(
                    array(
                        'relations' => $this->_name
                    ),
                    array('num' => 'COUNT(*)')
                )
                ->join(
                    array(
                        'tags' => $this->getPrefix() . 'news_tags'
                    ),
                    'relations.fk_news_tag_id = tags.id',
                    array(
                        'title', 'slug'
                    )
                )
                ->join(
                    array(
                        'news' => $this->getPrefix() . 'news'
                    ),
                    'relations.fk_news_id = news.id',
                    array(
                        ''
                    )
                )
                ->where('news.active = ?', true)
                ->group('tags.id')
                ->order('num DESC')
                ->order('tags.title ASC')
                ->limit($limit);

        return $this->fetchAll($select);
    }

    public function makeRelation($newsId, $relatedTags)
    {
        $relatedTags = explode("##", trim($relatedTags, '#'));
        
        if(!is_array($relatedTags)) {
            throw new Exception("Tag IDs must be an array, got: " . gettype($relatedTags));
        }

        try {
            $this->deleteRelationsForNews($newsId);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }

        if(empty($relatedTags)
                or (count($relatedTags) == 1 and $relatedTags[0] == '')) {
            return true;
        }

        try {
            $this->insertRelations($newsId, $relatedTags);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }

        return true;
    }

    public function insertRelations($newsId, $relatedTags)
    {
        $newsId = (int)$newsId;

        $adapter = $this->getAdapter();

        $insertRelationsSql = "INSERT INTO " . $this->_name ." (`fk_news_id`, `fk_news_tag_id`) VALUES ";

        foreach($relatedTags as $tagId) {
            $tagId = (int)$tagId;
            $insertRelationsSql .= "(" . $adapter->quote($newsId) . ", " . $adapter->quote($tagId) . "), ";
        }

        $insertRelationsSql = substr($insertRelationsSql, 0, -2);

        try {
            $adapter->query($insertRelationsSql);
            return true;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public function deleteRelationsForNews($newsId)
    {
        $newsId = (int)$newsId;

        return $this->delete(array('fk_news_id = ?' => $newsId));
    }

    public function deleteRelationsForTag($tagId)
    {
        $tagId = (int)$tagId;

        return $this->delete(array('fk_news_tag_id = ?' => $tagId));
    }

}