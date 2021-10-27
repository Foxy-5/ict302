<?php
session_start();

    include("connection.php");
    include("function.php");

    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        //something was posted
        $firstname = $_POST['first_name'];
        $lastname = $_POST['last_name'];
        $email = $_POST['email'];
        $user_name = $_POST['user_name'];
        $password = $_POST['password'];
        $password2 = $_POST['repassword'];
        if(!empty($firstname) && !empty($lastname) && !empty($email) && !empty($user_name) && !empty($password) && ($password == $password2))
        {
            //save to database
            
            $hash = password_hash($password,PASSWORD_DEFAULT);
            $query = "insert into staff (First_name,Last_name,Username,Password,Email) values ('$firstname','$lastname','$user_name','$hash','$email')";
            if(mysqli_query($con,$query)){
                echo '<script>
                alert("Account succesfully created.");
                window.location.href="login.php";
                </script>';
                die;
            }
            else{
                echo '<script>alert("Username already exists, please try another.")</script>';
                //echo "Username already exists, please try another.";
            }
            
        }
        else
        {
            echo '<script>alert("Please enter valid details.")</script>';
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Signup</title>
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
                <div style="font-size: 20px;margin: 10px;color:white">Sign Up</div>
                <label for="first_name">First name</label><br>
                <input type="text" name="first_name" id="text" placeholder="first name"><br><br>
                <label for="last_name">Last name</label><br>
                <input type="text" name="last_name" id="text" placeholder="last name"><br><br>
                <label for="email">email</label><br>
                <input type="email" name="email" id="text" placeholder="email address"><br><br>
                <label for="user_name">username</label><br>
                <input id="text" type="text" name="user_name" placeholder="username"><br><br>
                <label for="password">password</label><br>
                <input id="text" type="password" name="password" placeholder="password"><br><br>
                <label for="repassword">enter password again</label><br>
                <input id="text" type="password" name="repassword" placeholder="reenter password"><br><br>
                <input id="button" type="submit" value="Sign up"><br><br>
                <a href="login.php">Click to Login</a><br><br>
            </form>
        </div>
    </body>
</html>