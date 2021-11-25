<?php	
	session_start();
	//destroy session
	unset($_SESSION);
	session_destroy();
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
		<title>Booking Succeed | MeetMev2</title>
	</head>

	
	<body>
		<nav class="navbar navbar-inverse">
	        <div class="navbar-header">
	            <a href="#"><img src="Image/MU Logo.png" height="80"></a>
	        </div>
    	</nav>
		<nav class="successbookingtextcenter">
			<h1>Booking Successful</h1>
			<img src="Image/RedTick.jpg" height="100">
			<p>
				Your booking is successful, you will be receiving an Email confirmation for this booking and the details.<br/>
				You can add the event to your Outlook/Microsoft Teams calendar.<br/>
				Booking cancellation instructions and link will be provided in the same email as well.
			</p>
		</nav>
	</body>
</html>