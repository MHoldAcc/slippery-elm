<?php

class elm_UserManager {
    /**
     * Changes values of the logged in user
     * @param $userName The username to set
     * @param $password The password to set
     * @param $mail The mail to set
     * @return bool True if successful
     */
    public static function updateCurrentUser($userName, $password, $mail) : bool{
        if(isset($userName) && isset($password) && isset($mail) && self::isUsernameUnique($userName)){
            if(self::updateUser($_SESSION['login_user_id'], $userName, $password, $mail)){
                elm_LoginFunctionality::executeLogout();
                return true;
            }
            else
                return false;
        }
        else{
            return false;
        }
    }

    /**
     * Deletes the currently logged in user
     * @return bool True if successful
     */
    public static function deleteCurrentUser() : bool {
        GLOBAL $elm_Data;
        if(isset($_SESSION['login_user_id'])){
            $elm_Data->elm_Data_DeleteUser($_SESSION['login_user_id']);
            elm_LoginFunctionality::executeLogout();
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * function to check if username is unique
     * the username is unique if no other user or only the edited user has this username
     * @param $username, required
     * @param string|null $id, optional, used for user edit
     * @return bool True if username is unique
     */
    public static function isUsernameUnique(string $username, string $id=null) : bool{
        GLOBAL $elm_Data;
        $users = $elm_Data->elm_Data_GetUsers();

        foreach($users as $user){
            if ($user['username'] == $username){
                if ($user['usersid'] == $id) {
                    return true;
                }
                else {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Adds a user to the database
     * @param string $name The username of the user
     * @param string $password The password of the user
     * @param string $mail The mail of the user
     * @param string $roleId The role id of the users role
     * @return bool True if successful
     */
    public static function addUser(string $name, string $password, string $mail, string $roleId) : bool {
        GLOBAL $elm_Data;
        if (self::isUsernameUnique($name) === true) {
            if ($elm_Data->elm_Data_CreateUser($name, $password, $mail, $roleId)) {
                return true;
            } else {
                return false;
            }
        }
        else {
            return true;
        }
    }

    /**
     * Deletes a user with the given id
     * @param string $userId The id of the user to delete
     * @return bool True if successful
     */
    public static function deleteUser(string $userId) : bool {
        GLOBAL $elm_Data;
        $elm_Data->elm_Data_DeleteUser($userId);
        return true;
    }

    /**
     * Updates the values of a given user
     * @param $userId The id of the user to edit
     * @param $userName The username to set
     * @param $password The password to set
     * @param $mail The mail to set
     * @return bool True if successful
     */
    public static function updateUser($userId, $userName, $password, $mail){
        GLOBAL $elm_Data;
        if(isset($userName) && isset($password) && isset($mail) && self::isUsernameUnique($userName, $userId)){
            $elm_Data->elm_Data_UpdateUser($userId, $userName, $password, $mail);
            return true;
        }
        else{
            return false;
        }
    }
}

?>