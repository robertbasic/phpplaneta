<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Edit
 *
 * @author robert
 */
class Planet_Form_News_Edit extends Planet_Form_News
{
    public function init()
    {
        parent::init();

        $this->setAction('/admin/public/news/edit');
    }
}