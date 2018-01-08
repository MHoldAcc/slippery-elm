<?php
include_once("System/Business/Login/login.php");

function elm_Page_Load(){
    global  $elm_Page_HTML;
    global $elm_Page_Content;

    elm_Page_GetCurrentPageId();

    $elm_Page_HTML = file_get_contents('Styling/index.html', FILE_USE_INCLUDE_PATH);

    $elm_Page_Content = '<h1>404 Page not found</h1>';

    elm_Page_CreateMenu();

    elm_Page_LoginFunctionality();

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

function elm_Page_CreateMenu(){
    global $elm_Page_Content;

    $menuContent = '';
    foreach (elm_Page_GetAllPages() as $page){
        $menuContent = $menuContent . '<a href="index.php?page='. $page->id . '"';
        if($page->id == $_SESSION['elm_Pages_CurrentPageId']){
            $menuContent = $menuContent . 'class="active"';
            $elm_Page_Content = $page->content;
        }
        $menuContent = $menuContent . '>'. $page->name .'</a>';
    }
    elm_Page_ReplacePlaceholder("[elm_PageNav]", $menuContent);
}


function elm_Page_GetAllPages(){
    $pages = elm_Data_GetPages();

    //Add Admin Pages here



    //Orders all pages by sorting
    usort($pages, create_function('$a, $b', '
        $a = $a->sorting;
        $b = $b->sorting;

        if ($a == $b) return 0;

        $direction = strtolower(trim($direction));

        return ($a < $b) ? -1 : 1;
    '));
    return $pages;
}

function elm_Page_GetCurrentPageId(){
    if(isset($_SESSION['elm_Pages_CurrentPageId'])){
        if(isset($_GET["page"]))
            $_SESSION['elm_Pages_CurrentPageId'] = $_GET["page"];
    }
    else
        $_SESSION['elm_Pages_CurrentPageId'] = 0;
}

?>