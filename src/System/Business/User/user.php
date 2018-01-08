<?php

//Update User functionality
if(isset($_POST['elm_Login_Execute'])){
    elm_User_EditValues($_POST['elm_EditUser_Username'], $_POST['elm_EditUser_Password'], $_POST['elm_EditUser_Email']);
}

function elm_User_EditValues($userName, $password, $mail){
    if(isset($userName) && isset($password) && isset($mail)){
        elm_Data_UpdateUser($_SESSION['login_user_id'], $userName, $password, $mail);
        header("Location: index.php");
    }
    else{
        //Login failed
    }

}

?>