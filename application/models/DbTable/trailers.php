<?php
class Model_DbTable_trailers extends Zend_Db_Table_Abstract
{
    protected $_name = 'trailers';    // database table name
    protected $_rowClass = 'Model_Rowset_trailers';   // row class for extending
    //protected $_dependentTables = array('Model_DbTable_VideoTag');  // videos depends on the many-to-many join table for tags

    //protected $_referenceMap = array(
    //    'User' => array(
    //        'columns' => 'user_id',  // the column in the 'videos' table which is used for the join
    //        'refTableClass' => 'users',  // the users table name
    //        'refColumns' => 'id' // the primary key of the users table
    //    )
    //);
}
?>
