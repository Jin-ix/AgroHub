<?php
session_start();

header('Content-Type: application/json');

// Check if the email session variable is set
if (!isset($_SESSION['emailidb'])) {
    echo json_encode([]);
    exit();
}

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = '';
$dbname = "ahproject";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_SESSION['emailidb'];
$response = [];

// Fetch oil table details for the current seller
$sql = "SELECT SellerName, ProductName, ProductVariety, ProductPrice, ProductDesc, Quantity FROM oil WHERE SellerName = (SELECT email FROM seller WHERE email = ?)";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
    }
    $stmt->close();
}

$conn->close();
echo json_encode($response);
?>