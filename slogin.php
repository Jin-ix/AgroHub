54<?php
session_start();
include("connection.php"); // Ensure this file sets up the database connection in $con

// Validate user input
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    if (!empty($email) && !empty($password) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $query = "SELECT * FROM seller WHERE email='$email' LIMIT 1";
        $result = mysqli_query($con, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            if ($user_data['password'] == $password) {
                $_SESSION['ahproject'] = true;
                $_SESSION['emailidb'] = $email;
                $_SESSION['username'] = $user_data['username']; // Assuming the seller table has a 'username' column

                // Debugging: Print session variables
                echo "Session Username: " . $_SESSION['username'] . "<br>";
                echo "Session Email ID: " . $_SESSION['emailidb'] . "<br>";

                // Redirect to the profile page after debugging
                header("Location: seller_profile.html");
                exit();
            } else {
                echo "<script type='text/javascript'> alert('Wrong username or password')</script>";
            }
        } else {
            echo "<script type='text/javascript'> alert('Wrong username or password')</script>";
        }
    } else {
        echo "<script type='text/javascript'> alert('Invalid input')</script>";
    }
}
?>
