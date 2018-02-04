<?php
GLOBAL $elm_Data;
$page = $elm_Data->getPageById($_GET['id']);

/**
 * Form to create a page. "save"-Button triggers pageManagement.
 * */
?>
<h1>Seite bearbeiten</h1>
</br></br>

<form action="index.php?page=elm_Page_Edit" method="post">
    <h1></h1>
    <table>
        <tr>
            <input type="hidden" id="elm_EditPage_Id" name="elm_EditPage_Id" value="<?php echo $_GET['id']; ?>" />
        <tr>
            <td>Seitennamen: </td>
            <td><input type="text" id="elm_EditPage_Titel" value="<?php echo $page->name; ?>" name="elm_EditPage_Titel" size="42" autofocus ></td>
        </tr>

        <tr>
            <td>Parent Page:</td>
            <td><input type="text" id="elm_EditPage_parentPage" value="<?php echo $page->parentPage; ?>" name="elm_EditPage_parentPage" size="42" > </td>
        </tr>

        <tr>
            <td>Keyword:</td>
            <td><input type="text" id="elm_EditPage_Keyword" value="<?php echo $page->keywords; ?>" name="elm_EditPage_Keyword" size="42" > </td>
        </tr>

        <tr>
            <td>Sortierung:</td>
            <td><input type="text" id="elm_EditPage_Sorting" value="<?php echo $page->sorting; ?>" name="elm_EditPage_Sorting" size="42" > </td>
        </tr>

        <tr>
            <td>Seiteninhalt:</td>
            <td><textarea id="elm_EditPage_Content" name="elm_EditPage_Content" rows="10" cols="40"><?php echo $page->content; ?></textarea> </td>
        </tr>

        <tr>
            <td colspan="2">
                <input type="submit" value="Speichern" id="elm_EditPage_Execute" name="elm_EditPage_Execute_admin">
            </td>
        </tr>
    </table>
</form>

<?php

?>
