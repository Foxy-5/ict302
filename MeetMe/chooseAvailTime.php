<?php
	define('access', true);
	session_start();

	include("include/connection.php");
	include("include/function.php");

	$user_data = check_login($con);

	if($_SERVER['REQUEST_METHOD'] == "POST"){
		if(empty($_POST['staffid'])){
			$staffId = $user_data['StaffID'];
		}
		else{
			$staffId = $_POST['staffid'];

			$staffIdQuery = "SELECT `staffid` FROM `staff` WHERE `staffid` = '$staffId' limit 1";
			if(!$result = mysqli_query($con,$query)){
				echo "<script>alert('There's something wrong while trying to connect to the database');</script>";
				exit();
			}

			if(!mysqli_num_rows($staffIdQuery)>0){
				echo "<script>alert('Staff id cannot be found!');</script>";
				exit();
			}

		}


		//sets the time when the server receives the form
		$dateValidate = new DateTime("now");
		$dateReceived = $dateValidate->format('Y-m-d');
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

		//do validation
		for($i=0;$i<sizeof($dateInput);$i++){
			try{
				$dateHolder = new DateTime($dateInput[$i]);
			}
			catch (Exception $e) {
				echo "<script>alert('There's something wrong while receiving your input');</script>";
				die;
			}

			//validate date
			$startTimeHolder = new DateTime($dateInput[$i] . $startTimeInput[$i]);
			$endTimeHolder = new DateTime($dateInput[$i] . $endTimeInput[$i]);


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
				echo "<script>alert('The start and end time stamps are the same!');</script>";
				//stops entire process if one of it fails
				die;
			}

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

			$startTimeQuery = $startTimeHolder->format('Y-m-d H:i');
			$endTimeQuery = $endTimeHolder->format('Y-m-d H:i');

			//after validation

			//creating a unique id and then hashed to generate a unique identifier for each booking and saved in the database
			$uniqueId = $staffId.$dateReceived.uniqid("booking",true);
			$hashedAuthKey = str_split(hash('sha256',$uniqueid),32)[0];


			$query = "INSERT INTO `booking` (`ConvenerID`,`Booking_date`, `Booking_start`, `Booking_end`,`Auth_Key`) VALUES ('$staffId','$dateReceived', '$startTimeQuery', '$endTimeQuery','$hashedAuthKey');";
			if(!mysqli_query($con,$query)){
	            $commitToDatabase = false;
	            break;
	    	}
		}
		
		if($commitToDatabase){
			echo '<script>alert("Time slots added successfully");
	            			window.location.href="chooseAvailTime.php";
	            	  </script>';
			mysqli_commit($con);
		}
		else{
			echo '<script>alert("An error has occured.")</script>';
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
</head>

<body>
	<nav class="navbar navbar-inverse">
        <div class="navbar-header">
            <a href="home.php"><img src="Image/MU Logo.png" height="80"></a>
        </div>
        <div class="navpaddingright collapse navbar-collapse" id="mynavbar">
            <ul class="nav navbar-nav">
                <li><a href="home.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
                <li id="appointment" class="dropdown"><a href="#"><span class="glyphicon glyphicon-calendar"></span> Appointment <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="uploadExcel.php">Upload Excel</a></li>
                        <li id="sub-dropdown" class="dropdown"><a href="#">View Calendar <span class="glyphicon glyphicon-chevron-right"></span></a>
                            <ul id="sub-dropdown-menu" class="dropdown-menu">
                                <li><a href="upcoming.php">View Upcoming Bookings</a></li>
                                <li><a href="allbooking.php">View Concluded Bookings</a></li>
								<li><a href="openbooking.php">View Open Bookings</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li id="analytics" class="dropdown"><a href="#"><span class="glyphicon glyphicon-tasks"></span> Analytics <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="staffanalytics.php">Staff Analytics</a></li>
                        <li><a href="studentlisting.php">Student Analytics</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="profile.php"><span class="glyphicon glyphicon-user"></span> My Profile</a></li>
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
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
            	<input type="text" value="<?php echo $user_data['StaffID']?>"><br><br>

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
		//moment.js
		/*var something = new Date().getTimezoneOffset();
		document.getElementsByName('timezone')[0].value = something;*/

		//luxon.js
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

			//moment.js
			/*minimumDate = moment();
			minimumDate.add(1,'days');*/

			//luxon.js
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
				//moment.js
				//dateFields[i].min = minimumDate.format('YYYY-MM-DD');

				//luxon.js
				dateFields[i].min = minimumDate.toISODate();
			}
		}

		//updating the minimumm value on the time fields if applicable
		function updateTimeField(){
			var timeFields = document.getElementsByName('time[]');
			for(var i=0;i<timeFields.length;i++){
				if(timeFields[i].hasAttribute("min")){
					//moment.js
					//timeFields[i].min = minimumDate.format("HH:mm");

					//luxon.js
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

			/*var observer = new MutationObserver(function(mutations) {
				mutations.forEach(function(mutation) {
					if(mutation.type === "attributes") {
						checkDate;
					}
				});
			});


			observer.observe(element, {attributes: true});*/

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