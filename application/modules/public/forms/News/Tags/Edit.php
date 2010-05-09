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
class Planet_Form_News_Tags_Edit extends Planet_Form_News_Tags
{
    public function init()
    {
        parent::init();

        $this->removeElement('tags');
        $this->removeDisplayGroup('buttons');

        $this->addElement(
            'text',
            'title',
            array(
                'label' => 'Naziv:',
                'required' => true,
                'validators' => array(
                    array(
                        'validator' => 'StringLength', 'options' => array(3, 50)
                    )
                ),
                'filters' => array(
                    array(
                        'filter' => 'StringTrim',
                        'filter' => 'StripTags'
                    )
                )
            )
        );

        $this->addElement(
            'text',
            'slug',
            array(
                'label' => 'Slug:',
                'required' => true,
                'validators' => array(
                    array(
                        'validator' => 'StringLength', 'options' => array(3, 255),
                    )
                ),
                'filters' => array(
                    array(
                        'filter' => 'StringTrim',
                        'filter' => 'StripTags',
                        'filter' => 'Slug'
                    )
                )
            )
        );

        $this->addElement(
            'hidden',
            'id',
            array(
                'decorators' => $this->hiddenElementDecorators
            )
        );

        $this->addSubmitAndResetButtons();

    }

    public function setSlugValidator()
    {
        $this->getElement('slug')->addValidator(
                    'Db_NoRecordExists',
                    false,
                    array(
                        'table' => $this->getModel()->getResource('News_Tags')->getPrefix() . 'news_categories',
                        'field' => 'slug',
                        'exclude' => array(
                            'field' => 'id',
                            'value' => $this->getElement('id')->getValue()
                        )
                    )
                );
    }
}