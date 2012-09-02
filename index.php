<?php
/* 
 * @author Yuan Xu
*/

require_once('helper/utilities.php');

$logged_in = false;

if(!file_exists('config.php'))
{
require_once('template/empty_header.php');
?>
	<h1>Please run <a href="
	<?php 
		$pageURL = 'http';
		if(array_key_exists('HTTPS', $_SERVER) && $_SERVER['HTTPS'] == 'on') { $pageURL .= 's'; }
		$pageURL .='://';
		if($_SERVER['SERVER_PORT'] != '80') {
			$pageURL .= $_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];
		} else {
			$pageURL .= $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		}
		echo substr($pageURL, 0, strrpos($pageURL, '/')+1); 
	?>
	install">Install</a> before visiting again.</h1>
<?php
}
else
{
    require_once('template/header.php');
	require_once('users/userConfig.php');
    if($_SERVER['REQUEST_METHOD'] == 'GET')
    {
        if(isUserLoggedIn())
        {
            $logged_in = true;
            require_once('index_user.php');
        }
        else
        {
            require_once('index_guest.php');
        }
    }
    else
    {
        // TODO-L: error
    }
?>
<!-- Included JS Files -->
<script src="foundation3/javascripts/jquery.min.js"></script>
<script src="foundation3/javascripts/jquery.reveal.js"></script>
<script src="foundation3/javascripts/jquery.orbit-1.4.0.js"></script>
<script src="foundation3/javascripts/jquery.customforms.js"></script>
<script src="foundation3/javascripts/jquery.placeholder.min.js"></script>
<script src="foundation3/javascripts/jquery.tooltips.js"></script>
<script src="foundation3/javascripts/app.js"></script>
<script src='js/jquery-ui-1.8.16.custom.min.js'></script>
<script src='js/courses.js'></script>
<script src='js/main.js'></script>
<script src='js/login.js'></script>
<?php
    if($logged_in)
    {
?>
<script src='js/scores.js'></script>
<script src='js/user.js'></script>
<?php
    }
    require_once('template/footer.php');
}
?>
