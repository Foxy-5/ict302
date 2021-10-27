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
        $query = "select * from staff where Username = '$user_name' limit 1";
        $result = mysqli_query($con,$query);

        if($result && mysqli_num_rows($result) > 0)
        {
            $user_data = mysqli_fetch_assoc($result);
            if(password_verify($password,$user_data['Password']))
            {
                $_SESSION['StaffID'] = $user_data['StaffID'];
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
        <title>Login | Meetme v2</title>
    </head>
    <body>
        <style type = "text/css"> 
            #text{
                height: 30px;
                border-radius: 5px;
                border: solid thin #aaa;
            }

            #button{
                padding: 15px;
                width: 100px;
                color: white;
                background-color: #e12744;
                text-transform: uppercase;
                font-size: 14px;
                font-family: "Open Sans", sans-serif;
                border-radius: 8px;
                border: none;
                cursor: pointer;
            }

            #button:hover{
                background-color: #ac182f
            }

            #logobar{
                display: block;
                width: 100%;
                background-color: black;
                height: auto;
            }

            #authenticationBox{
                width: 400px;
                padding-top: 20px;
                padding-bottom: 20px;
            }
            #buttonBox{
                text-align: center;
            }

        </style>
            <div id="logobar">
                <img src="Image/MU Logo.png"><a href="_self"></a>
            </div>
            <form method="post">
            <div id="buttonBox">
                <div id="authenticationBox">
                    <input id="text" type="text" name="user_name" placeholder="username">
                    </br>
                    </br>
                    <input id="text" type="password" name="password" placeholder="password">
                </div>
                <input id="button" type="reset" value="Clear">
                <input id="button" type="submit" value="Login"><br><br>
                <a href="signup.php">Click to Sign Up</a>
                </div>
            </form>
    </body>
</html>