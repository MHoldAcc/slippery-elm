<?php

/**
 * Creates database tables and base data on first installation
 */
@session_start();
include_once("config.php");
$conn = new mysqli($elm_Settings_ConnectionString, $elm_Settings_DbUser, $elm_Settings_DbPassword, $elm_Settings_Db);
if ($conn->connect_error) {
    die('Connect Error (' . $conn->connect_errno . ') '. $conn->connect_error);
}

function elm_Data_InitializeDb(){
    include_once"MariaDb/initializeMariaDB.php";
    initializeMariaDB();
}

/**
 * Checks if tables in database are existing.
 * @return bool True if Db got created
 */
function elm_Data_GetIsDbInitialized(){
    GLOBAL $conn;
    $sql = "SELECT * FROM `elm_version`;";
    if ($conn->query($sql)){
        $initialized = true;
    } else {
        $initialized = false;
    }
    return $initialized;
}

/**
 * Returns all active users in an array
 * @return array All existing active users
 *
 * CREATE TABLE `elm_users` (
 * `usersID` int(11) NOT NULL,
 * `username` varchar(255) NOT NULL,
 * `password` varchar(255) NOT NULL,
 * `email` varchar(255) NOT NULL,
 * `isActive` tinyint(1) NOT NULL,
 * `usersCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 * `usersModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 * `usersCreaterID` int(11) NOT NULL,
 * `usersModifierID` int(11) NOT NULL,
 * `role_FK` int(11) NOT NULL
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 *
 */
function elm_Data_GetUsers(){
    //Use the class elm_User as return values
    //Example on how to use classes in PHP here:  TBZ - elm -> M151 -> Beispiel Code -> Webservice json_dayAndJoke
    GLOBAL $conn;
    $elmUsers = array();
    $sql = "SELECT * FROM `elm_users` WHERE `isActive` = 1;";
    $res = $conn->query($sql);
    if($res){
        while ($row = $res->fetch_assoc()){
            array_push($elmUsers, $row);
        }
    }
    return $elmUsers;
}

/**
 * Creates a user in the database
 * @param $userName
 * @param $password
 * @param $mail
 * @param $roleId
 * @return bool User creation was successful
 *
 * CREATE TABLE `elm_users` (
 * `usersID` int(11) NOT NULL,
 * `username` varchar(255) NOT NULL,
 * `password` varchar(255) NOT NULL,
 * `email` varchar(255) NOT NULL,
 * `isActive` tinyint(1) NOT NULL,
 * `usersCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 * `usersModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 * `usersCreaterID` int(11) NOT NULL,
 * `usersModifierID` int(11) NOT NULL,
 * `role_FK` int(11) NOT NULL
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 *
 */
function elm_Data_CreateUser($userName, $password, $mail, $roleId){
    GLOBAL $conn;
    $created = false;
    $name = $userName;
    $name = stripslashes($name);
    $password = stripslashes($password);
    $password = $conn->real_escape_string($password);
    $password = hash('sha512', $password);
    $stmt = "INSERT INTO `elm_users` (`username`, `password`, `email`, `isActive`, `role_FK`) VALUES ('".$name."', '".$password."', '".$mail."',1,'".$roleId."')";
    $query = $conn->query($stmt);
    if ($query){
        $created = true;
    }
    return $created;
}

/**
 * Gets the id of any role by the role name
 * @param $roleName
 * @return int RoleId
 */
function elm_Data_GetRoleId($roleName){
    GLOBAL $conn;
    $id = "";
    $sql = "SELECT `roleID` FROM `elm_role` WHERE `roleName` LIKE '.$roleName.'";
    $res = $conn->query($sql);
    if ($res){
        $rows = $res->fetch_row();
        $id = $rows[0];
    }
    return $id;
}

/**
 *
 */
function elm_Data_login_User($userName, $password, $verify){
    //Check if User exists and is using right password Login function
    GLOBAL $conn;
    $name = $userName;
    if ($verify === $password) {
        $name = stripslashes($name);
        $password = stripslashes($password);
        $password = $conn->real_escape_string($password);
        $password = hash('sha512', $password);
        // SQL query to fetch information of registered users and finds user match.
        $stmt = "SELECT * FROM `elm_users` WHERE `username` LIKE '".$name."' AND `password` LIKE '".$password."';";
        $res = $conn->query($stmt);
        $rows = $res->num_rows;
        if ($rows == 1) {
            $_SESSION['login_user'] = $name; // Initializing Session
            $_SESSION['login_failure'] = 'false';
        } else {
            $_SESSION['login_failure'] = 'true';
        }
    }
    return true;
}

