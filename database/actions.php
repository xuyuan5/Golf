<?php
function upgrade_db($from_version)
{
    $result = false;
    $con = mysql_connect(SERVER_NAME, DBO_USER_NAME, DBO_PASSWORD);
    if($con)
    {
        mysql_select_db(DATABASE_NAME, $con);
        $result = upgrade($from_version, $con);
        mysql_close($con);
    }
    return $result;
}

function upgrade($from_version, &$con)
{
    $result = false;
    if($from_version == LATEST_VERSION)
    {
        return true;
    }
    if($from_version == 0.1)
    {
        return upgrade_to_0_2($con)?upgrade(0.2, $con):false;
    }
    if($from_version == 0.2)
    {
        return upgrade_to_0_3($con)?upgrade(0.3, $con):false;
    }
    if($from_version == 0.3)
    {
        return upgrade_to_0_4($con)?upgrade(0.4, $con):false;
    }
	if($from_version == 0.4)
	{
		return upgrade_to_0_5($con)?upgrade(0.5, $con):false;
	}
    return $result;
}

function upgrade_to_0_2(&$con)
{
    $sql = "CREATE TABLE Users
    (
    ID int NOT NULL auto_increment,
    Email varchar(100) NOT NULL,
    PRIMARY KEY (ID),
    UNIQUE (Email)
    )";
    $result = mysql_query($sql, $con);
    if(!$result)
    {
        echo "error creating table Users ".mysql_error();
    }
    return $result;
}

function upgrade_to_0_3(&$con)
{
    $sql = "CREATE TABLE Scores
    (
    ID int NOT NULL auto_increment,
    CourseID int NOT NULL,
    TeeID int NOT NULL,
    UserID int NOT NULL,
    Date DATE,
    Score text,
    FOREIGN KEY (CourseID) REFERENCES Courses(ID) ON DELETE CASCADE,
    FOREIGN KEY (TeeID) REFERENCES Tees(ID) ON DELETE CASCADE,
    FOREIGN KEY (UserID) REFERENCES Users(ID) ON DELETE CASCADE,
    PRIMARY KEY (ID)
    )";
    $result = mysql_query($sql, $con);
    if(!$result)
    {
        echo "error creating table Scores ".mysql_error();
    }
    return $result;
}

function upgrade_to_0_4(&$con)
{
    $sql = "ALTER TABLE Scores ADD COLUMN HandicapDifferential float";
    $result = mysql_query($sql, $con);
    if(!$result)
    {
        echo "error modifiying table Scores ".mysql_error();
    }
    return $result;
}

function upgrade_to_0_5(&$con)
{
	$sql = "CREATE TABLE Groups 
	(
		`Group_ID` int(11) NOT NULL auto_increment,
		`Group_Name` varchar(225) NOT NULL,
		 PRIMARY KEY  (`Group_ID`)
	)";
    $result = mysql_query($sql, $con);
    if(!$result)
    {
        echo "error creating table Groups ".mysql_error();
    }
	else
	{
		$sql = "INSERT INTO Groups (`Group_ID`, `Group_Name`) VALUES (1, 'Standard User')";
		$result = mysql_query($sql, $con);
		if(!$result)
		{
			echo "error setting up table Groups ".mysql_error();
		}
	}
	
    
    $sql = "ALTER TABLE Users ADD COLUMN (`Username` varchar(150) NOT NULL),
		   ADD COLUMN (`Username_Clean` varchar(150) NOT NULL),
		   ADD COLUMN (`Password` varchar(225) NOT NULL),
		   ADD COLUMN (`ActivationToken` varchar(225) NOT NULL),
		   ADD COLUMN (`LastActivationRequest` int(11) NOT NULL),
		   ADD COLUMN (`LostPasswordRequest` int(1) NOT NULL default '0'),
		   ADD COLUMN (`Active` int(1) NOT NULL),
		   ADD COLUMN (`Group_ID` int(11) NOT NULL),
		   ADD COLUMN (`SignUpDate` int(11) NOT NULL),
		   ADD COLUMN (`LastSignIn` int(11) NOT NULL)";
    $result = mysql_query($sql, $con);
    if(!$result)
    {
        echo "error modifying table Users ".mysql_error();
    }
	
	$sql = "ALTER TABLE Users MODIFY ID int(11) NOT NULL auto_increment";
    $result = mysql_query($sql, $con);
    if(!$result)
    {
        echo "error modifying column ID of table Users ".mysql_error();
    }
	
	$sql = "ALTER TABLE Users MODIFY `Email` varchar(150) NOT NULL";
    $result = mysql_query($sql, $con);
    if(!$result)
    {
        echo "error modifying column Email of table Users ".mysql_error();
    }
	
	return result;
}

function setup_db($username, $password, $createDB = true)
{
    $result = false;
    $con = mysql_connect(SERVER_NAME, $username, $password);
    if($con)
    {
        if($createDB)
        {
            // create database
            if(!mysql_query("CREATE DATABASE ".DATABASE_NAME, $con))
            {
                echo "Database create failed!";
            }
            // only create user if it's a new database
            create_user($con);
        }
        // create table
        create_tables($con);
        
        mysql_close($con);
        $result = true;
    }
    return $result;
}

