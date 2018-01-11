<?php


function elm_Page_PageManagementFunctionality() {
    global $elm_Page_CurrentPage;

    if (isset($_GET['editPage_admin']) && isset($_GET['id'])){
        $elm_Page_CurrentPage = new elm_Page();
        $elm_Page_CurrentPage -> name = 'Page';
        $elm_Page_CurrentPage -> id = 'elm_Admin_EditPage';
        $elm_Page_CurrentPage -> content = file_get_contents('System/UI/HTML/editPageMask.php', FILE_USE_INCLUDE_PATH);
    }

    else if (isset($_POST['elm_EditPage_Execute_admin'])){
        if (isset($_POST['elm_EditPage_Titel']) && isset($_POST['elm_EditPage_Keyword']) && isset($_POST['elm_EditPage_Sorting']) && isset($_POST['elm_EditPage_Content']) && isset($_POST['elm_EditPage_Id'])) {
            elm_Data_AdminUpdatePage($_POST['elm_EditPage_Id'], $_POST['elm_EditPage_Titel'], $_POST['elm_EditPage_Content'], $_POST['elm_EditPage_Keyword'], $_POST['elm_EditPage_Sorting']);
            header("Location: index.php?page=elm_Page_Edit");
        }
        else {
            //TODO: error handling
            echo "error";
        }
    }

    /*else if (isset($_GET['deletePage_admin'])){
        if (isset($_GET['id'])) {
            elm_Data_DeletePage($_GET['id']);
            header("Location: index.php?page=elm_Page_Edit");
        }
        else {
            //TODO: error handling
            echo "error";
        }
    }*/
}

?>