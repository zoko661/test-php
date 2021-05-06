<script language="javascript">
    $(document).ready(function() {
        var table = $('#Cart').DataTable({
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

//Delete
if(isset($_GET["del"])){
    $ma = $_GET["del"];
    unset($_SESSION["cart"][$ma]);
    echo '<meta http-equiv="refresh" content="0;URL=index.php?key=Ccart">';
}

//Delete list
if(isset($_POST["btnDel"])){
    if(!isset($_POST["txtXoa"])){
        echo "<script> alert('Hãy chọn dòng muốn xóa'); </script>";
    }
    else{
        $list = count($_POST["txtXoa"]);
        for($i =0; $i<$list;$i++){
            $ma = $_POST["txtXoa"][$i];
            unset($_SESSION["cart"][$ma]);
            echo '<meta http-equiv="refresh" content="0;URL=index.php?key=Ccart">';
        }
    }

}

?>

<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 table-responsive table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl">
    <form action="" method="post">
        <input type="submit" value="Xóa các mục đã chọn" style="margin-top: 10px;" class="btn btn-danger" name="btnDel" onclick="return xacnhan();">
        <br><br>
        <input type="checkbox" name="txtAll" id="txtAll" onclick="CheckAll();"> All
        <table id="Cart" class="table table-striped table-bordered text-center" style="width:100%;">
            <thead>
                <tr>
                    <th>Chọn</th>
                    <th>Mã sản phẩm</th>
                    <th>Sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
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

                if (isset($_SESSION["cart"])) {
                    $total = 0;

                    foreach ($_SESSION["cart"] as $key => $value) {
                ?>
                        <tr>
                            <td><input type="checkbox" name="txtXoa[]" id="txtXoa[]" value="<?php echo $value["masp"]; ?>">
                            </td>
                            <td><?php echo $value["masp"]; ?></td>
                            <td><?php echo $value["tensp"]; ?></td>
                            <td><?php echo  number_format($value["giasp"], 0, ",", "."); ?></td>
                            <td>
                                <input type="text" name="SP<?php echo $key; ?>" value="<?php echo $value["soluong"]; ?>" disabled>
                            </td>
                            <td>
                                <?php echo number_format($value["giasp"] * $value["soluong"], 0, ",", "."); ?>
                            </td>
                            <td>
                                <a href="index.php?key=Ccart&del=<?php echo $value["masp"]; ?>" onclick="return xacnhan();">
                                    <button type="button" class="btn btn-danger">
                                        <i class="fas fa-minus"></i>
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
                        $total += $value["giasp"] * $value["soluong"];
                    }
                }
                ?>
            </tbody>
        </table>
        <div class="row text-right" style="margin-top: 20px">
            <div class="text-right" style="width:100%" >
                <b>Tổng Tiền </b>
                <input type="text" name="txtToTal" value="<?php echo number_format($total, 0, ",", "."); ?>" disabled>
            </div >
            <div class="text-right" style="width:100%; margin-top: 20px">
                <button type="submit" name="btnThanhToan" class="btn btn-info">Thanh Toán</button>
            </div>
        </div>
    </form>
</div>

<?php
    if(isset($_POST["btnThanhToan"])){
        if(!isset($_SESSION["taikhoan"])){
            echo "<script> alert('Hãy đăng nhập để thanh toán'); </script>";
        }else{
            foreach($_SESSION["cart"] as $key => $value){
                // $_SESSION["cart"][$key]["soluong"] = $_POST["SP". $key];
                $_SESSION["total"] = $total;
                echo '<meta http-equiv="refresh" content="0;URL=index.php?key=CThanhToan">';
            }
        }
    }
?>