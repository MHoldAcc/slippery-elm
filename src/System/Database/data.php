<?php
/**
 * Creates database tables and base data on first installation
 */

class elm_Data {

    private $conn;

    public function __construct(){
        @session_start();
        include("config.php");
        $this->conn = new PDO($elm_Settings_DSN, $elm_Settings_DbUser, $elm_Settings_DbPassword, array(
            PDO::ATTR_PERSISTENT => true
        ));
        $sql = $this->conn->prepare("SET NAMES utf8;");
        $sql->execute();
    }

    /**
     * initialize DB if it isn't initialized yet
     */
    public function elm_Data_InitializeDb(){
        include_once("config.php");
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
    public function elm_Data_GetIsDbInitialized(){
        $sql = $this->conn->prepare("SELECT * FROM elm_version;");
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
    public function elm_Data_UpdateUser($id, $name, $pass, $mail){
        $name = stripslashes($name);
        $password = stripslashes($pass);
        $password = hash('sha512', $password);
        $sql = $this->conn->prepare("UPDATE elm_users 
                                      SET username = ?, password = ?, email = ?
                                      WHERE usersid = ?;");
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
    public function elm_Data_DeleteUser($id){
        $id = stripslashes($id);
        $sql = $this->conn->prepare("DELETE FROM elm_users
                                      WHERE usersid = ?;");
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
    public function elm_Data_GetUsers(){
        //Use the class elm_User as return values
        //Example on how to use classes in PHP here:  TBZ - elm -> M151 -> Beispiel Code -> Webservice json_dayAndJoke
        $elmUsers = array();
        $sql = $this->conn->prepare("SELECT * FROM elm_users 
                                      WHERE isactive = TRUE;");
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
     * @param $email
     * @param $roleID
     * @return bool Returns true if user creation was succesful
     */
    public function elm_Data_CreateUser($userName, $password, $email, $roleID){
        $created = false;
        $name = stripslashes($userName);
        $password = stripslashes($password);
        $password = hash('sha512', $password);
        $email = stripslashes($email);
        $roleID = stripslashes($roleID);

        $sql = $this->conn->prepare("SELECT * FROM elm_users WHERE username = ?;");
        $sql->bindParam(1, $name);

        if ($sql->execute() && $sql->rowCount() == 0){
            $sql = $this->conn->prepare("INSERT INTO elm_users (username, password, email, isactive, role_fk) 
                                          VALUES 
                                          (?, ?, ?, TRUE, ?);");
            $sql->bindParam(1, $name);
            $sql->bindParam(2, $password);
            $sql->bindParam(3, $email);
            $sql->bindParam(4, $roleID);
            if ($sql->execute()){
                $created = true;
            }
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
    public function elm_Data_login_User($userName, $password, $verify){
        //Check if User exists and is using right password
        $name = $userName;
        if ($verify === $password) {
            $name = stripslashes($name);
            $password = stripslashes($password);
            $password = hash('sha512', $password);
            $sql = $this->conn->prepare("SELECT usersid, role_fk FROM elm_users 
                                          WHERE username = ? 
                                          AND password = ?;");
            $sql->bindParam(1, $name);
            $sql->bindParam(2, $password);
            if($sql->execute()){
                $rows = $sql->rowCount();
                if ($rows == 1) {
                    $_SESSION['login_user'] = $name; // Initializing Session
                    $row = $sql->fetch(PDO::FETCH_OBJ);
                    $_SESSION['login_user_id'] = $row->usersid;
                    $_SESSION['login_role_fk'] = $row->role_fk;
                    $_SESSION['login_failure'] = 'false';

                } else {
                    $_SESSION['login_failure'] = 'true';
                }
            }
            else {
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
     */
    public function elm_Data_CreatePage($title, $content, $parentPage, $keywords, $sorting){
        $sql = $this->conn->prepare("INSERT INTO elm_pages (pagesname, pagescontent, pagesparentpage, pageskeywords, pagessorting) 
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
     * Allows te creation of new pages in database
     * @param $pageID
     * @param $title
     * @param $content
     * @param $parentPage
     * @param $keywords
     * @param $sorting
     */
    public function elm_Data_AdminUpdatePage($pageID, $title, $content, $parentPage, $keywords, $sorting){
        $sql = $this->conn->prepare("UPDATE elm_pages 
                                      SET pagesname = ?, pagescontent = ?, pagesparentpage = ?, pageskeywords = ?, pagessorting = ? 
                                      WHERE pagesid = ?;");
        $sql->bindParam(1, $title);
        $sql->bindParam(2, $content);
        $sql->bindParam(3, $parentPage);
        $sql->bindParam(4, $keywords);
        $sql->bindParam(5, $sorting);
        $sql->bindParam(6, $pageID);
        $sql->execute();
    }

    /**
     * Allows the content of a page to be updated
     * @param $pageID
     * @param $content
     */
    public function elm_Data_UpdatePageContent($pageID, $content){
        $sql = $this->conn->prepare("UPDATE elm_pages 
                                      SET pagescontent = ? 
                                      WHERE pagesid = ?;");
        $sql->bindParam(1, $content);
        $sql->bindParam(2, $pageID);
        $sql->execute();
    }

    /**
     * This function returns the page object as an array
     * @return array
     */
    public function elm_Data_GetPages(){
        $pages = array();
        $sql = $this->conn->prepare("SELECT * FROM elm_pages;");
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
            $pageObject->id = $page['pagesid'];
            $pageObject->name = $page['pagesname'];
            $pageObject->content = $page['pagescontent'];
            $pageObject->parentPage = $page['pagesparentpage'];
            $pageObject->keywords = $page['pageskeywords'];
            $pageObject->sorting = $page['pagessorting'];
            $pageObject->sorting = $page['pagesishome'];
            $pageObject->created = $page['pagescreated'];
            $pageObject->modified = $page['pagesmodified'];
            $pageObject->creatorId = $page['pagescreaterid'];
            $pageObject->modifierId = $page['pagesmodifierid'];
            array_push($pageObjects, $pageObject);
        }
        return $pageObjects;
    }

    /**
     * This function returns a specific filled page object filtered by id
     * @param $pageID
     * @return array
     */
    public function elm_Data_GetSpecificPages($pageID){
        $pages = array();
        $pageObjects = array();

        $sql = $this->conn->prepare("SELECT * FROM elm_pages 
                                      WHERE pagesid = ?;");
        $sql->bindParam(1, $pageID);

        if($sql->execute()){
            while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                array_push($pages, $row);
            }
            //Parses Page Objects
            foreach ($pages as $page) {
                $pageObject = new elm_Page();
                $pageObject->id = $page['pagesid'];
                $pageObject->name = $page['pagesname'];
                $pageObject->content = $page['pagescontent'];
                $pageObject->parentPage = $page['pagesparentpage'];
                $pageObject->keywords = $page['pageskeywords'];
                $pageObject->sorting = $page['pagessorting'];
                $pageObject->sorting = $page['pagesishome'];
                $pageObject->created = $page['pagescreated'];
                $pageObject->modified = $page['pagesmodified'];
                $pageObject->creatorId = $page['pagescreaterid'];
                $pageObject->modifierId = $page['pagesmodifierid'];
                array_push($pageObjects, $pageObject);
            }
        }
        return $pageObjects;
    }

    /**
     * Deletes a page from database
     * @param $pageID
     */
    public function elm_Data_DeletePages($pageID){
        $sql = $this->conn->prepare("DELETE FROM elm_pages 
                                      WHERE pagesid = ?;");
        $sql->bindParam(1, $pageID);
        $sql->execute();
    }

    /**
     * Gets the id of any role by the role name
     * @param $roleName
     * @return int RoleID
     */
    public function elm_Data_GetRoleId($roleName){
        $id = array();
        $sql = $this->conn->prepare("SELECT roleid FROM elm_role 
              WHERE roleName = ?;");
        $sql->bindParam(1, $roleName, PDO::PARAM_STR);

        if ($sql->execute()){
            $id = $sql->fetch(PDO::FETCH_OBJ)->roleid;
        }
        return $id;
    }

    /**
     * Returns an array of all roles
     * @return array
     */
    public function elm_Data_GetRole(){
        $roles = array();
        $sql = $this->conn->prepare("SELECT * FROM elm_role;");
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
    public function elm_Data_CreateRole($roleName, $roleDescription){
        $sql = $this->conn->prepare("INSERT INTO elm_role (rolename, roledescription) 
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
    public function elm_Data_DeleteRole($roleId){
        $id = stripslashes($roleId);
        $sql = $this->conn->prepare("DELETE FROM elm_role
                                      WHERE roleid = ?;");
        $sql->bindParam(1, $id);
        $sql->execute();
    }

    /**
     * Returns a list of user that are assigned a specific role
     * @param $roleId
     * @return array
     */
    public function elm_Data_AssignmentRole($roleId){
        $roleId = stripslashes($roleId);
        $roles = array();
        $sql = $this->conn->prepare("SELECT * FROM elm_users
                                      WHERE role_fk = ?;");
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
    public function elm_Data_GetCurrentVersion(){
        $dbVersion = "";
        $sql = $this->conn->prepare("SELECT databaseversion FROM elm_version;");
        if ($sql->execute()){
            $rows = $sql->fetch(PDO::FETCH_OBJ);
            $dbVersion = $rows[0];
        }
        return $dbVersion;
    }

    /**
     * Executes all Scripts in MariaDb Folder which are not in database
     */
    public function elm_Data_ExecuteUpdate(){
        /*Insert code here*/
    }

    /**
     * Checks if the user can edit a page or not
     * Returns true or false
     */
    public function elm_Data_canUserEdit() {
        $canEdit = false;
        /*Insert code here*/
        return $canEdit;
    }
}

?>