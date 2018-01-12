<?php
    $page = elm_Data_GetSpecificPages($_GET['id']);
?>
<h1>Seite bearbeiten</h1>
</br></br>

<form action="" method="post">
    <h1></h1>
    <table>
        <tr>
            <input type="hidden" id="elm_EditPage_Id" name="elm_EditPage_Id" value="<?php echo $_GET['id']; ?>" />
        <tr>
            <td>Seitennamen: </td>
            <td><input type="text" id="elm_EditPage_Titel" value="<?php echo $page[0]->name; ?>" name="elm_EditPage_Titel" size="42" autofocus ></td>
        </tr>

        <tr>
            <td>Keyword:</td>
            <td><input type="text" id="elm_EditPage_Keyword" value="<?php echo $page[0]->keywords; ?>" name="elm_EditPage_Keyword" size="42" > </td>
        </tr>

        <tr>
            <td>Sortierung:</td>
            <td><input type="text" id="elm_EditPage_Sorting" value="<?php echo $page[0]->sorting; ?>" name="elm_EditPage_Sorting" size="42" > </td>
        </tr>

        <tr>
            <td>Seiteninhalt:</td>
            <td><textarea id="elm_EditPage_Content" name="elm_EditPage_Content" rows="10" cols="40"><?php echo $page[0]->content; ?></textarea> </td>
        </tr>

        <tr>
            <td colspan="2">
                <input type="submit" value="Speichern" id="elm_EditPage_Execute" name="elm_EditPage_Execute">
            </td>
        </tr>
    </table>
</form>

<?php

?>
