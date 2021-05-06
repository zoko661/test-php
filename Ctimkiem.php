<?php
header("Content-Type:application/json");

require_once("Connection.php");

if (isset($_GET["data"])) {
    $data = isset($_GET["data"]) ? mysqli_real_escape_string($connection, $_GET["data"]) : "";
}

$rs = $connection->query("select sp_ma,sp_ten,sp_gia from sanpham s, loaisanpham l, nhasanxuat n 
    where s.lsp_ma=l.lsp_ma and s.nsx_ma=n.nsx_ma and (sp_ten like '%" . $data . "%' or lsp_ten like '%" . $data . "%' or nsx_ten like '%" . $data . "%')");

$response = array();

if (mysqli_num_rows($rs) > 0) {
    while ($row = mysqli_fetch_array($rs)) {
        $response[] = $row;
    }
    echo json_encode($response);

    mysqli_close($connection);
}

// function response($masp,$tensp,$giasp){
// 	$response[0] = $masp;
// 	$response[1] = $tensp;
// 	$response[2] = $giasp;
// 	$json_response = json_encode($response);
// 	echo $json_response;
// }
?>
