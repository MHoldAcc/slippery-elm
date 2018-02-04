<?php
    if ($_SESSION['login_failure'] == true) {
        /**
         * Checks if the user is an admin
         * */
        if ($_SESSION['login_role_fk'] == 1) {
            echo '<h1>Adminpage</h1><p>Admin Settings coming in next Release of Slippery elm!</p><br><h2>Functionality Check</h2><form action="index.php?elmUnit_Testing" method="post"><input value="Execute functionality check" name="elm_elmUnit_ExecuteTests" type="submit"></form>';
        } else {
            echo "You have no permissions to see this page.<br>";
        }
    } else {
        echo "You must be logged in to see this content.";
    }
?>