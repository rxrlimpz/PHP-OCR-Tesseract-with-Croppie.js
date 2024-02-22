<?php
include('connect.php');
include('config.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $result = $db->prepare("SELECT * FROM docstbl WHERE id= :id");
    $result->bindParam('id', $id);
    $result->execute();

    if ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $data = array(
            'docName' => $row['DocName'],
            'college' => $row['College'],
            'course' => $row['Course'],
            'year' => $row['year_level'],
            'subject' => $row['Subject'],
            'schoolyear' => $row['SchoolYear'],
            'semester' => $row['Semester'],
            'class' => $row['classType']
        );
        echo json_encode($data);
    } else {
        echo json_encode(array('error' => 'No data found'));
    }
} else {
    echo json_encode(array('error' => 'ID not provided'));
}
?>