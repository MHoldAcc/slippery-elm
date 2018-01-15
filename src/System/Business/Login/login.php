<?php

/**
 * Login to slippery elm
 * @param $userName The username to login with
 * @param $password The password to login with
 * @param $verify The verification of the password to login with
 */
function elm_Login_Login($userName, $password, $verify){
    //Checks if all values are set
    if(isset($userName) && isset($password) && isset($verify)){
        //Executes login functionality
        elm_Data_login_User($userName, $password, $verify);
        //Refreshes page
        header("Location: index.php");
    }
    else{
        //Login failed
    }

}

/**
 * Logs out current user
 */
function elm_Login_Logout(){
    session_start();
    session_unset();
    session_destroy();
    //Refreshes page after logout
    header("Location: index.php");
}

/**
 * Checks if a current user is logged in
 * @return bool True if user is logged in
 */
function elm_Login_IsLoggedIn(){
    if(isset($_SESSION['login_user']))
        return true;
    else
        return false;
}

?>