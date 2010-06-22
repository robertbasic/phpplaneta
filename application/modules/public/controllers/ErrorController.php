<?php

class ErrorController extends Zend_Controller_Action
{

    public function errorAction()
    {
        $this->_cancelFullPageCache();

        $errors = $this->_getParam('error_handler');

        $errorCode = $errors->exception->getCode();

        if($errorCode == 404) {
            $this->_setParam('error_handler', $errors);
            return $this->_forward('not-found');
        }

        switch (get_class($errors->exception)) {
            case 'Zend_Controller_Dispatcher_Exception':
                $this->_setParam('error_handler', $errors);
                return $this->_forward('not-found');
                break;
            default:
                $this->_logError($errors, 2);
                // application error
                $stackTrace = $errors->exception->getTraceAsString();
                $message = $errors->exception->getMessage();
                $this->view->message = 'Došlo je do greške.';
                break;
        }

        $this->view->exception = $errors->exception;
        $this->view->request   = $errors->request;
    }

    public function notFoundAction()
    {        
        $errors = $this->_getParam('error_handler');

        $this->_logError($errors);

        $this->getResponse()
             ->setRawHeader('HTTP/1.1 404 Not Found');
        $this->view->message = $errors->exception->getMessage();
    }

    protected function _logError($errors, $level=3)
    {
        try {
            $logger = Zend_Registry::get('logger');
            $logger->log(
                    $errors->exception->getMessage() . "\n" .
                    $errors->exception->getTraceAsString() . "\n" .
                    var_export($errors->request->getParams(), true),
                    $level);
        } catch (Exception $e) {
        }
    }

    protected function _cancelFullPageCache()
    {
        try {
            $cache = Zend_Registry::get('pageCache');
            $cache->cancel();
        } catch(Exception $e) {
        }
    }

}

