<?php
    session_start();
    unset($_SESSION["taikhoan"]);
    unset($_SESSION["maKhachHang"]);
    unset($_SESSION["tenKhachHang"]);

    echo '<meta http-equiv="refresh" content="0;URL=index.php">';
?>