<?php

/**
 * News model
 *
 * @author robert
 * @todo throw specific exceptions
 */
class Planet_Model_News extends PPN_Model_Abstract
{

    public function __construct()
    {
        
    }

    /**
     * Getters
     */

    /**
     * Get active news.
     * Pagination is to be decided within the resource, based on $page
     * @param int|null $page
     */
    public function getAllActiveNews($page=null)
    {
        return $this->getResource('News')->getAllActiveNews($page);
    }

    public function searchActiveNews($keyword,$page=null)
    {
        return $this->getResource('News')->searchActiveNews($keyword,$page);
    }

    /**
     * Get all news.
     * Pagination is to be decided within the resource, based on $page
     * @param int|null $page
     */
    public function getAllNews($page=null)
    {
        return $this->getResource('News')->getAllNews($page);
    }

    /**
     * Get active news from a category, by the categories slug.
     * Pagination is to be decided within the resource, based on $page
     * @param string $slug
     * @param int|null $page
     */
    public function getAllActiveNewsFromCategoryBySlug($slug,$page=null)
    {
        $category = $this->getResource('News_Categories')->getCategoryBySlug($slug);

        if($category === null) {
            throw new PPN_Exception_NotFound(
                        sprintf(PPN_Exception_NotFound::NO_SUCH_CATEGORY, $slug)
                    );
        }

        return $this->getResource('News')->getAllActiveNewsFromCategoryBySlug($slug,$page);
    }

    public function getAllActiveNewsByTagSlug($slug,$page=null)
    {
        $tag = $this->getResource('News_Tags')->getTagBySlug($slug);

        if($tag === null) {
            throw new PPN_Exception_NotFound(
                        sprintf(PPN_Exception_NotFound::NO_SUCH_TAG, $slug)
                    );
        }

        return $this->getResource('News')->getNewsByTagId($tag->id, $page);
    }

    /**
     * Get active news from a category, by the categories id.
     * Pagination is to be decided within the resource, based on $page
     * I wonder will this ever be used?g Oh, well...
     * @param int $id
     * @param int|null $page
     */
    public function getAllActiveNewsFromCategoryById($id,$page=null)
    {
        $category = $this->getResource("News_Categories")->getCategoryById($id);

        if($category === null) {
            throw new PPN_Exception_NotFound(
                        sprintf(PPN_Exception_NotFound::NO_SUCH_CATEGORY, $id)
                    );
        }

        return $this->getResource('News')->getAllActiveNewsFromCategoryById($id,$page);
    }

    /**
     * Get one active news by it's slug
     * @param string $slug
     */
    public function getOneActiveNewsBySlug($slug)
    {
        $oneNews = $this->getResource('News')->getOneActiveNewsBySlug($slug);

        if($oneNews === null) {
            throw new PPN_Exception_NotFound(
                        sprintf(PPN_Exception_NotFound::NO_SUCH_NEWS, $slug)
                    );
        }

        return $oneNews;
    }

    /**
     * Get one active news by it's id
     * @param int $id
     */
    public function getOneActiveNewsById($id)
    {
        $oneNews = $this->getResource('News')->getOneActiveNewsById($id);

        if($oneNews === null) {
            throw new PPN_Exception_NotFound(
                        sprintf(PPN_Exception_NotFound::NO_SUCH_NEWS, $id)
                    );
        }
        
        return $oneNews;
    }

    /**
     * Get one news by it's id
     * @param int $id
     */
    public function getOneNewsById($id)
    {
        $oneNews = $this->getResource('News')->getOneNewsById($id);

        if($oneNews === null) {
            throw new PPN_Exception_NotFound(
                        sprintf(PPN_Exception_NotFound::NO_SUCH_NEWS, $id)
                    );
        }

        return $oneNews;
    }

    /**
     * Get all news categories, ready to be consumed by a select box
     * in a Zend_Form
     */
    public function getNewsCategoriesForSelectBox()
    {
        $categories = $this->getAllNewsCategories();

        $categoriesSelectBox = array();

        foreach($categories as $category) {
            $categoriesSelectBox[$category->id] = $category->title;
        }

        return $categoriesSelectBox;
    }

    public function getAllNewsCategories($page=null)
    {
        return $this->getResource('News_Categories')->getAllNewsCategories($page);
    }

    public function getOneNewsCategoryById($id)
    {
        $oneCategory = $this->getResource('News_Categories')->getCategoryById($id);

        if($oneCategory === null) {
            throw new PPN_Exception_NotFound(
                        sprintf(PPN_Exception_NotFound::NO_SUCH_CATEGORY, $id)
                    );
        }

        return $oneCategory;
    }

