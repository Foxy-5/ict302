<?php
	define('access', TRUE);	
	//change at configuration file to logout after browser closes
	session_start();

	include("include/connection.php");
	include("include/email.php");


	if($_SERVER['REQUEST_METHOD'] == "POST"){
		$bkKey = $_POST['bookingKey'];

		if(!in_array($bkKey,$_SESSION['bkAuthKeyArray'])){
			echo "<script>alert('Error: Booking cannot be found');</script>";
			exit();
		}
		//$submitButtonCheck = $_POST['submit']

		//gets the booking id by entering authentication key from booking
		$bkIdQuery = "SELECT bookingid FROM booking WHERE Auth_Key = '$bookingKey' limit 1;";

		if(!$bkIdResult = mysqli_query($con,$bkIdQuery)){
			header("Location: connectionError.php");
			exit();
		}
		
		if(!mysqli_num_rows($bkIdResult)>0){
			echo "<script>alert('Error: Booking cannot be found');</script>";
		}

		$stdtId = $_SESSION['stdtId'];
		$bkIdArray = mysqli_fetch_assoc($result);
		$bkId = $output['bookingid'];

		//creates new authkey for booking after booked
		$bkUniqId = $stdtid.$bkId.uniqid("booking",true);
    	$bkAuthKey = str_split(hash('sha256',$bkUniqId),32)[0];
    	
		$bkUpdateQuery = "UPDATE booking SET StudentID = '$stdtId', Status = 'confirmed', Auth_key = '$bkAuthKey' WHERE bookingid = '$bkId';";

		if(!mysqli_query($con,$bkUpdateQuery)){
			header("Location: connectionError.php");
			exit();
		}

		//gets authentication key of student
		$stdtAuthKey = $_SESSION['stdtAuthKey'];
		$removeQuery = "DELETE from studentlist WHERE Auth_Key = '$stdtAuthKey'";

		if(!mysqli_query($con,$removeQuery)){
			header("Location: connectionError.php");
			exit();
		}
		
		sendEmailStudent(1,$stdtAuthKey,$bkAuthKey);
		sendEmailStaff(1,$stdtAuthKey,$bkAuthKey);
		mysqli_commit($con);

	}
	
	if(!(isset($_SESSION['stdtId'])||empty($_SESSION['studentid']))){
		http_response_code(404);
		exit();
	}

	if(!(isset($_SESSION['bkStaffId'])||empty($_SESSION['bkStaffId']))){
		http_response_code(404);
		exit();
	}

	//gets the current session student id
	//$stdtId = $_SESSION['stdtId'];

	//sets the earliest possible booking time
	//to one day later
	$eBkDate = new DateTime('now');
	$eBkDate->modify('+1 day');
	$streBkDate = $eBkDate->format("Y-m-d H:i");
	$staffId = $_SESSION['bkStaffId'];

	//order by
	//
	$bkDetsQuery = "SELECT `booking`.`Auth_Key`, DATE_FORMAT(`booking`.`booking_start`,'%Y-%m-%d %h:%i %p') AS Start_Date, DATE_FORMAT(`booking`.`booking_end`,'%Y-%m-%d %h:%i %p') AS End_Date FROM `booking` WHERE `booking`.`ConvenerID` = '$staffId' AND `booking`.`booking_start` >= '$streBkDate' AND `booking`.`StudentID` IS NULL ORDER BY `booking`.`Booking_start`;";

	if(!$bkDets = mysqli_query($con,$bkDetsQuery)){
		echo "<script>alert('Error: Connection error with the system.')</script>";
		//header("Location: connectionError.php");
		if(!(isset($_SESSION['stdtAuthKey'])||empty($_SESSION['stdtAuthKey']))){
			http_response_code(404);
			exit();
		}

		header("Location: studentIdInput.php?authkey=$stdtAuthKey");

		exit();
	}


?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
	    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
	    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"/>
	    <link rel="stylesheet" href="css/mystyle.css"/>
	    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
		<script>
			$(document).ready(function() {
				$('#myTable').DataTable();
			});
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
			<table id="bookingtable">
				<thead>
					<tr>
						<th>Start Time</th>
						<th>End Time</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
						if(mysqli_num_rows($bkDets)<1){
							getStaffName($staffId);
							echo "<script>alert('No Available timing from the lecturer')</script>;";
							//header("Location: error404.php");

						}
						$authKeyArray = array();
						while($row = mysqli_fetch_array($bkDets)){
							$startDate = explode(" ",$row['Start_Date'])[0];
							$startTime = explode(" ",$row['Start_Date'])[1];
							$endDate = explode(" ",$row['End_Date'])[0];
							$endTime = explode(" ",$row['End_Date'])[1];
							$secretId = $row['Auth_Key'];
							$authKeyArray[] = $secretId;

					?>
					<tr>
						<td><?php echo "$startDate $startTime";?></td>
						<td><?php echo "$endDate $endTime"?></td>
						<td><form method="post">
								<input type = "hidden" name="bookingKey" value=<?php echo "$secretId"?>>
								<input type="submit" name="button" value="book">
							</form>
						</td>
					</tr>
					<?php 
						}
						
						$_SESSION['bkAuthKeyArray'] = $authKeyArray;
					?>
				</tbody>
			</table>
		</div>
	</body>
</html>