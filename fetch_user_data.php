<?php
session_start();

header('Content-Type: application/json');

// Check if the username session variable is set
if (isset($_SESSION['emailidb'])) {
    echo json_encode(['name' => $_SESSION['emailidb']]);
} else {
    echo json_encode(['name' => '']);
}
?>
