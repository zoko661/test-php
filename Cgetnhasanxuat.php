<?php
header("Content-Type:application/json");

require_once("Connection.php");
if(isset($_GET["nsx"])){
    $nsx = $_GET["nsx"];
    $rs = $connection->query("select sp_ma,sp_ten,sp_gia from sanpham where nsx_ma =" . $nsx . " order by sp_ma desc");

    $response = array();

    if(mysqli_num_rows($rs)>0){
        while($row = mysqli_fetch_array($rs)){
            $response[] = $row;				
        }
        echo json_encode($response);	
            
        mysqli_close($connection);
    }
}
?>