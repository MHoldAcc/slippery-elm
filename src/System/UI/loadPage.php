<?php
//Includes the needed files
include_once("System/Business/Login/login.php");
include_once("System/Business/User/user.php");
include_once("System/Business/UserManagement/userManagement.php");
include_once("System/Business/PageManagement/pageManagement.php");
include_once("System/Business/RoleManagement/roleManagement.php");

class elm_PageLoader {
    private $NavBar;
    private $HTML;
    private $CurrentPage;

    public function __construct()
    {
        $this->NavBar = '';
        $this->HTML = '';
        $this->CurrentPage = NULL;
    }

    /**
     * Loads and prints the content of the current webpage
     */
    public function PrintPageContent()
    {
        @session_start();

        $this->GetCurrentPageId();

        $this->HTML = file_get_contents('Styling/index.html', FILE_USE_INCLUDE_PATH);

        $this->CurrentPage = new elm_Page();
        $this->CurrentPage->name = '404 Error';
        $this->CurrentPage->id = 'elm_404';
        $this->CurrentPage->content = '<h1>404 Page not found</h1>';

        $this->SetNavBar();

        $this->elm_Page_LoginFunctionality();

        elm_Page_UserManagementFunctionality();

        elm_Page_PageManagementFunctionality();

        elm_Page_RoleManagementFunctionality();

        $this->ReplaceAllPlaceholders();

        eval('?>' . $this->HTML . '<?php');
    }

    /**elm_Page_Load
     * Replaces any text placeholder in html construct with given value
     * @param $placeholder The placeholder to replace
     * @param $value The value to set
     */
    function ReplacePlaceholder(string $placeholder, string $value)
    {
        $this->HTML = str_replace($placeholder, $value, $this->HTML);
    }

    /**
     * Sets Login Button (Login/Logout)
     * Load login form if url = index.php?login
     * Logs out user if url = index.php?logout
     */
    function elm_Page_LoginFunctionality()
    {
        //Login functionality
        if (isset($_GET['login'])) {
            $this->CurrentPage = new elm_Page();
            $this->CurrentPage->name = 'Login';
            $this->CurrentPage->id = 'elm_Login';
            $this->CurrentPage->content = file_get_contents('System/UI/HTML/loginMask.html', FILE_USE_INCLUDE_PATH);
        }

        if (isset($_GET['logout']))
            elm_Login_Logout();
        if (elm_Login_IsLoggedIn()) {
            $this->ReplacePlaceholder("[elm_Login_Link]", "index.php?logout");
            $this->ReplacePlaceholder("[elm_Login_Text]", "Abmelden");
        } else {
            $this->ReplacePlaceholder("[elm_Login_Link]", "index.php?login");
            $this->ReplacePlaceholder("[elm_Login_Text]", "Anmelden");
            if (isset($_POST['elm_Login_Execute'])) {
                elm_Login_Login($_POST['elm_Login_Username'], $_POST['elm_Login_Password'], $_POST['elm_Login_Password']);
            }
        }
    }

    /**
     * Creates the nav menu
     */
    function SetNavBar(){
        $menuContent = '';
        $allPages = $this->GetAllPages();
        foreach ($allPages as $page){
            if(!isset($page->parentPage) || strlen(str_replace(' ', '', $page->parentPage)) == 0){
                $currentId = $page->id;
                $menuContent = $menuContent . '<div class="dropdown-'. $currentId .'"><a class="dropbtn-' . $currentId . '" href="index.php?page='. $page->id . '">' . $page->name . '</a> <div class="dropdown-content-'. $currentId . '">';
                foreach ($allPages as $subpage){
                    if(isset($subpage->parentPage) && $page->id == $subpage->parentPage){
                        $menuContent = $menuContent. '<a href="index.php?page=' . $subpage->id . '">' . $subpage->name . '</a>';
                    }
                }
                $menuContent = $menuContent . '</div></div>';

                //Adds styling to menu so dropdown works
                $menuContent = $menuContent .
                    '<style>
                    .dropbtn-'.$currentId.' {
                        padding: 16px;
                        font-size: 16px;
                        border: none;
                        margin-bottom: 50px;
                    }
                
                    .dropdown-'. $currentId .' {
                        display: inline-block;
                    }
                   
                    .dropdown-content-'.$currentId.' {
                        display: none;
                        position: absolute;
                        min-width: 50px;
                        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
                        background-color: #333;
                        z-index: 1;
                        margin-top: 50px;
                    }
                
                    .dropdown-content-'.$currentId.' a {
                        padding: 12px 16px;
                        text-decoration: none;
                        display: block;
                    }
                
                    .dropdown-'. $currentId .':hover .dropdown-content-'.$currentId.' {
                        display: block;
                    }
                </style>';

            }
            //Sets page content if current page is in loop
            if($page->id == (string)$_SESSION['elm_Pages_CurrentPageId']){
                $this->SetCurrentPage($page);
            }
        }
        $this->NavBar = $menuContent;
    }

