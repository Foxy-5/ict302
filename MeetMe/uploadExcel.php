<?php
session_start();

include("connection.php");
include("function.php");

$user_data = check_login($con);

if(isset($_FILES['excel'])){
	$errors= array();
	$file_name = $_FILES['excel']['name'];
	$file_size =$_FILES['excel']['size'];
	$file_tmp =$_FILES['excel']['tmp_name'];
	$file_type=$_FILES['excel']['type'];
	//$file_ext=strtolower(end(explode('.',$_FILES['excel']['name'])));
	$file_ext=strtolower(pathinfo($_FILES['excel']['name'],PATHINFO_EXTENSION));
	$extensions= array("xlsx","xls");
	$errortype = 0;
	if(in_array($file_ext,$extensions)=== false){
	   $errortype = 1;
	}
	
	if($file_size > 2097152){
	   $errortype = 2;
	}
	
	if($errortype == 0){
	   move_uploaded_file($file_tmp,"uploads/".$file_name);
	   echo '<script>alert("Excel file uploaded.")</script>';
	}else{
		if($errortype == 1){
			echo '<script>alert("extension not allowed, please choose a excel file.")</script>';
		}
		else if($errortype == 2){
			echo '<script>alert("File size must be excately 2 MB")</script>';
		}
	}
 }
?>
<script>
        function fileValidation() {
            var fileInput = 
                document.getElementById('excel');
              
            var filePath = fileInput.value;
          
            // Allowing file type
            var allowedExtensions = /(\.xls|\.xlsx)$/i;
              
            if (!allowedExtensions.exec(filePath)) {
                alert('Invalid file type');
                fileInput.value = '';
                return false;
            } 
        }
    </script>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <title>navbar</title>
</head>

<body>
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">MeetMe</a>
            </div>
            <ul class="nav navbar-nav">
                <li class="active"><a href="home.php">Home</a></li>
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Appointment <span
                            class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li class="item"><a href="uploadExcel.php">Upload Excel</a></li>
                        <li class="item"><a href="#">View Appointment</a></li>
                    </ul>
                </li>
                <li><a href="about.php">About</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <span class="glyphicon glyphicon-user"></span><span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">User Profile</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <div class="content">
        <h3>Upload excel file</h3><br>
		<h4>Welcome <?php echo $user_data['First_name'];?> <?php echo $user_data['Last_name'];?>, to the excel upload page. </h4><br>
        <form action="" method="POST" enctype="multipart/form-data">
        <input type="file" id="excel"name="excel" onchange="return fileValidation()"/><br>
        <input type="submit"/>
        </form>
    </div>
</body>

</html>
