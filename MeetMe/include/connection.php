<?php
//only codes with global variable defined can access the files
if(!defined('access')) {
    http_response_code(404);
    exit();
}

//database name and password to access the database
$dbhost = "localhost:3306";
$dbuser = "root";
$dbpass = "";
$dbname = "meetmev2";

//attempts to connect to the database
if(!$con = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname))
{
    //shows error page if failed
    http_response_code(404);
    exit();
}

//sets auto commit to false
mysqli_autocommit($con,FALSE);

?>