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
    public function initializeDb(){
        include_once("config.php");
        if ($elm_Settings_ConnectionHost == "mysql") {
            include_once "MariaDb/initializeMariaDB.php";
            initializeMariaDB($this->conn);
        } else if ($elm_Settings_ConnectionHost == "pgsql"){
            include_once "Postgresql/initializePostgresqlDB.php";
            initializePostgresqlDB($this->conn);
        }
    }

    /**
     * Checks if tables in database are existing.
     * @return bool True if Db got created
     */
    public function getIsDbInitialized() : bool{
        $sql = $this->conn->prepare("SELECT * FROM elm_version;");
        if ($sql->execute()){
            $initialized = true;
        } else {
            $initialized = false;
        }
        return $initialized;
    }

    /**
     * Updates the values of a user with given id
     * @param string $id The id of the user to update
     * @param string $name The username to set
     * @param string $pass The password to set
     * @param string $mail The mail to set
     * @return bool True if successful
     */
    public function updateUser(string $id, string $name, string $pass, string $mail) : bool {
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
     * @param string $id the id of the user to delete
     */
    public function deleteUser(string $id){
        $id = stripslashes($id);
        $sql = $this->conn->prepare("DELETE FROM elm_users
                                      WHERE usersid = ?;");
        $sql->bindParam(1, $id);
        $sql->execute();
    }

    /**
     * Returns all active users in an array
     * @return array All existing active users
     */
    public function getUsers() : array {
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
     * @param string $userName The username to set
     * @param string $password The password to set
     * @param string $email The mail to set
     * @param string $roleID The role id to set
     * @return bool Returns true if user creation was successful
     */
    public function createUser(string $userName, string $password, string $email, string $roleID) : bool {
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
     * Takes input from login field verifies that the input was not altered during transmission and
     * proceeds to strip slashes
     * and finally completes the  login process.
     * @param string $userName The username to login
     * @param string $password The password to login
     * @param string $verify The password verification to login
     * @return bool True if successful
     */
    public function loginUser(string $userName, string $password, string $verify) : bool {
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
     * @param string $title The title of the page
     * @param string $content The content of the page
     * @param string $parentPage The parent page
     * @param string $keywords The keywords of the page
     * @param string $sorting The sorting of the page
     */
    public function createPage(string $title, string $content, string $parentPage, string $keywords, string $sorting){
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
     * Allows the update of existing pages in the database
     * @param string $pageID The id of the page to change
     * @param string $title The title to set
     * @param string $content The content to set
     * @param string $parentPage The parent page to set
     * @param string $keywords The keywords to set
     * @param string $sorting The sorting to set
     */
    public function updatePage(string $pageID, string $title, string $content, string $parentPage, string $keywords, string $sorting){
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
     * This function returns the page object as an array
     * @return array
     */
    public function getPages() : array {
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
     * @param string $pageID The id of the page to get
     * @return elm_Page The found page
     */
    public function getPageById(string $pageID) : elm_Page{
        $pages = array();
        $pageObject = new elm_Page();

        $sql = $this->conn->prepare("SELECT * FROM elm_pages 
                                      WHERE pagesid = ?;");
        $sql->bindParam(1, $pageID);

        if($sql->execute()){
            while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                array_push($pages, $row);
            }
            //Parses Page Objects
            foreach ($pages as $page) {
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
            }
        }
        return $pageObject;
    }

    /**
     * Deletes a page from database
     * @param string $pageID The page to delete
     */
    public function deletePage(string $pageID){
        $sql = $this->conn->prepare("DELETE FROM elm_pages 
                                      WHERE pagesid = ?;");
        $sql->bindParam(1, $pageID);
        $sql->execute();
    }

    /**
     * Gets the id of any role by the role name
     * @param string $roleName The name of the role to search
     * @return int RoleID The id of the role
     */
    public function getRoleId(string $roleName) : int {
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
     * @return array All existing roles
     */
    public function getRole() : array {
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
     * @param string $roleName The name of the role to create
     * @param string $roleDescription The description of the role to create
     */
    public function createRole(string $roleName, string $roleDescription){
        $sql = $this->conn->prepare("INSERT INTO elm_role (rolename, roledescription) 
                                      VALUES 
                                      (?, ?);");
        $sql->bindParam(1, $roleName);
        $sql->bindParam(2, $roleDescription);
        $sql->execute();
    }

    /**
     * This function is used to remove unwanted roles
     * @param string $roleId The id of the role to delete
     */
    public function deleteRole(string $roleId){
        $id = stripslashes($roleId);
        $sql = $this->conn->prepare("DELETE FROM elm_role
                                      WHERE roleid = ?;");
        $sql->bindParam(1, $id);
        $sql->execute();
    }

    /**
     * Returns a list of users that are assigned a specific role
     * @param string $roleId The role id to use
     * @return array All users assigned to that role
     */
    public function getUsersByRole(string $roleId) : array {
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
     * @return string The current database version
     */
    public function getCurrentDbVersion() : string {
        $dbVersion = "";
        $sql = $this->conn->prepare("SELECT databaseversion FROM elm_version;");
        if ($sql->execute()){
            $rows = $sql->fetch(PDO::FETCH_OBJ);
            $dbVersion = $rows[0];
        }
        return (string)$dbVersion;
    }

    /**
     * Executes all Scripts in MariaDb Folder which are not in database
     */
    public function executeUpdate(){
        /*Insert code here*/
    }

    /**
     * Checks if the user can edit a page or not
     * @return bool True if user can edit pages
     */
    public function canUserEditPages() : bool {
        $canEdit = false;
        /*Insert code here*/
        return $canEdit;
    }
}

?>