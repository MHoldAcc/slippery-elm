<?php

/**
 * This function loads/handels the pagemanagement and checks if something
 * is used/selected or not.
 * @param null
 * @return nothing
 */
function elm_Page_PageManagementFunctionality() {
    global $elm_Page_CurrentPage;

    /**
     * Gets triggered after "add new page" is clicked
     * */
    if (isset($_GET['addPage_admin'])){
        $elm_Page_CurrentPage = new elm_Page();
        $elm_Page_CurrentPage -> name = 'Add Page';
        $elm_Page_CurrentPage -> id = 'elm_Admin_AddPage';
        $elm_Page_CurrentPage -> content = file_get_contents('System/UI/HTML/addPageMask.php', FILE_USE_INCLUDE_PATH);

        /**
         * Gets triggered after "edit page" is clicked
         * */
    } else if (isset($_GET['editPage_admin']) && isset($_GET['id'])){
        $elm_Page_CurrentPage = new elm_Page();
        $elm_Page_CurrentPage -> name = 'Edit Page';
        $elm_Page_CurrentPage -> id = 'elm_Admin_EditPage';
        $elm_Page_CurrentPage -> content = file_get_contents('System/UI/HTML/editPageMask.php', FILE_USE_INCLUDE_PATH);
    }

    /**
     * Used to edit and save  a page
     * */
    else if (isset($_POST['elm_EditPage_Execute_admin'])){
        /**Parameter:
         * $pageID, $pageName, $title, $parentPage, $content, $keywords, $sorting
         **/
         if (isset($_POST['elm_EditPage_Titel']) && isset($_POST['elm_EditPage_Keyword']) && isset($_POST['elm_EditPage_Sorting']) && isset($_POST['elm_EditPage_Content']) && isset($_POST['elm_EditPage_Id'])) {
            elm_Data_AdminUpdatePage($_POST['elm_EditPage_Id'], $_POST['elm_EditPage_Titel'], $_POST['elm_EditPage_Content'], $_POST['elm_EditPage_parentPage'], $_POST['elm_EditPage_Keyword'], $_POST['elm_EditPage_Sorting']);
            header("Location: index.php?page=elm_elm_Admin_EditPage");
        }
        else {
            //TODO: error handling
            echo "error";
        }
    }

    /**
     * Add/create a new page
     * */
    else if (isset($_POST['elm_addPage_Execute_admin'])){
        if (isset($_POST['elm_addPage_Titel']) && isset($_POST['elm_addPage_Keyword']) && isset($_POST['elm_addPage_Sorting']) && isset($_POST['elm_addPage_Content']) && isset($_POST['elm_addPage_ParentPage'])) {
            if (elm_Data_CreatePage($_POST['elm_addPage_Titel'], $_POST['elm_addPage_Content'], $_POST['elm_addPage_ParentPage'], $_POST['elm_addPage_Keyword'], $_POST['elm_addPage_Sorting'])) {
                header("Location: index.php?page=elm_Page_Edit");
            }
        }
        else {
            //TODO: error handling
            echo "error";
        }
    }

    /**
     * Used to delete a page
     * */
    else if (isset($_GET['deletePage_admin']) && isset($_GET['id'])) {
        if(isset($_GET['id'])) {
            elm_Data_DeletePages($_GET['id']);
        } else {
            //TODO: error handling
            echo "error";
        }

        header("Location: index.php?page=elm_Page_Edit");
    }
}


?>