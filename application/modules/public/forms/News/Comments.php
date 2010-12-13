<?php

/**
 * Base form for adding/editing news
 *
 * @author robert
 * @todo tidy up validation and filtering
 */
class Planet_Form_News_Comments extends PPN_Form_Abstract
{

    public function init()
    {
        parent::init();

        $this->addElement(
            'text',
            'name',
            array(
                'label' => 'Ime',
                'required' => true,
                'validators' => array(
                    array(
                        'validator' => 'StringLength', 'options' => array(3, 20)
                    )
                ),
                'filters' => array(
                    array(
                        'filter' => 'StringTrim',
                    ),
                    array(
                        'filter' => 'StripTags'
                    )
                )
            )
        );

        $this->addElement(
            'text',
            'email',
            array(
                'label' => 'Email',
                'required' => true,
                'validators' => array(
                    array(
                        'validator' => 'EmailAddress'
                    )
                ),
                'filters' => array(
                    array(
                        'filter' => 'StringTrim',
                    ),
                    array(
                        'filter' => 'StripTags'
                    )
                )
            )
        );

        $this->addElement(
            'text',
            'url',
            array(
                'label' => 'Website',
                'required' => false,
                'filters' => array(
                    array(
                        'filter' => 'StringTrim',
                    ),
                    array(
                        'filter' => 'StripTags'
                    )
                )
            )
        );

        $this->addElement(
            'textarea',
            'comment',
            array(
                'label' => '',
                'required' => true,
                'options' => array(
                    'rows' => 10,
                    'cols' => 80
                ),
                'validators' => array(
                    array(
                        'validator' => 'StringLength', 'options' => array(6)
                    )
                ),
                'filters' => array(
                    array(
                        'filter' => 'StringTrim',
                    ),
                    array(
                        'filter' => 'StripTags'
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
            'text',
            'datetime_added',
            array(
                'label' => 'Datum:',
                'required' => false,
                'filters' => array(
                    array(
                        'filter' => 'StringTrim',
                    ),
                    array(
                        'filter' => 'StripTags'
                    )
                )
            )
        );

        $this->addElement(
            'text',
            'honeypot',
            array(
                'label' => 'Honeypot:',
                'required' => false,
                'class' => 'honeypot',
                'decorators' => array('ViewHelper'),
                'validators' => array(
                    array(
                        'validator' => 'Honeypot'
                    )
                )
            )
        );

        $this->addElement(
            'text',
            'js_fill',
            array(
                'label' => 'JS fill:',
                'required' => true,
                'class' => 'honeypot',
                'decorators' => array('ViewHelper')
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
            'fk_news_id',
            array(
                'decorators' => $this->hiddenElementDecorators,
                'required' => true,
                'validators' => array(
                    array(
                        'validator' => 'Digits'
                    )
                ),
                'filters' => array(
                    array(
                        'filter' => 'StringTrim'
                    ),
                    array(
                        'filter' => 'StripTags'
                    )
                )
            )
        );

        $this->addSubmitAndResetButtons();

        $this->removeElement('reset');
        $this->getElement('submit')->setLabel('Ostavi komentar');
    }
}