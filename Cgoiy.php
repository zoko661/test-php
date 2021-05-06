<?php
header('Content-Type: text/xml');


require_once("Connection.php");
if (isset($_GET["str"])) {
    $str = $_GET["str"];
    $sp = $connection->query("select sp_ten from sanpham where sp_ten like '%" . $str . "%'");
    $lsp = $connection->query("select lsp_ten from loaisanpham where lsp_ten like '%" . $str . "%'");
    $nsx = $connection->query("select nsx_ten from nhasanxuat where nsx_ten like '%" . $str . "%'");
    // var_dump($sp);
}

if (mysqli_num_rows($sp)>0 || mysqli_num_rows($lsp)>0 ||mysqli_num_rows($nsx)>0) {
    $xml = new DOMDocument();
    $xml->formatOutput = true;

    $ds = $xml->createElement("list");
    $xml->appendChild($ds);
    while($rowSP = mysqli_fetch_array($sp)){
        $gopy = $xml -> createElement("goiy");
        $ds -> appendChild($gopy);

        $data = $xml -> createElement("data",$rowSP[0]);
        $gopy -> appendChild($data);

    }
    while($rowLSP = mysqli_fetch_array($lsp)){
        $gopy = $xml -> createElement("goiy");
        $ds -> appendChild($gopy);

        $data = $xml -> createElement("data",$rowLSP[0]);
        $gopy -> appendChild($data);
    }
    while($rowNSX = mysqli_fetch_array($nsx)){
        $gopy = $xml -> createElement("goiy");
        $ds -> appendChild($gopy);

        $data = $xml -> createElement("data",$rowNSX[0]);
        $gopy -> appendChild($data);
    }

    mysqli_free_result($sp);
    mysqli_free_result($lsp);
    mysqli_free_result($nsx);
}
mysqli_close($connection);
echo $xml->saveXML();
?>