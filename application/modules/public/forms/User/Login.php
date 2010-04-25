<?php

/**
 * Login form for users
 *
 * @author robert
 */
class Planet_Form_User_Login extends PPN_Form_Abstract
{
    public function init()
    {
        parent::init();

        $this->setAction('/user/login');

        $this->addElement(
            'text',
            'email',
            array(
                'label' => 'E-mail:',
                'required' => true,
                'validators' => array(
                    array(
                        'validator' => 'EmailAddress'
                    )
                )
            )
        );

        $this->addElement(
            'password',
            'password',
            array(
                'label' => 'Password:',
                'required' => true,
                'validators' => array(
                    array(
                        'validator' => 'StringLength', 'options' => array(4, 12)
                    )
                )
            )
        );

        $this->addSubmitAndResetButtons();

        $this->getElement('submit')->setLabel('Login');

    }
}