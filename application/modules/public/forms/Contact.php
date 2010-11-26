<?php

/**
 * Base form for adding/editing news
 *
 * @author robert
 * @todo tidy up validation and filtering
 */
class Planet_Form_Contact extends PPN_Form_Abstract
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
            'subject',
            array(
                'label' => 'Tema',
                'required' => true,
                'validators' => array(
                    array(
                        'validator' => 'StringLength', 'options' => array(3, 50)
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
            'textarea',
            'message',
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
            'text',
            'honeypot',
            array(
                'label' => 'Honeypot',
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

        $this->addSubmitAndResetButtons();

        $this->removeElement('reset');
        $this->getElement('submit')->setLabel('Pošalji');
    }
}