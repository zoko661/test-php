<?php
header('Content-type: application/json');
require_once("Connection.php");
    if(isset($_GET["tk"]) && isset($_GET["mk"])){
        $tk = $_GET["tk"];
        $mk = $_GET["mk"];


        $sql = $connection->query("select kh_taikhoan,kh_ma,kh_ten,kh_trangthai from khachhang where kh_taikhoan = '" . $tk . "' and kh_matkhau = '" . $mk . "'");

        $response = array();
        if(mysqli_num_rows($sql) >0){
            while($row = mysqli_fetch_array($sql)){
                 $response[] = $row;
            }
            echo json_encode($response);	
        }
    }
    mysqli_close($connection);













//    $rowSQL = mysqli_num_rows($sql);

//    if ($rowSQL > 0) {
//        $rs = mysqli_fetch_array($sql, MYSQLI_ASSOC);
//        if($rs["kh_trangthai"] == 0){
//            echo "<script> alert('Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên để biết thêm chi tiết.'); </script>";
//            echo '<meta http-equiv="refresh" content="0;URL=index.php">';
//        }
//        else{
//            $_SESSION["taikhoan"] = $rs["kh_taikhoan"];
//            $_SESSION["maKhachHang"] = $rs["kh_ma"];
//            $_SESSION["tenKhachHang"] = $rs["kh_ten"];
   
//            echo "<script> alert('Đăng nhập thành công'); </script>";
//            echo '<meta http-equiv="refresh" content="0;URL=index.php">';
//        }
       
//    } else {
//        $sql1 = $connection->query("select nv_taikhoan from nhanvien where nv_taikhoan = '" . $tk . "' and nv_matkhau = '" . $mk . "'");
//        $rowSQL1 = mysqli_num_rows($sql1);
//        if($rowSQL1 >0){
//            echo '<meta http-equiv="refresh" content="0;URL=Alogin.php">';
//        }else{
//            echo "<script> alert('Tài khoản hoặc mật khẩu không đúng'); </script>";
//            echo '<meta http-equiv="refresh" content="0;URL=index.php">';
//        }
       
//    }
?>