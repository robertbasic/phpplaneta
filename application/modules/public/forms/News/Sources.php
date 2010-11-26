<?php

/**
 * Base form for adding/editing news
 *
 * @author robert
 * @todo tidy up validation and filtering
 */
class Planet_Form_News_Sources extends PPN_Form_Abstract
{

    public function init()
    {
        parent::init();

        $this->addElement(
            'text',
            'name',
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
            'url',
            array(
                'label' => 'URL:',
                'required' => true,
                'validators' => array(
                    array(
                        'validator' => 'StringLength', 'options' => array(3, 255),
                        'validator' => 'Hostname'
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
            'hidden',
            'id',
            array(
                'decorators' => $this->hiddenElementDecorators
            )
        );

        $this->addSubmitAndResetButtons();
    }
}