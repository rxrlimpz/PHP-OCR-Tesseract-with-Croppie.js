<?php

Session_start();
$_SESSION['currentPage'] = "upload.php";
header("Location: client/main/iframe.php");
?>