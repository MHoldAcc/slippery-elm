<?php
    if ($_SESSION['login_failure'] == true) {
        /**
         * Checks if the user is an admin
         * */
        if ($_SESSION['login_role_fk'] == 1) {
            echo "<h1>Adminpage</h1><p>Admin Settings coming in next Release of Slippery elm!</p>";
        } else {
            echo "You have no permissions to see this page.<br>";
        }
    } else {
        echo "You must be logged in to see this content.";
    }
?>