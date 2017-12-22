<?php

/**
 * Creates database tables and base data on first installation
 */
function elm_Data_InitializeDb(){
    $filename = "MariaDB/slippery_elm.sql";
    ExecSqlFile($filename);
}

/**
 * Checks if tables in database are existing.
 * @return bool True if Db got created
 */
function elm_Data_GetIsDbInitialized(){
    $sql = mysqli_query($conn, "SELECT * FROM `elm_version`;");
    if ($sql){
        $initialized = true;
    } else {
        $initialized = false;
    }
    return $initialized;
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
    //enter rolename return id
    return 1;
}

/**
 *
 *
 */
function elm_Data_login_User($userName, $password){
    //Check if User exists and is using right password Login function
    return true;
}

function ExecSqlFile($filename) {
    if (!file_exists($filename)) {
        return false;
    }
    $array = array();
    $querys = explode("\n", file_get_contents($filename));
    foreach ($querys as $q) {
        $q = trim($q);
        if (strlen($q)) {
            mysqli_query($conn, $q) or die(mysqli_error($conn));
        }
    }
    return $array;
}
?>