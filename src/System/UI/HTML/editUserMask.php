<h1>User bearbeiten</h1>
</br></br>

<form action="" method="POST">
    <table>
        <input type="hidden" id="elm_EditUser_Id" name="elm_EditUser_Id" value="<?php echo $_GET['id']; ?>" />
        <tr>
            <td><label>Username: </label></td>
            <td><input type="text" id="elm_EditUser_Username" name="elm_EditUser_Username" autofocus /></td>
        </tr>
        <tr>
            <td><label>Email: </label></td>
            <td><input type="email" id="elm_EditUser_Email" name="elm_EditUser_Email" /></td>
        </tr>
        <tr>
            <td><label>Passwort: </label></td>
            <td><input type="password" id="elm_EditUser_Password" name="elm_EditUser_Password" /></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" value="Speichern" id="elm_EditUser_Execute" name="elm_EditUser_Execute_admin" />
            </td>
        </tr>
    </table>
</form>