<?php
/* 
 * @author Yuan Xu
*/

require_once('../helper/utilities.php');
require_once('../config.php');
require_once('../database/courses.php');

if(isset($_GET['name']))
{
    echo json_encode(get_course($_GET['name']), JSON_NUMERIC_CHECK);
}
else if(isset($_GET['all']))
{
    echo json_encode(get_courses_and_tees(), JSON_NUMERIC_CHECK);
}
else
{
    $has_error = false;
    if(isset($_POST['action']))
    {
        if($_POST['action'] == 'delete')
        {
            remove_course($_POST['name']);
        }
        else if($_POST['action'] == 'update')
        {
            insert_or_update_course($_POST['name'], $_POST['9holes']);
            if(isset($_POST['tees']))
            {
                update_tees($_POST['name'], $_POST['tees']);
            }
        }
        else
        {
            $has_error = true;
        }
    }
    if(!$has_error)
    {
        echo json_encode(get_courses(), JSON_NUMERIC_CHECK);
    }
    else
    {
        print "please use proper interface to access this page.";
    }
}

?>
