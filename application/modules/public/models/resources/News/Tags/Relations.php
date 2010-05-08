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