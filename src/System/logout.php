<?php
session_start();
session_destroy();

echo "Loging out";
header("Location: ../index.php");
?>