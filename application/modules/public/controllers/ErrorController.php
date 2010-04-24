<?php

class ErrorController extends Zend_Controller_Action
{

    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');

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


}

