<?php

/**
 * News model
 *
 * @author robert
 */
class Planet_Model_News extends PPN_Model_Abstract
{
    public function __construct()
    {
        
    }

    public function getAllActiveNews($page=null)
    {
        return $this->getResource('News')->getAllActiveNews($page);
    }

    public function getAllActiveNewsFromCategoryBySlug($slug,$page=null)
    {
        return $this->getResource('News')->getAllActiveNewsFromCategoryBySlug($slug,$page);
    }

    public function getAllActiveNewsFromCategoryById($id,$page=null)
    {
        return $this->getResource('News')->getAllActiveNewsFromCategoryById($id,$page);
    }

    public function getOneActiveNewsBySlug($slug)
    {
        $oneNews = $this->getResource('News')->getOneActiveNewsBySlug($slug);

        if($oneNews === null) {
            throw new Exception("No such news");
        }

        return $oneNews;
    }

    public function getOneActiveNewsById($id)
    {
        $oneNews = $this->getResource('News')->getOneActiveNewsById($id);

        if($oneNews === null) {
            throw new Exception("No such news");
        }
        
        return $oneNews;
    }

}