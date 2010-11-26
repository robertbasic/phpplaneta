<?php

/**
 * Description of Tags
 *
 * @author robert
 * @todo tidy up validation and filtering
 */
class Planet_Form_News_Tags extends PPN_Form_Abstract
{
    public function init()
    {
        parent::init();

        $this->setName('tagsform');

        $this->addElement(
            'text',
            'tags',
            array(
                'label' => 'Oznake:',
                'required' => false,
                'validators' => array(
                    array(
                        'validator' => 'StringLength', 'options' => array(3, 255)
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

        $this->addSubmitAndResetButtons();
        $this->removeElement('reset');
        $this->removeElement('csrf');

    }
}