<?php
$result = false;
if($_SERVER['REQUEST_METHOD'] == 'GET')
{
    if(isset($_GET['action']) && $_GET['action'] == 'upgrade' && isset($_GET['from_version']))
    {
        $from_version = $_GET['from_version'];
        if(file_exists('../config.php'))
        {
            require_once('../config.php');
            require_once('../database/actions.php');
            $result = upgrade_db($from_version);
    
            if($result)
            {
                $file = fopen('version.php', 'w');
                fprintf($file, "<?php \n");
                fprintf($file, "define(\"VERSION\", %s);\n", LATEST_VERSION);
                fprintf($file, "?> \n");
                fclose($file);
            }
        }
    }
}
if($result)
{
    echo 'Upgrade Successful.';
}
else
{
    echo 'Upgrade Failed.';
}
?>