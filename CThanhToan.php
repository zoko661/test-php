<?php
if (isset($_POST["btnXacNhan"])) {
    if (empty($_POST["txtDiaChi"])) {
        echo "<script> alert('Hãy nhập địa chỉ bạn nhận hàng'); </script>";
    } else {
        $_SESSION["idDonHang"] = $_SESSION["taikhoan"] . date("dmYHis");
        $httt = $_POST["selHTTT"];
        $diachi = $_POST["txtDiaChi"];
        foreach ($_SESSION["cart"] as $key => $value) {
            $sql = $connection->query("insert into donhang(dh_ma,sp_ma,kh_ma,dh_soluong,dh_gia,dh_thoigian,dh_diachi,httt_ma) 
                    values('" . $_SESSION["idDonHang"] . "',$key," . $_SESSION["maKhachHang"] . "," . $value["soluong"] . ",".$value["giasp"].",now(),'" . $diachi . "',$httt)");
        }

        if ($sql) {
            echo '<meta http-equiv="refresh" content="0;URL=index.php?key=CThanhToan">';
        } else {
            echo "<script> alert('Tiếp nhận đơn hàng thất bại'); </script>";
            echo '<meta http-equiv="refresh" content="0;URL=index.php">';
        }
    }
}

if (isset($_POST["btnThanhToan"])) {
    $connection->query("update donhang set dh_trangthai = 1 where dh_ma = '" . $_SESSION["idDonHang"] . "'");
    foreach ($_SESSION["cart"] as $key => $value) {
        $sql = $connection->query("Update sanpham set sp_soluong = sp_soluong - " . $value["soluong"] . " where sp_ma = " . $key);
    }

    unset($_SESSION["cart"]);
    unset($_SESSION["total"]);
    unset($_SESSION["idDonHang"]);
    echo "<script> alert('Cảm ơn quý khách. Đơn hàng sẽ được giao sau 7 ngày kể từ thời gian lập đơn hàng.'); </script>";
    echo '<meta http-equiv="refresh" content="0;URL=index.php">';
}

if (isset($_POST["btnHuy"])) {
    $connection->query("delete from donhang where dh_ma = '" . $_SESSION["idDonHang"] . "'");
    unset($_SESSION["idDonHang"]);
    echo "<script> alert('Đơn hàng đã được hủy'); </script>";
    echo '<meta http-equiv="refresh" content="0;URL=index.php">';
}
?>

<div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
    <h1>Thông Tin Thanh Toán</h1>
    <form action="" method="post">
        <div class="form-group">
            <label for="#Total">Tổng số tiền của đơn hàng</label>
            <input type="text" name="txtTotal" id="txtTotal" disabled class="form-control" value="<?php echo number_format($_SESSION["total"], 0, ",", "."); ?> đ">
        </div>
        <div class="form-group">
            <label for="#DiaChi">Nơi giao hàng</label>
            <textarea name="txtDiaChi" id="txtDiaChi" cols="" rows="5" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="#HTTT">Hình thức thanh toán</label>
            <select name="selHTTT" id="selHTTT" class="form-control">
                <?php
                $httt = $connection->query("select httt_ma,httt_ten from hinhthucthanhtoan");
                while ($rs = mysqli_fetch_array($httt, MYSQLI_ASSOC)) {
                ?>
                    <option value="<?php echo $rs["httt_ma"]; ?>"><?php echo $rs["httt_ten"]; ?></option>
                <?php
                }
                ?>
            </select>
        </div>
        <div class="text-center">
            <button type="submit" name="btnXacNhan" class="btn btn-info">Xác Nhận</button>
        </div>
    </form>
</div>
<div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 table-responsive table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl">
    <h1>Thanh Toán Hóa Đơn</h1>
    <?php

    if (isset($_SESSION["idDonHang"])) {
        $thanhtoan = $connection->query("select DISTINCT dh_ma,dh_thoigian,dh_diachi,httt_ten 
        from donhang d, hinhthucthanhtoan h where d.httt_ma = h.httt_ma and dh_ma = '" . $_SESSION["idDonHang"] . "'");


        if ($thanhtoan) {
            while ($rs = mysqli_fetch_array($thanhtoan, MYSQLI_ASSOC)) {
    ?>
                <form action="" method="post">
                    <b>Được lập vào lúc <?php echo $rs["dh_thoigian"]; ?> </b>
                    <div class="form-inline">
                        <label for="#Id">Mã đơn hàng: </label>
                        <input type="text" value="<?php echo $_SESSION["idDonHang"] ?>" disabled>
                    </div>
                    <div class="form-inline">
                        <label for="#Name">Khách hàng: <?php echo $_SESSION["tenKhachHang"]; ?></label>
                    </div>
                    <table class="table table-striped table-bordered text-center" style="width:100%;">
                        <tr>
                            <td>Sản phẩm</td>
                            <td>Giá</td>
                            <td>Số lượng</td>
                            <td>Thành tiền</td>
                        </tr>
                        <?php
                        $sp = $connection->query("select  sp_ten,dh_gia,dh_soluong from sanpham s,donhang h where s.sp_ma=h.sp_ma and dh_ma = '" . $rs["dh_ma"] . "'");
                        while ($rsSP = mysqli_fetch_array($sp, MYSQLI_ASSOC)) {
                        ?>
                            <tr>
                                <td><?php echo $rsSP["sp_ten"]; ?></td>
                                <td><?php echo number_format($rsSP["dh_gia"], 0, ",", "."); ?> đ</td>
                                <td><?php echo $rsSP["dh_soluong"]; ?></td>
                                <td><?php echo number_format($rsSP["dh_gia"] * $rsSP["dh_soluong"], 0, ",", "."); ?> đ</td>
                            </tr>
                        <?php
                        }
                        ?>
                    </table>

                    <div class="form-inline">
                        <label for="#Diachi">Giao hàng đến: <?php echo $rs["dh_diachi"]; ?></label>
                    </div>

                    <div class="form-inline">
                        <label for="#HTTT">HTTT: <?php echo $rs["httt_ten"]; ?></label>
                    </div>
                    <div class="form-inline">
                        <label for="#VAT">VAT: <?php echo "10%" ?></label>
                    </div>
                    <div class="form-inline">
                        <label for="#Total">Tổng tiền phải trả: <?php echo number_format($_SESSION["total"] * 1.1, 0, ",", "."); ?> đ</label>
                    </div>
                    <div class="text-center">
                        <button type="submit" name="btnThanhToan" class="btn btn-info">Thanh Toán</button>
                        <button type="submit" name="btnHuy" class="btn btn-info">Hủy đơn hàng</button>
                    </div>
                </form>
    <?php
            }
        }
    }
    ?>
</div>