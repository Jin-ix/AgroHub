<?php
session_start();
include("connection.php");

// Validate user input
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Ensure email and password are not empty and that email is not numeric
    if (!empty($email) && !empty($password) && !is_numeric($email)) {

        // Prepare the SQL statement to prevent SQL injection
        $sql = "SELECT BuyerID, password, fname FROM buyer WHERE email = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $user_data = $result->fetch_assoc();
            
            // Verify the password (consider hashing the password for security)
            if ($user_data['password'] == $password) {
                // Set session variables
                $_SESSION['ahproject'] = true;
                $_SESSION['emailidb'] = $email;
                $_SESSION['BuyerID'] = $user_data['BuyerID'];
                $_SESSION['fname'] = $user_data['fname']; // Store the first name in the session

                // Redirect to buyer home
                header("location:buyerhome1.php");
                die;
            } else {
                echo "<script type='text/javascript'> alert('Wrong username or password')</script>";
            }
        } else {
            echo "<script type='text/javascript'> alert('Wrong username or password')</script>";
        }
    } else {
        echo "<script type='text/javascript'> alert('Please enter valid email and password')</script>";
    }
}
?>