function ExecSqlFile($filename) {
    GLOBAL $conn;
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

/**
 * CREATE TABLE `elm_pages` (
 * `pagesID` int(11) NOT NULL,
 * `pagesName` varchar(255) NOT NULL,
 * `pagesContent` text,
 * `pagesParentPage` varchar(255),
 * `pagesKeywords` varchar(255) NOT NULL,
 * `pagesSorting` int(11) NOT NULL,
 * `pagesCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 * `pagesModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 * `pagesCreaterID` int(11) NOT NULL,
 * `pagesModifierID` int(11) NOT NULL
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 */
function createPage($title, $content, $parentPage, $keywords, $sorting){
    GLOBAL $conn;
    $sql = "INSERT INTO `elm_pages` (`pagesName`, `pagesContent`, `pagesParentPage`, `pagesKeywords`, `pagesSorting`) 
            VALUES 
            ('".$title."', '".$content."', '".$parentPage."', '".$keywords."', ".$sorting.");";
    $conn->query($sql);
}

/**
 * CREATE TABLE `elm_pages` (
 * `pagesID` int(11) NOT NULL,
 * `pagesName` varchar(255) NOT NULL,
 * `pagesContent` text,
 * `pagesParentPage` varchar(255),
 * `pagesKeywords` varchar(255) NOT NULL,
 * `pagesSorting` int(11) NOT NULL,
 * `pagesCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 * `pagesModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 * `pagesCreaterID` int(11) NOT NULL,
 * `pagesModifierID` int(11) NOT NULL
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 */
function adminUpdatePage($pageID, $title, $content, $keywords, $sorting){
    GLOBAL $conn;
    $sql = "UPDATE `elm_pages` SET `pagesName` = ".$title.",`pagesContent` = ".$content.", `pagesKeywords` = ".$keywords.", `pagesSorting` = ".$sorting." WHERE `pagesID` = ".$pageID.";";
    $conn->query($sql);
}

/**
 * CREATE TABLE `elm_pages` (
 * `pagesID` int(11) NOT NULL,
 * `pagesName` varchar(255) NOT NULL,
 * `pagesContent` text,
 * `pagesParentPage` varchar(255),
 * `pagesKeywords` varchar(255) NOT NULL,
 * `pagesSorting` int(11) NOT NULL,
 * `pagesCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 * `pagesModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 * `pagesCreaterID` int(11) NOT NULL,
 * `pagesModifierID` int(11) NOT NULL
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 */
function updatePageContent($pageID, $content){
    GLOBAL $conn;
    $sql = "UPDATE `elm_pages` SET `pagesContent` = ".$content." WHERE `pagesID` = ".$pageID.";";
    $conn->query($sql);
}

function elm_Data_GetPages(){
    GLOBAL $conn;
    $pages = array();
    $sql = "SELECT * FROM `elm_pages`;";
    $res = $conn->query($sql);
    while ($row = $res->fetch_assoc()) {
        array_push($pages, $row);
    }

    //Parses Page Objects
    $pageObjects = array();
    foreach ($pages as $page) {
        $pageObject = new elm_Page();
        $pageObject->id = $page['pagesID'];
        $pageObject->name = $page['pagesName'];
        $pageObject->content = $page['pagesContent'];
        $pageObject->parentPage = $page['pagesParentPage'];
        $pageObject->keywords = $page['pagesKeywords'];
        $pageObject->sorting = $page['pagesSorting'];
        $pageObject->created = $page['pagesCreated'];
        $pageObject->modified = $page['pagesModified'];
        $pageObject->creatorId = $page['pagesCreaterID'];
        $pageObject->modifierId = $page['pagesModifierID'];
        array_push($pageObjects, $pageObject);
    }

    return $pageObjects;
}

/**
 * Creates a user in the database
 * @param $userName
 * @param $password
 * @param $mail
 * @param $roleId
 * @return bool User creation was successful
 *
 * CREATE TABLE `elm_users` (
 * `usersID` int(11) NOT NULL,
 * `username` varchar(255) NOT NULL,
 * `password` varchar(255) NOT NULL,
 * `email` varchar(255) NOT NULL,
 * `isActive` tinyint(1) NOT NULL,
 * `usersCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 * `usersModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 * `usersCreaterID` int(11) NOT NULL,
 * `usersModifierID` int(11) NOT NULL,
 * `role_FK` int(11) NOT NULL
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 *
 */
function updateUser($name, $pass, $mail){
    GLOBAL $conn;
    $sql = "UPDATE `elm_users` 
              SET `username` = ".$name.", `password` = ".$pass.", `email` = ".$mail.";";
    $conn->query($sql);
}

function deleteUser($name){
    GLOBAL $conn;
    $sql = "DELETE FROM `elm_users`
              WHERE `username` = ".$name.";";
    $conn->query($sql);
}

function getRole(){
    GLOBAL $conn;
    $roles = array();
    $sql = "SELECT * FROM `elm_role`;";
    $res = $conn->query($sql);
    while ($row = $res->fetch_assoc()){
        array_push($roles, $row);
    }
    return $roles;
}
?>