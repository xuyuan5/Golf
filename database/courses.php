<?php
/* 
 * @author Yuan Xu
*/

function get_courses_and_tees()
{
    $con = mysql_connect(SERVER_NAME, DBO_USER_NAME, DBO_PASSWORD);
    $all = array();
    
    if($con)
    {
        mysql_select_db(DATABASE_NAME, $con);
        $sql = "SELECT ID, Name, NineHole FROM Courses";
        $result = mysql_query($sql, $con);
        if(!$result)
        {
            ajax_error("error gathering all courses ".mysql_error());
        }
        while($row = mysql_fetch_array($result))
        {
            $course = array(
                        'name' => $row['Name'],
                        'id' => $row['ID'],
                        '9-holes' => $row['NineHole'],
                        'tees' => array());
            $sql = "SELECT ID, Name FROM Tees WHERE CourseID=".$row['ID'];
            $result_tee = mysql_query($sql, $con);
            if(!$result_tee)
            {
                ajax_error("error gathering tees information for course ".$row['Name']." ".mysql_error());
            }
            while($row_tee = mysql_fetch_array($result_tee))
            {
                $tee = array(
                            'name' => $row_tee['Name'],
                            'id' => $row_tee['ID']);
                array_push($course['tees'], $tee);
            }
            array_push($all, $course);
        }
        mysql_close($con);
    }
    
    return $all;
}

function get_courses()
{
    $con = mysql_connect(SERVER_NAME, DBO_USER_NAME, DBO_PASSWORD);
    $all_courses = array();
    
    if($con)
    {
        mysql_select_db(DATABASE_NAME, $con);
        $sql = "SELECT Name FROM Courses";
        $result = mysql_query($sql, $con);
        if(!$result)
        {
            ajax_error("error gathering all courses ".mysql_error());
        }
        while($row = mysql_fetch_array($result))
        {
            array_push($all_courses, $row['Name']);
        }
        mysql_close($con);
    }
    
    return $all_courses;
}

function get_course($course)
{
    if(!check_name($course))
    {
        ajax_error("course name is not of proper format: ".$course);
    }

    $con = mysql_connect(SERVER_NAME, DBO_USER_NAME, DBO_PASSWORD);
    $details = array();
    if($con)
    {
        mysql_select_db(DATABASE_NAME, $con);
        
        $course = mysql_real_escape_string($course);
        
        $sql = "SELECT * FROM Courses WHERE Name='".$course."'";
        $result = mysql_query($sql, $con);
        if(!$result)
        {
            ajax_error("error getting course details for course ".$course." ".mysql_error());
        }
        
        if($row = mysql_fetch_array($result))
        {
            $details['name'] = $row['Name'];
            $details['9-holes'] = $row['NineHole'];
        }
        
        // grab tees
        $sql = "SELECT * FROM Tees WHERE CourseID=".$row['ID']."";
        $result = mysql_query($sql, $con);
        if(!$result)
        {
            ajax_error("error getting tees details for course ".$course." ".mysql_error()." WITH SCRIPT ".$sql);
        }
        
        $tees = array();
        while($row = mysql_fetch_array($result))
        {
            $tees[$row['Name']] = array('name' => $row['Name'], 
                                        'slope' => $row['Slope'], 
                                        'rating' => $row['Rating'],
                                        'is-ladies' => $row['IsLadies'], 
                                        'par' => $row['Par'], 
                                        'handicap' => $row['Handicap']);
        }
        $details['tees'] = $tees;
        
        mysql_close($con);
    }
    
    return $details;
}

function remove_course($course)
{
    if(!check_name($course))
    {
        ajax_error("course name is not of proper format: ".$course);
    }

    $con = mysql_connect(SERVER_NAME, DBO_USER_NAME, DBO_PASSWORD);
    if($con)
    {
        mysql_select_db(DATABASE_NAME, $con);
        
        $course = mysql_real_escape_string($course);
        
        $sql = "DELETE FROM Courses WHERE Name='".$course."'";
        $result = mysql_query($sql, $con);
        if(!$result)
        {
            ajax_error("error removing queries with course ".$course." ".mysql_error());
        }
        // TODO: verify that CASCADE DELETE works for Tees table
        mysql_close($con);
    }
}

