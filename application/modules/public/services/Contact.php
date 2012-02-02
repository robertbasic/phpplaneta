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
        if(!empty($data)) {
            $this->setMailData($data);
        }

        $this->_setTransport();
    }

    public function setMailData($data)
    {
        if(!array_key_exists('name', $data) or $data['name'] == '') {
            throw new PPN_Exception_Runtime('No sender name provided');
        }

        if(!array_key_exists('email', $data) or $data['email'] == '') {
            throw new PPN_Exception_Runtime('No sender Email address provided');
        }

        if(!array_key_exists('subject', $data) or $data['subject'] == '') {
            throw new PPN_Exception_Runtime('No subject for Email provided');
        }

        if(!array_key_exists('message', $data) or $data['message'] == '') {
            throw new PPN_Exception_Runtime('No message for Email provided');
        }

        $this->_mailer = $this->_getMailer();
        
        $this->_mailer->setFrom('phpplaneta@gmail.com', 'PHPPlaneta kontakt');
        $this->_mailer->addTo('phpplaneta@gmail.com');

        $this->_mailer->setReplyTo($data['email'], $data['name']);

        $this->_mailer->setSubject($data['subject']);
        $this->_mailer->setBodyText($data['message']);
    }

    public function sendMail()
    {
        $this->_mailer = $this->_getMailer();
        try {
            $this->_mailer->send();
            return true;
        } catch (Exception $e) {
            throw new PPN_Exception_Runtime($e->getMessage(), $e->getCode());
        }
    }
    
    public function setMailer($mailer) {
        $this->_mailer = $mailer;
    }

    protected function _getMailer() {
        if($this->_mailer === null) {
            $this->_mailer = new Zend_Mail('utf-8');
        }
        
        return $this->_mailer;
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