<?php

/**
 * Abstract model. All models extend this one
 *
 * @author robert
 */
class PPN_Model_Abstract
{
    /**
     * An array of resources
     *
     * @var array
     */
    protected $_resources = array();

    /**
     * An array of forms
     *
     * @var array
     */
    protected $_forms = array();

    public function __construct()
    {
    }

    /**
     * Get a model resource
     * $resourceName must be in format Foo_Bar
     *
     * @param string $resourceName the resource name
     * @return PPN_Model_Resource_Abstract
     */
    public function getResource($resourceName)
    {
        if(!isset($this->_resources[$resourceName])) {
            $resourceClass = $this->_getNamespace() . '_Model_Resource_' . $resourceName;
            $this->_resources[$resourceName] = new $resourceClass();
        }

        return $this->_resources[$resourceName];
    }

    /**
     * Get a form used for data validation/filtering in models
     * $resourceName must be in format Foo_Bar
     *
     * @param string $formName the form name
     * @param array $options additional options, models...
     * @return Zend_Form
     */
    public function getForm($formName)
    {
        if(!isset($this->_forms[$formName])) {
            $formClass = $this->_getNamespace() . '_Form_' . $formName;
            $this->_forms[$formName] = new $formClass($this);
        }

        return $this->_forms[$formName];
    }

    protected function _getNamespace()
    {
        $namespace = explode('_', get_class($this));
        return $namespace[0];
    }

    protected function _cleanFullPageCache()
    {
        try {
            $cache = Zend_Registry::get('pageCache');
            $cache->clean(Zend_Cache::CLEANING_MODE_ALL);
        } catch(Exception $e) {
            
        }
    }

}