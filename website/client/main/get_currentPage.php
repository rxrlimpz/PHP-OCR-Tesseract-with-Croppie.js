<?php
require_once('auth.php');

if (isset($_SESSION['currentPage'])) {
    $currentPage = $_SESSION['currentPage'];
    echo $currentPage;
} else {
    // Return a default value if the variable is not set
    echo "default_page.php";
}
?>
