<?php
include('connect.php');
include('config.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $stmt = $db->prepare("SELECT DocIMG FROM docstbl WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        $imageData = $row['DocIMG'];
        // Output the image data
        header("Content-type: image/jpeg"); // Adjust the content type based on your image type
        echo base64_encode($imageData);
        exit();
    }
}

// Handle errors if the ID is not found
http_response_code(404);
echo "Image not found.";
?>
