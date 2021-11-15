<?php
if(!defined('access')) {
    http_response_code(404);
    exit();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once($_SERVER["DOCUMENT_ROOT"]."/lib/vendor/autoload.php");
require_once("connection.php");
require_once("info_retrieve.php");

/**
 * Takes in an authentication link and forges a unique url 
 * to book a meeting with a specific student id
 **/
function fgBkLink($stdtAuthKey){
    return "https://localhost/studentIdInput.php?authkey=$stdtAuthKey";
}


/**
 * Takes in an authentication link and forges a unique url 
 * to cancel a specific meeting
 **/
function fgCnclLink($bkAuthKey){
    return "https://localhost/cancelBooking.php?authkey=$bkAuthKey";   
}

//initializing a phpmailer object to send mail
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
        //echo $e->errorMessage();
        $emailSetup = false;
    }
    catch (Exception $e) {
        //echo $e->getMessage();
        $emailSetup = false;
    }

    if($emailSetup){
        return $mail;
    }
    else{
        return '';
    }

}

//requesting student to book an email
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

//0 for student's email body
//1 for lecturer's email body
//student auth key to get basic info
function fgCfmBody($recipient,...$AuthKeys){
    $stdtAuthKey = $AuthKeys[0];
    $bkAuthKey = $AuthKeys[1];

    if(empty($studentName = getStudentName(getStudentId(0,$stdtAuthKey)))){
        return '';
    }
    
    if(empty($staffName = getStaffName(getStaffId(0,$stdtAuthKey)))){
        return '';
    }

    $cnclLink = fgCnclLink($bkAuthKey);

    $bodyMsg = "Dear {RCP_NAME},\n\nYour meeting with {ORCP_NAME} is confirmed. Attached is the calendar meeting invitation.\n\nIf you wish to cancel your booking, please click on the following link.\n\n https://localhost/cancelBooking.php?authkey=$cnclLink\n\nAdvise on cancellation: {ADVISE_MESSAGE}\n\nThank you,\nMurodch University Meet Me.\n\nThis is an auto generated email. Please do not reply\n\n\nMurdoch Singapore\n390 Havelock Road #03-01 King’s Centre Singapore 169662.";
    
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

//0 for student email
//1 for staff email
function fgCnclBody($receipient,$bkAuthKey){
    $bodyMsg = "Dear {RCP_NAME},\n\nYour meeting with {ORCP_NAME} has been cancelled.\n\nThank you,\nMurodch University Meet Me.\n\nThis is an auto generated email. Please do not reply\n\n\nMurdoch Singapore\n390 Havelock Road #03-01 King’s Centre Singapore 169662.";

    $studentId = getStudentId(1,$bkAuthKey);
    $studentName = getStudentName($studentId);

    $staffId = getStaffId(1,$bkAuthKey);
    $staffName = getStaffName($staffId);
    
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

//mode 0 for booking request
//mode 1 for booking confirmed
//mode 2 for booking canceled
function sendEmailStudent($mode,...$AuthKeys){
    if(sizeof($AuthKeys)>2){
        return false;
    }

    if(empty($mail = initEmail())){
        return false;
    }

    if($mode==0||$mode==1){
        $studentId = getStudentId(0,$AuthKeys[0]);
        $studentEmail = getStudentEmail($studentId);
        $studentName = getStudentName($studentId);

        $staffId = getStaffId(0,$AuthKeys[0]);
        $staffName = getStaffName($staffId);

        if(empty($studentEmail)||empty($studentName)){
            return false;
        }

        if(empty($staffName)){
            return false;
        }

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

        //sending confirmation email
        else{
            if(sizeof($AuthKeys)!=2){
                return false;
            }

            if(empty($emailcontent = fgCfmBody(0,...$AuthKeys))){
                return false;
            }

            $subject = "Your meeting is confirmed!";

        }
    }
    else if(mode==2){
        if(sizeof($AuthKeys)!=1){
            return false;
        }

        if(empty($emailcontent = fgCnclBody(0,$AuthKeys[0]))){
            return false;
        }

        $subject = "Your meeting has been cancelled";
    }
    else{
        return false;
    }

    
    try{
        $mail->addAddress($studentEmail,$studentName);
        $mail->Subject = $subject;
        $mail->Body = $emailcontent;
        $mail->send();
    }
    catch (phpmailerException $e) {
        //echo $e->errorMessage();
        return false;
    }
    catch (Exception $e) {
        //echo $e->getMessage();
        return false;
    }
    
    return true;
}


//mode 1 for booking confirmation
//mode 2 for booking cancellation
function sendEmailStaff($mode,...$AuthKeys){
    if(sizeof($AuthKeys)>2){
        return false;
    }

    if(empty($mail = initEmail())){
        return false;
    }

    $studentId = getStudentId(0,$AuthKeys[0]);
    $studentName = getStudentName($studentId);

    $staffId = getStaffId(0,$AuthKeys[0]);
    $staffName = getStaffName($staffId);
    $staffEmail = getStaffEmail($staffId);

    if(empty($studentName)){
        return false;
    }

    if(empty($staffEmail)||empty($staffName)){
        return false;
    }

    if($mode==1){
        
        if(sizeof($AuthKeys)!=2){
            return false;
        }

        if(empty($emailcontent = fgCfmBody(1,...$AuthKeys))){
            return false;
        }

        $subject = "Your meeting is confirmed!";

    }

    else if($mode==2){
        if(sizeof($AuthKeys)!=1){
            return false;
        }

        if(empty($emailcontent = fgCnclBody(0,$AuthKeys[0]))){
            return false;
        }

        $subject = "Your meeting has been cancelled";

    }
    else{
        return false;
    }

    try{
        $mail->addAddress($staffEmail,$staffName);
        $mail->Subject = $subject;
        $mail->Body = $emailcontent;
    }
    catch (phpmailerException $e) {
        //echo $e->errorMessage();
        return false;
    }
    catch (Exception $e) {
        //echo $e->getMessage();
        return false;
    }

    return true;
}

function craftInvite($bkAuthKey){
    //VERSION:2.0 = RFC 5545 format
    //ORGANIZER;CN= {The person that organizes}
    //uid = (utctimestamp)+(student's email)
    //ical info
    $query = "SELECT * FROM";
    $string = '';
    return $string;
}
/*$calendar = "BEGIN:VCALENDAR\r\n
VERSION:2.0\r\n
PRODID:-//hacksw/handcal//NONSGML v1.0//EN\r\n
BEGIN:VTIMEZONE\r\n
TZID:Singapore Standard Time\r\n
BEGIN:STANDARD\r\n
DTSTART:16010101T000000\r\n
TZOFFSETFROM:+0800\r\n
TZOFFSETTO:+0800\r\n
END:STANDARD\r\n
BEGIN:DAYLIGHT\r\n
DTSTART:16010101T000000\r\n
TZOFFSETFROM:+0800\r\n
TZOFFSETTO:+0800\r\n
END:DAYLIGHT\r\n
END:VTIMEZONE\r\n
BEGIN:VEVENT\r\n
UID:20211114T000000-twotirt@gmail.com\r\n
DTSTAMP:19970610T172345Z\r\n
ORGANIZER;CN=Tiffany:mailto:tiffwonglh@gmail.com\r\n
DTSTART;TZID=Singapore Standard Time:20211114T000000\r\n
DTEND;TZID=Singapore Standard Time:20211114T010000\r\n
SUMMARY;LANGUAGE=en-US:Online Meeting\r\n
LOCATION;LANGUAGE=en-US:Microsoft Teams Meeting\r\n
BEGIN:VALARM\r\n
DESCRIPTION:REMINDER\r\n
TRIGGER;RELATED=START:-PT15M\r\n
ACTION:DISPLAY\r\n
END:VALARM\r\n
END:VEVENT\r\n
END:VCALENDAR";
        $mail->AddStringAttachment($calendar,'invite.ics');*/

?>