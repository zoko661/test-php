<?php
    session_start();
    unset($_SESSION["nv"]);
    unset($_SESSION["id"]);
    echo '<meta http-equiv="refresh" content="0;URL=Alogin.php">';
?>