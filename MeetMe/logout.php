<?php
session_start();

if(isset($_SESSION['StaffID'])){
    unset($_SESSION['StaffID']);
}
header("Location: login.php");
?>