<?php
include('connect.php');
include('config.php');

if (isset($_GET['id'])) {
    $taggingID = $_GET['id'];

    $sqlSearchStudent = "SELECT * FROM taggingtbl WHERE 
    id = ? ";

    $searchStudent = $con->prepare($sqlSearchStudent);
    $searchStudent->bind_param("s", $taggingID);
    $searchStudent->execute();

    $result_searchStudent = $searchStudent->get_result();

    if ($result_searchStudent->num_rows > 0) {
        $row = $result_searchStudent->fetch_assoc();
        $studentID = $row['studentID'];
    }

    $result = $db->prepare("SELECT * FROM studenttbl WHERE id= :id");
    $result->bindParam('id', $studentID);
    $result->execute();

    if ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $data = array(
            'firstname' => $row['SFName'],
            'surname' => $row['SLName'],
            'middlename' => $row['SMName'],
            'suffixname' => $row['S_suffix']
        );
        echo json_encode($data);
    } else {
        echo json_encode(array('error' => 'No data found'));
    }
} else {
    echo json_encode(array('error' => 'ID not provided'));
}
