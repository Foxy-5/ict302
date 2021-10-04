<?php
session_start();

include("connection.php");
include("function.php");

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    //something was posted
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];

    if(!empty($user_name) && !empty($password))
    {
        //read from database
        $query = "select * from users where username = '$user_name' limit 1";
        $result = mysqli_query($con,$query);

        if($result && mysqli_num_rows($result) > 0){
            $user_data = mysqli_fetch_assoc($result);
            if(password_verify($password,$user_data['password'])){
                $_SESSION['userid'] = $user_data['userid'];
                header("Location: home.php");
                die;
            }
            /*if($user_data['password'] === $password){
                $_SESSION['user_id'] = $user_data['user_id'];
                header("Location: home.php");
                die;
            }*/
        }
        echo '<script>
        alert("wrong username or password!");
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
        <title>Login</title>
    </head>
    <body>
        <style type = "text/css"> 
            #text{
                height: 25px;
                border-radius: 5px;
                padding: 4px;
                border: solid thin #aaa;
                width: 100%;
            }

            #button{
                padding: 10px;
                width: 100px;
                color: white;
                background-color: orange;
                border: none;
            }

            #box{
                background-color: skyblue;
                margin: auto;
                width: 300px;
                padding: 20px;
            }
        </style>

        <div id="box">
            <form method="post">
                <div style="font-size: 20px;margin: 10px;color:white">Login</div>
                <input id="text" type="text" name="user_name" placeholder="username"><br><br>
                <input id="text" type="password" name="password" placeholder="password"><br><br>

                <input id="button" type="submit" value="Login"><br><br>
                <a href="signup.php">Click to Sign Up</a><br><br>
            </form>
        </div>
    </body>
</html>