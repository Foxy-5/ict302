<?php
define('access', true);
session_start();

include("include/connection.php");
include("include/function.php");


if($_SERVER['REQUEST_METHOD'] == "POST")
{
    //something was posted
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];

    if(!empty($user_name) && !empty($password))
    {
        //read from database
        $query = "select * from staff where Username = '$user_name' limit 1";
        $result = mysqli_query($con,$query);

        if($result && mysqli_num_rows($result) > 0){
            $user_data = mysqli_fetch_assoc($result);
            if(password_verify($password,$user_data['Password'])){
                $_SESSION['StaffID'] = $user_data['StaffID'];
                header("Location: home");
                exit();
            }
        }
        echo '<script>
        alert("Wrong username or password!");
        </script>';
    }
    else
    {
        echo '<script>
        alert("Please enter valid details");
        </script>';
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Login | Meetme v2</title>
        <link rel="stylesheet" href="css/mystyle.css">
    </head>
    <body style="background-color:black">
        <div class="logobar">
            <div class="logintextbar">
                Login
            </div>
            <a href="https://www.murdoch.edu.au" target="_blank"><img src="Image/MU Logo.png" class="logo"></a>
        </div>
        <form method="post">
            <div>
                <img src="Image/LoginBackground.jpg" class="imagebackground">
                <div class="logincontainerbox">
                    <input class="loginboxtext" type="text" name="user_name" placeholder="USERNAME">
                    </br>
                    </br>
                    <input class="loginboxtext" type="password" name="password" placeholder="PASSWORD">
                    </br>
                    <div class="logincontainerbutton">
                        <input class="loginpagebutton" type="reset" value="Clear">
                        <input class="loginpagebutton" type="submit" value="Login"><br><br>
                        <div class="loginsignup">
                            Click <a href="signup"><em>here</em></a> to sign up
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </body>
</html>