    /**
     * Get all news sources, ready to be consumed by a select box
     * in a Zend_Form
     */
    public function getNewsSourcesForSelectBox()
    {
        $sources = $this->getAllNewsSources();

        $sourcesSelectBox = array();

        foreach($sources as $source) {
            $sourcesSelectBox[$source['id']] = $source['name'];
        }

        return $sourcesSelectBox;
    }

    public function getAllNewsSources($page=null)
    {
        return $this->getResource('News_Sources')->getAllNewsSources($page);
    }

    public function getOneNewsSourceById($id)
    {
        $oneSource = $this->getResource('News_Sources')->getSourceById($id);

        if($oneSource === null) {
            throw new Exception("No such source");
        }

        return $oneSource;
    }

    public function getAllNewsTags($page=null)
    {
        return $this->getResource('News_Tags')->getAllNewsTags($page);
    }

    public function getTagsForNews($data)
    {
        $tags = array();
        $newsId = null;
        
        if(is_array($data)
                and array_key_exists('newsId', $data)) {
            $newsId = (int)$data['newsId'];
        } else if(is_string($data)
                or is_int($data)) {
            $newsId = (int)$data;
        }

        $tags = $this->getResource('News_Tags_Relations')->getTagsForNewsById($newsId);
        
        return $tags;
    }

    public function getOneNewsTagById($tagId)
    {
        $oneTag = $this->getResource('News_Tags')->getTagById($tagId);

        if($oneTag === null) {
            throw new PPN_Exception_NotFound(
                        sprintf(PPN_Exception_NotFound::NO_SUCH_TAG, $id)
                    );
        }

        return $oneTag;
    }

    public function getMostUsedTags($limit=20)
    {
        $tags = $this->getResource('News_Tags_Relations')->getMostUsedTags($limit);

        return $tags;
    }

    public function getAllComments($page)
    {
        return $this->getResource('News_Comments')->getAllComments($page);
    }

    public function getOneCommentById($commentId)
    {
        $oneComment = $this->getResource('News_Comments')->getOneCommentById($commentId);

        if($oneComment === null) {
            throw new Exception("No such comment");
        }

        return $oneComment;
    }

    public function getCommentsForNews($data)
    {
        $comments = array();
        $newsId = null;

        if(is_array($data)
                and array_key_exists('newsId', $data)) {
            $newsId = (int)$data['newsId'];
        } else if(is_string($data)
                or is_int($data)) {
            $newsId = (int)$data;
        }

        $comments = $this->getResource('News_Comments')->getCommentsForNewsById($newsId);

        return $comments;
    }

    /**
     * Saves
     */

    /**
     * @todo Refactor this mess!
     * @param array $data
     * @return bool
     */
    public function saveNews($data)
    {
        $return = false;

        if(array_key_exists('fk_news_source_id', $data)
                and $data['fk_news_source_id'] == '') {
            unset($data['fk_news_source_id']);
        }

        if(!array_key_exists('id', $data)) {
            $form = $this->getForm('News_Add');
            $form->populate($data);
            $form->removeElement('csrf');

            if(!$form->isValid($data)) {
                return false;
            }

            $data = $form->getValues();

            if(array_key_exists('related_tags', $data)) {
                $relatedTags = $data['related_tags'];
                unset($data['related_tags']);
            }

            $data['datetime_added'] = date('Y-m-d H:i:s');

            $return = $this->getResource('News')->insertNews($data);

            $id = $this->getResource('News')->getAdapter()->lastInsertId();
        } else {
            $form = $this->getForm('News_Edit');
            $form->populate($data);
            $form->removeElement('csrf');

            if(!$form->isValid($data)) {
                return false;
            }

            $data = $form->getValues();

            if(array_key_exists('related_tags', $data)) {
                $relatedTags = $data['related_tags'];
                unset($data['related_tags']);
            }

            $return = $this->getResource('News')->updateNews($data);
            $id = $data['id'];
        }
        
        $this->getResource('News_Tags_Relations')->makeRelation($id, $relatedTags);

        return $return;
    }

