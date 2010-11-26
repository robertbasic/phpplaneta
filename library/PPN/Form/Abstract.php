<?php

/**
 * Base class for all the forms
 *
 * @author robert
 */
class PPN_Form_Abstract extends Zend_Form
{
    public $elementDecorators = array(
        'ViewHelper',
        'Errors',
        'Label',
        array(
            'HtmlTag',
            array(
                'tag' => 'li'
            )
        )
    );

    public $fileDecorators = array(
        'File',
        'Errors',
        'Label',
        array(
            'HtmlTag',
            array(
                'tag' => 'li', 'class' => 'form_element'
            )
        )
    );

    public $buttonDecorators = array(
        'ViewHelper'
    );

    public $hiddenElementDecorators = array(
        'ViewHelper'
    );

    protected $_model = null;

    public function __construct($model=null)
    {
        if($model !== null) {
            $this->setModel($model);
        }

        parent::__construct();
    }

    public function init()
    {
        $this->addElementPrefixPath(
            'PPN_Filter',
            realpath(APPLICATION_PATH . '/../library/PPN/Filter'),
            'filter'
        );

        $this->addElementPrefixPath(
            'PPN_Validate',
            realpath(APPLICATION_PATH . '/../library/PPN/Validate'),
            'validate'
        );

        /*$this->addPrefixPath(
            'DBS_Form_Element',
            realpath(APPLICATION_PATH . '/../library/DBS/Form/Element'),
            'element'
        );*/

        $this->setMethod('post');
        
        $this->setDecorators(
            array(
                'FormElements',
                array(
                    'HtmlTag',
                    array(
                        'tag' => 'ul', 'class' => 'form_ul'
                    )
                ),
                'Form'
            )
        );

        $this->setElementDecorators($this->elementDecorators);
    }

    public function addSubmitAndResetButtons()
    {
        $this->addElement(
            'submit',
            'submit',
            array(
                'label' => 'Submit',
                'ignore' => true,
                'decorators' => $this->buttonDecorators,
                'class' => 'submit_button'
            )
        );

        $this->addElement(
            'reset',
            'reset',
            array(
                'label' => 'Poništi',
                'ignore' => true,
                'decorators' => $this->buttonDecorators,
                'class' => 'reset_button'
            )
        );

        $this->addElement(
            'hash',
            'csrf',
            array(
                'ignore' => false,
                'decorators' => $this->buttonDecorators,
                'salt' => 'unique'
            )
        );

        $this->addDisplayGroup(
            array(
                'submit', 'reset', 'csrf'
            ),
            'buttons'
        );

        $this->setDisplayGroupDecorators(array(
            'FormElements',
            array(
                'HtmlTag',
                array(
                    'tag' => 'li'
                )
            )
        ));
    }

    public function setModel($model)
    {
        $this->_model = $model;
    }

    public function getModel()
    {
        return $this->_model;
    }

}