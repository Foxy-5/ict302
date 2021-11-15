<?php
	define('access', TRUE);	
	//change at configuration file to logout after browser closes
	session_start();

	include("include/connection.php");

	
	if(!isset($_SESSION['StudentID'])){
		header("Location: studentIdInput.php");
		die;

	}

	$studentid = $_SESSION['StudentID'];
	$earliestavailabledate = new DateTime('now');
	$earliestavailabledate->modify('+1 day');
	echo $earliestavailabledate->format('Y-m-d');

	//order by
	//
	$query = "SELECT DATE_FORMAT(`booking`.`booking_start`,'%Y-%m-%d') AS Date,DATE_FORMAT(`booking`.`booking_start`,'%h:%i %p') AS Start_Time, DATE_FORMAT(`booking`.`booking_end`,'%h:%i %p') AS End_Time
FROM `booking` WHERE `booking`.`ConvenerID` = 
	( SELECT `list`.`userid` FROM `list` WHERE `list`.`listid` = (SELECT `studentlist`.`listid`
	  FROM `studentlist` WHERE `StudentID` = %studentid)) 
      AND `booking`.`booking_start` >= '2021-05-01 00:00:00'
      AND (`booking`.`StudentID` = '' OR `booking`.`StudentID` IS NULL)
      ORDER BY `booking`.`Booking_start`;";
	/*if(!$result = mysqli_query($con,$query)){
		echo "<script>alert('Something went wrong, pleases try again later')</script>";
		header("Location: studentIdInput.php");
		die;
	}*/


?>

<!DOCTYPE html>
<html>
	<head>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
		<script>
			$(document).ready(function() {
				$('#myTable').DataTable();
			});
		</script>
	</head>

	<body>
		<table id="bookingtable">
			<thead>
				<tr>
					<th>Date</th>
					<th>Time</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php  
					$result = mysqli_query($con,$query);
					while($row = mysqli_fetch_array($result)){

				?>
				<tr>
					<td><?$date = explode(" ",$row['Booking_start'])[0];
						?></td>
					<td><?php echo $time;?></td>
				</tr>
				<?php }?>
			</tbody>
	</body>
</html>