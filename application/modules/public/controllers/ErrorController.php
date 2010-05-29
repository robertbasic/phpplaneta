<?php

class ErrorController extends Zend_Controller_Action
{

    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');

        $errorCode = $errors->exception->getCode();

        if($errorCode == 404) {
            $this->_setParam('error_handler', $errors);
            return $this->_forward('not-found');
        }

        switch (get_class($errors->exception)) {
            case 'Zend_Controller_Dispatcher_Exception':
                // send 404
                $this->getResponse()
                     ->setRawHeader('HTTP/1.1 404 Not Found');
                $this->view->message = 'Tražena stranica nije pronađena.';
                break;
            default:
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

        $this->getResponse()
             ->setRawHeader('HTTP/1.1 404 Not Found');
        $this->view->message = $errors->exception->getMessage();
    }

}

