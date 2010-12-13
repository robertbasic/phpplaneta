<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Add
 *
 * @author robert
 */
class Planet_Form_News_Comments_Edit extends Planet_Form_News_Comments
{
    
    public function init()
    {
        parent::init();

        $this->removeElement('honeypot');
        $this->removeElement('js_fill');
    }
}