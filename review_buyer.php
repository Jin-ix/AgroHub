<?php
session_start();
include('connection.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $orderID = $_POST['orderID'];
    $ProductID = $_POST['ProductID'];
    $SellerName = $_POST['SellerName'];
    $rating = $_POST['rating'];
    $review = $_POST['review'];
    $image = null;

    // Handle the image upload
    if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
        $image = file_get_contents($_FILES['image']['tmp_name']);
    }

    // Get the BuyerID from session (assuming it's stored there after login)
    $BuyerID = $_SESSION['BuyerID'];  // Ensure this is set when the user logs in

    // Insert the review data into the product_review table
    $sql = "INSERT INTO product_review (BuyerID, SellerName, ProductID, star, review, image, orderID)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("isiiisi", $BuyerID, $SellerName, $ProductID, $rating, $review, $image, $orderID);

    if ($stmt->execute()) {
        // Redirect back to the order history page after successful submission
        header("Location: order_history.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
} else {
    // If the request method is not POST, redirect back to the order history page
    header("Location: order_history.php");
    exit();
}
?>
