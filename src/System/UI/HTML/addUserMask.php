<?php
    //get roles form db
    $roles = elm_Data_GetRole();
?>

<h1>User hinzufügen</h1>
</br></br>

<form action="" method="POST">
    <table>
        <tr>
            <td><label>Username: </label></td>
            <td><input type="text" id="elm_AddUser_Username" name="elm_AddUser_Username" autofocus /></td>
        </tr>
        <tr>
            <td><label>Email: </label></td>
            <td><input type="email" id="elm_AddUser_Email" name="elm_AddUser_Email" /></td>
        </tr>
        <tr>
            <td><label>Passwort: </label></td>
            <td><input type="password" id="elm_AddUser_Password" name="elm_AddUser_Password" /></td>
        </tr>
        <tr>
            <td><label>Rolle: </label></td>
            <td>
                <select id="elm_AddUser_Roll" name="elm_AddUser_Roll">
                    <?php
                        foreach($roles as $role){
                            echo "<option value=\"".$role['roleID']."\">".$role['roleName']."</option>";
                        }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" value="User erstellen" id="elm_NewUser_Execute" name="elm_NewUser_Execute_admin" />
            </td>
        </tr>
    </table>
</form>
