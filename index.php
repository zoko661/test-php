<?php
session_start();
require_once("Connection.php");
?>



<?php
//Sign Up
if (isset($_POST["btnSignUp"])) {
    $ten = $_POST["txtDangKyHoTen"];
    $tk = $_POST["txtDangKyTaiKhoan"];
    $mk = md5($_POST["txtDangKyMatKhau"]);
    $sdt = $_POST["txtDangKySoDienThoai"];
    $dc = $_POST["txtDangKyDiaChi"];

    $str = $connection->query("select kh_taikhoan from khachhang where kh_taikhoan ='" . $tk . "'");
    $kt = mysqli_num_rows($str);
    $str1 = $connection->query("select kh_sodienthoai from khachhang where kh_sodienthoai ='" . $sdt . "'");
    $kt1 = mysqli_num_rows($str1);
    if ($kt > 0) {
        echo "<script> alert('Tài khoản đã tồn tại'); </script>";
    } else if ($kt1 > 0) {
        echo "<script> alert('Số điện thoại đã tồn tại'); </script>";
    } else {
        $sql = "insert into khachhang(kh_ten,kh_diachi,kh_taikhoan,kh_matkhau,kh_sodienthoai) 
            values('" . $ten . "','" . $dc . "','" . $tk . "','" . $mk . "','" . $sdt . "')";
        $dangky = $connection->query($sql);
        if (!$dangky) {
            echo "<script> alert('Đăng ký thất bại'); </script>";
        } else {
            echo "<script> alert('Đăng ký thành công'); </script>";
        }
        echo '<meta http-equiv="refresh" content="0;URL=index.php">';
    }
}

//Log In = JSON
if (isset($_POST["btnLogin"])) {
    $tk = $_POST["txtTK"];
    $mk =  md5($_POST["txtMK"]);

    $url = "http://localhost/php/Clogin.php?tk=" . $tk . "&mk=" . $mk;

    $client = curl_init($url);


    curl_setopt($client, CURLOPT_RETURNTRANSFER, true);


    $response = curl_exec($client);
    $result = json_decode($response, true);

    if (isset($result)) {
        foreach ($result as $key) {
            if ($key[3] == 0) {
                echo "<script> alert('Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên để biết thêm chi tiết.'); </script>";
            } else {
                $_SESSION["taikhoan"] = $key[0];
                $_SESSION["maKhachHang"] = $key[1];
                $_SESSION["tenKhachHang"] = $key[2];
                echo "<script> alert('Đăng nhập thành công'); </script>";
            }
        }
    } else {
        $sql = $connection->query("select nv_taikhoan from nhanvien where nv_taikhoan = '" . $tk . "' and nv_matkhau = '" . $mk . "'");
        if (mysqli_num_rows($sql) > 0) {
            echo '<meta http-equiv="refresh" content="0;URL=Alogin.php">';
        } else {
            echo "<script> alert('Tài khoản hoặc mật khẩu không đúng'); </script>";
        }
    }
}

//Add to cart
if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = array();
}

function cart($connection)
{
    $idsp = $_GET["idsp"];

    $rsCart = $connection->query("select sp_ma,sp_ten,sp_gia,sp_soluong from sanpham where sp_ma =" . $idsp);

    $rowCart = mysqli_fetch_row($rsCart);
    if ($rowCart[3] > 0) {
        $valid = false;

        foreach ($_SESSION["cart"] as $key => $value) {
            if ($key == $idsp) {
                $_SESSION["cart"][$key]["soluong"] += 1;
                $valid = true;
            }
        }
        if (!$valid) {
            $tensp = $rowCart[1];
            $giasp = $rowCart[2];

            $gioHang = array(
                "masp" => $idsp,
                "tensp" => $tensp,
                "giasp" => $giasp,
                "soluong" => 1
            );
            $_SESSION["cart"][$idsp] = $gioHang;
        }
    } else {
        echo "<script> alert('Sản phẩm hết hàng. Xin lỗi vì sự bất tiện này'); </script>";
    }
}
if (isset($_GET["func"]) && isset($_GET["idsp"])) {
    cart($connection);
    echo '<meta http-equiv="refresh" content="0;URL=index.php">';
}
?>

