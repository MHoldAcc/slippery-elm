<?php

class elm_Installation {
    /**
     * Initializes slippery elm database
     * @return bool True if db got initialized
     */
    public static function initializeDb() : bool {
        //Only execute if db wasn't already created
        if(!elm_Data_GetIsDbInitialized()){
            elm_Data_InitializeDb();
            return true;
        }
        return false;
    }

    /**
     * Creates an admin user with given credentials
     * @param string $username The username for the admin user
     * @param string $password The password for the admin user
     * @param string $email The email for the admin user
     * @return bool True if creation of the admin user was successful
     */
    public static function createAdminUser(string $username, string $password, string $email) : bool {
        //Stripslashes from variables so there can't be any sql injections
        $username = stripslashes($username);
        $password = stripslashes($password);
        $email = stripslashes($email);

        //Check if all variables have a value
        if($username != '' && $password != '' &&  $email != ''){
            //Create admin user
            if(elm_Data_CreateUser($username, $password, $email, elm_Data_GetRoleId('admin')))
                return true;
        }

        return false;
    }
}

?>