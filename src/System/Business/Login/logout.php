<?php
session_start();
session_unset();
session_destroy();

echo "Loging out";
header("Location: ../index.php");
?>