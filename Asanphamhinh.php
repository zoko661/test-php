<script>
    $(document).ready(function() {
        $("#btnThem").click(function() {
            $("#btnThem").before('<br><br><input type="file" name="dsHinh[]" id="dsHinh[]" class="form-control">')
        });
    });
</script>

<script language="javascript">
    $(document).ready(function() {
        var table = $('#dsSPH').DataTable({
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
if (isset($_GET["idsp"])) {
    $masp = $_GET["idsp"];
    $rsSP = $connection->query("select sp_ten from sanpham where sp_ma = $masp");

    $infoSP = mysqli_fetch_array($rsSP);
    $tensp = $infoSP[0];

    $rsSPH = $connection->query("select sph_ma,sph_hinh,sp_ma from sanphamhinh where sp_ma = $masp");
}
?>

<?php
//upload 
if (isset($_POST["btnCapNhat"])) {
    $masp = $_POST["txtIDSP"];
    if (kiemtrahinh()) {
        foreach ($_FILES["dsHinh"]["tmp_name"] as $key => $tmp_name) {
            if ($_FILES["dsHinh"]["name"][$key] != "") {
                $tenmoi = $masp . "_" . $_FILES["dsHinh"]["name"][$key];
                copy($tmp_name, "images/" . $tenmoi);
                $sql = "Insert into sanphamhinh(sph_hinh,sp_ma) values('" . $tenmoi . "',$masp)";
                $connection->query($sql);
                echo '<meta http-equiv="refresh" content="0;URL=Aindex.php?key=Asanphamhinh&idsp='.$masp.'">';
            }
        }
    }
}

//Kiem tra
function kiemtrahinh()
{
    foreach ($_FILES["dsHinh"]["tmp_name"] as $key => $tmp_name) {
        $size = $_FILES["dsHinh"]["size"][$key];
        $type = $_FILES["dsHinh"]["type"][$key];
        if ($type == "image/jpg" || $type == "image/png" || $type == "image/gif" || $type == "image/jpeg") {
            if ($size <= 614400) {
                return true;
            } else {
                echo "<script>alert('File kích thước quá lớn')</script>";
                return false;
            }
        } else {
            echo "<script>alert('Kiểu file không hợp lệ')</script>";
            return false;
        }
    }
}
?>

<?php
    if(isset($_GET["idsph"])){
        $maSPH = $_GET["idsph"];

        $rs = $connection -> query("select sph_hinh from sanphamhinh where sph_ma =".$maSPH);

        $hinh = mysqli_fetch_array($rs);
        $tenHinh = $hinh[0];

        $connection -> query("delete from sanphamhinh where sph_ma =".$maSPH);

        unlink("images/".$tenHinh);
        echo '<meta http-equiv="refresh" content="0;URL=Aindex.php?key=Asanphamhinh&idsp='.$masp.'">';
    }
?>

<div class="row">
    <div class="col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4" style="margin-bottom: 20px;">
        <legend>Upload Hình Sản Phẩm</legend>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="#IdSanPham">Mã Sản Phẩm</label>
                <input type="text" id="txtIDSP" name="txtIDSP" readonly class="form-control" value="<?php echo $masp; ?>">
            </div>
            <div class="form-group">
                <label for="#TenSanPham">Sản Phẩm</label>
                <input type="text" id="txtNameSP" name="txtNameSP" readonly class="form-control" value="<?php echo $tensp; ?>">
            </div>
            <div class="form-group">
                <label for="#HinhSanPham">Hình sản phẩm:</label>
                <div class="form-inline mb-3">
                    <input type="file" name="dsHinh[]" id="dsHinh[]" class="form-control">
                    <input name="btnThem" type="button" class="btn btn-success mx-sm-3 p-2" id="btnThem" value="Thêm">
                </div>

            </div>
            <div class="form-group text-right">
                <button type="submit" id="btnCapNhat" name="btnCapNhat" class="btn btn-info">Lưu</button>
            </div>
        </form>
    </div>
    <div class="col-8 col-sm-8 col-md-8 col-lg-8 col-xl-8 table-responsive table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl">
        <legend>Danh Sách Hình</legend>
        <form action="" method="post">
            <table id="dsSPH" class="table table-striped table-bordered text-center" style="width:100%;">
                <thead>
                    <th>Chọn</th>
                    <th>Hình Ảnh</th>
                    <th>Xóa</th>
                </thead>
                <tbody>
                    <?php
                        $stt=0;
                        while ($r = mysqli_fetch_array($rsSPH, MYSQLI_ASSOC)) {
                        $stt++;    
                    ?>
                            <tr>
                                <td><?php echo $stt;?></td>
                                <td>
                                    <img src="images/<?php echo $r["sph_hinh"]; ?>" alt="" width="100px" height="100px">
                                </td>
                                <td>
                                    <a href="Aindex.php?key=Asanphamhinh&idsp=<?php echo $r["sp_ma"]; ?>&idsph=<?php echo $r["sph_ma"]; ?>">
                                        <i class="fas fa-trash"></i>
                                    </a>
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