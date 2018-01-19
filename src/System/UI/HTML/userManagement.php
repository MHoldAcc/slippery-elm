<?php
    //load users and roles form db
    $users = elm_Data_GetUsers();
    $roles = elm_Data_GetRole();

    //create roleMap: key-> roleID, value-> roleName
    //used to display roleName in Table
    $roleMap = array();
    foreach ($roles as $role){
        $roleMap[$role['roleID']] = $role['roleName'];
    }

?>
<h1>User Management</h1>
</br>

<a class="content_link" href="index.php?addUser_admin" >Neuen User hinzufügen</a>

</br></br>

<table class="userMgmt_table">
    <tr>
        <th>Username</th>
        <th>E-Mail</th>
        <th>Rolle</th>
        <th>Actions</th>
        <th></th>
    </tr>
    <?php
        //create table row for each user
        foreach($users as $user){
            echo "<tr>".
                    "<td>".$user['username']."</td>".
                    "<td>".$user['email'] ."</td>".
                    "<td>".$roleMap[$user['role_FK']]."</td>".
                    "<td>".
                        "<a class=\"content_link\" href='index.php?id=".$user['usersID']."&editUser_admin'>Editieren</a>".
                    "</td>".
                    "<td>".
                        "<a class=\"content_link\" href='index.php?id=".$user['usersID']."&deleteUser_admin'>Löschen</a> ".
                    "</td>".
                "</tr>";
        }
    ?>
</table>

