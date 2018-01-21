<?php

/**
 * Creates database tables and base data on first installation
 */
@session_start();
include_once("config.php");
$conn = new PDO($elm_Settings_DNS, $elm_Settings_DbUser, $elm_Settings_DbPassword, array(
    PDO::ATTR_PERSISTENT => true
));
/*
if ($conn->connect_error) {
    die('Connect Error (' . $conn->connect_errno . ') '. $conn->connect_error);
}
*/
$sql->prepare("SET NAMES utf8;");
$sql->execute();

/**
 * initialize DB if it isn't initialized yet
 */
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
    $sql = $conn->prepare("SELECT * FROM `elm_version`;");
    if ($sql->execute()){
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
    $sql = $conn->prepare("SELECT * FROM `elm_users` 
              WHERE `isActive` = 1;");
    $res = $sql->execute();
    if($res){
        while ($row = $res->fetch(PDO::FETCH_ASSOC)){
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
function elm_Data_CreateUser($userName, $password, $email, $roleID){
    GLOBAL $conn;
    $created = false;
    $name = $userName;
    $name = stripslashes($name);
    $password = stripslashes($password);
    //$password = $conn->real_escape_string($password);
    $password = hash('sha512', $password);
    $email = stripslashes($email);
    $roleID = stripslashes($roleID);
    $sql = $conn->prepare("INSERT INTO `elm_users` (`username`, `password`, `email`, `isActive`, `role_FK`) 
              VALUES 
              (?, ?, ?, 1, ?)");
    $sql->bindParam(1, $name);
    $sql->bindParam(2, $password);
    $sql->bindParam(3, $email);
    $sql->bindParam(4, $roleID);
    if ($sql->execute()){
        $created = true;
    }
    return $created;
}

/**
 * Gets the id of any role by the role name
 * @param $roleName
 * @return int RoleID
 */
function elm_Data_GetRoleId($roleName){
    GLOBAL $conn;
    echo $roleName;
    $id = array();
    $sql = $conn->prepare("SELECT `roleID` FROM `elm_role` 
              WHERE `roleName` LIKE ?;");
    $sql->bindParam(1, $roleName);
    if ($sql->execute()){
        $rows = $sql->fetch(PDO::FETCH_OBJ);
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
        //$password = $conn->real_escape_string($password);
        $password = hash('sha512', $password);
        // SQL query to fetch information of registered users and finds user match.
        $sql = $conn->prepare("SELECT usersID FROM `elm_users` 
                  WHERE `username` LIKE ? AND `password` LIKE ?;");
        $sql->bindParam(1, $name);
        $sql->bindParam(2, $password);
        $res = $sql->execute();
        $rows = $res->num_rows;
        if ($rows == 1) {
            $_SESSION['login_user'] = $name; // Initializing Session
            $rows = $sql->fetch(PDO::FETCH_OBJ);
            $_SESSION['login_user_id'] = $rows[0];
            $_SESSION['login_failure'] = 'false';
        } else {
            $_SESSION['login_failure'] = 'true';
        }
    }
    return true;
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
function elm_Data_CreatePage($title, $content, $parentPage, $keywords, $sorting){
    GLOBAL $conn;
    $sql = $conn->prepare("INSERT INTO `elm_pages` (`pagesName`, `pagesContent`, `pagesParentPage`, `pagesKeywords`, `pagesSorting`) 
            VALUES 
            (?, ?, ?, ?, ?);");
    $sql->bindParam(1, $title);
    $sql->bindParam(2, $content);
    $sql->bindParam(3, $parentPage);
    $sql->bindParam(4, $keywords);
    $sql->bindParam(5, $sorting);
    $sql->execute();
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
 *
 * This function allows the editing of pages
 */
function elm_Data_AdminUpdatePage($pageID, $title, $parentPage, $content, $keywords, $sorting){
    GLOBAL $conn;
    $sql = $conn->prepare("UPDATE `elm_pages` 
              SET `pagesName` = ?,`pagesParentPage` = ?,`pagesContent` = ?, `pagesKeywords` = ?, `pagesSorting` = ? 
              WHERE `pagesID` = ?;");
    $sql->bindParam(1, $title);
    $sql->bindParam(2, $content);
    $sql->bindParam(3, $parentPage);
    $sql->bindParam(4, $keywords);
    $sql->bindParam(5, $sorting);
    $sql->bindParam(6, $pageID);
    $sql->execute();
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
 *
 */
function elm_Data_UpdatePageContent($pageID, $content){
    GLOBAL $conn;
    $sql = $conn->prepare("UPDATE `elm_pages` 
              SET `pagesContent` = ".$content." 
              WHERE `pagesID` = ".$pageID.";");
    $sql->bindParam(1, $content);
    $sql->bindParam(2, $pageID);
    $sql->execute();
}

function elm_Data_GetPages(){
    GLOBAL $conn;
    $pages = array();
    $sql = $conn->prepare("SELECT * FROM `elm_pages`;");
    $res = $sql->execute();
    while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
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

function elm_Data_GetSpecificPages($pageID){
    GLOBAL $conn;
    $pages = array();
    $sql = $conn->prepare("SELECT * FROM `elm_pages` 
              WHERE `pagesID` = ?;");
    $sql->bindParam(1, $pageID);
    $res = $sql->execute();
    while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
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
function elm_Data_DeletePages($pageID){
    GLOBAL $conn;
    $sql = $conn->prepare("DELETE FROM `elm_pages` 
              WHERE `pagesID` = ?;");
    $sql->bindParam(1, $pageID);
    $sql->execute();
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
function elm_Data_UpdateUser($id, $name, $pass, $mail){
    GLOBAL $conn;

    $name = $name;
    $name = stripslashes($name);
    $password = stripslashes($pass);
    //$password = $conn->real_escape_string($password);
    $password = hash('sha512', $password);

    $sql = $conn->prepare("UPDATE `elm_users` 
              SET `username` = ?, `password` = ?, `email` = ?
              WHERE `usersID` = ?;");
    $sql->bindParam(1, $name);
    $sql->bindParam(2, $password);
    $sql->bindParam(3, $mail);
    $sql->bindParam(4, $id);
    $sql->execute();
}

function elm_Data_DeleteUser($id){
    GLOBAL $conn;
    $id = stripslashes($id);
    $sql = $conn->prepare("DELETE FROM `elm_users`
              WHERE `usersID` = ?;");
    $sql->bindParam(1, $id);
    $sql->execute();
}

function elm_Data_GetRole(){
    GLOBAL $conn;
    $roles = array();
    $sql = $conn->prepare("SELECT * FROM `elm_role`;");
    $res = $sql->execute();
    while ($row = $res->fetch(PDO::FETCH_ASSOC)){
        array_push($roles, $row);
    }
    return $roles;
}

function elm_Data_GetCurrentVersion(){
    //Returns newest database version from db
}

function elm_Data_ExecuteUpdate(){
    //Executes all Scripts in MariaDb Folder which are not in database
}

/**
 * Looks if the page is the home page or not (create for this a homeFlag in the page table)
 * Returns true or false
 *
 */
function elm_Data_preventHomeDeletion($pageID) {
    $isHome = false;
    /*Insert code here*/
    return isHome;
}

/**
 * Checks if the user can edit a page or not
 * Returns true or false
 *
 */
function elm_Data_canUserEdit() {
    $canEdit = false;
    /*Insert code here*/
    return $canEdit;
}

?>