    /**
     * Sets the current page shown, as a global variable
     * @param $page The page to set
     */
    function SetCurrentPage(elm_Page $page)
    {
        $this->CurrentPage = $page;
    }

    /**
     * Gets all pages from the database and all predefined pages.
     * @return array An array of all pages as elm_Page objects
     */
    function GetAllPages()
    {
        $pages = elm_Data_GetPages();

        if (elm_Login_IsLoggedIn()) {

            if ($_SESSION['login_role_fk'] == 1) {
                //Adds Admin Page
                $adminpage = new elm_Page();
                $adminpage->id = 'elm_Admin';
                $adminpage->content = file_get_contents('System/UI/HTML/adminPage.php', FILE_USE_INCLUDE_PATH);
                $adminpage->name = 'Admin';
                $adminpage->sorting = 9900;
                array_push($pages, $adminpage);

                //Adds User Management Page
                $userMgmtPage = new elm_Page();
                $userMgmtPage->id = "elm_UserManagement";
                $userMgmtPage->content = file_get_contents('System/UI/HTML/userManagement.php', FILE_USE_INCLUDE_PATH);
                $userMgmtPage->name = "User Management";
                $userMgmtPage->parentPage = 'elm_Admin';
                $userMgmtPage->sorting = 9910;
                array_push($pages, $userMgmtPage);

                //Adds Role Management Page
                $roleMgmtPage = new elm_Page();
                $roleMgmtPage->id = "elm_RoleManagement";
                $roleMgmtPage->content = file_get_contents('System/UI/HTML/roleManagement.php', FILE_USE_INCLUDE_PATH);
                $roleMgmtPage->name = "Role Management";
                $roleMgmtPage->parentPage = 'elm_Admin';
                $roleMgmtPage->sorting = 9920;
                array_push($pages, $roleMgmtPage);
            }
            //Adds Edit Page
            $editPage = new elm_Page();
            $editPage->id = 'elm_Page_Edit';
            $editPage->content = file_get_contents('System/UI/HTML/editPage.php', FILE_USE_INCLUDE_PATH);
            $editPage->name = 'Edit Pages';
            $editPage->parentPage = 'elm_Admin';
            $editPage->sorting = 9930;
            array_push($pages, $editPage);

            //User edit Page
            $userPage = new elm_Page();
            $userPage->id = 'elm_User_Edit';
            $userPage->content = file_get_contents('System/UI/HTML/user.html', FILE_USE_INCLUDE_PATH);
            $userPage->name = $_SESSION['login_user'];
            $userPage->sorting = 9800;
            array_push($pages, $userPage);
        }

        //Orders Array
        usort($pages, function ($a, $b) {
            return strcmp($a->sorting, $b->sorting);
        });

        return $pages;
    }

    /**
     * Sets the id of the current page as a session variable
     * Also sets last page to the session
     * Variables = elm_Pages_CurrentPageId and elm_Pages_LastPageId
     */
    function GetCurrentPageId()
    {
        if (isset($_SESSION['elm_Pages_CurrentPageId'])) {
            $_SESSION['elm_Pages_LastPageId'] = $_SESSION['elm_Pages_CurrentPageId'];
            if (isset($_GET["page"])) {
                $_SESSION['elm_Pages_CurrentPageId'] = $_GET["page"];
            }
            else{
                $_SESSION['elm_Pages_CurrentPageId'] = 1;
            }
        } else{
            $_SESSION['elm_Pages_CurrentPageId'] = 1;
        }
    }

    /**
     * Replaces all placeholders created by slippery elm in the html construct
     */
    function ReplaceAllPlaceholders()
    {
        $this->ReplacePlaceholder("[elm_Page_Content]", $this->CurrentPage->content);
        $this->ReplacePlaceholder("[elm_Page_NavBar]", $this->NavBar);
        $this->ReplacePlaceholder("[elm_Page_Id]", $this->CurrentPage->id);
        $this->ReplacePlaceholder("[elm_Page_Name]", $this->CurrentPage->name);
    }
}

?>