<?php

//only allowed files can access this file
if(!defined('access')) {
    http_response_code(404);
    exit();
}

/**
 *  This function gets the student id given the authentication key
 *  
 *  param:
 *      mode: 0 for student list authentication key
 *            1 for booking authentication key
 *      AuthKey: either sutdent list or booking authentication key
 * 
 *  return:
 *      Student id
 **/
function getStudentId($mode,$AuthKey){
    global $con;

    $tableQuery = '';

    //mode of grabbing student id from database
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
 *  This function gets the staff id given the authentication key
 *  
 *  param:
 *      mode: 0 for student list authentication key
 *            1 for booking authentication key
 *      AuthKey: either sutdent list or booking authentication key
 * 
 *  return:
 *      Staff id
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
 *  This function gets the student name using a student id
 * 
 *  param:
 *      studentid: student id of a student
 * 
 *  return:
 *      full name of student
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
 *  This function gets the staff name using a staff id
 * 
 *  param:
 *      staffid: staff id of a staff
 * 
 *  return:
 *      full name of staff
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

/**
 *  This function gets the student email using a student id
 * 
 *  param:
 *      studentid: student id of a student
 * 
 *  return:
 *      email of student
 **/
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

/**
 *  This function gets the staff email using a staff id
 * 
 *  param:
 *      staffid: staff id of a staff
 * 
 *  return:
 *      email of staff
 **/
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

/**
 *  This function gets the start time of a booking
 * 
 *  param:
 *      bkAuthKey: authentication key for a booking
 * 
 *  return:
 *      start time of a booking
 **/
function getStartTime($bkAuthKey){
    global $con;
    $query = "SELECT Booking_start FROM booking WHERE Auth_key = '$bkAuthKey';";
    $result = mysqli_query($con,$query);
    if($result && mysqli_num_rows($result) > 0){
        $output = mysqli_fetch_assoc($result);
        return $output['Booking_start'];
    }

    return '';
}

/**
 *  This function gets the end time of a booking
 * 
 *  param:
 *      bkAuthKey: authentication key for a booking
 * 
 *  return:
 *      end time of a booking
 **/
function getEndTime($bkAuthKey){
    global $con;
    $query = "SELECT Booking_end FROM booking WHERE Auth_key = '$bkAuthKey';";
    $result = mysqli_query($con,$query);
    if($result && mysqli_num_rows($result) > 0){
        $output = mysqli_fetch_assoc($result);
        return $output['Booking_end'];
    }

    return '';
}

?>