<?php

if (isset($_GET['sellerName'])) {
    $sellerName = $_GET['sellerName'];

    // Database connection parameters
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ahproject";

    // Create a new database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query to fetch average rating and review count for the given sellerName
    $sql = "SELECT AVG(star) AS avgRating, COUNT(*) AS reviewCount FROM product_review WHERE SellerName = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $sellerName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $avgRating = round($row['avgRating'], 1);
        $reviewCount = $row['reviewCount'];

        // Prepare the response with average rating and review count
        $response = array(
            'avgRating' => $avgRating,
            'reviewCount' => $reviewCount
        );

        echo json_encode($response);
    } else {
        echo json_encode(array('message' => 'No reviews found for this seller.'));
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(array('error' => 'Seller name parameter is missing.'));
}
?>
