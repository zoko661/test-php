<?php
    include_once("Connection.php");
    session_start();
    if(isset($_POST["btnLogin"])){
        $user = $_POST["txtUser"];
        $password = md5($_POST["txtPass"]);
        
        $str = "select nv_ten,nv_ma,nv_chucvu,nv_trangthai from nhanvien where nv_taikhoan = '".$user."' and nv_matkhau = '".$password."'";

        $rs = $connection -> query($str);
        $row = mysqli_num_rows($rs);
        if($row == 0) {
            echo "<script> alert('Tài khoản hoặc mật khẩu không đúng.'); </script>";
        }else{
            $name = mysqli_fetch_assoc($rs);
            if($name["nv_trangthai"] == 0){
                echo "<script> alert('Tài khoản của bạn đã bị khóa.'); </script>";
            }else{
                $_SESSION["nv"] = $name["nv_ten"];
                $_SESSION["id"] = $name["nv_ma"];
                $_SESSION["cv"] = $name["nv_chucvu"];
                echo '<meta http-equiv="refresh" content="0;URL=Aindex.php">';
            }
            
        }
        

    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator - Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="CSS/login.css">
</head>

<body>
    <div class="wrapper fadeInDown">
        <div id="formContent">
            <!-- Tabs Titles -->

            <!-- Icon -->
            <div class="fadeIn first">
                <img src="images/logo.png" id="" alt="" />
            </div>

            <!-- Login Form -->
            <form method="POST" id="frmLogin" name="frmLogin">
                <input type="text" id="txtUser" class="fadeIn second" name="txtUser" placeholder="Enter user">
                <input type="password" id="txtPass" class="fadeIn third" name="txtPass" placeholder="Enter password">
                <input type="submit" class="fadeIn fourth" name="" id="btnLogin" value="Login" onclick="CheckValid()">
            </form>
            <script>
                    function CheckValid(){
                        var user = document.getElementById("txtUser").value;
                        var password = document.getElementById("txtPass").value;
                        if(user == "" || password == ""){
                            alert("User và Password không được để trống.");
                        }
                        else if(user.length <6 || user.length >18){
                            alert("User phải từ 6 đến 18 ký tự.");
                        }
                        else if(password.length <6 || password.length >32){
                            alert("Password phải từ 6 đến 18 ký tự.");
                        }
                        else{
                            document.getElementById("btnLogin").name = "btnLogin";
                        }
                    }
                </script>
            <!-- Remind Passowrd -->
            <div id="formFooter">
                <a class="underlineHover" href="#">Forgot Password?</a>
            </div>

        </div>
    </div>
</body>

</html>