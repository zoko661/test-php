<?php
    function getLoaiSanPham(){
            require_once("Connection.php");
            $rs = $connection -> query("select * from loaisanpham");

            $response = array();
            if(mysqli_num_rows($rs)>0){
                while($row = mysqli_fetch_array($rs)){
                    $response[] = $row;
                }

                return json_encode($response);
            }
    }

    function getNhaSanXuat(){
        require_once("Database/Connection.php");
        $rs = $connection -> query("select * from nhasanxuat");

        $response = array();
        if(mysqli_num_rows($rs)>0){
            while($row = mysqli_fetch_array($rs)){
                $response[] = $row;
            }

            return json_encode($response);
        }
    }

    function getHTTT(){
        require_once("Connection.php");
        $rs = $connection -> query("select * from hinhthucthanhtoan");

        $response = array();
        if(mysqli_num_rows($rs)>0){
            while($row = mysqli_fetch_array($rs)){
                $response[] = $row;
            }
            return json_encode($response);
        }
    }
?>