<?php
if (isset($_GET["idsp"])) {
    $masp = $_GET["idsp"];
}
?>

<script>
    function getComment() {
        var cmt = document.getElementById("txtCmt").value;
        var makh = document.getElementById("maKH").value;
        var masp = document.getElementById("txtMaSP").value;

        var url = "Ccomment.php?cmt=" + cmt + "&makh=" + makh + "&masp=" + masp;

        var xhttp;

        if (window.XMLHttpRequest) {
            xhttp = new XMLHttpRequest();
        } else {
            xhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xhttp.onreadystatechange = function() {

            if (this.readyState == 4 && this.status == 200) {

                var data = "";
                var noidung;
                var thoigian;
                var khachhang;
                var rs = xhttp.responseXML.documentElement.getElementsByTagName("data");
                // alert(rs.length);
                for (var i = 0; i < rs.length; i++) {
                    noidung = rs[i].getElementsByTagName("noidung");
                    thoigian = rs[i].getElementsByTagName("thoigian");
                    khachhang = rs[i].getElementsByTagName("khachhang");
                    data += '<div class="row" style="margin-top: 5px;" ><p class="card-text"><b>' + khachhang[0].firstChild.nodeValue + '</b> lúc <i>' + thoigian[0].firstChild.nodeValue + '</i>:  ' + noidung[0].firstChild.nodeValue + '</p></div>';
                }

                document.getElementById("showCMT").innerHTML = "";
                document.getElementById("showCMT").innerHTML = data;
            }
        }
        xhttp.open("GET", url, true);
        xhttp.send();
    }
</script>

<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 p-3" style="width:100%" >
    <div class="card">
        <div class="row no-gutters">
            <div class="col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                <div id="carouselIMGControls" style="width:100%" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <?php
                        $hinhList = $connection->query("select sph_hinh from sanphamhinh where sp_ma =" . $masp . " order by sph_ma desc");
                        $stt = 0;
                        while ($rList = mysqli_fetch_array($hinhList, MYSQLI_ASSOC)) {
                            $stt++;
                            if ($stt == 1) {
                        ?>
                                <div class="carousel-item active">
                                    <img src="images/<?php echo $rList["sph_hinh"]; ?>" class="d-block w-100 card-img" height="450px" alt="...">
                                </div>
                            <?php
                            } else {
                            ?>
                                <div class="carousel-item">
                                    <img src="images/<?php echo $rList["sph_hinh"]; ?>" class="d-block w-100 card-img" height="450px" alt="...">
                                </div>
                        <?php
                            }
                        }
                        ?>
                    </div>
                    <a class="carousel-control-prev" href="#carouselIMGControls" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon btn btn-info" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselIMGControls" role="button" data-slide="next">
                        <span class="carousel-control-next-icon btn btn-info" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
            <?php
            $infoSP = $connection->query("select sp_ma,sp_ten,sp_gia,sp_thongtin,sp_soluong,lsp_ten,nsx_ten 
                                from sanpham s,loaisanpham l, nhasanxuat n where s.lsp_ma = l.lsp_ma and s.nsx_ma=n.nsx_ma and sp_ma=" . $masp);
            while ($rs = mysqli_fetch_array($infoSP, MYSQLI_ASSOC)) {
            ?>
                <div class="col-8 col-sm-8 col-md-8 col-lg-8 col-xl-8">
                    <div class="card-body" style="margin-left: 30px;">
                        <h5 class="card-title"><?php echo $rs["sp_ten"]; ?></h5> <br>
                        <input type="hidden" value="<?php echo $rs["sp_ma"]; ?>" id="txtMaSP"></input>
                        <p class="card-text"><b>Giá: </b><?php echo $rs["sp_gia"]; ?> đ</p> <br>
                        <p class="card-text"><b>Loại sản phẩm: </b><?php echo $rs["lsp_ten"]; ?></p> <br>
                        <p class="card-text"><b>Nhà sản xuất: </b><?php echo $rs["nsx_ten"]; ?></p> <br>
                        <?php
                        if ($rs["sp_soluong"] > 0) {
                        ?>
                            <button class="btn btn-success">Còn hàng</button> <br> <br>
                        <?php
                        } else {
                        ?>
                            <button class="btn btn-danger">Hết hàng</button> <br> <br>
                        <?php
                        }
                        ?>
                        <a href="index.php?func=cart&idsp=<?php echo $masp; ?>" class="btn btn-outline-info">
                            Add to cart
                            <i class="fas fa-shopping-cart"></i>
                        </a>
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin-top: 40px;">
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                <h5>Thông tin sản phẩm</h5>
                                <p class="card-text"><?php echo $rs["sp_thongtin"]; ?></p>
                            </div>
                            <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 " >
                                <script>
                                    function cmt() {
                                        var bl = document.getElementById("txtCmt").value;
                                        var makh = document.getElementById("maKH").value;
                                        if (bl == "") {
                                            alert("Hãy nhập ý kiến của bạn về sản phẩm này");
                                        } else if (makh == "") {
                                            alert("Đăng nhập để bình luận");
                                        }
                                    }
                                </script>
                                <form action="" method="POST">
                                    <div class="form-inline">
                                        <input type="text" name="txtCmt" id="txtCmt" style="width: 75%;" class="form-control">
                                        <button type="button" name="btnCmt" id="btnCmt" onclick="cmt();getComment();" class="btn btn-outline-info">Bình luận</button>
                                    </div>
                                </form>
                                <div id="showCMT">
                                    <?php 
                                        $cmt = $connection -> query("select gy_noidung,gy_thoigian,kh_ten from gopy g, khachhang k where g.kh_ma=k.kh_ma and sp_ma =" . $masp ." order by gy_thoigian desc");

                                        while($r = mysqli_fetch_array($cmt)){
                                    ?>
                                        <div class="row" style="margin-top: 5px;" ><p class="card-text"><b><?php echo $r[2];?></b> lúc <i><?php echo $r[1];?></i>:  <?php echo $r[0];?></p></div>
                                    <?php
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php
            }
            ?>
        </div>
    </div>
</div>
</div>