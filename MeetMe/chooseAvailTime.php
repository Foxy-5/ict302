<?php
	session_start();

	include("connection.php");
	include("function.php");

	$user_data = check_login($con);
	
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
	<script>
		window['moment-range'].extendMoment(moment);

		//global variables to share within functions
		var fieldCounter = 1;
		var max = 10;

		//defines variable to store the time and date right now
		var minimumDate;

		//updates every 15 seconds
		var timerRefresh = setTimeout(updateLatestTime,15000);

		function updateLatestTime(){
			//as the minimum date to set available time
			//must be at least a day after the current date
			minimumDate = moment();
			minimumDate.add(1,'days');
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
				dateFields[i].min = minimumDate.format('YYYY-MM-DD');
			}
		}

		//updating the minimumm value on the time fields if applicable
		function updateTimeField(){
			var timeFields = document.getElementsByName('time[]');
			for(var i=0;i<timeFields.length;i++){
				if(timeFields[i].hasAttribute("min")){
					timeFields[i].min = minimumDate.format("HH:mm");
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
			//addingEventListener(elementsToAdd);
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
			deleteContainer.class = "delete";
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

			var observer = new MutationObserver(function(mutations) {
				mutations.forEach(function(mutation) {
					if(mutation.type === "attributes") {
						checkDate;
					}
				});
			});


			observer.observe(element, {attributes: true});

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
			/*var endTimeInputHolder = item.previousElementSibling;
			var endTimeLabelHolder = endTimeInputHolder.previousElementSibling;
			var startTimeInputHolder = endTimeLabelHolder.previousElementSibling;
			var startTimeLabelHolder = startTimeInputHolder.previousElementSibling;
			var dateInputHolder = startTimeLabelHolder.previousElementSibling;
			var dateLabelHolder = dateInputHolder.previousElementSibling;
			var brHolder = item.nextElementSibling;*/

			//remove the div that contains represents one entry of timing
			item.parentNode.remove();
			fieldCounter--;
			
			//var holderArray = [item,brHolder,dateLabelHolder,dateInputHolder,startTimeLabelHolder,
								//startTimeInputHolder,endTimeLabelHolder,endTimeInputHolder];
			//return holderArray;
		}

		/*function removeFromWebsite(holders,div){
			for(var i=0;i<holders.length;i++){
				div.removeChild(holders[i]);
			}
			fieldCounter--;
		}*/

		function checkDate(e){
			var flag = 0;

			//
			var input = e.target.value;
			//crafting the date to compare with input
			var compare = moment(CurrentDate);
			
			
			if(compare.diff(input, 'days')>0){
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
			console.log(errorMessageField);
			//show error message if valid
			errorMessage(flag,errorMessageField);

		}

		function errorMessage(flag,errorMessageField){
			if(flag==1){
				errorMessagefield.innerHTML = "Timing can be only one day after today!";
			}
			else if(flag==2){
				errorMessagefield.innerHTML = "Invalid Date";
			}
			else{
				errorMessagefield.innerHTML = "";
			}
		}

	</script>
</head>

<body>
	<nav class="navbar navbar-inverse">
        <div class="navbar-header">
            <a href="home.php"><img src="Image/MU Logo.png" height="80"></a>
        </div>
        <div class="navpaddingright">
            <ul class="nav navbar-nav">
                <li class="active"><a href="home.php">Home</a></li>
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Appointment <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li class="item"><a href="uploadExcel.php">Upload Excel</a></li>
                        <li class="item"><a href="calendar.php">View Calendar</a></li>
                    </ul>
                </li>
                <li><a href="analytics.php">Analytics</a></li>
                <li><a href="about.php">About</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="profile.php"><span class="glyphicon glyphicon-user"></span> My Profile</a></li>
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
            </ul>
        </div>
    </nav>
    <div class="content">
		<h1>Available timeslot</h1>
		<hr class="redbar">
		<p>Indicate ur available timeslot for students to start booking!</p>
		<form action = "POST" >
			<label>Date:</label>
			<input type="date" name="date[]" required/>
			<label>Start Time:</label>
			<input type="time" name="startTime[]" required/>
			<label>End Time:</label>
			<input type="time" name="endTime[]" required/>

			<div id="dynamic_field"></div>

			<button type="button" name="add_time_slot" id="add" onclick="addFields()">Add timeslot</button>
			<input type="submit" value="Update timeslot"/>
		</form>
	</div>
</body>
</html>