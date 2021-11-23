<?php
	session_start();
	//destroy session
	unset($_SESSION);
	session_destroy();
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="css/mystyle.css">
		<title>Booking Succeed | MeetMev2</title>
	</head>

	<body class="successbookingtextcenter">
		<h1>Booking Successful</h1>
		<img src="Image/RedTick.jpg" height="100">
		<p>
			Your booking is successful, you will be receiving an Email confirmation for this booking and the details.<br/>
			You can add the event to your Outlook/Microsoft Teams calendar.<br/>
			Booking cancellation instructions and link will be provided in the same email as well.
		</p>
	</body>
</html>