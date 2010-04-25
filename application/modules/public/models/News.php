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
        return $this->getResource('News')->getAllActiveNewsFromCategoryBySlug($slug,$page=null);
    }

    public function getAllActiveNewsFromCategoryById($id,$page=null)
    {
        return $this->getResource('News')->getAllActiveNewsFromCategoryById($id,$page=null);
    }

    public function getOneNewsBySlug()
    {

    }

    public function getOneNewsById()
    {
        
    }

}