function create_tables(&$con)
{
    mysql_select_db(DATABASE_NAME, $con);
    
    drop_tables($con);
    
    $sql = "CREATE TABLE Courses
    (
    ID int NOT NULL auto_increment,
    Name varchar(100) NOT NULL,
    NineHole BOOL NOT NULL,
    PRIMARY KEY (ID),
    UNIQUE (Name)
    )";
    $result = mysql_query($sql, $con);
    if(!$result)
    {
        die("error creating table Courses ".mysql_error());
    }
    
    $sql = "CREATE TABLE Tees
    (
    ID int NOT NULL auto_increment,
    CourseID int NOT NULL,
    Name varchar(100) NOT NULL,
    Slope int,
    Rating float,
    IsLadies BOOL,
    Par text,
    Handicap text,
    Distance text,
    FOREIGN KEY (CourseID) REFERENCES Courses(ID) ON DELETE CASCADE,
    PRIMARY KEY (ID)
    )";
    $result = mysql_query($sql, $con);
    if(!$result)
    {
        die("error creating table Tees ".mysql_error());
    }
	
	$sql = "CREATE TABLE Groups 
	(
		`Group_ID` int(11) NOT NULL auto_increment,
		`Group_Name` varchar(225) NOT NULL,
		 PRIMARY KEY  (`Group_ID`)
	)";
    $result = mysql_query($sql, $con);
    if(!$result)
    {
        die("error creating table Groups ".mysql_error());
    }
	
	$sql = "INSERT INTO Groups (`Group_ID`, `Group_Name`) VALUES (1, 'Standard User')";
    $result = mysql_query($sql, $con);
    if(!$result)
    {
        die("error setting up table Groups ".mysql_error());
    }
    
    $sql = "CREATE TABLE Users 
	(
		`ID` int(11) NOT NULL auto_increment,
		`Username` varchar(150) NOT NULL,
		`Username_Clean` varchar(150) NOT NULL,
		`Password` varchar(225) NOT NULL,
		`Email` varchar(150) NOT NULL,
		`ActivationToken` varchar(225) NOT NULL,
		`LastActivationRequest` int(11) NOT NULL,
		`LostPasswordRequest` int(1) NOT NULL default '0',
		`Active` int(1) NOT NULL,
		`Group_ID` int(11) NOT NULL,
		`SignUpDate` int(11) NOT NULL,
		`LastSignIn` int(11) NOT NULL,
		PRIMARY KEY  (`ID`),
		UNIQUE(Email)
	)";
    $result = mysql_query($sql, $con);
    if(!$result)
    {
        die("error creating table Users ".mysql_error());
    }
    
    // TODO-L: add index on Date column
    $sql = "CREATE TABLE Scores
    (
    ID int NOT NULL auto_increment,
    CourseID int NOT NULL,
    TeeID int NOT NULL,
    UserID int NOT NULL,
    Date DATE,
    Score text,
    HandicapDifferential float,
    FOREIGN KEY (CourseID) REFERENCES Courses(ID) ON DELETE CASCADE,
    FOREIGN KEY (TeeID) REFERENCES Tees(ID) ON DELETE CASCADE,
    FOREIGN KEY (UserID) REFERENCES Users(ID) ON DELETE CASCADE,
    PRIMARY KEY (ID)
    )";
    $result = mysql_query($sql, $con);
    if(!$result)
    {
        die("error creating table Scores ".mysql_error());
    }
}

function drop_tables(&$con)
{
    // drop all tables
    $sql = "DROP TABLE IF EXISTS Scores CASCADE";
    $result = mysql_query($sql, $con);
    if(!$result)
    {
        die("error dropping table Scores ".mysql_error());
    }

    $sql = "DROP TABLE IF EXISTS Tees CASCADE";
    $result = mysql_query($sql, $con);
    if(!$result)
    {
        die("error dropping table Tees ".mysql_error());
    }
    
    $sql = "DROP TABLE IF EXISTS Courses CASCADE";
    $result = mysql_query($sql, $con);
    if(!$result)
    {
        die("error dropping table Courses ".mysql_error());
    }

    $sql = "DROP TABLE IF EXISTS Groups CASCADE";
    $result = mysql_query($sql, $con);
    if(!$result)
    {
        die("error dropping table Groups ".mysql_error());
    }

    $sql = "DROP TABLE IF EXISTS Users CASCADE";
    $result = mysql_query($sql, $con);
    if(!$result)
    {
        die("error dropping table Users ".mysql_error());
    }
}

function create_user(&$con)
{
    $sql = "CREATE USER '".DBO_USER_NAME."'@'".SERVER_NAME."' IDENTIFIED BY '".DBO_PASSWORD."'";
    mysql_query($sql, $con);
    $sql = "GRANT ALL ON ".DATABASE_NAME.".* TO '".DBO_USER_NAME."'@'".SERVER_NAME."'";
    mysql_query($sql, $con);
}
?>
