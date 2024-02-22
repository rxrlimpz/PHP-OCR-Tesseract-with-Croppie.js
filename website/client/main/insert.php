<?php
include 'config.php';
include 'connect.php';

//Start session
ini_set('display_errors', 1);
error_reporting(E_ALL);


$data = json_decode($_POST['data'], true);

$firstname = [];
$surname = [];
$middlename = [];
$suffixname = [];

$rawHeaderData = $_POST['headerData'];
$headerData = json_decode($rawHeaderData, true);

$docName = $headerData['docName'];
$College = $headerData['College'];
$Course = $headerData['Course'];
$Year = $headerData['Year'];
$Subject = $headerData['Subject'];
$schoolYear = $headerData['schoolYear'];
$Semester = $headerData['Semester'];
$Class_Type = $headerData['Class_Type'];

foreach ($data as $item) {
    $value = trim($item['value']);
    $name = $item['name'];

    switch ($name) {
        case 'fname':
            $firstname[] = !empty($value) ? $value : "";
            break;
        case 'sname':
            $surname[] = !empty($value) ? $value : "";
            break;
        case 'middlename':
            $middlename[] = !empty($value) ? $value . "." : "";
            break;
        case 'suffixname':
            $suffixname[] = !empty($value) ? $value . "." : "";
            break;
        default:
            break;
    }
}

$newSurname = [];
$newFirstname = [];
$newMiddlename = [];
$newSuffixname = [];

for ($i = 0; $i < count($firstname); $i++) {
    if (!empty($surname[$i]) && !empty($firstname[$i])) {
        $newSurname[] = $surname[$i];
        $newFirstname[] = $firstname[$i];
        $newMiddlename[] = $middlename[$i];
        $newSuffixname[] = $suffixname[$i];
    }
}

// Replace the original arrays with the new filtered arrays
$surname = $newSurname;
$firstname = $newFirstname;
$middlename = $newMiddlename;
$suffixname = $newSuffixname;

$sqlSearchDoc = "SELECT id FROM docstbl WHERE 
DocName = '$docName' AND
classType = '$Class_Type'";

$result_searchDoc = $con->query($sqlSearchDoc) or die("Query error: " . $con->error);

//get the results for the search of redundant data from the database

if ($result_searchDoc) {
    if ($row = $result_searchDoc->fetch_assoc()) {
        $newDocsID = $row['id'];
        //get the DocID to change as newDocsID in the database

    } else {

        if (isset($_FILES['fileInput']) && $_FILES['fileInput']['error'] === UPLOAD_ERR_OK) {

            $imgData = file_get_contents($_FILES['fileInput']['tmp_name']);
            $originalFileType = $_FILES['fileInput']['type'];

            $sqlInsertDocs = "INSERT INTO docstbl (DocName, College, Course, year_level, Subject, SchoolYear, Semester, classType, DocIMG, OriginalFileType)
                VALUES (? , ? , ? , ? , ? , ? , ? , ? , ? , ? )";

            $insertDocs = $con->prepare($sqlInsertDocs);
            $insertDocs->bind_param(
                "ssssssssss",
                $docName,
                $College,
                $Course,
                $Year,
                $Subject,
                $schoolYear,
                $Semester,
                $Class_Type,
                $imgData,
                $originalFileType
            );

            if ($insertDocs->execute()) {
                $newDocsID = $con->insert_id;
            } else {
                echo "Error: " . $insertDocs->error;
            }
            $insertDocs->close();
        }
    }

    if (!empty($newDocsID)) {
        for ($i = 0; $i < count($firstname); $i++) {
            $sqlSearchStudent = "SELECT id FROM studenttbl WHERE 
                SFName = ? AND
                SLName = ? AND
                SMName = ? AND
                S_suffix = ?";

            // Prepare the SQL statement
            $searchStudent = $con->prepare($sqlSearchStudent);
            // Bind parameters and execute the query
            $searchStudent->bind_param("ssss", $firstname[$i], $surname[$i], $middlename[$i], $suffixname[$i]);
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
                    $insertName->bind_param("ssss", $firstname[$i], $surname[$i], $middlename[$i], $suffixname[$i]);
                    $insertName->execute();
                    $newStudentID = $insertName->insert_id;
                } catch (PDOException $e) {
                    echo "Error inserting record: " . $e->getMessage();
                }
            }

            if (!empty($newStudentID)) {
                $sqlCheckExisting = "SELECT COUNT(*) AS count FROM taggingtbl WHERE studentID = ? AND docsID = ?";
                $checkExisting = $con->prepare($sqlCheckExisting);
                $checkExisting->bind_param("ss", $newStudentID, $newDocsID);
                $checkExisting->execute();
                $result = $checkExisting->get_result();
                $row = $result->fetch_assoc();
                $existingCount = $row['count'];

                if ($existingCount === 0) {
                    // No existing record found, proceed with insertion
                    $sqlInsertTag = "INSERT INTO taggingtbl (studentID, docsID) VALUES (?, ?)";
                    $insertTag = $con->prepare($sqlInsertTag);
                    $insertTag->bind_param("ss", $newStudentID, $newDocsID);
                    $insertTag->execute();
                }
            }
        }
    }
}
