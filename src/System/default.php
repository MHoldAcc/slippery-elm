<?php

include_once("config.php");
include_once("System/objects.php");
include_once("System/Database/data.php");

//Check if installation was already done
if(elm_Data_GetIsDbInitialized() || count(elm_Data_GetUsers()) === 0){
    include_once("System/Installation/installation.php");
}
else{
    include_once("System/loadPage.php");
}
?>