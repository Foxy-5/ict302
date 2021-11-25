<?php

//only allowed files can access this file
if(!defined('access')) {
    http_response_code(404);
    exit();
}

/**
 * using PHPMailer library
 */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include_once("../lib/vendor/autoload.php");
require_once("connection.php");
require_once("info_retrieve.php");

/**
 * This function forges a unique url to book a meeting with a specific student id
 * 
 * param:
 *      stdtAuthKey: authentication key in student list
 * 
 * return:
 *      forged booking link for email
 * 
 **/
function fgBkLink($stdtAuthKey){
    return "https://localhost/MeetMe/studentidinput?authkey=$stdtAuthKey";
}


/**
 * This function forges a unique url to cancel a specific meeting
 * 
 * param:
 *      bkAuthKey: authentication key for a booking
 * 
 * return:
 *      forged cancellation link for email
 **/
function fgCnclLink($bkAuthKey){
    return "https://localhost/MeetMe/cancelbooking?authkey=$bkAuthKey";   
}

/**
 * This function initializes a PHPMailer object email for sending
 * 
 * return:
 *      Initialized PHPMailer object
 **/
function initEmail(){
    $emailSetup = true;
    try{
        $mail = new PHPMailer(true);
        $mail->setFrom('MeetMev2Dummy@gmail.com','Murdoch University');
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = TRUE;
        $mail->SMTPSecure = 'tls';
        $mail->Username = 'MeetMev2Dummy@gmail.com';
        $mail->Password = 'Localhost';
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';
    }
    catch (phpmailerException $e) {
        $emailSetup = false;
    }
    catch (Exception $e) {
        $emailSetup = false;
    }

    if($emailSetup){
        return $mail;
    }
    else{
        return '';
    }

}

//forging the email contents for student to book a meeting
function fgRqBody($stdtAuthKey){
    $fgSuccess = true;

    if(empty($studentName = getStudentName(getStudentId(0,$stdtAuthKey)))){
        $fgSuccess = false;
    }
    
    if(empty($staffName = getStaffName(getStaffId(0,$stdtAuthKey)))){
        $fgSuccess = false;
    }

    if($fgSuccess){
        $link = fgBkLink($stdtAuthKey);
        return "Dear $studentName,\n\n$staffName has requested to meet you/your group. Please book your meeting via the link given.\n\n$link\n\nThank you,\nMurodch University Meet Me.\n\nThis is an auto generated email. Please do not reply\n\n\nMurdoch Singapore\n390 Havelock Road #03-01 King’s Centre Singapore 169662";
    }
    else{
        return '';
    }
}


/**
 * 
 * This function forges the email content for a booking confirmation
 * for both staff and student
 * 
 * param:
 *   recipient: 0 for forging an email for student
 *              1 for forging an email for staff
 *   AuthKeys: authentication keys to get details of staff/students
 * 
 * 
 */
function fgCfmBody($recipient,...$AuthKeys){
    $stdtAuthKey = $AuthKeys[0];
    $bkAuthKey = $AuthKeys[1];

    if(empty($studentName = getStudentName(getStudentId(0,$stdtAuthKey)))){
        return '';
    }
    
    if(empty($staffName = getStaffName(getStaffId(0,$stdtAuthKey)))){
        return '';
    }

    //getting cancellation link
    $cnclLink = fgCnclLink($bkAuthKey);

    $bodyMsg = "Dear {RCP_NAME},\n\nYour meeting with {ORCP_NAME} is confirmed. Attached is the calendar meeting invitation.\n\nIf you wish to cancel your booking, please click on the following link.\n\n$cnclLink\n\nAdvise on cancellation: {ADVISE_MESSAGE}\n\nThank you,\nMurodch University Meet Me.\n\nThis is an auto generated email. Please do not reply\n\n\nMurdoch Singapore\n390 Havelock Road #03-01 King’s Centre Singapore 169662.";
    
    //if it's students
    if($recipient==0){
        $rplcBody = array(
            '{RCP_NAME}' => $studentName,
            '{ORCP_NAME}' => $staffName,
            '{ADVISE_MESSAGE}' => "You will need to inform your lecturer if you wish to book another meeting.",
        );
    }

    //if it's for the staff
    else{
        $rplcBody = array(
            '{RCP_NAME}' => $staffName,
            '{ORCP_NAME}' => $studentName,
            '{ADVISE_MESSAGE}' => "You will need to upload student's details again after cancellation to reschedule for another meeting.",
        );
    }

    return strtr($bodyMsg,$rplcBody);

}

