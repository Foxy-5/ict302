<?php
//files that can access the include files
define('access', TRUE);
session_start();

//including needed files
include("include/connection.php");
include("include/function.php");
include("include/email.php");

$user_data = check_login($con);

//if an excel file is uploaded
if (isset($_FILES['excel'])) {
    //$errors = array();

    //saves the details of the excel file
    $fileName = $_FILES['excel']['name'];
    $fileSize = $_FILES['excel']['size'];
    $fileTmp = $_FILES['excel']['tmp_name'];
    $fileType = $_FILES['excel']['type'];
    $fileExt = strtolower(pathinfo($_FILES['excel']['name'], PATHINFO_EXTENSION));

    $errorType = 0;

    //check if the file's extension is 'csv'
    if (!$fileExt == "csv") {
        $errortype = 1;
    }

    //check if the file size is larger than '2MB'
    if ($fileSize > 2097152) {
        $errortype = 2;
    }

    //saves the uploaded csv file into the 'uploads' directory if no
    //show error
    if ($errorType == 0) {
        move_uploaded_file($file_tmp, "uploads/" . $fileName);
    } 

    else {
        //alerts error message appropriately if error and exits the code
        if ($errorType == 1) {
            echo '<script>
                    alert("Error: File type not recognized, must be file type csv.")
                  </script>';
        }

        else if ($errorType == 2) {
            echo '<script>
                    alert("Error: File is larger than 2MB")
                  </script>';
        }
        exit();
    }
}

//if user submits the file
if (isset($_POST["import"])) {

    //grabs the file from the saved directory
    $file = "uploads/" . $_FILES['excel']['name'];

    //gets the staffid
    $staffId = $user_data['StaffID'];
    $dateCreate = date("Y-m-d H:i:s");

    //opens the excel file
    if ($fileOpen = fopen($file, "r")) {

        //adding a new list
        $insListQuery = "INSERT into list(StaffID,ListDate) values ('$staffId','$dateCreate')";
        $listCreate = mysqli_query($con, $insListQuery);

        //if list creation failed
        if (!$listCreate) {
            echo '<script>
                    alert("Error: Student cannot be registered into the system.");
                  </script>';
            exit();
        }

        //getting the list id that is added just now
        $getListQuery = "select ListID from list where (StaffID = '$staffId') AND (ListDate = '$dateCreate')";
        $getListId = mysqli_query($con, $listquery);

        //prints error message if fail to query
        if (!$getListId) {
            echo '<script>
                    alert("Error: ");
                  </script>';
            exit();
        }
        else{
            //gets the list id if successful
            $fetch = mysqli_fetch_assoc($getListId);
            $listId = $fetch['ListID'];
        }

        //flag and array to store authentication keys
        //for emailing
        $commitToDatabase = true;
        $dataValid = true;
        $listOfAuthKey = array();
        $lineCounter = 1;

        //check for empty input/invalid input
        //regex used to check for each line
        //student id must be only 8 numbers
        $rgStudentId = "/^[0-9]{8}$/";

        //checks for email syntax must have at least
        //something like xxxx@example.com
        $rgEmail = '/(?:[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9]))\.){3}(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9])|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/';

        //checks first name and last name
        //can only contain alphabets, spaces
        //and also special characters ".","-",",","\'",
        //minimum 2 letters and maximum 30 letters for both names
        $rgFirstName = "/^[a-zA-Z',. -]{2,30}$/";
        $rgLastName = "/^[a-zA-Z',. -]{2,30}$/";

        //while not end of file/no error
        while (($csv = fgetcsv($fileOpen, 1000)) !== FALSE) {
            //ignores blank lines in csv line
            if(array(null) !== $csv){
                //each row's entry must be exactly 4
                //as stated in the template
                if(sizeof($csv) == 4){
                    //gets data from each entry
                    $studentId = $csv[0];
                    $email = $csv[1];
                    $firstName = $csv[2];
                    $lastName = $csv[3];

                    //checks using regex for each input
                    if(!preg_match($rgStudentId,$studentId)){
                        $commitToDatabase = false;
                        break;
                    }

                    if(!preg_match($rgEmail,$email)){
                        $commitToDatabase = false;
                        break;
                    }

                    if(!preg_match($rgFirstName,$firstName)){
                        $commitToDatabase = false;
                        break;
                    }

                    if(!preg_match($rgLastName,$lastName)){
                        $commitToDatabase = false;
                        break;
                    }

                    //search for possible student id/email from the database
                    $stdtIdEmailCtQuery = "SELECT COUNT(StudentID) FROM student WHERE StudentID = '$studentId' AND Email = '$email';";
                    $stdtIdEmailSearch = mysqli_query($con,$emailCtQuery);

                    if(!$stdtIdEmailSearch){
                        echo '<script>
                                alert("Error: Cannot receive data from server, your excel is not uploaded.");
                              </script>';
                        exit();
                    }

                    $existingRecord = mysqli_fetch_array($stdtIdEmailSearch)[0]
                    $updateQuery = "UPDATE student SET StudentID = '$studentId',Email = '$email', First_name = '$firstName', Last_name = '$lastName' WHERE StudentID = '$studentId';";

                    //if the existing set of student id and does not exist
                    if($existingRecord == 0){
                        //finds out if *individually*
                        //email exists or student id exists
                        $emailCtQuery"SELECT COUNT(Email) FROM student WHERE Email = '$email'";
                        $studentIdCtQuery = "SELECT COUNT(Email) FROM student WHERE StudentID = '$studentId'";

                        $emailSearch = mysqli_query($con,$emailCtQuery);
                        $studentIdSearch = mysqli_query($con,$studentIdCtQuery);

                        if(!($emailSearch && $studentSearch)){
                            echo '<script>
                                    alert("Error: Cannot receive data from server, your excel is not uploaded.");
                                  </script>';
                            exit();
                        }

                        //0 means no records found in database
                        //1 means there are exisiting records in the database
                        $emailFound = mysqli_fetch_array($emailSearch)[0];
                        $studentIdFound = mysqli_fetch_array($studentId)[0];

                        //another record of email is found in database (different student id)
                        if($emailFound == 1){
                            echo '<script>
                                    alert("Email already existed in database under different student(Line $lineCounter)");
                                  </script>';
                            exit();
                        }

                        //if there are no duplicated email
                        else{
                            //inserts a brand new row into database (no records of either email or student id found)
                            if($studentIdFound == 0){
                                $updateQuery = "INSERT INTO student(StudentID,Email,First_name,Last_name) VALUES ('$studentId','$email','$firstName','$lastName');";
                            }
                            //the predefined update query will update the record if the student id record is found
                        }
                    }

                    $updateDbStudent = mysqli_query($con, $updateQuery);

                    if (!$updateDbStudent) {
                        echo '<script>
                                alert("Error: Student cannot be registered into the system.");
                              </script>';
                        $commitToDatabase = false;
                        break;
                    }

                    //provides a unique authentication key for every student in a student list
                    //used to validate student's identity when they book for a meeting
                    $uniqueid = $studentId.$listId.uniqid("user",true);
                    $authKey = str_split(hash('sha256',$uniqueid),32)[0];

                    $insStdtListQuery = "INSERT into studentlist(ListID,StudentID,Auth_Key) VALUES ('$listid','$studentId','$authKey')";
                    $insStdtList = mysqli_query($con, $query2);

                    if (!$insStdtList) {
                        echo '<script>
                                alert("Error: Student cannot be registered into the system.");
                              </script>';
                        $commitToDatabase = false;
                        break;
                    }

                    $listOfAuthKey[] = $hashedAuthKey;

                    $lineCounter++;
                }
            }
        }

        if($commitToDatabase){
            mysqli_commit($con);
            //sends student booking request email
            for($emailLoop = 0;$emailLoop < sizeof($listOfAuthKey);$emailLoop++){
                sendCfmEmailStudent($listOfAuthKey[$emailLoop],0);
            }
        }
    }
}
?>
<script>
    function fileValidation() {
        var fileInput =
            document.getElementById('excel');

        var filePath = fileInput.value;

        // Allowing file type
        var allowedExtensions = /(\.csv)$/i;

        if (!allowedExtensions.exec(filePath)) {
            alert('Invalid file type');
            fileInput.value = '';
            return false;
        }
    }
