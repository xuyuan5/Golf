<?php
/* 
 * @author Yuan Xu
*/

require_once('common_header.php');

function ajax_error($error_message)
{
    header('HTTP/1.1 500 Internal Server Error');
    print $error_message;
    exit();
}

function log_message($message)
{
    $file_feed = fopen(__DIR__."/../log", "a");
    $str = "[" . date("Y/m/d h:i:s", mktime()) . "] " . $message;
    fwrite($file_feed, $str."\n");
    fclose($file_feed);
}

function check_name($string_value)
{
    log_message("Checking String \"".$string_value."\"");
    return preg_match("/^[[:alpha:]]+( [[:alpha:]]+)*$/", $string_value);
}

function check_date($date_value)
{
    log_message("Checking Date \"".$date_value."\"");
    return preg_match("/^\d\d\d\d-\d\d-\d\d$/", $date_value);
}

function check_email($email_value)
{
    log_message("Checking Email \"".$email_value."\"");
    return preg_match("/^[a-zA-Z0-9][a-zA-Z0-9\.-_]*@([a-zA-Z0-9-_]+[\.])+[a-zA-Z]{2,}$/", $email_value);
}

function check_boolean($boolean_value)
{
    log_message("Checking Boolean \"".$boolean_value."\"");
    //return filter_var($boolean_value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) !== NULL;
    return is_bool($boolean_value);
}

function check_string_list($list_value)
{
    log_message("Checking String List \"".$list_value."\"");
    $list_array = explode(',', $list_value);
    foreach($list_array as $list_item)
    {
        if(!check_name($list_item))
        {
            return false;
        }
    }
    return true;
}

function check_float($float_value)
{
    log_message("Checking Float \"".$float_value."\"");
    if(is_numeric($float_value))
    {
        return is_float(floatval($float_value));
    }
    return false;
}

function check_int($int_value)
{
    log_message("Checking Int \"".$int_value."\"");
    if(is_numeric($int_value))
    {
        return is_int(intval($int_value));
    }
    return false;
}

function check_int_list($list_value, $min_value=0, $max_value=-1)
{
    log_message("Checking Integer List \"".$list_value."\"");

    $should_check_range = $max_value > $min_value;
    $list_array = explode(',', $list_value);
    $int_value = 0;
    foreach($list_array as $list_item)
    {
        if(!check_int($list_item))
        {
            return false;
        }
        if($should_check_range)
        {
            $int_value = intval($list_item);
            if($int_value < $min_value || $int_value > $max_value)
            {
                return false;
            }
        }
    }
    return true;
}

?>
