<?php
	//must be connected to the database before running the website
	require_once("connection.php");

	//if the form is submitted(to search for student id)
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{

		$studentid = $_POST['studentid'];

		//if the input is not empty
		if(!empty($studentid))
		{
			//crafts query and queries from server database
			$query = "select * from student where StudentID = '$studentid' limit 1";
			$result = mysqli_query($con,$query);

			//if the data is found
			if($result && mysqli_num_rows($result) > 0)
			{

				$studentData = mysqli_fetch_assoc($result);

				if($studentData['StudentID'] == $studentid)
				{
					//saves the student id into current session and brings user to the booking page
					$_SESSION['StudentID'] = $studentData['StudentID'];
					header("Location: booking.php");
					die;
				}

			}

			else
			{

				//show error message
				echo <<<END
						<script>
							window.onload = function () {
								document.getElementById('errorMessage').innerText = "Student id not found!";
							};
						</script>
						END;

			}

		}

		else
		{
			
			//show error message
			echo <<<END
						<script>
							window.onload = function () {
								document.getElementById('errorMessage').innerText = "Student id not found!";
							};
						</script>
						END;
		}
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
			if ( window.history.replaceState ) {
				window.history.replaceState( null, null, window.location.href );
			}
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
				<input type="text" name="studentid">
				<p id="errorMessage"></p>
				<input type="submit" value="Book Meeting">
			</form>
		</div>
	</body>
</html>