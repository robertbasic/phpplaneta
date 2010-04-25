<?php

/**
 * Model resource for news
 * table news
 *
 * @author robert
 */
class Planet_Model_Resource_User extends PPN_Model_Resource_Abstract
{
    /**
     * Name of the table
     * gets overwritten in PPN_Model_Resource_Abstract::init()
     * by adding the prefix
     *
     * @var string
     */
    protected $_name = 'users';

    /**
     *
     * @var Zend_Db_Table_Row_Abstract
     */
    protected $_rowClass = 'Planet_Model_Resource_User_Item';

    public function getUserByEmail($email)
    {
        $email = (string)$email;
        $select = $this->_getAllUsersSelect(
                    array(
                        array('users.email = ?', $email)
                    )
                );

        return $this->fetchRow($select);
    }

    /**
     * Build the select object for news
     * optionally pass in an array for the where part
     * without the where it will return all the news
     *
     * @param array $where
     * @return Zend_Db_Select
     */
    protected function _getAllUsersSelect($where=null)
    {
        $select = $this->select();
        $select->setIntegrityCheck(false);

        $select->from(
                    array(
                        'users' => $this->_name
                    ),
                    array(
                        'id', 'email', 'firstname', 'lastname', 'datetime_added',
                            'role', 'active'
                    )
                );

        if($where !== null and is_array($where)) {
            foreach($where as $w) {
                $select->where($w[0], $w[1]);
            }
        }

        $select->order('users.datetime_added DESC');

        return $select;
    }
}