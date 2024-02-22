<?php
include 'config.php';
include 'connect.php';
$response = array();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $result = $db->prepare("DELETE FROM taggingtbl WHERE id= :id");
    $result->bindParam(':id', $id);

    if ($result->execute()) {
        $response['success'] = true;
        $response['message'] = "Document deleted successfully";
    } else {
        $response['success'] = false;
        $response['message'] = "Failed to delete document";
    }
} else {
    $response['success'] = false;
    $response['message'] = "ID not provided";
}

header('Content-Type: application/json');
echo json_encode($response);
?>
