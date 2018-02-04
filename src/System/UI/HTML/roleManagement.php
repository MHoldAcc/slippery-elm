<?php
    GLOBAL $elm_Data;
    //load roles form db
    $roles = $elm_Data->getRole();

?>
<h1>Role Management</h1>
</br>

<a class="content_link" href="index.php?addRole_admin" >Neue Rolle hinzufügen</a>

</br></br>

<table class="roleMgmt_table">
    <tr style="font-weight: bold">
        <td>Name</td>
        <td>Description</td>
        <td>Members</td>
        <td>Action</td>
    </tr>
    <?php
        //create table row for each user
        foreach($roles as $role){
            //get assignedUsers of role
            $assignedUsers = $elm_Data->getUsersByRole($role['roleid']);
            echo "<tr>".
                    "<td>".$role['rolename']."</td>".
                    "<td>".$role['roledescription'] ."</td>".
                    "<td>".count($assignedUsers)." User(s)</td>".
                    "<td>".
                        // don't allow role deletion if users are assigned
                        (count($assignedUsers) == 0 ? "<a class=\"content_link\" href='index.php?id=".$role['roleid']."&deleteRole_admin'>Löschen</a> " : "-").
                    "</td>".
                "</tr>";
        }
    ?>
</table>