<?php
//Tim kiem = JSON
$arr = "";
if (isset($_POST["btnTimKiem"])) {
    $timkiem = $_POST["txtTimKiem"];
    $url = "http://localhost/php/Ctimkiem.php?data=" . $timkiem;

    $client = curl_init($url);


    curl_setopt($client, CURLOPT_RETURNTRANSFER, true);


    $response = curl_exec($client);
    $arr = json_decode($response, true);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZuZuKo - My Website</title>
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
        <!-- header -->
        <div class="row ">
            <nav class="navbar navbar-expand-sm navbar-light " style="background-color: #e3f2fd; width:100%;">
                <a class="navbar-brand" href="index.php" style="margin-left: 100px;">
                    <img src="images/logo.png" alt="" width="45px;">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <ul class="nav nav-pills row">
                            <li class="nav-item col-12 col-sm-6 col-md-8 col-lg-8 col-xl-8">
                                <form class="form-inline" action="index.php?key=Ckqtimkiem" method="POST">
                                    <div class="input-group " style="width:100%">
                                        <input type="text" class="form-control" onkeyup="goiY();" aria-haspopup="true" aria-expanded="false" id="txtTimKiem" name="txtTimKiem" placeholder="Tìm kiếm">
                                        <ul class="dropdown-menu" id="dsGoiY" style="width: 100%;">
                                        </ul>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-info" type="submit" name="btnTimKiem">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                        <!-- Goi y tim kiem -->

                                        
                                    </div>
                                </form>
                                <script>
                                    $(document).ready(function() {
                                        $("#txtTimKiem").focusin(function() {
                                            $("#dsGoiY").show();
                                        });
                                        $("#txtTimKiem").focusout(function() {
                                            $("#dsGoiY").hide();
                                        });
                                        $("#dsGoiY li").click(function() {
                                           setText(this); 
                                        });

                                        function setText(element) {
                                            var value = $(element).text();
                                            $("#txtTimKiem").val(value);
                                        }
                                    });
                                </script>
                                <script>
                                    function goiY() {
                                        var str = document.getElementById("txtTimKiem").value;
                                        var url = "http://localhost/php/Cgoiy.php?str=" + str;
                                        var xhttp;

                                        if (window.XMLHttpRequest) {
                                            xhttp = new XMLHttpRequest();
                                        } else {
                                            xhttp = new ActiveXObject("Microsoft.XMLHTTP");
                                        }

                                        xhttp.onreadystatechange = function() {
                                            if (this.readyState == 4 && this.status == 200) {
                                                // alert(this.responseText);
                                                var data = "";
                                                var id;
                                                var rs = xhttp.responseXML.documentElement.getElementsByTagName("goiy");
                                                // alert(rs.length);
                                                for (var i = 0; i < rs.length; i++) {
                                                    id = rs[i].getElementsByTagName("data");
                                                    data += '<li class="dropdown-item" >' + id[0].firstChild.nodeValue + '</li>';
                                                }
                                                // alert(data);
                                                document.getElementById("dsGoiY").innerHTML = data;
                                            }
                                        }
                                        xhttp.open("GET", url, true);
                                        xhttp.send();
                                    }

                                    function getGoiY() {
                                        alert("Asdadsa");
                                        // var goiy = id;
                                        // document.getElementById("txtTimKiem").value = "";
                                    }
                                </script>
                            </li>
                            <li class="nav-item col-6 col-sm-3 col-md-2 col-lg-2 col-xl-2">
                                <a class="nav-link btn btn-outline-info" href="index.php?key=Ccart">
                                    <i class="fas fa-shopping-cart"></i>
                                    <?php
                                    if (isset($_SESSION["cart"]) && count($_SESSION["cart"]) > 0) {
                                        echo count($_SESSION["cart"]);
                                    }
                                    ?>
                                </a>
                            </li>
                            <li class="nav-item col-6 col-sm-3 col-md-2 col-lg-2 col-xl-2">
                                <?php
                                if (!isset($_SESSION["taikhoan"])) {
                                ?>
                                    <a class="nav-link btn btn-outline-info" href="#modalUser" data-toggle="modal">
                                        <i class="fas fa-user"></i>
                                        <input type="hidden" value="" id="maKH">
                                    </a>
                                <?php
                                } else {
                                ?>
                                    <a class="nav-link btn btn-outline-info" data-toggle="dropdown" href="#navbarDropdownMenuLink" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-user"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right dropdown-menu-sm-right dropdown-menu-md-right dropdown-menu-lg-right dropdown-menu-xl-right" aria-labelledby="navbarDropdownMenuLink">
                                        <li href="" class="dropdown-item disabled" id="maKH" value="<?php echo $_SESSION["maKhachHang"]; ?>" href="#" tabindex="-1" aria-disabled="true">
                                            <b>Mã: </b>
                                            <?php echo $_SESSION["maKhachHang"]; ?>
                                        </li>
                                        <div class="dropdown-divider"></div>
                                        <li href="" class="dropdown-item disabled" href="#" tabindex="-1" aria-disabled="true">
                                            <b>Tài khoản: </b>
                                            <?php echo $_SESSION["taikhoan"]; ?>
                                        </li>
                                        <div class="dropdown-divider"></div>
                                        <li href="" class="dropdown-item disabled" href="#" tabindex="-1" aria-disabled="true">
                                            <b>Khách hàng: </b>
                                            <?php echo $_SESSION["tenKhachHang"]; ?>
                                        </li>
                                        <div class="dropdown-divider"></div>
                                        <div class="text-center">
                                            <a href="Clogout.php" class="badge badge-danger text-wrap">Đăng Xuất</a>
                                        </div>
                                    </ul>
                                <?php
                                }
                                ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
        <script>
            $(document).ready(function() {
                $('.active').carousel({
                    interval: 2000
                })
            })
        </script>
        <div class="row">
            <div id="carouselExampleControls" class="carousel slide" data-ride="carousel" style="width:100%;">
                <div class="carousel-inner ">
                    <div class="carousel-item active">
                        <img src="images/banner.jpg" class="d-block w-100" height="300px" alt="">
                    </div>
                    <div class="carousel-item ">
                        <img src="images/banner1.jpg" class="d-block w-100" width="100%" height="300px" alt="">
                    </div>
                    <div class="carousel-item ">
                        <img src="images/banner2.jpg" class="d-block w-100" width="100%" height="300px" alt="">
                    </div>
                    <div class="carousel-item ">
                        <img src="images/banner3.jpg" class="d-block w-100" width="100%" height="300px" alt="">
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
        <!-- /header -->

        <!-- body -->
        <div class="row" style="margin-top: 40px; margin-bottom:40px">
            <?php
            if (!isset($_GET["key"])) {
                include_once("Ccontent_index.php");
            }else if(!file_exists($_GET["key"].".php")){
                echo ("Key invalid.");
            }else {
                include_once($_GET["key"] . ".php");
            }
            ?>
        </div>
        <!-- /body -->

        <!-- footer -->
        <footer class="row" style="background-color: #e3f2fd;">
            <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                <b>Giới Thiệu</b>
                <p class="text-monospace">
                    Đây wesite thương mại điện tử chuyên kinh doanh các sản phẩm công nghệ cao.
                    Với 2000 năm kinh nghiệm trong nghề, chúng tôi tin rằng mình sẽ đưa đến các dịch vụ tốt nhất để làm hài lòng tất cả quý khách.
                    Một lần nữa, ZuZuKo xin cảm ơn vì đã vinh dự được phục vụ các quý khách. <br>
                    <hr>
                    Chủ tịch ZuZuKo, <br>
                    Phạm Thanh Duy
                </p>
            </div>
            <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                <b>Thông Tin</b> <br>
                <hr>
                <p class="text-monospace"><b>Lịch Sử:</b> Trang web được thiết kế vào giữa tháng 12/2020. ZuZuKo có lịch sử kinh doanh lâu đời, với uy tín tuyệt đối.</p>
                <p class="text-monospace"><b>Địa Chỉ:</b> Hẻm 12, đường 3/2, Ninh Kều, Cần Thơ</p>

            </div>
            <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                <b>Link</b> <br>
                <hr>
                <a href="https://www.facebook.com/profile.php?id=100008220699068">Facebook</a> <br>
                <a href="index.php">ZuZuKo</a><br>
                <a href="https://www.w3schools.com/">w3schools</a><br>
                <a href="https://www.google.com.vn/">Google</a><br>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center" style="background-color: #008080;">
                <b style="color:#F5F5F5">COPYRIGHT &copy 2021 by Pham Thanh Duy. All Rights Reserved.</b>
            </div>
        </footer>

        <!-- /footer -->
    </div>

    <script>
        $(document).ready(function() {
            $("#frmSignUp").hide();
            $("#LogIn").hide();
            $("#SIGNUP").click(function() {
                $("#frmSignUp").show();
                $("#frmLogIn").hide();
                $("#SignUp").hide();
                $("#LogIn").show();
            });
            $("#LOGIN").click(function() {
                $("#frmSignUp").hide();
                $("#frmLogIn").show();
                $("#SignUp").show();
                $("#LogIn").hide();
            });
        });
    </script>

    <!-- Modal user-->
    <div class="modal fade" id="modalUser" tabindex="-1" role="dialog" aria-labelledby="modalUserLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUserLabel">Xin chào quý khách</h5>
                    <a href="" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body">
                    <div id="frmLogIn">
                        <script>
                            function checkLogin() {
                                var tk = document.getElementById("txtTK").value;
                                var mk = document.getElementById("txtMK").value;

                                if (tk.length == 0 || mk.length == 0) {
                                    alert("Tài khoản và mật khẩu không được rỗng");
                                } else {
                                    if (tk.length < 6 || tk.length > 18) {
                                        alert("Tài khoản phải là 6 đến 18 ký tự");
                                    } else if (mk.length < 6 || mk.length > 32) {
                                        alert("Mật khẩu phải là 6 đến 25 ký tự");
                                    } else {
                                        document.getElementById("btnLogin").name = "btnLogin";
                                    }
                                }
                            }
                        </script>
                        <form action="" method="post" onsubmit="checkLogin();">
                            <div class="form-group">
                                <label for="#TaiKhoan">Tài Khoản</label>
                                <input type="text" id="txtTK" name="txtTK" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="#MatKhau">Mật Khẩu</label>
                                <input type="password" id="txtMK" name="txtMK" class="form-control">
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" id="btnLogin" class="btn btn-outline-info">Đăng Nhập</button>
                            </div>
                        </form>
                    </div>
                    <div id="frmSignUp">
                        <script>
                            function checkSignUp() {
                                var vnf_regex = /((09|03|07|08|05)+([0-9]{8})\b)/g;
                                var tk_regex = /[a-zA-Z0-9]{6,18}/g;
                                var mk_regex = /[a-zA-Z0-9]{6,32}/g;
                                var name_regex = /[a-zA-Z0-9]{6,50}/g;
                                var tk = document.getElementById("txtDangKyTaiKhoan").value;
                                var mk = document.getElementById("txtDangKyMatKhau").value;
                                var ten = document.getElementById("txtDangKyHoTen").value;
                                var sdt = document.getElementById("txtDangKySoDienThoai").value;
                                if (tk.length == 0 || mk.length == 0 || ten.length == 0 || sdt.length == 0) {
                                    alert("Thông tin cá nhân không được rỗng");
                                } else {
                                    if (tk_regex.test(tk) == false) {
                                        alert("Tài khoản phải là chữ, số và phải từ 6-18 ký tự");
                                    } else if (mk_regex.test(mk) == false) {
                                        alert("Mật khẩu phải là chữ, số và phải từ 6-32 ký tự");
                                    } else if (vnf_regex.test(sdt) == false) {
                                        alert("Số điện thoại không đúng định dạng");
                                    } else if(name_regex.test(ten) == false){
                                        alert("Tên phải là chữ, số và từ 6-50 ký tự.");
                                    } 
                                    else {
                                        document.getElementById("btnSignUp").name = "btnSignUp";
                                    }
                                }
                            }
                        </script>
                        <form action="" method="post" onsubmit="checkSignUp();">
                            <div class="form-group">
                                <label for="#HoTen">Họ Tên</label>
                                <input type="text" id="txtDangKyHoTen" name="txtDangKyHoTen" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="#TaiKhoan">Tài Khoản</label>
                                <input type="text" id="txtDangKyTaiKhoan" name="txtDangKyTaiKhoan" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="#MatKhau">Mật Khẩu</label>
                                <input type="password" id="txtDangKyMatKhau" name="txtDangKyMatKhau" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="#SoDienThoai">Số Điện Thoại</label>
                                <input type="text" id="txtDangKySoDienThoai" name="txtDangKySoDienThoai" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="#DiaChi">Địa Chỉ</label>
                                <textarea name="txtDangKyDiaChi" id="txtDangKyDiaChi" cols="" rows="5" class="form-control"></textarea>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" id="btnSignUp" class="btn btn-outline-info">Đăng Ký</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <div id="SignUp">
                        <small>Nếu bạn chưa có tài khoản. Hãy đăng ký ngay ! </small>
                        <a href="#" class="badge badge-success text-wrap" id="SIGNUP">Đăng Ký</a>
                    </div>
                    <div id="LogIn">
                        <small>Vô số ưu đãi đang chờ bạn. Hãy đăng nhập ngay !</small>
                        <a href="#" class="badge badge-info text-wrap" id="LOGIN">Đăng Nhập</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>