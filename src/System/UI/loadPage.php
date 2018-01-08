<?php
include_once("System/Business/Login/login.php");

function elm_Page_Load(){
    @session_start();

    global  $elm_Page_HTML;
    global $elm_Page_Content;
    global $elm_Page_CurrentPage;

    elm_Page_GetCurrentPageId();

    $elm_Page_HTML = file_get_contents('Styling/index.html', FILE_USE_INCLUDE_PATH);

    $elm_Page_CurrentPage = new elm_Page();
    $elm_Page_CurrentPage -> name = '404 Error';
    $elm_Page_CurrentPage -> id = 'elm_404';
    $elm_Page_CurrentPage -> content = '<h1>404 Page not found</h1>';

    elm_Page_CreateMenu();

    elm_Page_LoginFunctionality();

    elm_Page_ReplacePlaceholder("[elm_PageContent]", $elm_Page_CurrentPage->content);

    eval('?>'. $elm_Page_HTML . '<?php');
}

function elm_Page_ReplacePlaceholder($placeholder, $value){
    global  $elm_Page_HTML;
    $elm_Page_HTML = str_replace($placeholder, $value, $elm_Page_HTML);
}

function elm_Page_LoginFunctionality(){
    global $elm_Page_CurrentPage;

    //Login functionality
    if(isset($_GET['login'])){
        $elm_Page_CurrentPage = new elm_Page();
        $elm_Page_CurrentPage -> name = 'Login';
        $elm_Page_CurrentPage -> id = 'elm_Login';
        $elm_Page_CurrentPage -> content = file_get_contents('System/UI/HTML/loginMask.html', FILE_USE_INCLUDE_PATH);
    }

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
    $menuContent = '';
    foreach (elm_Page_GetAllPages() as $page){
        $menuContent = $menuContent . '<a href="index.php?page='. $page->id . '"';
        if($page->id == (string)$_SESSION['elm_Pages_CurrentPageId']){
            $menuContent = $menuContent . 'class="active"';
            elm_Page_SetPageContent($page);
        }
        $menuContent = $menuContent . '>'. $page->name .'</a>';
    }
    elm_Page_ReplacePlaceholder("[elm_PageNav]", $menuContent);
}

function elm_Page_SetPageContent($page){
    global $elm_Page_Content;
    global $elm_Page_CurrentPage;
    $elm_Page_Content = $page->content;
    $elm_Page_CurrentPage = $page;
}

function elm_Page_GetAllPages(){
    $pages = elm_Data_GetPages();

    //Adds Admin Page
    $adminpage = new elm_Page();
    $adminpage -> id = 'elm_Admin';
    $adminpage -> content = file_get_contents('System/UI/HTML/adminPage.html', FILE_USE_INCLUDE_PATH);
    $adminpage -> name = 'Admin';
    $adminpage -> sorting = 9900;
    array_push($pages, $adminpage);

    //Adds Edit Page
    $editPage = new elm_Page();
    $editPage -> id = 'elm_Page_Edit';
    $editPage -> content = file_get_contents('System/UI/HTML/editPage.html', FILE_USE_INCLUDE_PATH);
    $editPage -> name = 'Seite bearbeiten';
    $editPage -> parentPage = 'elm_Admin';
    $editPage -> sorting = 9930;
    array_push($pages, $editPage);

    //Orders Array
    usort($pages, function ($a, $b){return strcmp($a->sorting, $b->sorting);});

    return $pages;
}

function elm_Page_GetCurrentPageId(){
    if(isset($_SESSION['elm_Pages_CurrentPageId'])){
        if(isset($_GET["page"])){
            $_SESSION['elm_Pages_LastPageId'] = $_SESSION['elm_Pages_CurrentPageId'];
            $_SESSION['elm_Pages_CurrentPageId'] = $_GET["page"];
        }
    }
    else
        $_SESSION['elm_Pages_CurrentPageId'] = 0;
}

?>