function insert_or_update_course($course, $is_9_hole)
{
    if(!check_name($course))
    {
        ajax_error("course name is not of proper format: ".$course);
    }

    if(!check_boolean($is_9_hole))
    {
        ajax_error("is_9_hole is not of boolean type: ".$is_9_hole);
    }

    $con = mysql_connect(SERVER_NAME, DBO_USER_NAME, DBO_PASSWORD);
    if($con)
    {
        mysql_select_db(DATABASE_NAME, $con);
        
        $course = mysql_real_escape_string($course);
        $is_9_hole = filter_var($is_9_hole, FILTER_VALIDATE_BOOLEAN);
        
        $sql = "INSERT INTO Courses 
        (Name, NineHole) VALUES('".$course."', ".$is_9_hole.") 
        ON DUPLICATE KEY UPDATE NineHole=".$is_9_hole;
        
        $result = mysql_query($sql, $con);
        if(!$result)
        {
            ajax_error("error inserting course ".mysql_error());
        }
        mysql_close($con);
    }
}

function insert_or_update_tee($course, $tee, $is_ladies, $slope, $rating, $par, $handicap)
{
    if(!check_name($course))
    {
        ajax_error("course name is not of proper format: ".$course);
    }
    if(!check_name($tee))
    {
        ajax_error("tee name is not of proper format: ".$tee);
    }
    if(!check_boolean($is_ladies))
    {
        ajax_error("is_ladies is not of boolean type: ".$is_ladies);
    }
    if(!check_int($slope))
    {
        ajax_error("slope is not of integer type: ".$slope);
    }
    if(!check_float($rating))
    {
        ajax_error("rating is not of float type: ".$rating);
    }
    if(!check_int_list($par, 3, 5))
    {
        ajax_error("par list is not of proper format or value: ".$par);
    }
    if(!check_int_list($handicap, 1, 18))
    {
        ajax_error("handicap list is not of proper format or value: ".$handicap);
    }

    $id = get_course_id($course);
    $con = mysql_connect(SERVER_NAME, DBO_USER_NAME, DBO_PASSWORD);
    if($con)
    {
        mysql_select_db(DATABASE_NAME, $con);
        
        $course = mysql_real_escape_string($course);
        
        $sql = "SELECT * FROM Tees WHERE CourseID='".$id."' AND Name='".$tee."'";
        $result = mysql_query($sql, $con);
        if(!$result)
        {
            ajax_error("error looking up tees table ".mysql_error());
        }
        if(mysql_num_rows($result) > 0)
        {
            $sql = "UPDATE Tees 
                    SET Slope=".$slope.", Rating=".$rating.", IsLadies=".$is_ladies.
                    ", Par='".$par."', Handicap='".$handicap."' WHERE CourseID='".
                    $id."' AND Name='".$tee."'"; 
        }
        else
        {
            $sql = "INSERT INTO Tees 
                    (CourseID, Name, Slope, Rating, IsLadies, Par, Handicap) 
                    VALUES('".$id."', '".$tee."', ".$slope.", ".$rating.", "
                    .$is_ladies.", '".$par."', '".$handicap."')"; 
        }
        
        $result = mysql_query($sql, $con);
        if(!$result)
        {
            ajax_error("error inserting tee ".mysql_error()." WITH SCRIPT ".$sql);
        }
        mysql_close($con);
    }
}

function update_tees($course, $tees)
{
    if(!check_name($course))
    {
        ajax_error("course name is not of proper format: ".$course);
    }
    if(!check_string_list($tees))
    {
        ajax_error("tees are not in proper comma separated format: ".$tees);
    }

    $id = get_course_id($course);
    $con = mysql_connect(SERVER_NAME, DBO_USER_NAME, DBO_PASSWORD);
    if($con)
    {
        $course = mysql_real_escape_string($course);
        $tees = mysql_real_escape_string($tees);
        
        $sql = "DELETE FROM Tees WHERE CourseID='".$id."' AND Name NOT IN ('".str_replace(",", "','", $tees)."')";
        $result = mysql_query($sql, $con);
        if(!$result)
        {
            ajax_error("error removing tees with course ".$course." ".mysql_error()."WITH SCRIPT:".$sql);
        }
        
        mysql_close($con);
    }
}

function get_course_id($course)
{
    if(!check_name($course))
    {
        ajax_error("course name is not of proper format: ".$course);
    }

    $con = mysql_connect(SERVER_NAME, DBO_USER_NAME, DBO_PASSWORD);
    if($con)
    {
        mysql_select_db(DATABASE_NAME, $con);
        
        $course = mysql_real_escape_string($course);
        
        $sql = "SELECT ID FROM Courses WHERE Name='".$course."'";
        $result = mysql_query($sql, $con);
        if(!$result)
        {
            ajax_error("error getting course ID for course ".$course." ".mysql_error());
        }
        
        if($row = mysql_fetch_array($result))
        {
            return $row['ID'];
        }
        else
        {
            ajax_error("error getting course ID for course ".$course." ".mysql_error());
        }
    }
}

?>
