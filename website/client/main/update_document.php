<?php
include('connect.php');
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

$id = $_POST['doc_id'];
$docName = $_POST['docName'];
$college = $_POST['college'];
$course = $_POST['course'];
$yearLevel = $_POST['yearLevel'];
$subject = $_POST['subject'];
$schoolYear = $_POST['schoolYear'];
$semester = $_POST['semester'];
$classType = $_POST['classType'];

$sql = "UPDATE docstbl SET DocName=?, College=?, Course=?, year_level=?, Subject=?, SchoolYear=?, Semester=?, classType=? WHERE id=?";

$update = $con->prepare($sql);
$update->bind_param("ssssssssi", $docName, $college, $course, $yearLevel, $subject, $schoolYear, $semester, $classType, $id);

if ($update->execute()) {
    // If the update was successful, return a success message
    echo "success";
} else {
    // If there was an error, return an error message
    echo "Error: " . $update->error;
}

$update->close();
$con->close();
}
?>
