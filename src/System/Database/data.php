<?php

/**
 * Creates database tables and base data on first installation
 */
@session_start();
include_once("config.php");
$conn = new PDO($elm_Settings_DSN, $elm_Settings_DbUser, $elm_Settings_DbPassword, array(
    PDO::ATTR_PERSISTENT => true
));
$sql = $conn->prepare("SET NAMES utf8;");
$sql->execute();

/**
 * initialize DB if it isn't initialized yet
 */
function elm_Data_InitializeDb(){
    include("config.php");
    if ($elm_Settings_ConnectionHost == "mysql") {
        include_once "MariaDb/initializeMariaDB.php";
        initializeMariaDB();
    } else if ($elm_Settings_ConnectionHost == "pgsql"){
        include_once "Postgresql/initializePostgresqlDB.php";
        initializePostgresqlDB();
    }
}

/**
 * Checks if tables in database are existing.
 * @return bool True if Db got created
 */
function elm_Data_GetIsDbInitialized(){
    GLOBAL $conn;
    $sql = $conn->prepare("SELECT * FROM elm_version;");
    if ($sql->execute()){
        $initialized = true;
    } else {
        $initialized = false;
    }
    return $initialized;
}

/**
 * Creates a user in the database
 * @param $userName
 * @param $password
 * @param $mail
 * @param $roleId
 * @return bool User creation was successful
 *
 * CREATE TABLE elm_users (
 * usersID int(11) NOT NULL,
 * username varchar(255) NOT NULL,
 * password varchar(255) NOT NULL,
 * email varchar(255) NOT NULL,
 * isActive tinyint(1) NOT NULL,
 * usersCreated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 * usersModified timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 * usersCreaterID int(11) NOT NULL,
 * usersModifierID int(11) NOT NULL,
 * role_FK int(11) NOT NULL
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

    $sql = $conn->prepare("UPDATE elm_users 
              SET username = ?, password = ?, email = ?
              WHERE usersID = ?;");
    $sql->bindParam(1, $name);
    $sql->bindParam(2, $password);
    $sql->bindParam(3, $mail);
    $sql->bindParam(4, $id);
    $sql->execute();
}

/**
 * Deletes a user from the database
 * @param $id
 *
 * CREATE TABLE elm_users (
 * usersID int(11) NOT NULL,
 * username varchar(255) NOT NULL,
 * password varchar(255) NOT NULL,
 * email varchar(255) NOT NULL,
 * isActive tinyint(1) NOT NULL,
 * usersCreated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 * usersModified timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 * usersCreaterID int(11) NOT NULL,
 * usersModifierID int(11) NOT NULL,
 * role_FK int(11) NOT NULL
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 *
 */
