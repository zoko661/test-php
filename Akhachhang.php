<script language="javascript">
    $(document).ready(function() {
        var table = $('#dsKhachHang').DataTable({
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
if (isset($_GET["status"]) && isset($_GET["id"])) {
    $tt = $_GET["status"];
    $id = $_GET["id"];
    $connection->query("Update khachhang set kh_trangthai =" . $tt . " where kh_ma =" . $id);
    echo '<meta http-equiv="refresh" content="0;URL=Aindex.php?key=Akhachhang">';
}
?>

<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 table-responsive table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl">
        <form action="" method="post">
            <table id="dsKhachHang" class="table table-striped table-bordered text-center" style="width:100%;">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Mã Khách Hàng</th>
                        <th>Họ Tên</th>
                        <th>Tài Khoản</th>
                        <th>Số Điện Thoại</th>
                        <th>Địa Chỉ</th>
                        <th>Trạng Thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $connection->query("select kh_ma,kh_ten,kh_taikhoan,kh_sodienthoai,kh_diachi,kh_trangthai from khachhang");

                    $stt = 0;
                    while ($r = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        $stt++;
                    ?>
                        <tr>
                            <td><?php echo $stt; ?></td>
                            <td><?php echo $r["kh_ma"]; ?></td>
                            <td><?php echo $r["kh_ten"]; ?></td>
                            <td><?php echo $r["kh_taikhoan"]; ?></td>
                            <td><?php echo $r["kh_sodienthoai"]; ?></td>
                            <td>
                                <?php
                                    if (!isset($r["kh_diachi"]) || empty($r["kh_diachi"])) {
                                        echo "Chưa cập nhật";
                                    } else {
                                        echo $r["kh_diachi"];
                                    }
                                ?>
                            </td>
                            <td>
                                <?php
                                    if ($r["kh_trangthai"] == 1) {
                                    ?>
                                        <a href="Aindex.php?key=Akhachhang&status=0&id=<?php echo $r["kh_ma"]; ?>">
                                            <button type="button" class="btn btn-success">
                                                Hoạt Động
                                            </button>
                                        </a>
                                    <?php
                                    } else {
                                ?>
                                        <a href="Aindex.php?key=Akhachhang&status=1&id=<?php echo $r["kh_ma"]; ?>">
                                            <button type="button" class="btn btn-danger">
                                                Khóa
                                            </button>
                                        </a>
                                <?php
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </form>
    </div>
</div>