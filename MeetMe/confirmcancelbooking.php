<?php
session_start();

define('access',true);

require_once("include/connection.php");
require_once("include/email.php");


if(!isset($_SESSION['cnclBk'])){
	http_response_code(404);
	exit();
}

$bkAuthKey = $_SESSION['cnclBk'];

if(strlen($bkAuthKey)!=32||!preg_match("/^[a-zA-Z0-9]*$/",$bkAuthKey)){
	http_response_code(404);
	exit();	
}

$deleteQuery = "UPDATE booking SET status = 'Cancelled' WHERE Auth_key = '$bkAuthKey'";

if(!mysqli_query($con,$deleteQuery)){
	//header("Location: connectionfailed.php");
	echo '<script>alert("Failed")</script>;';
	exit();
	mysqli_rollback($con);
}

$stdtEmail = prepEmailStudent(2,$bkAuthKey);
$staffEmail = prepEmailStaff(2,$bkAuthKey);

if(!$stdtEmail||!$staffEmail){
	http_response_code(404);
	header("Location: error404");
	exit();
}
sendEmail($stdtEmail);
sendEmail($staffEmail);

mysqli_commit($con);

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
	    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
	    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" />
	    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	    <link rel="stylesheet" href="css/mystyle.css">
		<title>Cancellation Success | MeetMev2</title>
	</head>

	
	<body>
		<nav class="navbar navbar-inverse">
	        <div class="navbar-header">
	            <a href="#"><img src="Image/MU Logo.png" height="80"></a>
	        </div>
    	</nav>
    	<nav class="successbookingtextcenter">
			<img src="Image/Mail Sent.svg" height="100">
			<p>Your booking has been successfully cancelled! Email has been sent to you.</p>
		</nav>
	</body>
</html>

