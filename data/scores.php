<?php
/* 
 * @author Yuan Xu
*/

require_once('../helper/utilities.php');
require_once('../config.php');
require_once('../database/scores.php');
require_once('../users/userConfig.php');

if($_SERVER['REQUEST_METHOD'] == 'GET')
{
    echo json_encode(get_scores($loggedInUser->user_id), JSON_NUMERIC_CHECK);
}
else
{
    $has_error = false;
    if(isset($_POST['action']))
    {
        if($_POST['action'] == 'delete' && isset($_POST['id']))
        {
            remove_score($_POST['id']);
        }
        else if($_POST['action'] == 'update' && isset($_POST['id']) && isset($_POST['score']))
        {
            update_score($_POST['id'], $_POST['score']);
        }
        else if($_POST['action'] == 'add' && isset($_POST['courseID']) && isset($_POST['teeID']) && isset($_POST['date']) && isset($_POST['score']))
        {
            echo json_encode(add_score($_POST['courseID'], $_POST['teeID'], $loggedInUser->user_id, $_POST['date'], $_POST['score']), JSON_NUMERIC_CHECK);
        }
        else
        {
            $has_error = true;
        }
    }
    if($has_error)
    {
        print "please use proper interface to access this page.";
    }
}

?>
