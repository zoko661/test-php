<script language="javascript">
    $(document).ready(function() {
        var table = $('#dsGopY').DataTable({
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
        new $.fn.dataTable.FixedHeader(table);
    });
</script>

<?php
//List Delete
if (isset($_POST["btnDel"])) {
    if (!isset($_POST["txtXoa"])) {
        echo "<script> alert('Hãy chọn dòng muốn xóa'); </script>";
    } else {
        $listDel = count($_POST["txtXoa"]);
        for ($i = 0; $i < $listDel; $i++) {
            $id = $_POST["txtXoa"][$i];
            $connection->query("Delete from gopy where gy_id =" . $id);
            echo '<meta http-equiv="refresh" content="0;URL=Aindex.php?key=Agopy">';
        }
    }
}

// Delete
if (isset($_GET["del"])) {
    $id = $_GET["del"];

    $str = "delete from gopy where gy_id =" . $id;
    $connection->query($str);
    echo '<meta http-equiv="refresh" content="0;URL=Aindex.php?key=Agopy">';
}
?>

<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 table-responsive table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl">
        <form action="" method="post">
            <input type="submit" value="Xóa các mục đã chọn" style="margin-top: 10px;" class="btn btn-danger" name="btnDel" onclick="return xacnhan();">
            <br><br>
            <input type="checkbox" name="txtAll" id="txtAll" onclick="CheckAll();"> All
            <table id="dsGopY" class="table table-striped table-bordered text-center" style="width:100%;">
                <thead>
                    <tr>
                        <th>Chọn</th>
                        <th>Mã</th>
                        <th>Khách Hàng</th>
                        <th>Sản Phẩm</th>
                        <th>Nội Dung</th>
                        <th>Thời Gian</th>
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
                    $result = $connection->query("select gy_id,gy_noidung,gy_thoigian,kh_ten,sp_ten 
                    from gopy g, sanpham s, khachhang k where g.kh_ma = k.kh_ma and g.sp_ma = s.sp_ma");

                    $stt = 0;
                    if ($result) {
                        while ($r = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                            $stt++;


                    ?>
                            <tr>
                                <td><input type="checkbox" name="txtXoa[]" id="txtXoa[]" value="<?php echo $r['gy_id']; ?>">
                                </td>
                                <td><?php echo $r["gy_id"]; ?></td>
                                <td><?php echo $r["kh_ten"]; ?></td>
                                <td><?php echo $r["sp_ten"]; ?></td>
                                <td><?php echo $r["gy_noidung"]; ?></td>
                                <td><?php echo $r["gy_thoigian"]; ?></td>
                                <td>

                                    <a href="Aindex.php?key=Agopy&del=<?php echo $r['gy_id']; ?>" onclick="return xacnhan();">
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
                    }
                    ?>
                </tbody>
            </table>
        </form>
    </div>
</div>