<?php

//Does basic includes
include_once("config.php");
include_once("version.php");
include_once("System/Business/Objects/objects.php");
include_once("System/Database/data.php");
include_once("System/Business/Installation/installation.php");

GLOBAL $elm_Data;
$elm_Data = new elm_Data();

//Check if installation was already done
if(!$elm_Data->getIsDbInitialized()){
    if(isset($_GET['install_db'])){
        elm_Installation::doInstallation();
        header("Refresh:0");
    }
    else
        include_once("System/UI/HTML/installation.html");
}
//If installation was done but no admin user got created yet:
else if (count($elm_Data->getUsers()) === 0){
    //If admin data is given in post -> Create admin user
    if(isset($_POST['elm_Post_Username']) && isset($_POST['elm_Post_Password']) && isset($_POST['elm_Post_Email'])){
        elm_Installation::createAdminUser($_POST['elm_Post_Username'], $_POST['elm_Post_Password'], $_POST['elm_Post_Email']);
        header("Refresh:0");
    }
    else
        include_once("System/UI/HTML/installation.html");
}
//If installation was completed:
else{
    //Execute Update if needed
    if($elm_Version_Number != $elm_Data->getCurrentDbVersion())
        $elm_Data->executeUpdate();

    //Includes loading the page and content functionality
    include_once("System/UI/pageLoader.php");
    $pageLoader = new elm_PageLoader;
    $pageLoader->printPageContent();
}
?>