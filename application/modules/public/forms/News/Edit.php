<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Edit
 *
 * @author robert
 */
class Planet_Form_News_Edit extends Planet_Form_News
{
    public function init()
    {
        parent::init();
    }

    public function setSlugValidator()
    {
        $this->getElement('slug')->addValidator(
                    'Db_NoRecordExists',
                    false,
                    array(
                        'table' => $this->getModel()->getResource('News')->getPrefix() . 'news',
                        'field' => 'slug',
                        'exclude' => array(
                            'field' => 'id',
                            'value' => $this->getElement('id')->getValue()
                        )
                    )
                );
    }
}