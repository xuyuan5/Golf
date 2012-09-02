<?php
/* 
 * @author Yuan Xu
*/

error_reporting(E_ALL);
//error_reporting(E_ERROR);
ini_set('display_errors', true);

if(!date_default_timezone_set("America/Toronto"))
{
	// TODO-L: print error
}

define("LATEST_VERSION", 0.5);

require_once('../template/empty_header.php');
if(file_exists('../config.php'))
{
    if(isset($_GET['action']) && $_GET['action'] == 'recreate')
    {
        require_once('content.php');
    }
    else if(isset($_GET['action']) && $_GET['action'] == 'upgrade' && isset($_GET['from_version']))
    {
        require_once('upgrade.php');
    }
    else
    {
        $from_version = 0.1;
        if(file_exists('version.php'))
        {
            require_once('version.php');
            if(defined('VERSION'))
            {
                $from_version=VERSION;
            }
        }
        // TODO-M: if $from_version is the same as current version, don't provide option to upgrade
?>
        <p>Installation is already done! 
<?php
        if(VERSION != LATEST_VERSION)
        {
?>
            You could either <a href='?action=recreate'>recreate database</a> or <a href=<?php echo "'?action=upgrade&from_version=".$from_version."'"; ?>>upgrade</a>
<?php
        }
        else
        {
?>
            You could <a href='?action=recreate'>recreate the database</a>.
<?php
        }
?>
        </p>
<?php
    }
} else {
	require_once('content.php');
}
require_once('../template/footer.php');
?>
