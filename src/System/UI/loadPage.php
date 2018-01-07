<?php
include_once("System/Business/Login/login.php");

function elm_Page_Load(){
    global  $elm_Page_HTML;
    global  $elm_Page_Content;

    $elm_Page_HTML = file_get_contents('Styling/index.html', FILE_USE_INCLUDE_PATH);

    $elm_Page_Content = '<h1>404 Page not found</h1>';

    elm_Page_LoginFunctionality();

    foreach (getPages() as $page) {
        echo $page['pagesName'];
    }

    elm_Page_ReplacePlaceholder("[elm_PageContent]", $elm_Page_Content);

    eval('?>'. $elm_Page_HTML . '<?php');
}

function elm_Page_ReplacePlaceholder($placeholder, $value){
    global  $elm_Page_HTML;
    $elm_Page_HTML = str_replace($placeholder, $value, $elm_Page_HTML);
}

function elm_Page_LoginFunctionality(){
    global  $elm_Page_Content;

    //Login functionality
    if(isset($_GET['login']))
        $elm_Page_Content = file_get_contents('System/UI/HTML/loginMask.html', FILE_USE_INCLUDE_PATH);
    if(isset($_GET['logout']))
        elm_Login_Logout();
    if(elm_Login_IsLoggedIn()){
        elm_Page_ReplacePlaceholder("[elm_Login_Link]", "index.php?logout");
        elm_Page_ReplacePlaceholder("[elm_Login_Text]", "Abmelden");
    }
    else{
        elm_Page_ReplacePlaceholder("[elm_Login_Link]", "index.php?login");
        elm_Page_ReplacePlaceholder("[elm_Login_Text]", "Anmelden");
        if(isset($_POST['elm_Login_Execute'])){
            elm_Login_Login($_POST['elm_Login_Username'], $_POST['elm_Login_Password'], $_POST['elm_Login_Password']);
        }
    }
}

?>