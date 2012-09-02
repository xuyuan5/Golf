<?php
/* 
 * @author Yuan Xu
*/

require_once('../helper/utilities.php');
require_once('../config.php');
require_once('../database/courses.php');

if(isset($_POST['action']))
{
    if($_POST['action'] == 'update')
    {
        insert_or_update_tee($_POST['courseName'], $_POST['name'], $_POST['isLadies'], 
                             $_POST['slope'], $_POST['rating'], $_POST['par'], $_POST['handicap']);
    }
}

?>
