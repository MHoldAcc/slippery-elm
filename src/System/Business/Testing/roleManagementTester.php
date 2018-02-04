<?php

include_once("System/Business/RoleManagement/roleManagement.php");

class elm_roleManagementTester{
    /**
     * Tests if is role unique works
     */
    public static function isRoleUnique() {
        //Tests role admin which already exists
        if(elm_RoleManger::isRoleUnique("user"))
            throw new Exception("Not unique role counts as unique");
        if(!elm_RoleManger::isRoleUnique("789abcdefghijklmno"))
            throw new Exception("Unique role counts as not unique");
    }

    /**
     * Tests if add role and delete role works
     */
    public static function addRoleAndDeleteRole() {
        
    }
}

?>