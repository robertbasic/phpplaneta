<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Add
 *
 * @author robert
 */
class Planet_Form_News_Categories_Add extends Planet_Form_News_Categories
{
    
    public function init()
    {
        parent::init();

        $this->removeElement('id');
    }

    public function setSlugValidator()
    {
        $this->getElement('slug')->addValidator(
                    'Db_NoRecordExists',
                    false,
                    array(
                        'table' => $this->getModel()->getResource('News_Categories')->getPrefix() . 'news_categories',
                        'field' => 'slug'
                    )
                );
    }

}