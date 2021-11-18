<?php
session_start();

//if staff is not logged in
if(!isset($_SESSION['StaffID'])) {
    http_response_code(404);
    header("Location: error404");
    exit();
}

//destroy session
unset($_SESSION);
session_destroy();
header("Location: login");
?>