<?php

//Variable to check if the user got created
$elm_Installation_UserGotCreated = false;

//If URL = index.php?install_db and database is non existing -> Initialize database
if(isset($_GET['install_db']) && !elm_Data_GetIsDbInitialized()){
    elm_Data_InitializeDb();
    //Refresh page after initializing db
    header("Refresh:0");
}
//If admin data is given in post -> Create admin user
else if(isset($_POST['elm_Post_Username']) && isset($_POST['elm_Post_Password']) && isset($_POST['elm_Post_Email'])){
    //Stripslashes from variables so there can't be any sql injections
    $elm_Post_Username = stripslashes($_POST['elm_Post_Username']);
    $elm_Post_Password = stripslashes($_POST['elm_Post_Password']);
    $elm_Post_Email = stripslashes($_POST['elm_Post_Email']);

    //Check if all variables have a value
    if($elm_Post_Username != '' && $elm_Post_Password != '' &&  $elm_Post_Email != ''){
        //Create admin user
        if(elm_Data_CreateUser($elm_Post_Username, $elm_Post_Password, $elm_Post_Email, elm_Data_GetRoleId('admin')))
            $elm_Installation_UserGotCreated = true;
    }
}

//Show the correct content depending on database state
if($elm_Installation_UserGotCreated) {
    //Refreshes page so page content can gets loaded
    header("Refresh:0");
}
else if(elm_Data_GetIsDbInitialized()){
    include_once("System/UI/HTML/adminCreation.html");
}
else {
    include_once("System/UI/HTML/installation.html");
}

?>