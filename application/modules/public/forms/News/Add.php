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
class Planet_Form_News_Add extends Planet_Form_News
{
    
    public function init()
    {
        parent::init();

        $this->setAction('/admin/public/news/add');

        $this->removeElement('id');
        $this->removeElement('datetime_added');

        $this->getElement('slug')->addValidator(
                    'Db_NoRecordExists',
                    false,
                    array(
                        'table' => $this->getModel()->getResource('News')->getPrefix() . 'news',
                        'field' => 'slug'
                    )
                );

    }
}