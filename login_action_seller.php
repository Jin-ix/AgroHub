<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ahproject";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$firstname = $_POST["firstname"];
$lastname = $_POST["lastname"];
$email = $_POST["email"];
$password = $_POST["password"];
$phonenumber = $_POST["phonenumber"];
$address = $_POST["address"];
$about = $_POST["about"];

// Check if email already exists
$emailCheckSql = "SELECT * FROM seller WHERE email = ?";
$emailStmt = $conn->prepare($emailCheckSql);

if ($emailStmt) {
    $emailStmt->bind_param("s", $email);
    $emailStmt->execute();
    $result = $emailStmt->get_result();

    if ($result->num_rows > 0) {
        // Email already exists
        echo "Already Registered!!";
    } else {
        // Email does not exist, proceed with registration
        $sql = "INSERT INTO seller (SellerName, lname, email, password, phonenumber, address, about) VALUES (?, ?, ?, ?, ?, ?,?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sssssss", $firstname, $lastname, $email, $password, $phonenumber, $address, $about);

            if ($stmt->execute()) {
                $_SESSION['ahproject'] = 'true';
                $_SESSION['emailidb'] = $email;
                header("Location: login_seller.html"); // Redirect to login page after successful registration
                exit(); // Make sure to exit after redirecting
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    }

    $emailStmt->close();
} else {
    echo "Error preparing statement: " . $conn->error;
}

$conn->close();
?>
