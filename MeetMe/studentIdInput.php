<?php
define('access',true);
session_start();
//must be connected to the database before running the website
require_once("include/connection.php");
require_once("include/email.php");
require_once("include/info_retrieve.php");

if(isset($_GET['authkey'])){
	$authKey = $_GET['authkey'];
	$_SESSION['stdtAuthKey'] = $authKey;
	//can use function from email.php
	/*$authkeyquery = "SELECT StudentID FROM studentlist WHERE Auth_Key = '$authkey' limit 1;";
	$authenticate = mysqli_query($con,$authkeyquery);*/

	/*if($authenticate && mysqli_num_rows($authenticate) > 0){
		$studentData = mysqli_fetch_assoc($authenticate);*/

	//getting student id based on the unique authentication key
	//function in : include/email.php
	$stdtId = getStudentId(0,$authKey);

	//if empty, means authentication key does not exist in database
	if(empty($stdtId)){
		http_response_code(404);
		exit();
	}

	//sets student id to current session
	$_SESSION['stdtId'] = $stdtId;
		//$_SESSION['studentid'] = $studentData['StudentID'];

		/*$query = "SELECT staffid FROM list WHERE listid = (SELECT listid FROM studentlist WHERE Auth_key = '$authkey' limit 1)limit 1;";
        $result = mysqli_query($con,$query);
        if($result && mysqli_num_rows($result) > 0){
            $output = mysqli_fetch_assoc($result);
            $_SESSION['bookingstaffid'] = $output['userid'];
        }*/

    //getting staff id that assigned the booking request from the same authentication key
    $bkStaffId = getStaffId(0,$authKey);

    if(empty($bkStaffId)){
    	http_response_code(404);
    	exit();
    }

    //saves the staff id to current session
    $_SESSION['bkStaffId'] = $bkStaffId;

}
else{
	http_response_code(404);
	exit();
}

//if the form is submitted(to search for student id)
if($_SERVER['REQUEST_METHOD'] == "POST"){

	$stdtIdInput = $_POST['studentId'];

	//if the input is not empty
	if(!empty($stdtIdInput)){
		//crafts query and queries from server database
		/*$query = "select * from student where StudentID = '$studentid' limit 1";
		$result = mysqli_query($con,$query);

		//if the data is found
		if($result && mysqli_num_rows($result) > 0)
		{

			$studentData = mysqli_fetch_assoc($result);*/

		if($stdtIdInput == $_SESSION['stdtId']){

			//saves the student id into current session and brings user to the booking page
			//$_SESSION['StudentID'] = $studentData['StudentID'];

			//redirects to booking page if the student id input corresponds
			//to the student id retrieved via the authentication key
			header("Location: booking.php");
			exit();
		}

	}

	echo <<<END
			<script>
				window.onload = function () {
					document.getElementById('errorMessage').innerText = "Student id not found!";
				};
			</script>
			END;
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
	    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
	    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" />
	    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	    <link rel="stylesheet" href="css/mystyle.css">
		<script>
			//prevents website to send another POST request when user refreshes website
			/*if ( window.history.replaceState ) {
				window.history.replaceState( null, null, window.location.href );
			}*/
		</script>
		<title>Booking Page | MeetMe v2</title>

	</head>


	<body>
		<nav class="navbar navbar-inverse">
	        <div class="navbar-header">
	            <a href="home.php"><img src="Image/MU Logo.png" height="80"></a>
	        </div>
	        <div class="navpaddingright">
	            <ul class="nav navbar-nav">
	                <li class="active"><a href="home.php">Home</a></li>
	            </ul>
	        </div>
    	</nav>
    	
	    <div class="content">
			<form method="post">
				<label>StudentID : </label>
				<input type="text" name="studentId">
				<p id="errorMessage"></p>
				<input type="submit" value="Book Meeting">
			</form>
		</div>
	</body>
</html>