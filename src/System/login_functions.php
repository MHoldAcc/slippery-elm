<?php
session_start();

function isLoggedIn(){
    if(isset($_SESSION['loggedIn'])){
        return true;
    }else{
        return false;
    }
}
?>