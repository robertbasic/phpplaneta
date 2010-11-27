<?php

/**
 * Base form for adding/editing news
 *
 * @author robert
 * @todo tidy up validation and filtering
 */
class Planet_Form_News extends PPN_Form_Abstract
{

    public function init()
    {
        parent::init();

        $this->addElement(
            'text',
            'title',
            array(
                'label' => 'Naslov:',
                'required' => true,
                'validators' => array(
                    array(
                        'validator' => 'StringLength', 'options' => array(3, 100)
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
                        'validator' => 'StringLength', 'options' => array(3, 255)
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
            'select',
            'fk_news_category_id',
            array(
                'label' => 'Kategorija:',
                'required' => true
            )
        );

        $this->addElement(
            'textarea',
            'text',
            array(
                'label' => 'Tekst:',
                'required' => true,
                'validators' => array(
                    array(
                        'validator' => 'StringLength', 'options' => array(6)
                    )
                ),
                'filters' => array(
                    array(
                        'filter' => 'StringTrim'
                    )
                )
            )
        );

        $this->addElement(
            'checkbox',
            'active',
            array(
                'label' => 'Aktivno?',
                'required' => true
            )
        );

        $this->addElement(
            'checkbox',
            'comments_enabled',
            array(
                'label' => 'Komentari?',
                'required' => true
            )
        );

        $this->addElement(
            'hidden',
            'fk_user_id',
            array(
                'decorators' => $this->hiddenElementDecorators
            )
        );

        $this->addElement(
            'hidden',
            'datetime_added',
            array(
                'decorators' => $this->hiddenElementDecorators
            )
        );

        $this->addElement(
            'hidden',
            'id',
            array(
                'decorators' => $this->hiddenElementDecorators
            )
        );

        $this->addElement(
            'hidden',
            'related_tags',
            array(
                'decorators' => $this->hiddenElementDecorators,
                'required' => false
            )
        );

        $this->addSubmitAndResetButtons();

        $this->getElement('fk_news_category_id')
                ->addMultiOptions(
                            $this->getModel()
                                ->getNewsCategoriesForSelectBox()
                        );

        $tagValidator = new Zend_Validate_Regex('/^(#\d+\#)+$/');
        $this->getElement('related_tags')->addValidator($tagValidator);

    }
}