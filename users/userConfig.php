<?php
	$dbtype = "mysql"; 
	$db_port = "3306";
	$emailActivation = false;
	$langauge = "en";
	$db_table_prefix = "";
	$debug_mode = true;
	
	require_once("db/".$dbtype.".php");
	$db = new $sql_db();
	if(is_array($db->sql_connect(
							SERVER_NAME, 
							DBO_USER_NAME,
							DBO_PASSWORD,
							DATABASE_NAME, 
							$db_port,
							false, 
							false
	))) {
		die("Unable to connect to the database");
	}
	
	if(!isset($language)) $langauge = "en";

	require_once("lang/".$langauge.".php");
	require_once("class.user.php");
	require_once("funcs.user.php");
	require_once("funcs.general.php");
	require_once("class.newuser.php");

	session_start();
	
    //log_message(print_r(debug_backtrace()));
	
    //Global User Object Var
	//loggedInUser can be used globally if constructed
	if(isset($_SESSION["userCakeUser"]) && is_object($_SESSION["userCakeUser"]))
	{
		$loggedInUser = $_SESSION["userCakeUser"];
	}

?>