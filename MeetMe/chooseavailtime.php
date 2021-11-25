<?php
	define('access', true);
	session_start();

	include("include/connection.php");
	include("include/function.php");

	$user_data = check_login($con);

	if($_SERVER['REQUEST_METHOD'] == "POST"){

		//check if staff id is a valid input (numbers)
		if(!is_numeric($_POST['staffid'])){
			echo "<script>
					alert('StaffID must be in numbers');
				  </script>;";
			exit();
		}


		else{
			$staffId = $_POST['staffid'];

			//check if staff id exists
			if($staffId!=$user_data['StaffID']){
				$staffIdQuery = "SELECT `StaffID` FROM `staff` WHERE `StaffID` = '$staffId' limit 1";
				
				if(!$result = mysqli_query($con,$staffIdQuery)){
					echo "<script>
							alert('Cannot connect to system, try again later');
						  </script>";

					exit();
				}

				if(!mysqli_num_rows($result)>0){
					echo "<script>
							alert('Staff id cannot be found!');
						  </script>";

					exit();
				}
			}

		}


		//get date time now
		$dateValidate = new DateTime("now");
		
		//converted to minutes to match with the luxon output
		$systemoffset = $dateValidate->getOffset()/60;

		//get all the data from form
		$dateInput = $_POST['date'];
		$startTimeInput = $_POST['startTime'];
		$endTimeInput = $_POST['endTime'];
		$timezoneoffset = $_POST['timezone'];

		$timeZoneDiff = $systemoffset - $timezoneoffset;

		$changeDateToTimeZone = false;

		if($timezoneoffset!=$systemoffset){
			$changeDateToTimeZone = true;

		}

		$commitToDatabase = true;

		//do validation for each input received
		for($i=0;$i<sizeof($dateInput);$i++){

			//check if date input and time input is a valid input date time
			try{
				$startTimeHolder = new DateTime($dateInput[$i] . $startTimeInput[$i]);
				$endTimeHolder = new DateTime($dateInput[$i] . $endTimeInput[$i]);
			}
			catch (Exception $e) {
				echo "<script>
						alert('There's something wrong while receiving your input');
					  </script>";
				exit();
			}


			//if the start time is earlier than the end time
			if($startTimeHolder<$endTimeHolder){
				//do nothing as it is the same day
			}
			//assumes that the time is the next day if the end time is earlier than the start time
			else if($startTimeHolder>$endTimeHolder){
				$endTimeHolder->modify('+1 day');
			}
			
			//shows error if both start and end time are the same
			else{
				echo "<script>
						alert('The start and end time stamps are the same!');
					  </script>";
				//stops entire process if one of it fails
				exit();
			}

			//converts date time to time zone after it's validated
			if($changeDateToTimeZone==true){
				$inputDateToUtc = -($timezoneoffset);
				$systemTimeFromUtc = $systemoffset;

				$inputDateUtc = $inputDateToUtc . ' ' . 'minutes';
				$systemDateUtc = $systemTimeFromUtc . ' ' . 'minutes';

				$startTimeHolder->modify($inputDateUtc);
				$endTimeHolder->modify($inputDateUtc);

				$startTimeHolder->modify($systemDateUtc);
				$endTimeHolder->modify($systemDateUtc);

			}

			//convert processed input to 
			$startDateStr = $startTimeHolder->format('Y-m-d');
			$startTimeStr = $startTimeHolder->format('Y-m-d H:i');
			$endTimeStr = $endTimeHolder->format('Y-m-d H:i');


			//creating a unique id and then hashed to generate a unique identifier
			//for each booking and saved in the database
			$uniqueId = $staffId.$startTimeStr.uniqid("booking",true);
			$hashedAuthKey = str_split(hash('sha256',$uniqueId),32)[0];

			//inserts a new booking into the database
			$query = "INSERT INTO `booking` (`ConvenerID`, `Booking_date`, `Booking_start`, `Booking_end`,`Auth_Key`) VALUES ('$staffId', '$startDateStr', '$startTimeStr', '$endTimeStr','$hashedAuthKey');";

			if(!mysqli_query($con,$query)){
	            $commitToDatabase = false;
	            break;
	    	}
		}
		

		if($commitToDatabase){
			echo '<script>
					alert("Time slots added successfully");
	            	window.location.href="chooseAvailTime";
	              </script>';
			mysqli_commit($con);
		}

		//if any of the validation fails
		else{
			echo '<script>
					alert("An error has occured.")
				  </script>';
			mysqli_rollback($con);
		}

		exit();
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
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-range/4.0.2/moment-range.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/luxon/2.0.2/luxon.min.js"></script>
	<title>Indication of Timeslot | Meetme v2</title>
</head>

<body>
	<nav class="navbar navbar-inverse">
        <div class="navbar-header">
            <a href="home"><img src="Image/MU Logo.png" height="80"></a>
        </div>
        <div class="navpaddingright collapse navbar-collapse" id="mynavbar">
            <ul class="nav navbar-nav">
                <li><a href="home"><span class="glyphicon glyphicon-home"></span> Home</a></li>
                <li id="appointment" class="dropdown active"><a href="#"><span class="glyphicon glyphicon-calendar"></span> Appointment <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="uploadExcel">Upload Excel</a></li>
                        <li><a href="chooseavailtime">Upload Time</a></li>
                        <li id="sub-dropdown" class="dropdown"><a href="#">View Calendar <span class="glyphicon glyphicon-chevron-right"></span></a>
                            <ul id="sub-dropdown-menu" class="dropdown-menu">
                                <li><a href="upcoming">View Upcoming Bookings</a></li>
                                <li><a href="allbooking">View All Bookings</a></li>
                                <li><a href="openbooking">View Open Bookings</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li id="analytics" class="dropdown"><a href="#"><span class="glyphicon glyphicon-tasks"></span> Analytics <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="staffanalytics">Staff Analytics</a></li>
                        <li><a href="studentlisting">Student Analytics</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="profile"><span class="glyphicon glyphicon-user"></span> My Profile</a></li>
                <li><a href="logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
            </ul>
        </div>
    </nav>
    <div class="content">
		<h1>Available Timeslot</h1>
		<hr class="redbar">
		<p>Indicate your available timeslot for students to start booking!</p>
		<div class="containerprofile">
			<form method="post">

				<label>Staff ID</label>
            	<input type="text" name="staffid" value="<?php echo $user_data['StaffID']?>"><br><br>

				<label>Date</label>
            	<input type="date" name="date[]" required/>

				<label>Start Time</label>
            	<input type="time" name="startTime[]" required/>

				<label>End Time</label>
            	<input type="time" name="endTime[]" required/><br>

				<div id="dynamic_field"></div>
				<input type="hidden" name="timezone">

				<button class="linktobutton" type="button" name="add_time_slot" id="add" onclick="addFields()">Add Timeslot</button>
				<input class="linktobutton" type="submit" value="Update Timeslot"/>
			</form>
		</div>
	</div>

	<script>
		window['moment-range'].extendMoment(moment);

		//set timezone of form once user enters

		const DateTime = luxon.DateTime;
		var timeZone = DateTime.now();
		console.log(timeZone.toString());
		
		//returns timezone in minutes
		console.log(timeZone.offset);


		//global variables to share within functions
		var fieldCounter = 1;
		var max = 10;

		//defines variable to store the time and date right now
		var minimumDate;

		//starts the loop once the user starts
		updateLatestTime();
		
		//updates every 15 seconds
		var timerRefresh;

		function updateLatestTime(){
			//as the minimum date to set available time
			//must be at least a day after the current date

			minimumDate = DateTime.now();

			document.getElementsByName('timezone')[0].value = minimumDate.offset;

			//immutable :D
			minimumDate = minimumDate.plus({days: 1});
			console.log(minimumDate.toISO());
			console.log("refreshing");
			updateDateField();
			updateTimeField();

			//calls recursive to avoid over crowding the system if any error occurs.
			timerRefresh = setTimeout(updateLatestTime,15000);
		}

		//updating the minimumm value on the date fields
		function updateDateField(){
			var dateFields = document.getElementsByName('date[]');
			for(var i=0;i<dateFields.length;i++){
				dateFields[i].min = minimumDate.toISODate();
			}
		}

		//updating the minimumm value on the time fields if applicable
		function updateTimeField(){
			var timeFields = document.getElementsByName('time[]');
			for(var i=0;i<timeFields.length;i++){
				if(timeFields[i].hasAttribute("min")){

					timeFields[i].min = minimumDate.toISODate();
				}

			}
		}


		//add additional field to add time slot
		function addFields(){
			//maximum time slot to add is 10
			if(fieldCounter>=10){
				alert("No more fields can be added!");
				return;
			}

			var container = document.getElementById("dynamic_field");
			var elementsToAdd = createNodeElements();
			addingEventListener(elementsToAdd);
			addToDiv(elementsToAdd,container);

			fieldCounter++;
		}

		function createNodeElements(){
			var dateLabel = document.createElement("label");
			dateLabel.innerHTML = " Date: ";

			//creating an input element with type date
			var dateContainer = document.createElement("input");
			dateContainer.type = "date";
			dateContainer.name = "date[]";
			dateContainer.setAttribute("required", "");
			dateContainer.setAttribute("min",minimumDate.toISODate());

			var startTimeLabel = document.createElement("label");
			startTimeLabel.innerHTML = " Start Time: ";

			var startTimeContainer = document.createElement("input");
			startTimeContainer.type = "time";
			startTimeContainer.name = "startTime[]";
			startTimeContainer.setAttribute("required", "");

			var endTimeLabel = document.createElement("label");
			endTimeLabel.innerHTML = " End Time: ";

			var endTimeContainer = document.createElement("input");
			endTimeContainer.type = "time";
			endTimeContainer.name = "endTime[]";
			endTimeContainer.setAttribute("required", "");

			var deleteContainer = document.createElement("a");
			deleteContainer.innerHTML = "Delete";
			deleteContainer.href = "#";
			//deleteContainer.setAttribute("onclick","removeFromWebsite(fieldToDelete(this),document.getElementById(\"dynamic_field\"))");
			deleteContainer.setAttribute("onclick","deleteSubForm(this)");

			var errorMessageContainer = document.createElement("span");
			errorMessageContainer.class = "errorMessage";

			return [dateLabel,dateContainer,startTimeLabel,startTimeContainer,endTimeLabel,endTimeContainer,deleteContainer,errorMessageContainer];
		}

		function addingEventListener(array){
			//date input container
			array[1].addEventListener("input",checkDate);

			//start time input container
			array[3].addEventListener("input",checkTime);

		}

		function addToDiv(array,div){
			var innerContainer = document.createElement("div");
			innerContainer.name = "subForm[]";


			for(var i=0;i<array.length;i++){
				innerContainer.appendChild(array[i]);
			}

			//adding breakline to segregate different input boxes
			innerContainer.appendChild(document.createElement("br"));


			//adding sub division to the parent division
			div.appendChild(innerContainer);
		}

		//cna change to one div for one set of input
		function deleteSubForm(item){

			item.parentNode.remove();
			fieldCounter--;

		}

		function checkDate(e){
			var flag = 0;

			//get input from field
			var input = DateTime.fromString(e.target.value,'yyyy-MM-dd');
			console.log(input);
			
			
			if(minimumDate.diff(input, 'days')>0){
				flag = 1;
			}

			//prioritize error message for invalid date
			//check if input date is valid (user able to change the date using arrow keys
			//although the ui is disabled)
			if(!moment(input).isValid()){
				flag = 2;
			}

			errorMessageField = e.target.parentNode.querySelector('.errorMessage');
			console.log(errorMessageField);
			//show error message if valid
			errorMessage(flag,errorMessageField);

		}

		function checkTime(e){
			var flag=0;

			//if there is minimum time enforced
			if(e.target.hasAttribute("min")){
				var inputTime = moment(e.target.value,"HH:mm");
				var minTime = moment(e.target.min,"HH:mm");
				
				if(minTime.diff(inputTime, 'minutes')>0){
					flag = 1;
				}
			}

			errorMessageField = e.target.parentNode.querySelector('.errorMessage');
			//show error message if valid
			errorMessage(flag,errorMessageField);

		}

		function errorMessage(flag,errorMessageField){
			if(flag==1){
				errorMessageField.innerHTML = "Timing can be only one day after today!";
			}
			else if(flag==2){
				errorMessageField.innerHTML = "Invalid Date";
			}
			else{
				errorMessageField.innerHTML = "";
			}
		}

	</script>
</body>
</html>