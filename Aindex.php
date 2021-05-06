<?php
// ob_start();
session_start();
require_once("Connection.php");
require_once("Classes/PHPExcel.php");
?>

<?php
if (isset($_SESSION["cv"]) && isset($_GET["key"])) {
    if ($_SESSION["cv"] == 0 && ($_GET["key"] == "Anhanvien" || $_GET["key"] == "Athongke" || $_GET["key"] == "AHTTT")) {
        // echo "<script> alert('Bạn không có quyền sử dụng chức năng này'); </script>";
        echo '<meta http-equiv="refresh" content="0;URL=Aindex.php">';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator - ZuZuKo</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <!-- Datatable -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css"> -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> -->
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
</head>

<body>
    <div class="container-fluid">
        <div class="row" style="margin-top: 20px;">
            <nav class="navbar navbar-expand-sm navbar-light" style="background-color: #e3f2fd; width:100%;">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-4">
                    <a class="navbar-brand" href="Aindex.php">
                        <b>
                            <img src="images/logo.png" alt="" width="75%;">
                        </b>
                    </a>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
                    <ul class="nav justify-content-end">
                        <li class="nav-item">
                            <a class="nav-link disabled" href="#" aria-disabled="true">
                                <b>
                                    <i>
                                        <?php
                                        if (empty($_SESSION["nv"])) {
                                            echo '<meta http-equiv="refresh" content="0;URL=Alogin.php">';
                                        } else {
                                            echo $_SESSION["nv"];
                                        }
                                        ?>
                                    </i>
                                </b>
                            </a>
                        </li>
                        <li class="nav-item"><a href="Alogout.php" class="nav-link">Thoát</a></li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="row" style="margin-top: 20px; margin-bottom: 20px;">
            <nav class="navbar navbar-expand-sm navbar-light justify-content-center" style="background-color: #e3f2fd; width:100%;">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-center" id="navbarNavDropdown">
                    <ul class="navbar-nav ">
                        <?php
                        if ($_SESSION["cv"] == 1) {
                        ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle btn btn-light" href="#" id="SPDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <b>Quản Lý Sản Phẩm</b>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="SPDropdown">
                                    <a class="dropdown-item" href="Aindex.php?key=Asanpham">Sản Phẩm</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="Aindex.php?key=Aloaisanpham">Loại Sản Phẩm</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="Aindex.php?key=Anhasanxuat">Nhà Sản Xuất</a>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn btn-light" href="Aindex.php?key=Akhachhang"><b>Khách Hàng</b></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn btn-light" href="Aindex.php?key=Anhanvien"><b>Nhân Viên</b></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn btn-light" href="Aindex.php?key=Agopy"><b>Góp Ý</b></a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link btn btn-light" href="Aindex.php?key=Athongke"><b>Thống Kê</b></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn btn-light" href="Aindex.php?key=AHTTT"><b>Hình Thức Thanh Toán</b></a>
                            </li>
                        <?php
                        } else {
                        ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle btn btn-light" href="#" id="SPDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <b>Quản Lý Sản Phẩm</b>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="SPDropdown">
                                    <a class="dropdown-item" href="Aindex.php?key=Asanpham">Sản Phẩm</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="Aindex.php?key=Aloaisanpham">Loại Sản Phẩm</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="Aindex.php?key=Anhasanxuat">Nhà Sản Xuất</a>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn btn-light" href="Aindex.php?key=Akhachhang"><b>Khách Hàng</b></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn btn-light disabled" href="Aindex.php?key=Anhanvien" aria-disabled="true"><b>Nhân Viên</b></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn btn-light" href="Aindex.php?key=Agopy"><b>Góp Ý</b></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn btn-light disabled" href="Aindex.php?key=Athongke" aria-disabled="true"><b>Thống Kê</b></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn btn-light disabled" href="Aindex.php?key=AHTTT" aria-disabled="true"><b>Hình Thức Thanh Toán</b></a>
                            </li>
                        <?php
                        }
                        ?>

                    </ul>
                </div>
            </nav>
        </div>
        <?php
        if (isset($_GET["key"])) {
            $key = $_GET["key"];
            include_once($key . ".php");
        }
        // ob_flush();
        ?>
    </div>
</body>