<?php
    $connection = mysqli_connect("localhost","root","","zuzuko");

    if(!$connection){
        echo "Failed to connect to Database: ";
        exit();
    }
?>