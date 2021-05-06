<?php
if (isset($_GET["id_lsp"])) {
    $lsp = $_GET["id_lsp"];

    $url = "http://localhost/php/Cgetloaisanpham.php?lsp=" . $lsp;

    $client = curl_init($url);


    curl_setopt($client, CURLOPT_RETURNTRANSFER, true);


    $response = curl_exec($client);

    $rs = json_decode($response, true);




    if (isset($rs)) {
        foreach ($rs as $row) {
?>
            <div class=" col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2 text-center" style="margin-top: 5px;">
                <div class="card ">
                    <a href="index.php?key=Cchitietsanpham&idsp=<?php echo $row[0]; ?>">
                        <?php
                        $hsp = $connection->query("Select sph_hinh from sanphamhinh where sp_ma = '" . $row[0] . "' order by sph_ma desc limit 1 ");
                        while ($r = mysqli_fetch_array($hsp, MYSQLI_ASSOC)) {
                        ?>
                            <img src="images/<?php echo $r["sph_hinh"]; ?>" class="card-img-top" height="150px" alt="">
                        <?php
                        }
                        ?>
                    </a>
                    <div class="card-body">
                        <a href="index.php?key=Cchitietsanpham&idsp=<?php echo $row[0]; ?>">
                            <span class="card-title"><?php echo $row[1]; ?></span>
                        </a>
                        <p><?php echo $row[2]; ?> đ</p>
                    </div>
                    <div class="card-footer">
                        <a href="index.php?func=cart&idsp=<?php echo $row[0]; ?>" class="btn btn-outline-info">
                            Add to cart
                            <i class="fas fa-shopping-cart"></i>
                        </a>
                    </div>
                </div>
            </div>
<?php
        }
    }
}
?>