<script language="javascript">
    $(document).ready(function() {
        var table = $('#dsSanPham').DataTable({
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
    $ten = $_POST["txtTen"];
    $gia = $_POST["txtGia"];
    $sl = $_POST["txtSL"];
    $tt = $_POST["txtTT"];
    $lsp = $_POST["selLSP"];
    $nsx = $_POST["selNSX"];
    $str = "select * from sanpham where sp_ten ='" . $ten . "'";
    $rs = $connection->query($str);
    $row = mysqli_num_rows($rs);
    if ($row > 0) {
        echo "<script> alert('Sản phẩm đã tồn tại'); </script>";
    } else {
        $sql = "insert into sanpham(sp_ten,sp_gia,sp_soluong,sp_thongtin,nsx_ma,lsp_ma) values('" . $ten . "',$gia,$sl,'" . $tt . "',$nsx,$lsp)";
        $query = $connection->query($sql);
        echo '<meta http-equiv="refresh" content="0;URL=Aindex.php?key=Asanpham">';
    }
}

// Delete
if (isset($_GET["del"])) {
    $id = $_GET["del"];
    $kq = "";
    $dh = $connection->query("select sp_ma from donhang where sp_ma =" . $id);
    $row = mysqli_num_rows($dh);
    if ($row > 0) {
        $kq = $id . " , " . $kq;
    } else {
        $connection->query("Delete from gopy where sp_ma =" . $id);
        $connection->query("Delete from sanphamhinh where sp_ma =" . $id);
        $connection->query("Delete from sanpham where sp_ma =" . $id);
    }
    if ($kq != "") {
        echo "<script> alert('Không thể xóa các sản phẩm có mã là: $kq vì đang được đặt hàng. '); </script>";
    }
    echo '<meta http-equiv="refresh" content="0;URL=Aindex.php?key=Asanpham">';
}

//List Delete
if (isset($_POST["btnDel"])) {
    if (!isset($_POST["txtXoa"])) {
        echo "<script> alert('Hãy chọn dòng muốn xóa'); </script>";
    } else {
        $listDel = count($_POST["txtXoa"]);
        $kq = "";
        for ($i = 0; $i < $listDel; $i++) {
            $id = $_POST["txtXoa"][$i];

            $dh = $connection->query("select sp_ma from donhang where sp_ma =" . $id);
            $row = mysqli_num_rows($dh);
            if ($row > 0) {
                $kq = $id . " , " . $kq;
            } else {
                $connection->query("Delete from gopy where sp_ma =" . $id);
                $connection->query("Delete from sanphamhinh where sp_ma =" . $id);
                $connection->query("Delete from sanpham where sp_ma =" . $id);
            }
        }
        if ($kq != "") {
            echo "<script> alert('Không thể xóa các sản phẩm có mã là: $kq vì đang được đặt hàng. '); </script>";
        }
        echo '<meta http-equiv="refresh" content="0;URL=Aindex.php?key=Asanpham">';
    }
}

//Update
if (isset($_POST["btnCapNhat"])) {
    $ten = $_POST["txtCNTen"];
    $ma = $_POST["txtID"];
    $gia = $_POST["txtCNGia"];
    $sl = $_POST["txtCNSL"];
    $tt = $_POST["txtCNTT"];
    $lsp = $_POST["selCNLSP"];
    $nsx = $_POST["selCNNSX"];

    $sql = "Update sanpham 
        set 
        sp_ten ='" . $ten . "', 
        sp_gia = " . $gia . ", 
        sp_soluong = " . $sl . ", 
        sp_thongtin = '" . $tt . "', 
        lsp_ma = " . $lsp . ", 
        nsx_ma = " . $nsx . " 
        where sp_ma =" . $ma;

    $connection->query($sql);
    echo '<meta http-equiv="refresh" content="0;URL=Aindex.php?key=Asanpham">';
}
?>

<?php
if (isset($_POST["btnLuuFile"])) {
    // Duong dan file
    $file = $_FILES["txtFile"]["tmp_name"];

    $objReader = PHPExcel_IOFactory::createReaderForFile($file);
    $listWorkSheets = $objReader->listWorksheetNames($file);
    $count = 0;
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
            $name = $sheetData[$i]["A"];
            $price = $sheetData[$i]["B"];
            $num = $sheetData[$i]["C"];
            $info = $sheetData[$i]["D"];

            $str = "select sp_ten from sanpham where sp_ten ='" . $name . "'";

            $rs = $connection->query($str);
            $row = mysqli_num_rows($rs);

            if ($row == 0) {
                $sql = "insert into sanpham(sp_ten,sp_gia,sp_soluong,sp_thongtin) values('" . $name . "',$price,$num,'" . $info . "')";
                $connection->query($sql);
                $count++;
            }
        }
        echo "<script> alert('Đã thêm $count/$total dòng dữ liệu'); </script>";
    }
}
?>




<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin-bottom: 20px;">
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ThemSanPham">
            Thêm sản phẩm
        </button>

        <!-- Modal Insert-->
        <div class="modal fade " id="ThemSanPham" tabindex="-1" aria-labelledby="ThemSanPhamLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="exampleModalLabel">Thêm Sản Phẩm</h5>
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
                                // var vnf_regex = /((09|03|07|08|05)+([0-9]{8})\b)/g;
                                var price_regex = /(\d{4,})/g;
                                var num_regex = /(\d{1,})/g;
                                var tensp = document.getElementById("txtTen").value;
                                var gia = document.getElementById("txtGia").value;
                                var sl = document.getElementById("txtSL").value;
                                var tt = document.getElementById("txtTT").value;
                                var nsx = document.getElementById("selNSX").value;
                                var lsp = document.getElementById("selLSP").value;

                                if (tensp == "" || gia == "" || sl == "" || tt == "" || nsx == "" || lsp == "") {
                                    alert("Thông tin không đầy đủ. Vui lòng kiểm tra lại.");
                                } else {
                                    if (price_regex.test(gia) == false || gia < 1000) {
                                        alert("Giá sản phẩm phải là số, ít nhất 4 chữ số và >= 1000.");
                                    } else if (num_regex.test(sl) == false || sl < 1) {
                                        alert("Số lượng phải là số và > 0.");
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
                            <form action="" method="post">
                                <div class="form-group" style="margin-top: 10px;">
                                    <label for="#Tensanpham">Tên sản phẩm</label>
                                    <input type="text" name="txtTen" id="txtTen" class="form-control">
                                </div>
                                <div class="form-group" style="margin-top: 10px;">
                                    <label for="#Giasanpham">Giá</label>
                                    <input type="text" name="txtGia" id="txtGia" class="form-control">
                                </div>
                                <div class="form-group" style="margin-top: 10px;">
                                    <label for="#Soluong">Số Lượng</label>
                                    <input type="number" name="txtSL" id="txtSL" class="form-control">
                                </div>
                                <div class="form-group" style="margin-top: 10px;">
                                    <label for="#Thongtinsanpham">Thông tin sản phẩm</label>
                                    <textarea name="txtTT" id="txtTT" cols="" rows="5" class="form-control"></textarea>
                                </div>
                                <div class="form-group" style="margin-top: 10px;">
                                    <label for="#Nhasanxuat">Nhà sản xuất</label>
                                    <select name="selNSX" id="selNSX" class="form-control">
                                        <?php
                                        $nsx = $connection->query("select nsx_ma,nsx_ten from nhasanxuat");
                                        while ($rowNSX = mysqli_fetch_array($nsx, MYSQLI_ASSOC)) {
                                        ?>
                                            <option value="<?php echo $rowNSX["nsx_ma"]; ?>"><?php echo $rowNSX["nsx_ten"]; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group" style="margin-top: 10px;">
                                    <label for="#Loaisanpham">Loại sản phẩm</label>
                                    <select name="selLSP" id="selLSP" class="form-control">
                                        <?php
                                        $lsp = $connection->query("select lsp_ma,lsp_ten from loaisanpham");
                                        while ($rowLSP = mysqli_fetch_array($lsp, MYSQLI_ASSOC)) {
                                        ?>
                                            <option value="<?php echo $rowLSP["lsp_ma"]; ?>"><?php echo $rowLSP["lsp_ten"]; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group text-right">
                                    <button type="submit" onclick="kiemtraInsert();" id="btnLuu" class="btn btn-info">Lưu</button>
                                </div>
                            </form>
                        </div>
                        <div id="frmImport">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="form-group text-right">
                                    <a href="Excel_Mau/SanPham_Mau.xlsx" download class="btn btn-outline-success">
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
        <form action="" method="post">
            <input type="submit" value="Xóa các mục đã chọn" style="margin-top: 10px;" class="btn btn-danger" name="btnDel" onclick="return xacnhan();">
            <br><br>
            <input type="checkbox" name="txtAll" id="txtAll" onclick="CheckAll();"> All
            <table id="dsSanPham" class="table table-striped table-bordered text-center" style="width:100%;">
                <thead>
                    <tr>
                        <th>Chọn</th>
                        <th>Mã</th>
                        <th>Tên</th>
                        <th>Giá</th>
                        <th>Số Lượng</th>
                        <th>Thông Tin</th>
                        <th>Nhà Sản Xuất</th>
                        <th>Loại Sản Phẩm</th>
                        <th>Hình Ảnh</th>
                        <th>Cập Nhật</th>
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
                    $result = $connection->query("select sp_ma,sp_ten,sp_gia,sp_soluong,sp_thongtin,nsx_ma,lsp_ma from sanpham");
                    $stt = 0;
                    while ($r = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        $stt++;
                    ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="txtXoa[]" id="txtXoa[]" value="<?php echo $r['sp_ma']; ?>">
                            </td>

                            <td><?php echo $r["sp_ma"]; ?></td>
                            <td><?php echo $r["sp_ten"]; ?></td>
                            <td><?php echo $r["sp_gia"]; ?></td>
                            <td><?php echo $r["sp_soluong"]; ?></td>
                            <td>
                                <?php
                                if (!isset($r["sp_thongtin"])) {
                                    echo "Chưa cập nhật";
                                } else {
                                    echo $r["sp_thongtin"];
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if (!isset($r["nsx_ma"])) {
                                    echo "Chưa cập nhật";
                                } else {
                                    $rsNSX = $connection->query("select nsx_ten from nhasanxuat where nsx_ma =" . $r["nsx_ma"]);
                                    $nameNSX = mysqli_fetch_array($rsNSX);
                                    echo $nameNSX[0];
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if (!isset($r["lsp_ma"])) {
                                    echo "Chưa cập nhật";
                                } else {
                                    $rsLSP = $connection->query("select lsp_ten from loaisanpham where lsp_ma =" . $r["lsp_ma"]);
                                    $nameLSP = mysqli_fetch_array($rsLSP);
                                    echo $nameLSP[0];
                                }
                                ?>
                            </td>
                            <td>
                                <a href="Aindex.php?key=Asanphamhinh&idsp=<?php echo $r["sp_ma"]; ?>">
                                    <button type="button" class="btn btn-primary">
                                        <i class="fas fa-images"></i>
                                    </button>
                                </a>
                            </td>
                            <td>
                                <button type="button" data-target="#CapNhatSanPham" data-toggle="modal" onclick="getIDName(this.value,this.name);" name="<?php echo $r["sp_ten"]; ?>" id="" value="<?php echo $r['sp_ma']; ?>" class="btn btn-primary">
                                    <input type="hidden" id="Gia<?php echo $r["sp_ma"]; ?>" value="<?php echo $r["sp_gia"]; ?>">
                                    <input type="hidden" id="SL<?php echo $r["sp_ma"]; ?>" value="<?php echo $r["sp_soluong"]; ?>">
                                    <input type="hidden" id="TT<?php echo $r["sp_ma"]; ?>" value="<?php echo $r["sp_thongtin"]; ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                            <script>
                                function getIDName(value, name) {
                                    var va = value;
                                    var na = name;

                                    document.getElementById("txtID").value = va;
                                    document.getElementById("txtCNTen").value = na;
                                    document.getElementById("txtCNGia").value = document.getElementById("Gia" + va).value;
                                    document.getElementById("txtCNSL").value = document.getElementById("SL" + va).value;
                                    document.getElementById("txtCNTT").value = document.getElementById("TT" + va).value;
                                }
                            </script>
                            <td>

                                <a href="Aindex.php?key=Asanpham&del=<?php echo $r['sp_ma']; ?>" onclick="return xacnhan();">
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
</div>

<!-- Modal Update -->
<div class="modal fade " id="CapNhatSanPham" tabindex="-1" aria-labelledby="CapNhatSanPhamLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="exampleModalLabel">Cập Nhật Sản Phẩm</h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button> -->
            </div>
            <div class="modal-body">
                <script>
                    function kiemtraUpdate() {
                        var price_regex = /(\d{4,})/g;
                        // var num_regex = /(\d{1,})/g;
                        var tensp = document.getElementById("txtCNTen").value;
                        var gia = document.getElementById("txtCNGia").value;
                        var sl = document.getElementById("txtCNSL").value;
                        var tt = document.getElementById("txtCNTT").value;
                        var nsx = document.getElementById("selCNNSX").value;
                        var lsp = document.getElementById("selCNLSP").value;

                        if (tensp == "" || gia == "" || sl == "" || tt == "" || nsx == "" || lsp == "") {
                            alert("Thông tin không đầy đủ. Vui lòng kiểm tra lại.");
                        } else {
                            if (price_regex.test(gia) == false || gia < 1000) {
                                alert("Giá sản phẩm phải là số, ít nhất 4 chữ số và >= 1000.");
                            } else {
                                document.getElementById("btnCapNhat").name = "btnCapNhat";
                            }
                        }
                    }
                </script>
                <form action="" method="post" onsubmit="kiemtraUpdate()">
                    <input type="hidden" name="txtID" id="txtID">
                    <div class="form-group" style="margin-top: 10px;">
                        <label for="#UpdateTensanpham">Tên sản phẩm</label>
                        <input type="text" name="txtCNTen" id="txtCNTen" class="form-control">
                    </div>
                    <div class="form-group" style="margin-top: 10px;">
                        <label for="#UpdateGiasanpham">Giá</label>
                        <input type="text" name="txtCNGia" id="txtCNGia" class="form-control">
                    </div>
                    <div class="form-group" style="margin-top: 10px;">
                        <label for="#UpdateSoluong">Số Lượng</label>
                        <input type="number" name="txtCNSL" id="txtCNSL" class="form-control">
                    </div>
                    <div class="form-group" style="margin-top: 10px;">
                        <label for="#UpdateThongtinsanpham">Thông tin sản phẩm</label>
                        <textarea name="txtCNTT" id="txtCNTT" cols="" rows="5" class="form-control"></textarea>
                    </div>
                    <div class="form-group" style="margin-top: 10px;">
                        <label for="#UpdateNhasanxuat">Nhà sản xuất</label>
                        <select name="selCNNSX" id="selCNNSX" class="form-control">
                            <?php
                            $nsx = $connection->query("select nsx_ma,nsx_ten from nhasanxuat");
                            while ($rowNSX = mysqli_fetch_array($nsx, MYSQLI_ASSOC)) {
                            ?>
                                <option value="<?php echo $rowNSX["nsx_ma"]; ?>"><?php echo $rowNSX["nsx_ten"]; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group" style="margin-top: 10px;">
                        <label for="#UpdateLoaisanpham">Loại sản phẩm</label>
                        <select name="selCNLSP" id="selCNLSP" class="form-control">
                            <?php
                            $lsp = $connection->query("select lsp_ma,lsp_ten from loaisanpham");
                            while ($rowLSP = mysqli_fetch_array($lsp, MYSQLI_ASSOC)) {
                            ?>
                                <option value="<?php echo $rowLSP["lsp_ma"]; ?>"><?php echo $rowLSP["lsp_ten"]; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" id="btnCapNhat" class="btn btn-info">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>