    public function saveNewsCategory($data)
    {
        $return = false;

        if(!array_key_exists('id', $data)) {
            $form = $this->getForm('News_Categories_Add');
            $form->populate($data);
            $form->removeElement('csrf');

            if(!$form->isValid($data)) {
                return false;
            }

            $data = $form->getValues();

            $return = $this->getResource('News_Categories')->insertCategory($data);
        } else {
            $form = $this->getForm('News_Categories_Edit');
            $form->populate($data);
            $form->removeElement('csrf');

            if(!$form->isValid($data)) {
                return false;
            }

            $data = $form->getValues();

            $return = $this->getResource('News_Categories')->updateCategory($data);
        }

        return $return;
    }

    public function saveNewsSource($data)
    {
        $return = false;

        if(!array_key_exists('id', $data)) {
            $form = $this->getForm('News_Sources_Add');
            $form->populate($data);
            $form->removeElement('csrf');

            if(!$form->isValid($data)) {
                return false;
            }

            $data = $form->getValues();

            $return = $this->getResource('News_Sources')->insertSource($data);
        } else {
            $form = $this->getForm('News_Sources_Edit');
            $form->populate($data);
            $form->removeElement('csrf');

            if(!$form->isValid($data)) {
                return false;
            }

            $data = $form->getValues();

            $return = $this->getResource('News_Sources')->updateSource($data);
        }

        return $return;
    }

    public function saveNewsTags($data)
    {
        $return = false;

        if(!array_key_exists('id', $data)) {
            if(!array_key_exists('tags', $data)
                    or $data['tags'] == '') {
                throw new Exception("No tags provided");
            }
            
            $tags = explode(",", $data['tags']);

            $tagsSanitized = array();

            $trimmer = new Zend_Filter_StringTrim();
            $stripper = new Zend_Filter_StripTags();
            $slugger = new PPN_Filter_Slug();

            $i = 0;
            foreach($tags as $tag) {
                $tmpTag = $trimmer->filter($stripper->filter($tag));
                $tmpSlug = $slugger->filter($tmpTag);

                if($tmpTag != '' and $tmpSlug != '') {
                    $tagsSanitized[$i]['title'] = $tmpTag;
                    $tagsSanitized[$i]['slug'] = $tmpSlug;
                    $i++;
                }
            }

            $insertedTags = $this->getResource('News_Tags')
                                    ->insertTags($tagsSanitized);

            return $insertedTags;
        } else {
            $form = $this->getForm('News_Tags_Edit');
            $form->populate($data);
            $form->removeElement('csrf');

            if(!$form->isValid($data)) {
                return false;
            }

            $data = $form->getValues();

            $return = $this->getResource('News_Tags')->updateTag($data);
        }

        return $data;
    }

    public function saveComment($data)
    {
        $return = false;

        if(!array_key_exists('id', $data)) {
            $form = $this->getForm('News_Comments_Add');
            $form->populate($data);
            $form->removeElement('csrf');

            if(!$form->isValid($data)) {
                return false;
            }

            $data = $form->getValues();

            if(array_key_exists('honeypot', $data)) {
                unset($data['honeypot']);
            }

            $data['datetime_added'] = date('Y-m-d H:i:s');
            $data['active'] = false;

            $return = $this->getResource('News_Comments')->insertComment($data);
        } else {
            $form = $this->getForm('News_Comments_Edit');
            $form->populate($data);
            $form->removeElement('csrf');

            if(!$form->isValid($data)) {
                return false;
            }

            $data = $form->getValues();

            if(array_key_exists('honeypot', $data)) {
                unset($data['honeypot']);
            }

            $return = $this->getResource('News_Comments')->updateComment($data);
        }

        return $return;
    }

    /**
     * Deletes
     */

    public function deleteNews($id)
    {
        try {
            $this->getResource('News_Tags_Relations')->deleteRelationsForNews($id);
        } catch(Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }

        return $this->getResource('News')->deleteNews($id);
    }

    public function deleteNewsFromCategory($categoryId)
    {
        return $this->getResource('News')->deleteNewsFromCategory($categoryId);
    }

    public function deleteNewsCategory($id)
    {
        if($this->deleteNewsFromCategory($id) !== false) {
            return $this->getResource('News_Categories')->deleteCategory($id);
        } else {
            return false;
        }
    }

    public function deleteNewsSource($id)
    {
        return $this->getResource('News_Sources')->deleteSource($id);
    }

    public function deleteNewsTag($id)
    {
        if($this->getResource('News_Tags_Relations')->deleteRelationsForTag($id) !== false) {
            return $this->getResource('News_Tags')->deleteTag($id);
        } else {
            return false;
        }
    }

    public function deleteComment($id)
    {
        return $this->getResource('News_Comments')->deleteComment($id);
    }

}