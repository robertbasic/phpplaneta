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
class Planet_Model_News_Tags_Relations extends PPN_Model_Resource_Abstract
{
    protected $_name = 'news_tags_relations';

    public function makeRelation($newsId, $tagIds)
    {
        if(!is_array($tagIds)) {
            $tagIds = (array)$tagIds;
        }

        try {
            $this->deleteRelationsForNews($newsId);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }

        /**
         * @todo try to insert all with one insert
         */
        foreach($tagIds as $tagId) {
            try {
                $this->insertRelation($newsId, $tagId);
                $return = true;
            } catch (Exception $e) {
                $return = false;
            }
        }

        return $return;
    }

    public function insertRelation($newsId, $tagId)
    {
        $newsId = (int)$newsId;
        $tagId = (int)$tagId;

        $data = array(
            'fk_news_id' => $newsId,
            'fk_news_tag_id' => $tagId
        );

        try {
            $this->insert($data);
            return true;
        } catch(Exception $e) {
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