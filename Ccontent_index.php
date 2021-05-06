<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin-top: 40px; margin-bottom:40px">
    <h5>Loại Sản Phẩm</h5>
    <?php
    $listLSP = $connection->query("select lsp_ma,lsp_ten from loaisanpham");
    while ($r = mysqli_fetch_array($listLSP, MYSQLI_ASSOC)) {
    ?>
        <a href="index.php?key=Cloaisanpham&id_lsp=<?php echo $r["lsp_ma"]; ?>" style="margin-top: 5px;" class="btn btn-outline-info"><?php echo $r["lsp_ten"]; ?></a>
    <?php
    }
    ?>
</div>

<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin-bottom:40px">
    <h5>Nhà Sản Xuất</h5>
    <?php
    $listNSX = $connection->query("select nsx_ma,nsx_ten from nhasanxuat");
    while ($r = mysqli_fetch_array($listNSX, MYSQLI_ASSOC)) {
    ?>
        <a href="index.php?key=Cnhasanxuat&id_nsx=<?php echo $r["nsx_ma"]; ?>" style="margin-top: 5px;" class="btn btn-outline-info"><?php echo $r["nsx_ten"]; ?></a>
    <?php
    }
    ?>
</div>

<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
    <div class="row">
        <?php
        $spMoi = $connection->query("select sp_ma,sp_ten,sp_gia from sanpham order by sp_ma desc");
        while ($row = mysqli_fetch_array($spMoi, MYSQLI_ASSOC)) {
        ?>
            <div class=" col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2 text-center" style="margin-top: 5px;">
                <div class="card ">
                    <a href="index.php?key=Cchitietsanpham&idsp=<?php echo $row["sp_ma"]; ?>">
                        <?php
                        $hsp = $connection->query("Select sph_hinh from sanphamhinh where sp_ma = '" . $row['sp_ma'] . "' order by sph_ma desc limit 1 ");
                        while ($r = mysqli_fetch_array($hsp, MYSQLI_ASSOC)) {
                        ?>
                            <img src="images/<?php echo $r["sph_hinh"]; ?>" class="card-img-top" height="150px" alt="">
                        <?php
                        }
                        ?>
                    </a>
                    <div class="card-body">
                        <a href="index.php?key=Cchitietsanpham&idsp=<?php echo $row["sp_ma"]; ?>">
                            <span class="card-title"><?php echo $row["sp_ten"]; ?></span>
                        </a>
                        <p><?php echo $row["sp_gia"]; ?> đ</p>
                    </div>
                    <div class="card-footer">
                        <a href="index.php?func=cart&idsp=<?php echo $row["sp_ma"]; ?>" class="btn btn-outline-info">
                            Add to cart
                            <i class="fas fa-shopping-cart"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
</div>