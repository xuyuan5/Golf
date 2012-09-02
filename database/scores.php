<?php
/* 
 * @author Yuan Xu
*/

function get_scores($userid)
{
    if(!check_int($userid))
    {
        ajax_error("userid is not of integer type: ".$userid);
    }

    $con = mysql_connect(SERVER_NAME, DBO_USER_NAME, DBO_PASSWORD);
    $all_scores = array();
    
    if($con)
    {
        mysql_select_db(DATABASE_NAME, $con);
        $sql = "SELECT * FROM Scores WHERE UserID=".$userid;
        $result = mysql_query($sql, $con);
        if(!$result)
        {
            ajax_error("error gathering all scores ".mysql_error());
        }
        while($row = mysql_fetch_array($result))
        {
            $sql = "SELECT Name FROM Courses WHERE ID=".$row['CourseID'];
            $result_course = mysql_query($sql, $con);
            if(!$result_course)
            {
                ajax_error("error getting course information for course id: ".$row['CourseID']." ".mysql_error());
            }
            if($row_course = mysql_fetch_array($result_course))
            {
                array_push($all_scores, array(
                                            'id' => $row['ID'],
                                            'name' => $row_course['Name'],
                                            'date' => $row['Date'],
                                            'score' => $row['Score']));
            }
            else
            {
                ajax_error("related course info could not be found for course id: ".$row['CourseID']);
            }
        }
        mysql_close($con);
    }
    
    return $all_scores;
}

function remove_score($id)
{
    if(!check_int($id))
    {
        ajax_error("id is not of integer type: ".$id);
    }

    $con = mysql_connect(SERVER_NAME, DBO_USER_NAME, DBO_PASSWORD);
    
    if($con)
    {
        mysql_select_db(DATABASE_NAME, $con);
        $sql = "DELETE FROM Scores WHERE ID=".$id;
        $result = mysql_query($sql, $con);
        if(!$result)
        {
            ajax_error("error deleting score ".mysql_error());
        }
    }
}

function add_score($courseID, $teeID, $userID, $date, $score)
{
    if(!check_int($courseID))
    {
        ajax_error("courseID is not of integer type: ".$courseID);
    }
    if(!check_int($teeID))
    {
        ajax_error("teeID is not of integer type: ".$teeID);
    }
    if(!check_int($userID))
    {
        ajax_error("userID is not of integer type: ".$userID);
    }
    if(!check_date($date))
    {
        ajax_error("date is not of propery date type: ".$date);
    }
    if(!check_int_list($score))
    {
        ajax_error("score is not a list of integers: ".$score);
    }

    $reply = "";
    $con = mysql_connect(SERVER_NAME, DBO_USER_NAME, DBO_PASSWORD);
    if($con)
    {
        mysql_select_db(DATABASE_NAME, $con);
        
        $handicap_differential = compute_handicap_differential_helper($teeID, $score, $con);
        
        $sql = "INSERT INTO Scores 
                (CourseID, TeeID, UserID, Date, Score, HandicapDifferential) VALUES('".
                $courseID."', '".$teeID."', '".$userID."', '".$date."', '".$score."', '".$handicap_differential."')";
        
        $result = mysql_query($sql, $con);
        if(!$result)
        {
            ajax_error("error inserting score ".mysql_error());
        }
        $id = mysql_insert_id();
        $sql = "SELECT * FROM Scores WHERE ID='".$id."'";
        $result = mysql_query($sql, $con);
        if(!$result)
        {
            ajax_error("Something unthinkable happened ".mysql_error());
        }
        if($row = mysql_fetch_array($result))
        {
            $sql = "SELECT Name FROM Courses WHERE ID=".$row['CourseID'];
            $result_course = mysql_query($sql, $con);
            if(!$result_course)
            {
                ajax_error("error getting course information for course id: ".$row['CourseID']." ".mysql_error());
            }
            if($row_course = mysql_fetch_array($result_course))
            {
                $reply = array( 'id' => $row['ID'],
                                'name' => $row_course['Name'],
                                'date' => $row['Date'],
                                'score' => $row['Score']);
            }
            else
            {
                ajax_error("related course info could not be found for course id: ".$row['CourseID']);
            }
        }
        mysql_close($con);
    }
    return $reply;
}

function update_score($scoreID, $score)
{
    if(!check_int($scoreID))
    {
        ajax_error("scoreID is not of integer type: ".$scoreID);
    }
    if(!check_int_list($score))
    {
        ajax_error("score is not a list of integers: ".$score);
    }

    $con = mysql_connect(SERVER_NAME, DBO_USER_NAME, DBO_PASSWORD);
    if($con)
    {
        mysql_select_db(DATABASE_NAME, $con);
        
        $teeID = get_tee_id_helper($scoreID);
        $handicap_differential = compute_handicap_differential_helper($teeID, $score, $con);
        
        $sql = "UPDATE Scores SET Score='".$score."', HandicapDifferential='".$handicap_differential."' WHERE ID='".$scoreID."'";
        
        $result = mysql_query($sql, $con);
        if(!$result)
        {
            ajax_error("error updating course ".mysql_error());
        }
        mysql_close($con);
    }
}

function get_tee_id_helper($scoreID, $con)
{
    $sql = "SELECT TeeID FROM Scores WHERE ID='".$scoreID."'";
    $result = mysql_query($sql, $con);
    if(!$result)
    {
        ajax_error("error selecting from Scores using id: ".$scoreID." ".mysql_error());
    }
    if($row = mysql_fetch_array($result))
    {
        return $row['TeeID'];
    }
    else
    {
        ajax_error("unable to retrieve Scores using id: ".$scoreID);
    }
}

function compute_handicap_differential_helper($teeID, $score, $con)
{
    $sql = "SELECT Slope, Rating FROM Tees WHERE ID='".$teeID."'";
    $result = mysql_query($sql, $con);
    $slope = 0;
    $rating = 0;
    if(!$result)
    {
        ajax_error("error selecting slope/rating from Tees using id: ".$teeID." ".mysql_error());
    }
    if($row = mysql_fetch_array($result))
    {
        $slope = $row['Slope'];
        $rating = $row['Rating'];
    }
    else
    {
        ajax_error("unable to retrieve Tees using id: ".$teeID);
    }
    
    $scores = explode(',', $score);
    $total = count($scores);
    if($total == 9 || $total == 18)
    {
        $total_score = array_sum($scores);
        return ($total_score - $rating) * 113/ $slope;
    }
    else
    {
        ajax_error("score count is wrong! Some score's missing");
    }
}

?>
