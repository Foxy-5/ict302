<?php
define('access', TRUE);
session_start();

    include("include/connection.php");
    include("include/function.php");

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
                mysqli_commit($con);
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
        <title>Signup | Meetme v2</title>
        <link rel="stylesheet" href="css/mystyle.css">
    </head>
    <body>
        <style type = "text/css"> 

        </style>
        <div class="signupflexcontainer">
            <div class="logobar">
                <div class="signuptextbar">
                    Registration Form
                </div>
                <a href="https://www.murdoch.edu.au" target="_blank"><img src="Image/MU Logo.png" class="logo"></a>
            </div>

            <img src="Image/LoginBackground.jpg" class="imagebackground">
            <form method="post">
                <div class="signupcontainerbox">
                    <label for="first_name" class="signuptext">First Name</label><br>
                    <input class="signuptextbox" name="first_name" id="text" placeholder="FIRST NAME"><br><br>
                    <label for="last_name" class="signuptext">Last Name</label><br>
                    <input class="signuptextbox"" name="last_name" id="text" placeholder="LAST NAME/FAMILY NAME"><br><br>
                    <label for="email" class="signuptext">Email</label><br>
                    <input class="signuptextbox" name="email" id="text" placeholder="EMAIL ADDRESS"><br><br>
                    <label for="user_name" class="signuptext">Username</label><br>
                    <input class="signuptextbox" type="text" name="user_name" placeholder="USERNAME"><br><br>
                    <label for="password" class="signuptext">Password</label><br>
                    <input class="signuptextbox" type="password" name="password" placeholder="PASSWORD"><br><br>
                    <label for="repassword" class="signuptext">Re-Enter Password</label><br>
                    <input class="signuptextbox" type="password" name="repassword" placeholder="RE-ENTER PASSWORD"><br><br>

                    <input class="signuppagebutton" type="reset" value="Clear">
                    <input class="signuppagebutton" type="submit" value="Sign Up"><br><br>

                    <div class="loginsignup">
                        Click <a href="login.php"><em>here</em></a> back to Login
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>