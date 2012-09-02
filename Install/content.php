<?php
if($_SERVER['REQUEST_METHOD'] == 'GET')
{
    if(file_exists('../config.php'))
    {
        // this is essentially hard upgrade script
        require_once('../config.php');
        require_once('../database/actions.php');
        setup_db(DBO_USER_NAME, DBO_PASSWORD, false); // don't create db
    }
    else
    {
        // TODO: form validation
        // TODO: password matching
?>
        <form name="installation" action="" method="post">
            <table>
                <tr>
                    <td>
                        Server: 
                    </td>
                    <td>
                        <input type="text" name="servername" />
                    </td>
                </tr>
                <tr>
                    <td>
                        System Username: 
                    </td>
                    <td>
                        <input type="text" name="sysusername" />
                    </td>
                </tr>
                <tr>
                    <td>
                        System Password: 
                    </td>
                    <td>
                        <input type="password" name="syspassword" />
                    </td>
                </tr>
                <tr>
                    <td>
                        DB Username: 
                    </td>
                    <td>
                        <input type="text" name="dbusername" />
                    </td>
                </tr>
                <tr>
                    <td>
                        DB Password: 
                    </td>
                    <td>
                        <input type="password" name="dbpassword" />
                    </td>
                </tr>
                <tr>
                    <td>
                        Confirm DB Password: 
                    </td>
                    <td>
                        <input type="password" name="cdbpassword" />
                    </td>
                </tr>
                <tr>
                    <td>
                        Database Name: 
                    </td>
                    <td>
                        <input type="text" name="databasename" />
                    </td>
                </tr>
                <tr>
                    <td>
                    </td>
                    <td>
                        <input type="submit" value="Submit" />
                    </td>
                </tr>
            </table>
        </form>
<?php
    }
}
else
{
	// TODO-L: figure out security later
	// this is post, we should process the submission
	$serverName = $_POST['servername'];
	$sysUserName = $_POST['sysusername'];
	$sysPassword = $_POST['syspassword'];
	$dbUserName = $_POST['dbusername'];
	$dbPassword = $_POST['dbpassword'];
	$dbName = $_POST['databasename'];
	
	// TODO: form validation
	
	$file = fopen('../config.php', 'w');
	fprintf($file, "<?php \n");
	fprintf($file, "define(\"SERVER_NAME\", \"%s\");\n", $serverName);
	fprintf($file, "define(\"DBO_USER_NAME\", \"%s\");\n", $dbUserName);
	fprintf($file, "define(\"DBO_PASSWORD\", \"%s\");\n", $dbPassword);
	fprintf($file, "define(\"DATABASE_NAME\", \"%s\");\n", $dbName);
	fprintf($file, "?> \n");
	fclose($file);
    
	$file = fopen('version.php', 'w');
	fprintf($file, "<?php \n");
    fprintf($file, "define(\"VERSION\", %s);\n", LATEST_VERSION);
	fprintf($file, "?> \n");
	fclose($file);
	
	require_once('../config.php');
	require_once('../database/actions.php');
	
	// check username password
	if(!setup_db($sysUserName, $sysPassword))
	{
		unlink('../config.php');
        unlink('../version.php');
		print "Unable to connect to database with credentials provided. Please try again.";
	}
}
?>