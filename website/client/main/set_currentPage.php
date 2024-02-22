<?php

if (isset($_POST['currentPage'])) {
    $_SESSION['currentPage'] = $_POST['currentPage'];
    echo "Success";
} else {
    echo "Error: currentPage parameter not provided.";
}
?>

