<?php
    GLOBAL $elm_Data;
    //load users and roles form db
    $users = $elm_Data->getUsers();
    $roles = $elm_Data->getRole();

    //create roleMap: key-> roleID, value-> roleName
    //used to display roleName in Table
    $roleMap = array();
    foreach ($roles as $role){
        $roleMap[$role['roleid']] = $role['rolename'];
    }
?>
<h1>User Management</h1>
</br>

<a class="content_link" href="index.php?addUser_admin" >Neuen User hinzufügen</a>

</br></br>

<table class="userMgmt_table">
    <tr style="font-weight: bold">
        <td>Username</td>
        <td>E-Mail</td>
        <td>Rolle</td>
        <td>Actions</td>
        <td></td>
    </tr>
    <?php
        //create table row for each user
        foreach($users as $user){
            echo "<tr>".
                    "<td>".$user['username']."</td>".
                    "<td>".$user['email'] ."</td>".
                    "<td>".$roleMap[$user['role_fk']]."</td>".
                    "<td>".
                        "<a class=\"content_link\" href='index.php?id=".$user['usersid']."&editUser_admin'>Editieren</a>".
                    "</td>".
                    "<td>".
                        "<a class=\"content_link\" href='index.php?id=".$user['usersid']."&deleteUser_admin'>Löschen</a> ".
                    "</td>".
                "</tr>";
        }
    ?>
</table>

