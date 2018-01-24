<?php

/**
 * function to check if roleName is unique
 * the roleName is unique if no other role has this username
 * @param $roleName, required
 * @return bool
 */
function elm_RoleManagement_isRoleUnique($roleName){
    $roles = elm_Data_GetRole();

    foreach($roles as $role){
        if ($role['rolename'] == $roleName){
            return false;
        }
    }
    return true;
}

function elm_Page_RoleManagementFunctionality(){
    global $elm_Page_CurrentPage;

    if(isset($_GET['addRole_admin'])){
        //go to Add User Page
        $elm_Page_CurrentPage = new elm_Page();
        $elm_Page_CurrentPage -> name = 'Add Role';
        $elm_Page_CurrentPage -> id = 'elm_Admin_AddRole';
        $elm_Page_CurrentPage -> content = file_get_contents('System/UI/HTML/addRoleMask.html', FILE_USE_INCLUDE_PATH);
    }

    if (isset($_POST['elm_NewRole_Execute_admin'])) {
        //validate inputs and if valid -> Store new role
        if (isset($_POST['elm_AddRole_Name']) && isset($_POST['elm_AddRole_Description'])) {
            if (elm_RoleManagement_isRoleUnique($_POST['elm_AddRole_Name']) === true) {
                elm_Data_CreateRole($_POST['elm_AddRole_Name'], $_POST['elm_AddRole_Description']);
                header("Location: index.php?page=elm_RoleManagement");
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
    else if (isset($_GET['deleteRole_admin']) && isset($_GET['id'])){
        //allow role deletion only if no users are assigned
        $assignedUsers = elm_Data_AssignmentRole($_GET['id']);
        if (count($assignedUsers) == 0){
            elm_Data_DeleteRole($_GET['id']);
            header("Location: index.php?page=elm_RoleManagement");
        }
        else {
            //TODO: error handling
            echo "Not allowed";
        }
    }
}

?>