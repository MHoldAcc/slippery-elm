<?php

class elm_UserManager{

    /**
     * Changes values of the logged in user
     * @param $userName The username to set
     * @param $password The password to set
     * @param $mail The mail to set
     * @return bool True if successful
     */
    public static function elm_User_EditValues($userName, $password, $mail) : bool{
        if(isset($userName) && isset($password) && isset($mail)){
            elm_Data_UpdateUser($_SESSION['login_user_id'], $userName, $password, $mail);
            elm_LoginFunctionality::executeLogout();
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * Deletes the currently logged in user
     * @return bool True if successful
     */
    public static function elm_User_DeleteCurrentUser() : bool {
        if(isset($_SESSION['login_user_id'])){
            elm_Data_DeleteUser($_SESSION['login_user_id']);
            elm_LoginFunctionality::executeLogout();
            return true;
        }
        else{
            return false;
        }
    }
}

?>