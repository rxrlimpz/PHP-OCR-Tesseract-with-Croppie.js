<?php
include('connect.php');
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $taggingID = $_POST['record_id'];
    $surname = $_POST['surname'];
    $firstname = $_POST['firstname'];
    $Temp_middlename = $_POST['middlename'];
    $Temp_suffixname = $_POST['suffixname'];
    
    $middlename = !empty($Temp_middlename) ? rtrim($Temp_middlename) . "." : "";
    $suffixname = !empty($Temp_suffixname) ? rtrim($Temp_suffixname) . "." : "";
    
    $sqlSearchStudent = "SELECT id FROM studenttbl WHERE 
    SLName = ? AND
    SFName = ? AND
    SMName = ? AND
    S_suffix = ?";

    // Prepare the SQL statement
    $searchStudent = $con->prepare($sqlSearchStudent);
    // Bind parameters and execute the query
    $searchStudent->bind_param("ssss", $surname, $firstname, $middlename, $suffixname);
    $searchStudent->execute();
    // Get the result
    $result_searchStudent = $searchStudent->get_result();

    $newStudentID = "";

    // Check if there are rows returned
    if ($result_searchStudent->num_rows > 0) {
        $row = $result_searchStudent->fetch_assoc();
        $newStudentID = $row['id'];
    } else {
        $sqlInsertName = "INSERT INTO studenttbl (SFName, SLName, SMName, S_suffix) VALUES (?, ?, ?, ?)";
        try {
            $insertName = $con->prepare($sqlInsertName);
            $insertName->bind_param("ssss", $firstname, $surname, $middlename, $suffixname);
            $insertName->execute();
            $newStudentID = $insertName->insert_id;
        } catch (PDOException $e) {
            echo "Error inserting record: " . $e->getMessage();
        }
    }

    if (!empty($newStudentID)) {
        $sqlInsertTag = "UPDATE taggingtbl SET studentID =? WHERE id=?";
        $update = $con->prepare($sqlInsertTag);
        $update->bind_param("si", $newStudentID, $taggingID);

        if ($update->execute()) {
            // If the update was successful, return a success message
            echo "success";
        } else {
            // If there was an error, return an error message
            echo "Error: " . $update->error;
        }
    }

    $update->close();
    $con->close();
}
