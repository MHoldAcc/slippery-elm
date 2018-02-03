<?php

//Update User functionality
if(isset($_POST['elm_EditUser_Execute'])){
    elm_User_EditValues($_POST['elm_EditUser_Username'], $_POST['elm_EditUser_Password'], $_POST['elm_EditUser_Email']);
}

function elm_User_EditValues($userName, $password, $mail){
    if(isset($userName) && isset($password) && isset($mail)){
        elm_Data_UpdateUser($_SESSION['login_user_id'], $userName, $password, $mail);
        header("Location: index.php?logout");
    }
    else{
        //Update failed
    }
}

if(isset($_GET['elm_EditUser_DeleteCurrentUser'])){
    elm_User_DeleteCurrentUser();
}

function elm_User_DeleteCurrentUser(){
    if(isset($_SESSION['login_user_id'])){
        elm_Data_DeleteUser($_SESSION['login_user_id']);
        elm_LoginFunctionality::executeLogout();
        header("Location: index.php");
    }
    else{
        //Deletion failed
    }
}
?>