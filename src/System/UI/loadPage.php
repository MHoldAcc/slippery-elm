<?php

function elm_Page_Load(){
    $elm_Page_HTML = file_get_contents('Styling/index.html', FILE_USE_INCLUDE_PATH);

    $elm_Page_Content = '<h1>404 Page not found</h1>';

    if(isset($_GET['login']))
        $elm_Page_Content = file_get_contents('System/UI/HTML/loginMask.html', FILE_USE_INCLUDE_PATH);

    $elm_Page_HTML = elm_Page_ReplacePlaceholder($elm_Page_HTML, "[elm_PageContent]", $elm_Page_Content);

    eval('?>'. $elm_Page_HTML . '<?php');
}

function elm_Page_ReplacePlaceholder($pagecontent, $placeholder, $value){
    $pagecontent = str_replace("$placeholder", $value, $pagecontent);
    return $pagecontent;
}

?>