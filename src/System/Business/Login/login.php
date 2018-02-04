<?php

class elm_LoginFunctionality{
    /**
     * Login to slippery elm
     * @param $userName The username to login with
     * @param $password The password to login with
     * @param $verify The verification of the password to login with
     */
    public static function executeLogin(string $userName, string $password, string $verify){
        GLOBAL $elm_Data;
        //Checks if all values are set
        if(isset($userName) && isset($password) && isset($verify)){
            //Executes login functionality
            $elm_Data->loginUser($userName, $password, $verify);
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
    public static function executeLogout(){
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
    public static function userIsLoggedIn() : bool {
        if(isset($_SESSION['login_user']))
            return true;
        else
            return false;
    }
}
?>