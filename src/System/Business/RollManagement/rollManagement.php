<?php

/**
 * function to check if rollName is unique
 * the rollName is unique if no other roll has this username
 * @param $rollName, required
 * @return bool
 */
function elm_RollManagement_isRollUnique($rollName){
    $rolls = elm_Data_GetRole();

    foreach($rolls as $roll){
        if ($roll['rolename'] == $rollName){
            return false;
        }
    }
    return true;
}

function elm_Page_RollManagementFunctionality(){
    global $elm_Page_CurrentPage;

    if(isset($_GET['addRoll_admin'])){
        //go to Add User Page
        $elm_Page_CurrentPage = new elm_Page();
        $elm_Page_CurrentPage -> name = 'Add Roll';
        $elm_Page_CurrentPage -> id = 'elm_Admin_AddRoll';
        $elm_Page_CurrentPage -> content = file_get_contents('System/UI/HTML/addRollMask.html', FILE_USE_INCLUDE_PATH);
    }

    if (isset($_POST['elm_NewRoll_Execute_admin'])) {
        //validate inputs and if valid -> Store new roll
        if (isset($_POST['elm_AddRoll_Name']) && isset($_POST['elm_AddRoll_Description'])) {
            if (elm_RollManagement_isRollUnique($_POST['elm_AddRoll_Name']) === true) {
                elm_Data_CreateRole($_POST['elm_AddRoll_Name'], $_POST['elm_AddRoll_Description']);
                header("Location: index.php?page=elm_RollManagement");
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
    else if (isset($_GET['deleteRoll_admin']) && isset($_GET['id'])){
        //allow roll deletion only if no users are assigned
        $assignedUsers = elm_Data_AssignmentRole($_GET['id']);
        if (count($assignedUsers) == 0){
            elm_Data_DeleteRole($_GET['id']);
            header("Location: index.php?page=elm_RollManagement");
        }
        else {
            //TODO: error handling
            echo "Not allowed";
        }
    }
}

?>