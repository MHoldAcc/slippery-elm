<?php

//Does basic includes
include_once("config.php");
include_once("version.php");
include_once("System/Business/Objects/objects.php");
include_once("System/Database/data.php");

//Check if installation was already done
if(!elm_Data_GetIsDbInitialized()){
    include_once("System/Business/Installation/installation.php");
}
//If installation was done but no admin user got created yet:
else if (count(elm_Data_GetUsers()) === 0){
    include_once("System/Business/Installation/installation.php");
}
//If installation was completed:
else{
    //Execute Update if needed
    if($elm_Version_Number != elm_Data_GetCurrentVersion())
        elm_Data_ExecuteUpdate();

    //Includes loading the page and content functionality
    include_once("System/UI/pageLoader.php");
    $pageLoader = new elm_PageLoader;
    $pageLoader->printPageContent();
}
?>