<script language="javascript">
    $(document).ready(function() {
        var table = $('#dsNhaSanXuat').DataTable({
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
require_once("lib/nusoap.php");
$client = new nusoap_client("http://localhost/php/service.php?wsdl");
?>

<?php
// inserts
if (isset($_POST["btnLuu"])) {
    $nsx = $_POST["txtNSX"];
    $str = "select nsx_ten from nhasanxuat where nsx_ten ='" . $nsx . "'";
    $rs = mysqli_query($connection, $str);
    $row = mysqli_num_rows($rs);
    if ($row > 0) {
        echo "<script> alert('Nhà sản xuất đã tồn tại'); </script>";
    } else {
        $sql = "insert into nhasanxuat(nsx_ten) values('" . $nsx . "')";
        $query = $connection->query($sql);
        echo '<meta http-equiv="refresh" content="0;URL=Aindex.php?key=Anhasanxuat">';
    }
}

// Delete
if (isset($_GET["del"])) {
    $id = $_GET["del"];

    $str = "delete from nhasanxuat where nsx_ma =" . $id;
    $connection->query($str);
    echo '<meta http-equiv="refresh" content="0;URL=Aindex.php?key=Anhasanxuat">';
}

//List Delete
if (isset($_POST["btnDel"])) {
    if (!isset($_POST["txtXoa"])) {
        echo "<script> alert('Hãy chọn dòng muốn xóa'); </script>";
    } else {
        $listDel = count($_POST["txtXoa"]);
        for ($i = 0; $i < $listDel; $i++) {
            $id = $_POST["txtXoa"][$i];
            $connection->query("Delete from nhasanxuat where nsx_ma ='" . $id . "'");
            echo '<meta http-equiv="refresh" content="0;URL=Aindex.php?key=Anhasanxuat">';
        }
    }
}

//Update
if (isset($_POST["btnCapNhat"])) {
    $ten = $_POST["txtCNNSX"];
    $ma = $_POST["txtID"];

    $str = "Select nsx_ten from nhasanxuat where nsx_ten = '" . $ten . "'";
    $rs = $connection->query($str);
    $row = mysqli_num_rows($rs);
    if ($row > 0) {
        echo "<script> alert('Nhà sản xuất đã tồn tại'); </script>";
    } else {
        $sql = "Update nhasanxuat set nsx_ten ='" . $ten . "' where nsx_ma =" . $ma;

        $connection->query($sql);
        echo '<meta http-equiv="refresh" content="0;URL=Aindex.php?key=Anhasanxuat">';
    }
}
?>

<?php
//Import file
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

            $str = "select nsx_ten from nhasanxuat where nsx_ten ='" . $name . "'";

            $rs = $connection->query($str);
            $row = mysqli_num_rows($rs);
            if ($row < 1) {
                $sql = "insert into nhasanxuat(nsx_ten) values('" . $name . "')";
                $connection->query($sql);
                $count++;
            }
        }
    }
    echo "<script> alert('Đã thêm $count/$total dòng dữ liệu'); </script>";
}

?>

<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin-bottom: 20px;">
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ThemNhaSanXuat">
            Thêm nhà sản xuất
        </button>

        <!-- Modal Insert-->
        <div class="modal fade " id="ThemNhaSanXuat" tabindex="-1" aria-labelledby="TThemNhaSanXuatLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="exampleModalLabel">Thêm Nhà Sản Xuất</h5>
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
                                var lsp = document.getElementById("txtNSX").value;

                                if (lsp == "") {
                                    alert("Hãy nhập tên nhà sản xuất");
                                } else {
                                    document.getElementById("btnLuu").name = "btnLuu";
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
                                    <label for="#Nhapnhasanxuat">Nhập nhà sản xuất</label>
                                    <input type="text" name="txtNSX" id="txtNSX" class="form-control">
                                </div>
                                <div class="form-group text-right">
                                    <button type="submit" id="btnLuu" class="btn btn-info">Lưu</button>
                                </div>
                            </form>
                        </div>
                        <div id="frmImport">

                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="form-group text-right">
                                    <a href="Excel_Mau/NhaSanXuat_Mau.xlsx" download class="btn btn-outline-success">
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
            <table id="dsNhaSanXuat" class="table table-striped table-bordered text-center" style="width:100%;">
                <thead>
                    <tr>
                        <th>Chọn </th>
                        <th>STT</th>
                        <th>Mã</th>
                        <th>Nhà Sản Xuất</th>
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
                    $response = $client->call("getNhaSanXuat");
                    $result = json_decode($response, true);
                    $stt = 0;
                    if (isset($result)) {
                        foreach ($result as $r) {
                            $stt++;
                    ?>
                            <tr>
                                <td><input type="checkbox" name="txtXoa[]" id="txtXoa[]" value="<?php echo $r[0]; ?>">
                                </td>
                                <td><?php echo $stt; ?></td>
                                <td><?php echo $r[0]; ?></td>
                                <td><?php echo $r[1]; ?></td>
                                <td>
                                    <button type="button" data-target="#CapNhatNhaSanXuat" data-toggle="modal" onclick="getIDName(this.value,this.name);" name="<?php echo $r[1]; ?>" id="" value="<?php echo $r[0]; ?>" class="btn btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                                <script>
                                    function getIDName(value, name) {
                                        var va = value;
                                        var na = name;
                                        document.getElementById("txtID").value = va;
                                        document.getElementById("txtCNNSX").value = na;
                                    }
                                </script>
                                <td>

                                    <a href="Aindex.php?key=Anhasanxuat&del=<?php echo $r[0]; ?>" onclick="return xacnhan();">
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

<!-- Modal Update -->
<div class="modal fade " id="CapNhatNhaSanXuat" tabindex="-1" aria-labelledby="CapNhatLoaiSanPhamLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="exampleModalLabel">Cập Nhật Nhà Sản Xuất</h5>
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
                        var nsx = document.getElementById("txtCNNSX").value;

                        if (nsx == "") {
                            alert("Hãy nhập tên nhà sản xuất");
                        } else {
                            document.getElementById("btnCapNhat").name = "btnCapNhat";
                        }
                    }
                </script>
                <form action="" method="post" onsubmit="kiemtraUpdate()">
                    <input type="hidden" name="txtID" id="txtID">
                    <div class="form-group" style="margin-top: 10px;">
                        <label for="#Nhaploaisanpham">Nhập loại sản phẩm</label>
                        <input type="text" name="txtCNNSX" id="txtCNNSX" class="form-control">
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" id="btnCapNhat" class="btn btn-info">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>