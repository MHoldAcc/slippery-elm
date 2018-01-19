<?php

/**
 * function to check if username is unique
 * the username is unique if no other user or only the edited user has this username
 * @param $username, required
 * @param null $id, optional, used for user edit
 * @return bool
 */
function elm_UserManagement_isUsernameUnique($username, $id=null){
    $users = elm_Data_GetUsers();

    foreach($users as $user){
        if ($user['username'] == $username){
            if ($user['usersID'] == $id) {
                return true;
            }
            else {
                return false;
            }
        }
    }
    return true;
}

function elm_Page_UserManagementFunctionality(){
    global $elm_Page_CurrentPage;

    if(isset($_GET['addUser_admin'])){
        //go to Add User Page
        $elm_Page_CurrentPage = new elm_Page();
        $elm_Page_CurrentPage -> name = 'Add User';
        $elm_Page_CurrentPage -> id = 'elm_Admin_AddUser';
        $elm_Page_CurrentPage -> content = file_get_contents('System/UI/HTML/addUserMask.php', FILE_USE_INCLUDE_PATH);
    }
    else if (isset($_GET['editUser_admin']) && isset($_GET['id'])){
        //go to Edit User Page
        $elm_Page_CurrentPage = new elm_Page();
        $elm_Page_CurrentPage -> name = 'Edit User';
        $elm_Page_CurrentPage -> id = 'elm_Admin_AddUser';
        $elm_Page_CurrentPage -> content = file_get_contents('System/UI/HTML/editUserMask.php', FILE_USE_INCLUDE_PATH);
    }

    if (isset($_POST['elm_NewUser_Execute_admin'])) {
        //validate inputs and if valid -> Store new User
        if (isset($_POST['elm_AddUser_Username']) && isset($_POST['elm_AddUser_Email']) && isset($_POST['elm_AddUser_Password']) && isset($_POST['elm_AddUser_Roll'])) {
            header("Location: index.php?isUnique".(elm_UserManagement_isUsernameUnique($_POST['elm_AddUser_Username']) === true ? "true" : "false"));
            if (elm_UserManagement_isUsernameUnique($_POST['elm_AddUser_Username']) === true) {
                if (elm_Data_CreateUser($_POST['elm_AddUser_Username'], $_POST['elm_AddUser_Password'], $_POST['elm_AddUser_Email'], $_POST['elm_AddUser_Roll'])) {
                    header("Location: index.php?page=userMgmt");
                } else {
                    //TODO: error handling
                    echo "error";
                }
            }
            else {
                //TODO: error handling
                echo "invalid_username";
            }
        } else {
            //TODO: error handling
            echo "error";
        }
    }
    else if (isset($_POST['elm_EditUser_Execute_admin'])){
        //validate inputs and if valid -> update user
        if (isset($_POST['elm_EditUser_Username']) && isset($_POST['elm_EditUser_Email']) && isset($_POST['elm_EditUser_Password']) && isset($_POST['elm_EditUser_Id'])) {
            header("Location: index.php?isUnique".(elm_UserManagement_isUsernameUnique($_POST['elm_AddUser_Username'], $_POST['elm_EditUser_Id']) === true ? "true" : "false"));
            if (elm_UserManagement_isUsernameUnique($_POST['elm_AddUser_Username'], $_POST['elm_EditUser_Id']) === true) {
                elm_Data_UpdateUser($_POST['elm_EditUser_Id'], $_POST['elm_EditUser_Username'], $_POST['elm_EditUser_Password'], $_POST['elm_EditUser_Email']);
                header("Location: index.php?page=elm_UserMgmt");
            }
            else {
                //TODO: error handling
                echo "invalid_username";
            }
        }
        else {
            //TODO: error handling
            echo "error";
        }
    }
    else if (isset($_GET['deleteUser_admin'])){
        //delete User
        if (isset($_GET['id'])) {
            elm_Data_DeleteUser($_GET['id']);
            header("Location: index.php?page=elm_UserMgmt");
        }
        else {
            //TODO: error handling
            echo "error";
        }
    }
}

?>