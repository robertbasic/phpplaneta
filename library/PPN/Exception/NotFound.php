<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NotFound
 *
 *
 * @author robert
 */
class PPN_Exception_NotFound extends PPN_Exception
{
    const NO_SUCH_CATEGORY = 'Tražena kategorija %s nije pronađena.';

    const NO_SUCH_TAG = 'Oznaka %s ne postoji.';

    const NO_SUCH_NEWS = 'Tražena vest %s nije pronađena.';

    protected $code = 404;
}