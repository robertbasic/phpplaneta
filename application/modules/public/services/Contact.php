<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Contact
 *
 * @author robert
 */
class Planet_Service_Contact
{
    protected $_mailer = null;

    protected $_mailTransport = null;

    public function __construct($data=array())
    {
        $this->_mailer = new Zend_Mail('utf-8');

        if(!empty($data)) {
            $this->setMailData($data);
        }

        $this->_setTransport();
    }

    public function setMailData($data)
    {
        if(!array_key_exists('name', $data)) {
            throw new Exception('No sender name provided');
        }

        if(!array_key_exists('email', $data)) {
            throw new Exception('No sender Email address provided');
        }

        if(!array_key_exists('subject', $data)) {
            throw new Exception('No subject for Email provided');
        }

        if(!array_key_exists('message', $data)) {
            throw new Exception('No message for Email provided');
        }

        $this->_mailer->setFrom('phpplaneta@gmail.com', 'PHPPlaneta kontakt');
        $this->_mailer->addTo('phpplaneta@gmail.com');

        $this->_mailer->setReplyTo($data['email'], $data['name']);

        $this->_mailer->setSubject($data['subject']);
        $this->_mailer->setBodyText($data['message']);
    }

    public function sendMail()
    {
        try {
            $this->_mailer->send();
            return true;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    protected function _setTransport()
    {
        if($this->_mailTransport === null) {
            $config = array(
                'auth' => 'login',
                'username' => 'phpplaneta@gmail.com',
                'password' => 'password (not really)',
                'ssl' => 'tls',
                'port' => 587
            );

            $this->_mailTransport = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);

            Zend_Mail::setDefaultTransport($this->_mailTransport);
        }
    }

}