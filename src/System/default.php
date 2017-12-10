<?php

include_once("/settings.php");

//Check Database if Installation was already done
if(true){
    include_once("/System/Installation/installation.php");
}
else{
    include_once("/System/loadPage.php");
}
?>