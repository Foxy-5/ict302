<?php
	session_start();
	//destroy session
	unset($_SESSION);
	session_destroy();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Booking Succeed | MeetMev2</title>
	</head>

	<body>
		<p>Your booking is successful, you will be receiving an Email confirmation for this booking and the details.
		You can add the event to your outlook/Mircrosoft Teams calendar. Booking cancellation instructions and link will be provided in the same email as well.</p>
	</body>
</html>