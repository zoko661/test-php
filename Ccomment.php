<?php
    header('Content-Type: text/xml');
    require_once("Connection.php");

    if(isset($_GET["cmt"]) && isset($_GET["makh"]) && isset($_GET["masp"])){
        $cmt = $_GET["cmt"];
        $makh = $_GET["makh"];
        $masp = $_GET["masp"];
        if(!empty($cmt) && !empty($makh)){
            $connection->query("insert into gopy(gy_noidung,gy_thoigian,sp_ma,kh_ma) values('" . $cmt . "',now()," . $masp . "," . $makh . ")");
        }
        
    }

    $rs = $connection -> query("select gy_noidung,gy_thoigian,kh_ten from gopy g, khachhang k where g.kh_ma=k.kh_ma and sp_ma =" . $masp ." order by gy_thoigian desc");
    
    if(mysqli_num_rows($rs) >0){
        $xml = new DOMDocument();
        $xml->formatOutput = true;

        $list = $xml->createElement("list");
        $xml->appendChild($list);

        while($row = mysqli_fetch_array($rs,MYSQLI_ASSOC)){
            $data = $xml -> createElement("data");
            $list->appendChild($data);

            $noidung = $xml -> createElement("noidung",$row["gy_noidung"]);
            $data->appendChild($noidung);
            $thoigian = $xml -> createElement("thoigian",$row["gy_thoigian"]);
            $data->appendChild($thoigian);
            $khachhang = $xml -> createElement("khachhang",$row["kh_ten"]);
            $data->appendChild($khachhang);
        }
        mysqli_free_result($rs);
    }
    mysqli_close($connection);
    echo $xml->saveXML();
?>