/**
 * 
 * This function forges the email content for a booking cancellation
 * for both staff and student
 * 
 * param:
 *   recipient: 0 for forging an email for student
 *              1 for forging an email for staff
 *   bkAuthKey: booking authentication key to retrieve details of student and staff
 * 
 * 
 */
function fgCnclBody($receipient,$bkAuthKey){
    if(empty($studentName = getStudentName(getStudentId(1,$bkAuthKey)))){
        return '';
    }
    
    if(empty($staffName = getStaffName(getStaffId(1,$bkAuthKey)))){
        return '';
    }

    $bodyMsg = "Dear {RCP_NAME},\n\nYour meeting with {ORCP_NAME} has been cancelled.\n\nThank you,\nMurodch University Meet Me.\n\nThis is an auto generated email. Please do not reply\n\n\nMurdoch Singapore\n390 Havelock Road #03-01 King’s Centre Singapore 169662.";
    
    if($receipient==0){
        $rplcBody = array(
            '{RCP_NAME}' => $studentName,
            '{ORCP_NAME}' => $staffName,
            '{ADVISE_MESSAGE}' => "You will need to inform your lecturer if you wish to book another meeting.",
        );
    }

    //if it's for the staff
    else{
        $rplcBody = array(
            '{RCP_NAME}' => $staffName,
            '{ORCP_NAME}' => $studentName,
            '{ADVISE_MESSAGE}' => "You will need to upload student's details again after cancellation to reschedule for another meeting.",
        );
    }

    return strtr($bodyMsg,$rplcBody);
}

/**
 * 
 * This function prepares the PHPMailer object to be sent to student
 * 
 * param:
 *   recipient: 0 for email to request for booking
 *              1 for email for booking confirmation
 *              2 for email upon successful cancellation
 *   bkAuthKey: booking authentication key to retrieve details of student
 * 
 * 
 */
function prepEmailStudent($mode,...$AuthKeys){
    if(sizeof($AuthKeys)>2){
        return false;
    }

    if(empty($mail = initEmail())){
        return false;
    }

    //gets student id with different methods according to the mode
    if($mode==0||$mode==1){
        $studentId = getStudentId(0,$AuthKeys[0]);
    }
    else if($mode==2){
        $studentId = getStudentId(1,$AuthKeys[0]);
    }
    else{
        return false;
    }

    $studentEmail = getStudentEmail($studentId);
    $studentName = getStudentName($studentId);

    //sending booking request only needs student authentication key
    if($mode==0){
        if(sizeof($AuthKeys)!=1){
            return false;
        }

        if(empty($emailcontent = fgRqBody($AuthKeys[0]))){
            return false;
        }

        $subject = "Action Required: Please respond regarding the best meeting time";

    }

    /**
      * sending confirmation email [needs both the student authentication key and
      * booking authentication key]
      * 
      */
    else if($mode==1){
        if(sizeof($AuthKeys)!=2){
            return false;
        }

        if(empty($emailcontent = fgCfmBody(0,...$AuthKeys))){
            return false;
        }

        $subject = "Your meeting is confirmed!";

        //creating ics file and adding as attachment
        if(empty($invite = craftInvite($AuthKeys[1]))){
            return false;
        }

        $mail->AddStringAttachment($invite,'invite.ics');

    }

    else{
        if(sizeof($AuthKeys)!=1){
            return false;
        }

        if(empty($emailcontent = fgCnclBody(0,$AuthKeys[0]))){
            return false;
        }

        $subject = "Your meeting has been cancelled";
    }
    
    try{
        $mail->addAddress($studentEmail,$studentName);
        $mail->Subject = $subject;
        $mail->Body = $emailcontent;
    }
    catch (phpmailerException $e) {
        return false;
    }
    catch (Exception $e) {
        return false;
    }
    
    return $mail;
}


