<?php
if(!defined('access')) {
    http_response_code(404);
    exit();
}

/**
 *  Gets the student id given the authentication key from studentlist
 *  0 for student list authentication key
 *  1 for booking authentication key
 **/
function getStudentId($mode,$AuthKey){
    global $con;

    $tableQuery = '';

    if($mode==0){
        $tableQuery = "studentlist";
    }

    if($mode==1){
        $tableQuery = "booking";
    }

    $query = "SELECT StudentID FROM $tableQuery WHERE Auth_key = '$AuthKey' limit 1";
    $result = mysqli_query($con,$query);
    if($result && mysqli_num_rows($result) > 0){
        $output = mysqli_fetch_assoc($result);
        return $output['StudentID'];
    }

    return '';
}

/**
 *  Gets the staff id given the authentication key from studentlist
 *  0 for student list authentication key
 *  1 for booking authentication key
 **/
function getStaffId($mode,$AuthKey){
    global $con;

    if($mode==0){
        $query = "SELECT StaffID FROM list WHERE listid = (SELECT listid FROM studentlist WHERE Auth_key = '$AuthKey' limit 1)limit 1;";
        $result = mysqli_query($con,$query);
        if($result && mysqli_num_rows($result) > 0){
            $output = mysqli_fetch_assoc($result);
            return $output['StaffID'];
        }
    }

    if($mode==1){
        $query = "SELECT ConvenerID FROM booking WHERE Auth_key = '$AuthKey' limit 1";
        $result = mysqli_query($con,$query);
        if($result && mysqli_num_rows($result) > 0){
            $output = mysqli_fetch_assoc($result);
            return $output['ConvenerID'];
        }
    }


    return '';
}


/**
 *  Gets the student name given the student id
 **/
function getStudentName($studentid){
    global $con;
    $query = "SELECT first_name, last_name FROM student WHERE studentid = '$studentid' limit 1;";
    
    $result = mysqli_query($con,$query);
    if($result && mysqli_num_rows($result) > 0){
        $output = mysqli_fetch_assoc($result);
        $firstName = $output['first_name'];
        $lastName = $output['last_name'];
        return $firstName . ' ' .$lastName;
        
    }

    return '';

}

/**
 *  Gets the staff name given the student id
 **/
function getStaffName($staffid){
    global $con;
    $query = "SELECT first_name, last_name FROM staff WHERE staffid = '$staffid' limit 1;";
    $result = mysqli_query($con,$query);
    if($result && mysqli_num_rows($result) > 0){
        $output = mysqli_fetch_assoc($result);
        $firstName = $output['first_name'];
        $lastName = $output['last_name'];
        return $firstName . ' ' . $lastName;
       
    }

    return '';
}

function getStudentEmail($studentid){
    global $con;
    $query = "SELECT email FROM student WHERE studentid = '$studentid' limit 1;";
    
    $result = mysqli_query($con,$query);
    if($result && mysqli_num_rows($result) > 0){
        $output = mysqli_fetch_assoc($result);
            return $output['email'];
    }

    return '';
}

function getStaffEmail($staffid){
    global $con;
    $query = "SELECT email FROM staff WHERE staffid = '$staffid' limit 1;";
    $result = mysqli_query($con,$query);
    if($result && mysqli_num_rows($result) > 0){
        $output = mysqli_fetch_assoc($result);
        return $output['email'];
    }

    return '';
}

function getStartTime($bkAuthKey){
    global $con;
    $query = "SELECT Booking_start FROM booking WHERE Auth_key = '$bkAuthKey';";
    $result = mysqli_query($con,$query);
    if($result && mysqli_num_rows($result) > 0){
        $output = mysqli_fetch_assoc($result);
        return $output['booking_start'];
    }
}

function getEndTime($bkAuthKey){
    global $con;
    $query = "SELECT Booking_end FROM booking WHERE Auth_key = '$bkAuthKey';";
    $result = mysqli_query($con,$query);
    if($result && mysqli_num_rows($result) > 0){
        $output = mysqli_fetch_assoc($result);
        return $output['booking_end'];
    }
}

?>