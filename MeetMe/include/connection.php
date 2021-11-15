<?php
if(!defined('access')) {
    http_response_code(404);
    exit();
}
$dbhost = "localhost:3306";
$dbuser = "root";
$dbpass = "";
$dbname = "meetmev2";

if(!$con = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname))
{
    die("failed to connect!");
}

mysqli_autocommit($con,FALSE);

//$siteURL = "https://localhost/MeetMe";
?>