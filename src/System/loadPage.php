<?php

$elm_Page_Content = '<h1>404 Page not found</h1>';

$elm_Page_HTML = file_get_contents('Styling/index.html', FILE_USE_INCLUDE_PATH);

$elm_Page_HTML = str_replace("[elm_PageContent]", $elm_Page_Content, $elm_Page_HTML);

eval('?>'. $elm_Page_HTML . '<?php');

?>