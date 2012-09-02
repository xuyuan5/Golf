<?php
error_reporting(E_ALL);
//error_reporting(E_ERROR);
ini_set('log_errors', true);
ini_set('error_log', __DIR__.'/../error.log');

if(!date_default_timezone_set("America/Toronto"))
{
	// TODO-L: print error
}
?>
