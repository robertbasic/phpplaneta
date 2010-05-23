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
class Planet_Form_News_Comments_Add extends Planet_Form_News_Comments
{
    
    public function init()
    {
        parent::init();

        $this->removeElement('active');
        $this->removeElement('id');
        $this->removeElement('datetime_added');
    }
}