<?php
/* 
 * @author Yuan Xu
*/

function get_handicap_overall($userid)
{
    if(!check_int($userid))
    {
        ajax_error("userid is not of integer type: ".$userid);
    }

    $con = mysql_connect(SERVER_NAME, DBO_USER_NAME, DBO_PASSWORD);
    $score = 100;
    if($con)
    {
        mysql_select_db(DATABASE_NAME, $con);
        $sql = "SELECT * FROM (SELECT HandicapDifferential FROM Scores WHERE 
                UserID='".$userid."' ORDER BY Date DESC LIMIT 20) AS HD ORDER BY HandicapDifferential";
        $result = mysql_query($sql, $con);
        if(!$result)
        {
            ajax_error("error getting handicap differential from scores table using user: ".$userid." ".mysql_error());
        }
        $score = compute_handicap_helper($result, mysql_num_rows($result));
        mysql_close($con);
    }
    return "Handicap: ".$score;
}

function compute_handicap_helper($result, $total_rows)
{
    if($total_rows == 0)
    {
        return 100;
    }
    $total = 0.0;
    $rows_to_use = 1;
    if($total_rows <= 6)
    {
        $rows_to_use = 1;
    }
    else if($total_rows <= 8)
    {
        $rows_to_use = 2;
    }
    else if($total_rows <= 10)
    {
        $rows_to_use = 3;
    }
    else if($total_rows <= 12)
    {
        $rows_to_use = 4;
    }
    else if($total_rows <= 14)
    {
        $rows_to_use = 5;
    }
    else if($total_rows <= 16)
    {
        $rows_to_use = 6;
    }
    else if($total_rows == 17)
    {
        $rows_to_use = 7;
    }
    else if($total_rows == 18)
    {
        $rows_to_use = 8;
    }
    else if($total_rows == 19)
    {
        $rows_to_use = 9;
    }
    else
    {
        $rows_to_use = 10;
    }
    
    for($i = 0; $i < $rows_to_use; $i++)
    {
        $row = mysql_fetch_array($result);
        $total += $row['HandicapDifferential'];
    }
    return 0.96 * $total / $rows_to_use;
}

function get_user($userid)
{
    if(!check_int($userid))
    {
        ajax_error("userid is not of integer type: ".$userid);
    }

    $con = mysql_connect(SERVER_NAME, DBO_USER_NAME, DBO_PASSWORD);
    $details = array();
    if($con)
    {
        mysql_select_db(DATABASE_NAME, $con);
        $sql = "SELECT * FROM Users WHERE ID='".$userid."'";
        $result = mysql_query($sql, $con);
        if(!$result)
        {
            ajax_error("error getting user details for user ".$userid." ".mysql_error());
        }
        
        if($row = mysql_fetch_array($result))
        {
            $details['email'] = $row['Email'];
        }
        
        mysql_close($con);
    }
    
    return $details;
}

function get_user_id($email)
{
    if(!check_email($email))
    {
        ajax_error("email is not of email type: ".$email);
    }

    $con = mysql_connect(SERVER_NAME, DBO_USER_NAME, DBO_PASSWORD);
    $userid = -1;
    if($con)
    {
        mysql_select_db(DATABASE_NAME, $con);
        $sql = "SELECT * FROM Users WHERE Email='".$email."'";
        $result = mysql_query($sql, $con);
        if(!$result)
        {
            ajax_error("error getting user details for user ".$userid." ".mysql_error());
        }
        
        if($row = mysql_fetch_array($result))
        {
            $userid = $row['ID'];
        }
        
        mysql_close($con);
    }
    return $userid;
}

function create_user($email)
{
    if(!check_email($email))
    {
        ajax_error("email is not of email type: ".$email);
    }

    $con = mysql_connect(SERVER_NAME, DBO_USER_NAME, DBO_PASSWORD);
    if($con)
    {
        mysql_select_db(DATABASE_NAME, $con);
        
        $sql = "INSERT INTO Users (Email) VALUES('".$email."')";
        
        $result = mysql_query($sql, $con);
        if(!$result)
        {
            ajax_error("error creating user ".mysql_error());
        }
        mysql_close($con);
    }
}

function update_user($userid, $email)
{
    if(!check_email($email))
    {
        ajax_error("email is not of email type: ".$email);
    }
    if(!check_int($userid))
    {
        ajax_error("userid is not of integer type: ".$userid);
    }

    $con = mysql_connect(SERVER_NAME, DBO_USER_NAME, DBO_PASSWORD);
    if($con)
    {
        mysql_select_db(DATABASE_NAME, $con);
        
        $sql = "UPDATE Users SET Email='".$email."' WHERE ID='".$userid."'";
        
        $result = mysql_query($sql, $con);
        if(!$result)
        {
            ajax_error("error updating user ".mysql_error());
        }
        mysql_close($con);
    }
}

?>
