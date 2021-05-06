<script language="javascript">
    $(document).ready(function() {
        var table = $('#ThongKe').DataTable({
            responsive: true,
            "language": {
                "lengthMenu": "Dòng hiển thị _MENU_",
                "info": "_START_ / _TOTAL_ dòng dữ liệu",
                "infoEmpty": "Dữ liệu rỗng",
                "emptyTable": "Bảng chưa có dữ liệu",
                "processing": "Đang xử lý dữ liệu...",
                "search": "Tìm kiếm",
                "loadingRecords": "Đang tải dữ liệu...",
                "zeroRecords": "Không tìm thấy dữ liệu",
                "infoFiltered": "Được từ tổng số _MAX_ dòng dữ liệu",
                "paginate": {
                    "first": "|<",
                    "last": ">|",
                    "next": ">>",
                    "previous": "<<"
                }
            },
            "lengthMenu": [
                [10, 50, 100, 250, 500, 1000, -1],
                [10, 50, 100, 250, 500, 1000, "Tất cả"]
            ]
        });
        // new $.fn.dataTable.FixedHeader(table);
    });
</script>

<?php
// Delete
if (isset($_GET["del"])) {
    $id = $_GET["del"];

    $str = "delete from donhang where dh_ma = '" . $id . "'";
    $connection->query($str);
    echo '<meta http-equiv="refresh" content="0;URL=Aindex.php?key=Athongke">';
}

//List Delete
if (isset($_POST["btnDel"])) {
    if (!isset($_POST["txtXoa"])) {
        echo "<script> alert('Hãy chọn dòng muốn xóa'); </script>";
    } else {
        $listDel = count($_POST["txtXoa"]);
        for ($i = 0; $i < $listDel; $i++) {
            $id = $_POST["txtXoa"][$i];
            $connection->query("Delete from donhang where dh_ma = '" . $id . "'");
            echo '<meta http-equiv="refresh" content="0;URL=Aindex.php?key=Athongke">';
        }
    }
}


?>

