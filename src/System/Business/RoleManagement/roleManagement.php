<?php

class elm_RoleManger{
    /**
     * Function to check if roleName is unique
     * the roleName is unique if no other role has this username
     * @param string $roleName, required
     * @return bool True if unique
     */
    public static function isRoleUnique(string $roleName) : bool {
        GLOBAL $elm_Data;
        $roles = $elm_Data->getRole();

        foreach($roles as $role){
            if ($role['rolename'] == $roleName){
                return false;
            }
        }
        return true;
    }

    /**
     * Deletes a given role
     * @param string $roleId The id of the role to delete
     * @return bool True if successful
     */
    public static function deleteRole(string $roleId) : bool {
        GLOBAL $elm_Data;
        //Allow role deletion only if no users are assigned
        $assignedRoles = $elm_Data->getUsersByRole($roleId);
        if (count($assignedRoles) == 0){
            $elm_Data->deleteRole($roleId);
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Adds a role
     * @param string $name The name of the role to add
     * @param string $description The description of the role to add
     * @return bool True if successful
     */
    public static function addRole(string $name, string $description) : bool {
        GLOBAL $elm_Data;
        //Validates inputs, if valid -> Store new role
        if (self::isRoleUnique($name) === true) {
            $elm_Data->createRole($name, $description);
            return true;
        }
        else {
            return false;
        }
    }
}


?>