</script>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/mystyle.css">
    <title>Upload File | Meetme v2</title>
</head>

<body>
    <nav class="navbar navbar-inverse">
        <div class="navbar-header">
            <a href="home.php"><img src="Image/MU Logo.png" height="80"></a>
        </div>
        <div class="navpaddingright collapse navbar-collapse" id="mynavbar">
            <ul class="nav navbar-nav">
                <li><a href="home.php">Home</a></li>
                <li id="appointment" class="dropdown active"><a href="#">Appointment <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="uploadExcel.php">Upload Excel</a></li>
                        <li id="sub-dropdown" class="dropdown"><a href="#">View Calendar <span class="glyphicon glyphicon-chevron-right"></span></a>
                            <ul id="sub-dropdown-menu" class="dropdown-menu">
                                <li><a href="upcoming.php">View Upcoming Bookings</a></li>
                                <li><a href="allbooking.php">View All Bookings</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li id="analytics" class="dropdown"><a href="#">Analytics <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="staffanalytics.php">Staff Analytics</a></li>
                        <li><a href="studentlisting.php">Student Analytics</a></li>
                    </ul>
                </li>
                <li><a href="about.php">About</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="profile.php"><span class="glyphicon glyphicon-user"></span> My Profile</a></li>
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
            </ul>
        </div>
    </nav>
    <div class="content">
        <h5>Hi <?php echo $user_data['First_name']; ?> <?php echo $user_data['Last_name']; ?>!</h5>
        <h3>Upload Excel File</h3>
        <hr class="redbar">
        <div class="uploadfilecontainer">
            <p>
                <b><em>Instructions</em></b>
                <br>
                1) Open up Microsoft Excel
                <br>
                2) Fill up Student's Details accordingly in a horizontal manner (Number, Email, First Name, Family Name/Last Name)
                <br>
                3) Save file as <b><em>filename.csv</em></b>
                <br>
            </p>
            <h5>
                <p><i>You are allow to download the templete below or refer to the image below for more details</i></p>
                Example: <a href="uploads/example.csv" download="example.csv">example.csv</a>
            </h5>
            <img src="Image/UploadFileExample.png">
            <br>
            <br>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="file" id="excel" name="excel" onchange="return fileValidation()" /><br>
                <input class="linktobutton" id="import" name="import" type="submit" />
            </form>

        </div>
    </div>
</body>

</html>