function elm_Data_DeleteUser($id){
    GLOBAL $conn;
    $id = stripslashes($id);
    $sql = $conn->prepare("DELETE FROM elm_users
              WHERE usersID = ?;");
    $sql->bindParam(1, $id);
    $sql->execute();
}

/**
 * Returns all active users in an array
 * @return array All existing active users
 *
 * CREATE TABLE elm_users (
 * usersID int(11) NOT NULL,
 * username varchar(255) NOT NULL,
 * password varchar(255) NOT NULL,
 * email varchar(255) NOT NULL,
 * isActive tinyint(1) NOT NULL,
 * usersCreated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 * usersModified timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 * usersCreaterID int(11) NOT NULL,
 * usersModifierID int(11) NOT NULL,
 * role_FK int(11) NOT NULL
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 *
 */
function elm_Data_GetUsers(){
    //Use the class elm_User as return values
    //Example on how to use classes in PHP here:  TBZ - elm -> M151 -> Beispiel Code -> Webservice json_dayAndJoke
    GLOBAL $conn;
    $elmUsers = array();
    $sql = $conn->prepare("SELECT * FROM elm_users 
              WHERE isActive = 1;");
    if($sql->execute()){
        while ($row = $sql->fetch(PDO::FETCH_ASSOC)){
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
 * CREATE TABLE elm_users (
 * usersID int(11) NOT NULL,
 * username varchar(255) NOT NULL,
 * password varchar(255) NOT NULL,
 * email varchar(255) NOT NULL,
 * isActive tinyint(1) NOT NULL,
 * usersCreated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 * usersModified timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 * usersCreaterID int(11) NOT NULL,
 * usersModifierID int(11) NOT NULL,
 * role_FK int(11) NOT NULL
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
    $sql = $conn->prepare("INSERT INTO elm_users (username, password, email, isActive, role_FK) 
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
 * Takes input from login field varifies that the input was not altered during transmission and
 * proceeds to strip slashes
 * and finally completes the  login process.
 * @param $userName
 * @param $password
 * @param $verify
 * @return bool
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
        $sql = $conn->prepare("SELECT usersID FROM elm_users 
                  WHERE username LIKE ? AND password LIKE ?;");
        $sql->bindParam(1, $name);
        $sql->bindParam(2, $password);
        if($sql->execute()){
            $rows = $sql->rowCount();
            if ($rows == 1) {
                $_SESSION['login_user'] = $name; // Initializing Session
                $row = $sql->fetch(PDO::FETCH_OBJ);
                $_SESSION['login_user_id'] = $row->usersID;
                $_SESSION['login_failure'] = 'false';
            } else {
                $_SESSION['login_failure'] = 'true';
            }
        }
        else{
            $_SESSION['login_failure'] = 'true';
        }

    }
    return true;
}

/**
 * Creates page in database
 * @param $title
 * @param $content
 * @param $parentPage
 * @param $keywords
 * @param $sorting
 *
 * CREATE TABLE elm_pages (
 * pagesID int(11) NOT NULL,
 * pagesName varchar(255) NOT NULL,
 * pagesContent text,
 * pagesParentPage varchar(255),
 * pagesKeywords varchar(255) NOT NULL,
 * pagesSorting int(11) NOT NULL,
 * pagesCreated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 * pagesModified timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 * pagesCreaterID int(11) NOT NULL,
 * pagesModifierID int(11) NOT NULL
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 */
function elm_Data_CreatePage($title, $content, $parentPage, $keywords, $sorting){
    GLOBAL $conn;
    $sql = $conn->prepare("INSERT INTO elm_pages (pagesName, pagesContent, pagesParentPage, pagesKeywords, pagesSorting) 
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
 * allows te creation of new pages in database
 *
 * @param $pageID
 * @param $pageName
 * @param $title
 * @param $parentPage
 * @param $content
 * @param $keywords
 * @param $sorting
 *
 * CREATE TABLE elm_pages (
 * pagesID int(11) NOT NULL,
 * pagesName varchar(255) NOT NULL,
 * pagesContent text,
 * pagesParentPage varchar(255),
 * pagesKeywords varchar(255) NOT NULL,
 * pagesSorting int(11) NOT NULL,
 * pagesCreated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 * pagesModified timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 * pagesCreaterID int(11) NOT NULL,
 * pagesModifierID int(11) NOT NULL
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 */
function elm_Data_AdminUpdatePage($pageID, $pageName, $title, $parentPage, $content, $keywords, $sorting){
    GLOBAL $conn;
    $sql = $conn->prepare("UPDATE elm_pages 
              SET pagesName = ?,pagesParentPage = ?,pagesContent = ?, pagesParentPage = ?, pagesKeywords = ?, pagesSorting = ? 
              WHERE pagesID = ?;");
    $sql->bindParam(1, $pageName);
    $sql->bindParam(2, $title);
    $sql->bindParam(3, $content);
    $sql->bindParam(4, $parentPage);
    $sql->bindParam(5, $keywords);
    $sql->bindParam(6, $sorting);
    $sql->bindParam(7, $pageID);
    $sql->execute();
}

/**
 * allows the cotent of a page to be updated
 *
 * @param $pageID
 * @param $content
 *
 * CREATE TABLE elm_pages (
 * pagesID int(11) NOT NULL,
 * pagesName varchar(255) NOT NULL,
 * pagesContent text,
 * pagesParentPage varchar(255),
 * pagesKeywords varchar(255) NOT NULL,
 * pagesSorting int(11) NOT NULL,
 * pagesCreated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 * pagesModified timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 * pagesCreaterID int(11) NOT NULL,
 * pagesModifierID int(11) NOT NULL
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 */
function elm_Data_UpdatePageContent($pageID, $content){
    GLOBAL $conn;
    $sql = $conn->prepare("UPDATE elm_pages 
              SET pagesContent = ".$content." 
              WHERE pagesID = ".$pageID.";");
    $sql->bindParam(1, $content);
    $sql->bindParam(2, $pageID);
    $sql->execute();
}

/**
 * This function returns the page object as an array
 *
 * @return array
 */
function elm_Data_GetPages(){
    GLOBAL $conn;
    $pages = array();
    $sql = $conn->prepare("SELECT * FROM elm_pages;");
    $sql->execute();
    if($sql->execute()){
        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            array_push($pages, $row);
        }
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
        $pageObject->sorting = $page['pagesIsHome'];
        $pageObject->created = $page['pagesCreated'];
        $pageObject->modified = $page['pagesModified'];
        $pageObject->creatorId = $page['pagesCreaterID'];
        $pageObject->modifierId = $page['pagesModifierID'];
        array_push($pageObjects, $pageObject);
    }
    return $pageObjects;
}

/**
 * This function returns a specific filled page object filtered by id
 * @param $pageID
 * @return array
 */
function elm_Data_GetSpecificPages($pageID){
    GLOBAL $conn;
    $pages = array();
    $pageObjects = array();

    $sql = $conn->prepare("SELECT * FROM elm_pages 
              WHERE pagesID = ?;");

    $sql->bindParam(1, $pageID);

    if($sql->execute()){
        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            array_push($pages, $row);
        }
        //Parses Page Objects
        foreach ($pages as $page) {
            $pageObject = new elm_Page();
            $pageObject->id = $page->pagesID;
            $pageObject->name = $page->pagesName;
            $pageObject->content = $page->pagesContent;
            $pageObject->parentPage = $page->pagesParentPage;
            $pageObject->keywords = $page->pagesKeywords;
            $pageObject->sorting = $page->pagesSorting;
            $pageObject->sorting = $page->pagesIsHome;
            $pageObject->created = $page->pagesCreated;
            $pageObject->modified = $page->pagesModified;
            $pageObject->creatorId = $page->pagesCreaterID;
            $pageObject->modifierId = $page->pagesModifierID;
            array_push($pageObjects, $pageObject);
        }
    }

    return $pageObjects;
}

/**
 * Deletes a page from database
 *
 * CREATE TABLE elm_pages (
 * pagesID int(11) NOT NULL,
 * pagesName varchar(255) NOT NULL,
 * pagesContent text,
 * pagesParentPage varchar(255),
 * pagesKeywords varchar(255) NOT NULL,
 * pagesSorting int(11) NOT NULL,
 * pagesCreated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 * pagesModified timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 * pagesCreaterID int(11) NOT NULL,
 * pagesModifierID int(11) NOT NULL
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 */
function elm_Data_DeletePages($pageID){
    GLOBAL $conn;
    $sql = $conn->prepare("DELETE FROM elm_pages 
              WHERE pagesID = ?;");
    $sql->bindParam(1, $pageID);
    $sql->execute();
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
    $sql = $conn->prepare("SELECT roleID FROM elm_role 
              WHERE roleName LIKE ?;");
    $sql->bindParam(1, $roleName);
    if ($sql->execute()){
        $rows = $sql->fetch(PDO::FETCH_OBJ);
        $id = $rows->roleID;
    }
    return $id;
}

/**
 * Returns an array of all roles
 * @return array
 */
function elm_Data_GetRole(){
    GLOBAL $conn;
    $roles = array();
    $sql = $conn->prepare("SELECT * FROM elm_role;");
    if($sql->execute()){
        while ($row = $sql->fetch(PDO::FETCH_ASSOC)){
            array_push($roles, $row);
        }
    }
    return $roles;
}

/**
 * This function is used to create new roles to assign to users
 * @param $roleName
 * @param $roleDescription
 */
function elm_Data_CreateRole($roleName, $roleDescription){
    GLOBAL $conn;
    $sql = $conn->prepare("INSERT INTO elm_role (roleName, roleDescription) 
            VALUES 
            (?, ?);");
    $sql->bindParam(1, $roleName);
    $sql->bindParam(2, $roleDescription);
    $sql->execute();
}

/**
 * This function is used to remove unwanted roles
 * @param $roleId
 */
function elm_Data_DeleteRole($roleId){
    GLOBAL $conn;
    $id = stripslashes($roleId);
    $sql = $conn->prepare("DELETE FROM elm_role
              WHERE roleID = ?;");
    $sql->bindParam(1, $roleId);
    $sql->execute();
}

/**
 * Returns a list of user that are assigned a specific role
 *
 * @param $roleId
 * @return array
 */
function elm_Data_AssignmentRole($roleId){
    GLOBAL $conn;
    $roleId = stripslashes($roleId);
    $roles = array();
    $sql = $conn->prepare("SELECT * FROM elm_role
              WHERE roleID = ?;");
    $sql->bindParam(1, $roleId);
    $sql->execute();
    while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
        array_push($roles, $row);
    }
    return $roles;
}

/**
 * Returns newest database version from db
 */
function elm_Data_GetCurrentVersion(){
    GLOBAL $conn;
    $dbVersion = "";
    $sql = $conn->prepare("SELECT databaseVersion FROM elm_version;");
    if ($sql->execute()){
        $rows = $sql->fetch(PDO::FETCH_OBJ);
        $dbVersion = $rows[0];
    }
    return $dbVersion;
}

/**
 * Executes all Scripts in MariaDb Folder which are not in database
 */
function elm_Data_ExecuteUpdate(){
    /*Insert code here*/
}

/**
 * Checks if the user can edit a page or not
 * Returns true or false
 */
function elm_Data_canUserEdit() {
    $canEdit = false;
    /*Insert code here*/
    return $canEdit;
}

?>