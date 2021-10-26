<?php
require_once 'vendor/autoload.php';
session_start();
include("connection.php");
include("function.php");
$user_data = check_login($con);

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

if (isset($_POST['submit'])) {
    $userid = $user_data['StaffID'];

    $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
     
    if(isset($_FILES['file']['name']) && in_array($_FILES['file']['type'], $file_mimes)) {
     
        $arr_file = explode('.', $_FILES['file']['name']);
        $extension = end($arr_file);
     
        if('csv' == $extension) {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        } else {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }
 
        $spreadsheet = $reader->load($_FILES['file']['tmp_name']);
 
        $sheetData = $spreadsheet->getActiveSheet()->toArray();
         
        if (!empty($sheetData)) {
            for ($i=1; $i<count($sheetData); $i++) {
                $StudentID = $sheetData[$i][0];
                $Email = $sheetData[$i][1];
                $Firstname = $sheetData[$i][2];
                $Lastname = $sheetData[$i][3];
                $query1 = "INSERT into student('StudentID','Email','First_name','Last_name') VALUES ('$StudentID','$Email','$Firstname','$Lastname')";
                if (!mysqli_query($con, $query1)) {
                    echo '<script>
                    alert("an error with insert student has occurred.");
                    </script>';
                }
                $query2 = "INSERT into studentlist('ListID','StudentID') VALUES ('$userid','$StudentID')";
                $result2 = mysqli_query($con,$query2);
                if(!$result2){
                    echo '<script>
                    alert("an error with insert studentlist has occurred.");
                    </script>';
                } 
            }
        }
    }
}
echo '<script>
    alert("Successfully uploaded student list");
    </script>';
    //redirect back to home page
    header("Location: home.php");
?>