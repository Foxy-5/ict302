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
		<script>
			//prevents website to send another POST request when user refreshes website
			if ( window.history.replaceState ) {
				window.history.replaceState( null, null, window.location.href );
			}
		</script>

	</head>


	<body>
		<form method="post">
			<label>StudentID : </label>
			<input type="text" name="studentid">
			<p id="errorMessage"></p>
			<input type="submit" value="Book Meeting">
		</form>
	</body>
</html>