<?php
    $y = date("Y");
    $m = date("m");

    $month = $connection -> query("select dh_gia,dh_soluong from donhang 
    where year(dh_thoigian) = $y and month(dh_thoigian)=$m and dh_trangthai = 1");
    $year = $connection -> query("select dh_gia,dh_soluong from donhang 
    where year(dh_thoigian) = $y and dh_trangthai = 1");

    $vaMonth = 0;
    $vaYear = 0;
    while($rs = mysqli_fetch_array($month,MYSQLI_ASSOC)){
        $vaMonth += ($rs["dh_gia"] * $rs["dh_soluong"]) *1.1;
    }

    while($rs1 = mysqli_fetch_array($year,MYSQLI_ASSOC)){
        $vaYear += ($rs1["dh_gia"] * $rs1["dh_soluong"]) *1.1;
    }

?>

<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="form-inline">
            <b>Doanh Thu Tháng <?php echo date("m")."/".date("Y") ;?>:</b>
            <input type="text" class="text-center" value="<?php echo number_format($vaMonth,0,",",".")." đ"?>" disabled style="margin-left: 10px;">
        </div>
        <div class="form-inline">
            <b>Doanh Thu <?php echo date("Y");?>:</b>
            <input type="text" class="text-center" value="<?php echo number_format($vaYear,0,",",".")." đ"?>" disabled style="margin-left: 10px;">
        </div>
    </div>


    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 table-responsive table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl" style="margin-bottom:100px">
        <form action="" method="post">
            <input type="submit" value="Xóa các mục đã chọn" style="margin-top: 10px;" class="btn btn-danger" name="btnDel" onclick="return xacnhan();">
            <br><br>
            <input type="checkbox" name="txtAll" id="txtAll" onclick="CheckAll();"> All
            <table id="ThongKe" class="table table-striped table-bordered text-center" style="width:100%;">
                <thead>
                    <tr>
                        <th>Chọn</th>
                        <th>Mã đơn hàng</th>
                        <th>Khách hàng</th>
                        <th>Giá trị đơn hàng</th>
                        <th>Thời gian lập</th>
                        <th>Địa điểm giao</th>
                        <th>Hình thức thanh toán</th>
                        <th>Trạng thái</th>
                        <th>Xóa</th>
                    </tr>
                    <script>
                        function CheckAll() {
                            var listCheck = document.getElementsByName("txtXoa[]");

                            var checked = document.getElementById("txtAll").checked;

                            if (checked == true) {
                                for (var i = 0; i < listCheck.length; i++) {
                                    listCheck[i].checked = true;
                                }
                            } else {
                                for (var i = 0; i < listCheck.length; i++) {
                                    listCheck[i].checked = false;
                                }
                            }


                        }
                    </script>
                </thead>
                <tbody>
                    <?php
                    $result = $connection->query("select dh_ma,sp_ma,kh_ten,dh_thoigian,dh_diachi,dh_trangthai,httt_ten 
                    from donhang d, khachhang k, hinhthucthanhtoan h where d.kh_ma=k.kh_ma and d.httt_ma=h.httt_ma group by dh_ma");
                    while ($r = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    ?>
                        <tr>
                            <script>
                                function getID(id) {
                                    document.getElementById("txtID").value = id;
                                }
                            </script>
                            <td><input type="checkbox" name="txtXoa[]" id="txtXoa[]" value="<?php echo $r['dh_ma']; ?>"></td>
                            <td><a href="Aindex.php?key=Athongke&idDonHang=<?php echo $r["dh_ma"]; ?>"><?php echo $r["dh_ma"]; ?></a></td>
                            <td><?php echo $r["kh_ten"]; ?></td>
                            <td>
                                <?php
                                $total = 0;
                                $gtDonHang = $connection->query("select dh_gia,dh_soluong from donhang 
                                            where dh_ma ='" . $r["dh_ma"] . "'");
                                while ($va = mysqli_fetch_array($gtDonHang, MYSQLI_ASSOC)) {
                                    $total += ($va["dh_gia"] * $va["dh_soluong"]) * 1.1;
                                }

                                echo number_format($total, 0, ",", ".") . " đ";
                                ?>
                            </td>
                            <td><?php echo $r["dh_thoigian"]; ?></td>
                            <td><?php echo $r["dh_diachi"]; ?></td>
                            <td><?php echo $r["httt_ten"]; ?></td>
                            <td>
                                <?php
                                if ($r["dh_trangthai"] == 1) {
                                ?>
                                    <button type="button" class="btn btn-success">
                                        Đã thanh toán
                                    </button>

                                <?php
                                } else {
                                ?>
                                    <button type="button" class="btn btn-danger">
                                        Chưa thanh toán
                                    </button>
                                <?php
                                }
                                ?>
                            </td>
                            <td>
                                <a href="Aindex.php?key=Athongke&del=<?php echo $r['dh_ma']; ?>" onclick="return xacnhan();">
                                    <button type="button" class="btn btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </a>

                                <script>
                                    function xacnhan() {
                                        var conf = confirm("Bạn thật sự muốn xóa ?");
                                        return conf;
                                    }
                                </script>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </form>
    </div>


    <?php if (isset($_GET["idDonHang"])) { ?>
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 table-responsive table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl" style="margin-top: 50px; margin-bottom:100px">
            <hr>
            <div class="text-right"><a href="Aindex.php?key=Athongke" class="btn btn-info">Đóng</a></div>
            <div class="form-inline">
                <b>Mã Đơn Hàng:</b>
                <input type="text" disabled class="form-control" value="<?php echo $_GET["idDonHang"]; ?>" style="margin-left: 20px;">
            </div>
            <div style="margin-bottom:20px"><b>VAT: 10%</b></div>
            <table class="table table-striped table-bordered text-center" style="width:100%;">
                <thead>
                    <tr>
                        <td>Sản phẩm</td>
                        <td>Đơn giá</td>
                        <td>Số lượng</td>
                        <td>Thành tiền</td>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    $id = $_GET["idDonHang"];
                    $sql = $connection->query("select sp_ten,dh_gia,dh_soluong from donhang d, sanpham s 
                where s.sp_ma=d.sp_ma and dh_ma ='" . $id . "'");
                    while ($rs = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
                    ?>
                        <tr>
                            <td><?php echo $rs["sp_ten"];?></td>
                            <td><?php echo number_format($rs["dh_gia"],0,",",".")?></td>
                            <td><?php echo $rs["dh_soluong"];?></td>
                            <td><?php echo number_format($rs["dh_gia"] * $rs["dh_soluong"],0,",",".")?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
</div>