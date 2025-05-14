<?php
// Make sure to edit this mess later, OK?
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: /login.php");
    exit();
}

?>
