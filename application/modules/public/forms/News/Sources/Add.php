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
class Planet_Form_News_Sources_Add extends Planet_Form_News_Sources
{
    
    public function init()
    {
        parent::init();

        $this->removeElement('id');
    }

}