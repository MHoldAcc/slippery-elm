<?php
    //load roles form db
    $roles = elm_Data_GetRole();

?>
<h1>Roll Management</h1>
</br>

<a class="content_link" href="index.php?addRoll_admin" >Neue Rolle hinzufügen</a>

</br></br>

<table class="rollMgmt_table">
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
            //$assignedUsers = elm_Data_AssignmentRole($role['roleID']);
            $assignedUsers = array();
            echo "<tr>".
                    "<td>".$role['roleName']."</td>".
                    "<td>".$role['roleDescription'] ."</td>".
                    "<td>".count($assignedUsers)." User(s)</td>".
                    "<td>".
                        // don't allow roll deletion if users are assigned
                        (count($assignedUsers) == 0 ? "<a class=\"content_link\" href='index.php?id=".$role['roleID']."&deleteRoll_admin'>Löschen</a> " : "-").
                    "</td>".
                "</tr>";
        }
    ?>
</table>

