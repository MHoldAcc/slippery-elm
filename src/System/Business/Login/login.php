<?php

function elm_Login_Login($userName, $password, $verify){
    if(isset($userName) && isset($password) && isset($verify)){
        elm_Data_login_User($userName, $password, $verify);
        header("Location: index.php");
    }
    else{
        //Login failed
    }

}

function elm_Login_Logout(){
    session_start();
    session_unset();
    session_destroy();
    header("Location: index.php");
}

function elm_Login_IsLoggedIn(){
    if(isset($_SESSION['login_user']))
        return true;
    else
        return false;
}

?>