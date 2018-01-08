<?php

include_once("config.php");
include_once("version.php");
include_once("System/Business/Objects/objects.php");
include_once("System/Database/data.php");

//Check if installation was already done
if(!elm_Data_GetIsDbInitialized()){
    include_once("System/Business/Installation/installation.php");
}
else if (count(elm_Data_GetUsers()) === 0){
    include_once("System/Business/Installation/installation.php");
}
else{
    //Execute Update if needed
    if($elm_Version_Number != elm_Data_GetCurrentVersion())
        elm_Data_ExecuteUpdate();

    include_once("System/UI/loadPage.php");
    elm_Page_Load();
}
?>