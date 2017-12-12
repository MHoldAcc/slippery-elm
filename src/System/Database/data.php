<?php

/**
 * Creates database tables and base data on first installation
 */
function elm_Data_InitializeDb(){

}

/**
 * Checks if tables in database are existing.
 * @return bool True if Db got created
 */
function elm_Data_GetIsDbInitialized(){
    return false;
}

/**
 * Returns all active users in an array
 * @return array All existing active users
 */
function elm_Data_GetUsers(){
    //Use the class elm_User as return values
    //Example on how to use classes in PHP here:  TBZ - elm -> M151 -> Beispiel Code -> Webservice json_dayAndJoke
    return array();
}

/**
 * Creates a user in the database
 * @param $userName
 * @param $password
 * @param $mail
 * @param $roleId
 * @return bool User creation was successful
 */
function elm_Data_CreateUser($userName, $password, $mail, $roleId)
{
    //Hash password and create user in db
    return true;
}

/**
 * Gets the id of any role by the role name
 * @param $roleName
 * @return int RoleId
 */
function elm_Data_GetRoleId($roleName){
    return 1;
}

/**
 *
 *
 */
function elm_Data_login_User($userName, $password){
    //Check if User exists and is using right password
    return true;
}
?>