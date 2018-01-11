<h1>Seite bearbeiten</h1>
</br></br>

<form action="#" method="post">
    <h1></h1>
    <table>
        <tr>
            <input type="hidden" id="elm_EditPage_Id" name="elm_EditPage_Id" value="<?php echo $_GET['id']; ?>" />
        <tr>
            <td>Seitentitel: </td>
            <td><input type="text" id="elm_EditPage_Titel" name="elm_EditPage_Titel" size="42" autofocus ></td>
        </tr>

        <tr>
            <td>keyword:</td>
            <td><input type="text" id="elm_EditPage_Keyword" name="elm_EditPage_Keyword" size="42" > </td>
        </tr>

        <tr>
            <td>Sortierung:</td>
            <td><input type="text" id="elm_EditPage_Sorting" name="elm_EditPage_Sorting" size="42" > </td>
        </tr>

        <tr>
            <td>Seiteninhalt:</td>
            <td><textarea id="elm_EditPage_Content" name="elm_EditPage_Content" rows="10" cols="40"></textarea> </td>
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
