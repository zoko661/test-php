<script language="javascript">
    $(document).ready(function() {
        var table = $('#dsNhanVien').DataTable({
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

<script>
    $(document).ready(function() {
        $("#frmImport").hide();
        $("#btnBack").hide();
        $("#btnFileMau").hide();
        $("#btnImport").click(function() {
            $("#frmImport").show();
            $("#btnBack").show();
            $("#frmInsert").hide();
            $("#btnImport").hide();
            $("#btnFileMau").show();
        });

        $("#btnBack").click(function() {
            $("#frmImport").hide();
            $("#btnImport").show();
            $("#frmInsert").show();
            $("#btnBack").hide();
            $("#btnFileMau").hide();
        });
    });
</script>

<?php
// inserts
if (isset($_POST["btnLuu"])) {
    $ten = $_POST["txtHoTen"];
    $tk = $_POST["txtTK"];
    $mk = md5($_POST["txtPass"]);
    $sdt = $_POST["txtSDT"];
    $dc = $_POST["txtDiaChi"];
    $cv = $_POST["selChucVu"];
    $tt = $_POST["selTT"];
    $str = "select * from nhanvien where nv_taikhoan ='" . $tk . "'";
    $rs = $connection->query($str);
    $row = mysqli_num_rows($rs);
    if ($row > 0) {
        echo "<script> alert('Tài Khoản đã tồn tại'); </script>";
    } else {
        $sql = "insert into nhanvien(nv_ten,nv_sodienthoai,nv_diachi,nv_taikhoan,nv_matkhau,nv_chucvu,nv_trangthai) 
                values('" . $ten . "','" . $sdt . "','" . $dc . "','" . $tk . "','" . $mk . "',$cv,$tt)";
        $query = $connection->query($sql);
        echo '<meta http-equiv="refresh" content="0;URL=Aindex.php?key=Anhanvien">';
    }
}

//Update
if (isset($_POST["btnCapNhat"])) {
    $id = $_POST["txtID"];
    $ten = $_POST["txtCNHoTen"];
    $tk = $_POST["txtCNTK"];
    $mk = md5($_POST["txtCNPass"]);
    $sdt = $_POST["txtCNSDT"];
    $dc = $_POST["txtCNDiaChi"];
    $cv = $_POST["selCNChucVu"];

        $sql = "Update nhanvien 
                set nv_ten ='" . $ten . "',
                    nv_taikhoan = '" . $tk . "',
                    nv_matkhau = '" . $mk . "',
                    nv_sodienthoai = '" . $sdt . "',
                    nv_diachi = '" . $dc . "',
                    nv_chucvu = " . $cv . " 
                    where nv_ma = " . $id;

        $connection->query($sql);
        echo '<meta http-equiv="refresh" content="0;URL=Aindex.php?key=Anhanvien">';
}
?>

<?php
//Import file
if (isset($_POST["btnLuuFile"])) {
    // Duong dan file
    $file = $_FILES["txtFile"]["tmp_name"];

    $objReader = PHPExcel_IOFactory::createReaderForFile($file);
    $listWorkSheets = $objReader->listWorksheetNames($file);
    $count =0;
    $total = 0;
    foreach ($listWorkSheets as $name) {
        $objReader->setReadDataOnly($name);
        // setReadDataOnly
        // setloadSheetsOnly
        $objExcel = $objReader->load($file);
        $sheetData = $objExcel->getActiveSheet()->toArray("null", true, true, true);

        $highestRow = $objExcel->setActiveSheetIndex()->getHighestRow();
        $total = $highestRow - 1;
        for ($i = 2; $i <= $highestRow; $i++) {
            $ten = $sheetData[$i]["A"];
            $tk = $sheetData[$i]["B"];
            $mk = md5($sheetData[$i]["C"]);
            $sdt = $sheetData[$i]["D"];
            $dc = $sheetData[$i]["E"];
            $str = "select nv_taikhoan from nhanvien where nv_taikhoan ='" . $tk . "'";

            $rs = $connection->query($str);
            $row = mysqli_num_rows($rs);

            if ($row == 0) {
                $sql = "insert into nhanvien(nv_ten,nv_sodienthoai,nv_diachi,nv_taikhoan,nv_matkhau) 
                values('" . $ten . "','" . $sdt . "','" . $dc . "','" . $tk . "','" . $mk . "')";
                $connection->query($sql);
                $count++;
            }
        }
    }
    echo "<script> alert('Đã thêm $count/$total dòng dữ liệu'); </script>";
}

//Status

if (isset($_GET["status"]) && isset($_GET["id"])) {
    $tt = $_GET["status"];
    $id = $_GET["id"];
    $connection->query("Update nhanvien set nv_trangthai =" . $tt . " where nv_ma =" . $id);
    echo '<meta http-equiv="refresh" content="0;URL=Aindex.php?key=Anhanvien">';
}

?>

<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin-bottom: 20px;">
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ThemNhanVien">
            Thêm Nhân Viên
        </button>

        <!-- Modal Insert-->
        <div class="modal fade " id="ThemNhanVien" tabindex="-1" aria-labelledby="ThemNhanVienLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="exampleModalLabel">Thêm Nhân Viên</h5>
                        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button> -->
                    </div>
                    <div class="modal-body">
                        <button type="button" class="btn btn-outline-info" id="btnImport">
                            <i class="fas fa-file-import"></i>
                            File Excel
                        </button>

                        <button type="button" class="btn btn-outline-info" id="btnBack">
                            <i class="fas fa-arrow-left"></i>
                            Trở Lại
                        </button>

                        <script>
                            function kiemtraInsert() {
                                var vnf_regex = /((09|03|07|08|05)+([0-9]{8})\b)/g;
                                var ten = document.getElementById("txtHoTen").value;
                                var tk = document.getElementById("txtTK").value;
                                var mk = document.getElementById("txtPass").value;
                                var sdt = document.getElementById("txtSDT").value;
                                var dc = document.getElementById("txtDiaChi").value;
                                var cv = document.getElementById("selChucVu").value;
                                var tt = document.getElementById("selTT").value;
                                if (ten == "" || tk == "" || mk == "" || sdt == "" || dc == "") {
                                    alert("Thông tin không đầy đủ");
                                } else {
                                    if (tk.length < 6 || tk.length > 18) {
                                        alert("Tài khoản phải từ 6 đến 18 ký tự");
                                    } else if (mk.length < 6 || mk.length > 25) {
                                        alert("Mật khẩu phải từ 6 đến 25 ký tự");
                                    } else if (vnf_regex.test(sdt) == false) {
                                        alert("Số điện thoại không đúng định dạng");
                                    } else {
                                        document.getElementById("btnLuu").name = "btnLuu";
                                    }
                                }
                            }

                            function kiemtraFile() {
                                var file = document.getElementById("txtFile").value;

                                if (file == "") {
                                    alert("Hãy chọn file");
                                } else {
                                    document.getElementById("btnLuuFile").name = "btnLuuFile";
                                }
                            }
                        </script>
                        <div id="frmInsert">
                            <form action="" method="post" onsubmit="kiemtraInsert()">
                                <div class="form-group" style="margin-top: 10px;">
                                    <label for="#HoTen">Họ Tên</label>
                                    <input type="text" name="txtHoTen" id="txtHoTen" class="form-control">
                                </div>
                                <div class="form-group" style="margin-top: 10px;">
                                    <label for="#TaiKhoan">Tài Khoản</label>
                                    <input type="text" name="txtTK" id="txtTK" class="form-control">
                                </div>
                                <div class="form-group" style="margin-top: 10px;">
                                    <label for="#MatKhau">Mật Khẩu</label>
                                    <input type="password" name="txtPass" id="txtPass" class="form-control">
                                </div>
                                <div class="form-group" style="margin-top: 10px;">
                                    <label for="#SDT">Số Điện Thoại</label>
                                    <input type="text" name="txtSDT" id="txtSDT" class="form-control">
                                </div>
                                <div class="form-group" style="margin-top: 10px;">
                                    <label for="#DiaChi">Địa Chỉ</label>
                                    <textarea name="txtDiaChi" id="txtDiaChi" cols="" rows="5" class="form-control"></textarea>
                                </div>
                                <div class="form-group" style="margin-top: 10px;">
                                    <label for="#ChucVu">Chức Vụ</label>
                                    <select name="selChucVu" id="selChucVu" class="form-control">
                                        <option value="0">Nhân Viên</option>
                                        <option value="1">Quản Trị</option>
                                    </select>
                                </div>
                                <div class="form-group" style="margin-top: 10px;">
                                    <label for="#TrangThai">Trạng Thái</label>
                                    <select name="selTT" id="selTT" class="form-control">
                                        <option value="0">Khóa</option>
                                        <option value="1">Bình Thường</option>
                                    </select>
                                </div>
                                <div class="form-group text-right">
                                    <button type="submit" id="btnLuu" class="btn btn-info">Lưu</button>
                                </div>
                            </form>
                        </div>
                        <div id="frmImport">

                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="form-group text-right">
                                    <a href="Excel_Mau/NhanVien_Mau.xlsx" download class="btn btn-outline-success">
                                        <i class="fas fa-file-download"></i>
                                        File Mẫu
                                    </a>
                                </div>
                                <div class="form-group" style="margin-top: 10px;">
                                    <label for="#ChonFile">Chọn File</label>
                                    <input type="file" name="txtFile" id="txtFile">
                                </div>
                                <div class="form-group text-right">
                                    <button type="submit" id="btnLuuFile" onclick="kiemtraFile();" class="btn btn-info">Lưu</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 table-responsive table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl">
        <table id="dsNhanVien" class="table table-striped table-bordered text-center" style="width:100%;">
            <thead>
                <tr>
                    <th>Mã</th>
                    <th>Họ Tên</th>
                    <th>Tài Khoản</th>
                    <th>Mật Khẩu</th>
                    <th>Số Điện Thoại</th>
                    <th>Địa Chỉ</th>
                    <th>Chức Vụ</th>
                    <th>Trạng Thái</th>
                    <th>Cập Nhật</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $connection->query("select nv_ma,nv_ten,nv_taikhoan,nv_matkhau,nv_sodienthoai,nv_diachi,nv_chucvu,nv_trangthai from nhanvien");

                while ($r = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                ?>
                    <tr>
                        <td><?php echo $r["nv_ma"]; ?></td>
                        <td><?php echo $r["nv_ten"]; ?></td>
                        <td><?php echo $r["nv_taikhoan"]; ?></td>
                        <td><?php echo $r["nv_matkhau"]; ?></td>
                        <td><?php echo $r["nv_sodienthoai"]; ?></td>
                        <td>
                            <?php
                            if (!isset($r["nv_diachi"])) {
                                echo "Chưa cập nhật";
                            } else {
                                echo $r["nv_diachi"];
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($r["nv_chucvu"] == 1) echo "Quản trị";
                            else echo "Nhân Viên";
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($r["nv_trangthai"] == 1) {
                            ?>
                                <a href="Aindex.php?key=Anhanvien&status=0&id=<?php echo $r["nv_ma"]; ?>">
                                    <button type="button" class="btn btn-success">
                                        Hoạt Động
                                    </button>
                                </a>
                            <?php
                            } else {
                            ?>
                                <a href="Aindex.php?key=Anhanvien&status=1&id=<?php echo $r["nv_ma"]; ?>">
                                    <button type="button" class="btn btn-danger">
                                        Khóa
                                    </button>
                                </a>
                            <?php
                            }
                            ?>
                        </td>
                        <td>
                            <button type="button" data-target="#CapNhatNhanVien" data-toggle="modal" onclick="getIDName(this.value,this.name,this.id);" name="<?php echo $r["nv_ten"]; ?>" id="<?php echo $r["nv_ma"]; ?>" value="<?php echo $r['nv_taikhoan']; ?>" class="btn btn-primary">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>
                        <script>
                            function getIDName(value, name, id) {
                                var id = id;
                                var na = name;
                                var va = value;
                                document.getElementById("txtID").value = id;
                                document.getElementById("txtCNHoTen").value = na;
                                document.getElementById("txtCNTK").value = va;
                            }
                        </script>

                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Update -->
<div class="modal fade " id="CapNhatNhanVien" tabindex="-1" aria-labelledby="CapNhatNhanVienLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="exampleModalLabel">Cập Nhật Nhân Viên</h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button> -->
            </div>
            <div class="modal-body">
                <!-- <button type="button" class="btn btn-outline-info">
                    <i class="fas fa-file-import"></i>
                    File Excel
                </button> -->
                <script>
                    function kiemtraUpdate() {
                        var vnf_regex = /((09|03|07|08|05)+([0-9]{8})\b)/g;
                        var ten = document.getElementById("txtCNHoTen").value;
                        var tk = document.getElementById("txtCNTK").value;
                        var mk = document.getElementById("txtCNPass").value;
                        var sdt = document.getElementById("txtCNSDT").value;
                        var dc = document.getElementById("txtCNDiaChi").value;
                        var cv = document.getElementById("selCNChucVu").value;
                        if (ten == "" || tk == "" || mk == "" || sdt == "" || dc == "") {
                            alert("Thông tin không đầy đủ");
                        } else {
                            if (tk.length < 6 || tk.length > 18) {
                                alert("Tài khoản phải từ 6 đến 18 ký tự");
                            } else if (mk.length < 6 || mk.length > 25) {
                                alert("Mật khẩu phải từ 6 đến 25 ký tự");
                            } else if (vnf_regex.test(sdt) == false) {
                                alert("Số điện thoại không đúng định dạng");
                            } else {
                                document.getElementById("btnCapNhat").name = "btnCapNhat";
                            }
                        }
                    }
                </script>
                <form action="" method="post" onsubmit="kiemtraUpdate()">
                    <input type="hidden" name="txtID" id="txtID">
                    <div class="form-group" style="margin-top: 10px;">
                        <label for="#HoTen">Họ Tên</label>
                        <input type="text" name="txtCNHoTen" id="txtCNHoTen" class="form-control">
                    </div>
                    <div class="form-group" style="margin-top: 10px;">
                        <label for="#TaiKhoan">Tài Khoản</label>
                        <input type="text" name="txtCNTK" id="txtCNTK" class="form-control">
                    </div>
                    <div class="form-group" style="margin-top: 10px;">
                        <label for="#MatKhau">Mật Khẩu</label>
                        <input type="password" name="txtCNPass" id="txtCNPass" class="form-control">
                    </div>
                    <div class="form-group" style="margin-top: 10px;">
                        <label for="#SDT">Số Điện Thoại</label>
                        <input type="text" name="txtCNSDT" id="txtCNSDT" class="form-control">
                    </div>
                    <div class="form-group" style="margin-top: 10px;">
                        <label for="#DiaChi">Địa Chỉ</label>
                        <textarea name="txtCNDiaChi" id="txtCNDiaChi" cols="" rows="5" class="form-control"></textarea>
                    </div>
                    <div class="form-group" style="margin-top: 10px;">
                        <label for="#ChucVu">Chức Vụ</label>
                        <select name="selCNChucVu" id="selCNChucVu" class="form-control">
                            <option value="0">Nhân Viên</option>
                            <option value="1">Quản Trị</option>
                        </select>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" id="btnCapNhat" class="btn btn-info">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>