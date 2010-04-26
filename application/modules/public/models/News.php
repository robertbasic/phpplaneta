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

    public function getAllNews($page=null)
    {
        return $this->getResource('News')->getAllNews($page);
    }

    public function getAllActiveNewsFromCategoryBySlug($slug,$page=null)
    {
        $category = $this->getResource('News_Categories')->getCategoryBySlug($slug);

        if($category === null) {
            throw new Exception("No such category");
        }

        return $this->getResource('News')->getAllActiveNewsFromCategoryBySlug($slug,$page);
    }

    public function getAllActiveNewsFromCategoryById($id,$page=null)
    {
        $category = $this->getResource("News_Categories")->getCategoryById($id);

        if($category === null) {
            throw new Exception("No such category");
        }

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

    public function getOneNewsById($id)
    {
        $oneNews = $this->getResource('News')->getOneNewsById($id);

        if($oneNews === null) {
            throw new Exception("No such news");
        }

        return $oneNews;
    }

    public function getNewsCategoriesForSelectBox()
    {
        $categories = $this->getResource('News_Categories')->fetchAll()->toArray();

        $categoriesSelectBox = array();

        foreach($categories as $category) {
            $categoriesSelectBox[$category['id']] = $category['title'];
        }

        return $categoriesSelectBox;
    }

    /**
     * @todo Refactor this mess!
     * @param array $data
     * @return bool
     */
    public function saveNews($data)
    {
        $return = false;
        if(!array_key_exists('id', $data)) {
            $form = $this->getForm('News_Add');
            $form->populate($data);
            $form->removeElement('csrf');

            if(!$form->isValid($data)) {
                return false;
            }

            $data = $form->getValues();

            $data['datetime_added'] = date('Y-m-d H:i:s');

            $return = $this->getResource('News')->insertNews($data);
        } else {
            $form = $this->getForm('News_Edit');
            $form->populate($data);
            $form->removeElement('csrf');

            if(!$form->isValid($data)) {
                return false;
            }

            $data = $form->getValues();

            $return = $this->getResource('News')->updateNews($data);
        }
        
        return $return;
    }

    public function deleteNews($id)
    {
        return $this->getResource('News')->deleteNews($id);
    }

}