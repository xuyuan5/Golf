<?php
/* 
 * @author Yuan Xu
*/

require_once('../helper/utilities.php');
require_once('../config.php');
require_once('../database/users.php');
require_once('../users/userConfig.php');

if(isset($_POST['action']))
{
    if($_POST['action'] == 'handicap-overall')
    {
        echo get_handicap_overall($loggedInUser->user_id);
    }
}
?>