/**
 * 
 * This function prepares the PHPMailer object to be sent to staff
 * 
 * param:
 *   recipient: 1 for email for booking confirmation
 *              2 for email upon successful cancellation
 *   bkAuthKey: booking authentication key to retrieve details of staff
 * 
 * 
 */
function prepEmailStaff($mode,...$AuthKeys){
    if(sizeof($AuthKeys)>2){
        return false;
    }

    if(empty($mail = initEmail())){
        return false;
    }


    //gets staff id using different queries according to input
    if($mode==1){
        $staffId = getStaffId(0,$AuthKeys[0]);
    }
    else if($mode==2){
        $staffId = getStaffId(1,$AuthKeys[0]);
    }
    else{
        return false;
    }

    $staffEmail = getStaffEmail($staffId);
    $staffName = getStaffName($staffId);

    if($mode==1){
        
        if(sizeof($AuthKeys)!=2){
            return false;
        }

        if(empty($emailcontent = fgCfmBody(1,...$AuthKeys))){
            return false;
        }

        //adding subject and ics attachment to the email
        $subject = "Your meeting is confirmed!";
        $mail->AddStringAttachment(craftInvite($AuthKeys[1]),'invite.ics');

    }

    else{
        if(sizeof($AuthKeys)!=1){
            return false;
        }

        if(empty($emailcontent = fgCnclBody(1,$AuthKeys[0]))){
            return false;
        }

        $subject = "Your meeting has been cancelled";

    }

    try{
        $mail->addAddress($staffEmail,$staffName);
        $mail->Subject = $subject;
        $mail->Body = $emailcontent;
    }
    catch (phpmailerException $e) {
        return false;
    }
    catch (Exception $e) {
        return false;
    }

    return $mail;
}

/**
 * This function crafts the string for a ics (calendar invitation) file
 * 
 * param:
 *      bkAuthKey: booking authentication key to get staff and student info
 * 
 * return:
 *      String to insert as ics attachment
 * 
 **/
function craftInvite($bkAuthKey){
    //getting time zone name
    $date = new DateTime();
    $timeZone = $date->getTimezone();
    $strTimeZone = $timeZone->getName();

    //getting the offsett in +XXXX format from UTC
    $offset = $date->format('O');

    //gets staff details for organizer details
    $staffEmail = getStaffEmail(getStaffId(1,$bkAuthKey));
    $staffName = getStaffName(getStaffId(1,$bkAuthKey));
    $startTime = new DateTime(getStartTime($bkAuthKey));
    $endTime = new DateTime(getEndTime($bkAuthKey));

    if(!($staffEmail&&$staffName&&$startTime&&$endTime)){
        return '';
    }

    $strStartTime = $startTime->format('Ymd\THis');
    $strEndTime = $endTime->format('Ymd\THis');
    
    //forging ics body
    $icalBody="BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hacksw/handcal//NONSGML v1.0//EN
BEGIN:VTIMEZONE
TZID:$strTimeZone
BEGIN:STANDARD
DTSTART:16010101T000000
TZOFFSETFROM:$offset
TZOFFSETTO:$offset
END:STANDARD
BEGIN:DAYLIGHT
DTSTART:16010101T000000
TZOFFSETFROM:$offset
TZOFFSETTO:$offset
END:DAYLIGHT
END:VTIMEZONE
BEGIN:VEVENT
UID:$strStartTime-$staffEmail
DTSTAMP:$strStartTime
ORGANIZER;CN=$staffName:mailto:$staffEmail
DTSTART;TZID=$strTimeZone:$strStartTime
DTEND;TZID=$strTimeZone:$strEndTime
SUMMARY;LANGUAGE=en-US:Online Meeting
LOCATION;LANGUAGE=en-US:Microsoft Teams Meeting
BEGIN:VALARM
DESCRIPTION:REMINDER
TRIGGER;RELATED=START:-PT15M
ACTION:DISPLAY
END:VALARM
END:VEVENT
END:VCALENDAR";
    
    return $icalBody;
}

/**
 * This function sends the PHPMailer object that is received as a parameter
 * 
 * param:
 *   mail: PHPMailer object that is ready to send
 * 
 */ 
function sendEmail($mail){
    try{
        $mail->send();
    }
    catch (phpmailerException $e) {
        return false;
    }
    catch (Exception $e) {
        return false;
    }

    return true;
}

?>