<?php
	session_start();
	//must be connected to the database before running the website
	require_once("connection.php");
	//var_dump($_SERVER);

	if(isset($_GET['authkey'])){
		$authkey = $_GET['authkey'];
		//can use function from email.php
		$authkeyquery = "SELECT StudentID FROM studentlist WHERE Auth_Key = '$authkey' limit 1;";
		$authenticate = mysqli_query($con,$authkeyquery);

		if($authenticate && mysqli_num_rows($authenticate) > 0){
			$studentData = mysqli_fetch_assoc($authenticate);

			$_SESSION['studentid'] = $studentData['StudentID'];

			$query = "SELECT userid FROM list WHERE listid = (SELECT listid FROM studentlist WHERE Auth_key = '$authkey' limit 1)limit 1;";
	        $result = mysqli_query($con,$query);
	        if($result && mysqli_num_rows($result) > 0){
	            $output = mysqli_fetch_assoc($result);
	            $_SESSION['bookingstaffid'] = $output['userid'];
	        }

		}
		else{
			header("Location: error.php");
			exit();
		}
	}
	else{
		header("Location: error.php");
		exit();
	}

	//if the form is submitted(to search for student id)
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		$errorMessage = false;

		$studentidinput = $_POST['studentid'];

		//if the input is not empty
		if(!empty($studentidinput))
		{
			//crafts query and queries from server database
			/*$query = "select * from student where StudentID = '$studentid' limit 1";
			$result = mysqli_query($con,$query);

			//if the data is found
			if($result && mysqli_num_rows($result) > 0)
			{

				$studentData = mysqli_fetch_assoc($result);*/

			if($studentidinput == $_SESSION['studentid']){

				//saves the student id into current session and brings user to the booking page
				$_SESSION['StudentID'] = $studentData['StudentID'];
				header("Location: booking.php");
				exit();
			}
			else{
				$errorMessage = true;
			}

		}
		else{
			$errorMessage = true;
			//show error message
		}

		if($errorMessage){
			echo <<<END
					<script>
						window.onload = function () {
							document.getElementById('errorMessage').innerText = "Student id not found!";
						};
					</script>
					END;
		}

	}

		/*else
		{
			
			//show error message
			echo <<<END
						<script>
							window.onload = function () {
								document.getElementById('errorMessage').innerText = "Student id not found!";
							};
						</script>
						END;
		}*/
		//header( "Location: {$_SERVER['REQUEST_URI']}", true, 303 );
   		//exit();

	
?>

<!DOCTYPE html>
<html>
	<head>
		<script>
			//prevents website to send another POST request when user refreshes website
			/*if ( window.history.replaceState ) {
				window.history.replaceState( null, null, window.location.href );
			}*/
		</script>

	</head>


	<body>
    	
	    <div class="content">
			<form method="post">
				<label>StudentID : </label>
				<input type="text" name="studentid">
				<p id="errorMessage"></p>
				<input type="submit" value="Book Meeting">
			</form>
		</div>
	</body>
</html>