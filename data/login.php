<?php
/* 
 * @author Yuan Xu
*/

require_once('../helper/utilities.php');
global $loggedInUser;

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if(isset($_POST['action']))
    {
        require_once('../config.php');
        require_once('../users/userConfig.php');
        require_once('../database/users.php');

        if(isset($_POST['email']))
        {
            if($_POST['action'] == 'login')
            {
                $errors = array();
                $username = trim($_POST["email"]);
                $password = trim($_POST["password"]);
            
                //Perform some validation
                //Feel free to edit / change as required
                if($username == "")
                {
                    $errors[] = lang("ACCOUNT_SPECIFY_USERNAME");
                }
                if($password == "")
                {
                    $errors[] = lang("ACCOUNT_SPECIFY_PASSWORD");
                }
                
                //End data validation
                if(count($errors) == 0)
                {
                    //A security note here, never tell the user which credential was incorrect
                    if(!usernameExists($username))
                    {
                        $errors[] = lang("ACCOUNT_USER_OR_PASS_INVALID");
                    }
                    else
                    {
                        $userdetails = fetchUserDetails($username);
                    
                        //See if the user's account is activation
                        if($userdetails["Active"]==0)
                        {
                            $errors[] = lang("ACCOUNT_INACTIVE");
                        }
                        else
                        {
                            //Hash the password and use the salt from the database to compare the password.
                            $entered_pass = generateHash($password,$userdetails["Password"]);

                            if($entered_pass != $userdetails["Password"])
                            {
                                //Again, we know the password is at fault here, but lets not give away the combination incase of someone bruteforcing
                                $errors[] = lang("ACCOUNT_USER_OR_PASS_INVALID");
                            }
                            else
                            {
                                //Passwords match! we're good to go'
                                
                                //Construct a new logged in user object
                                //Transfer some db data to the session object
                                $loggedInUser = new loggedInUser();
                                $loggedInUser->email = $userdetails["Email"];
                                $loggedInUser->user_id = $userdetails["ID"];
                                $loggedInUser->hash_pw = $userdetails["Password"];
                                $loggedInUser->display_username = $userdetails["Username"];
                                $loggedInUser->clean_username = $userdetails["Username_Clean"];
                                
                                //Update last sign in
                                $loggedInUser->updateLastSignIn();
                
                                $_SESSION["userCakeUser"] = $loggedInUser;
                                
                                //Redirect to user account page
                                //header("Location: account.php");
                                //die();
                            }
                        }
                    }
                }
                if(count($errors) > 0)
                {
                    $error_message = "";
                    foreach($errors as $error)
                    {
                        $error_message .= $error."\n";
                    }
                    ajax_error($error_message);
                }
            }
            else if($_POST['action'] == 'register')
            {
                $errors = array();
                $email = trim($_POST["email"]);
                $username = $email;
                $password = trim($_POST["password"]);
                $confirm_pass = trim($_POST["passwordc"]);
            
                //Perform some validation
                //Feel free to edit / change as required
                if(minMaxRange(8,50,$password) && minMaxRange(8,50,$confirm_pass))
                {
                    $errors[] = lang("ACCOUNT_PASS_CHAR_LIMIT",array(8,50));
                }
                else if($password != $confirm_pass)
                {
                    $errors[] = lang("ACCOUNT_PASS_MISMATCH");
                }
                if(!isValidEmail($email))
                {
                    $errors[] = lang("ACCOUNT_INVALID_EMAIL");
                }
                
                //End data validation
                if(count($errors) == 0)
                {	
                    //Construct a user object
                    $user = new User($username,$password,$email);
                    
                    //Checking this flag tells us whether there were any errors such as possible data duplication occured
                    if(!$user->status)
                    {
                        if($user->username_taken) $errors[] = lang("ACCOUNT_USERNAME_IN_USE",array($username));
                        if($user->email_taken) 	  $errors[] = lang("ACCOUNT_EMAIL_IN_USE",array($email));		
                    }
                    else
                    {
                        //Attempt to add the user to the database, carry out finishing  tasks like emailing the user (if required)
                        if(!$user->userCakeAddUser())
                        {
                            if($user->mail_failure) $errors[] = lang("MAIL_ERROR");
                            if($user->sql_failure)  $errors[] = lang("SQL_ERROR");
                        }
                    }
                }
                if(count($errors) > 0)
                {
                    $error_message = "";
                    foreach($errors as $error)
                    {
                        $error_message .= $error."\n";
                    }
                    ajax_error($error_message);
                }
            }
            else if($_POST['action'] == 'update' && isset($_POST['email']))
            {
                update_user($loggedInUser->user_id, $_POST['email']);
            }
            else
            {
                // TODO-L: error
            }
        }
        else if($_POST['action'] == 'logout')
        {
            if(isUserLoggedIn()) $loggedInUser->userLogOut();
        }
        else
        {
            // TODO-L: error
        }
    }
    else
    {
        // TODO-L: error
    }
}
else
{
    // TODO-L: error
}
?>
