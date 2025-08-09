<?php
session_start();

// Debugging: Check session variables
echo "Session Username: " . (isset($_SESSION['username']) ? $_SESSION['username'] : 'Not set') . "<br>";
echo "Session Email ID: " . (isset($_SESSION['emailidb']) ? $_SESSION['emailidb'] : 'Not set') . "<br>";

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

// Check if the email session variable is set
if (!isset($_SESSION['emailidb'])) {
    die("Error: User not logged in.");
}

// Retrieve the logged-in seller's name from the session
$Sellername = $_SESSION['emailidb'];

// Sanitize POST variables
$Proname = $conn->real_escape_string($_POST["name"]);
$Provariety = $conn->real_escape_string($_POST["varietyname"]);
$Proprice = $conn->real_escape_string($_POST["price"]);
$Prodesc = $conn->real_escape_string($_POST["description"]);
$Quantity = $conn->real_escape_string($_POST["quantity"]);

// Handling image data
$Proimage = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $Proimage = file_get_contents($_FILES['image']['tmp_name']);
}

// Prepare the SQL statement
$sql = "INSERT INTO fruits (SellerName, ProductName, ProductVariety, ProductPrice, ProductDesc, ProductImage, Quantity) VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if ($stmt) {
    // Bind parameters with the correct type string
    // Make sure the binding order matches the SQL query
    $stmt->bind_param('sssdssi', $Sellername, $Proname, $Provariety, $Proprice, $Prodesc, $Proimage, $Quantity);

    // Send long data for ProductDesc and ProductImage
    $stmt->send_long_data(4, $Prodesc); // 4th parameter for ProductDesc
    if ($Proimage) {
        $stmt->send_long_data(5, $Proimage); // 5th parameter for ProductImage
    }

    // Execute the statement
    if ($stmt->execute()) {
        // No need to overwrite session variables unnecessarily
        // $_SESSION['emailidb'] = $email; // Remove this, since $email is not defined

        // Redirect to the product upload page after successful insertion
        header("Location: productupload.html");
        exit(); // Ensure script stops after redirect
    } else {
        // Output any errors
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the statement
    $stmt->close();
} else {
    // Output any errors in preparing the statement
    echo "Error preparing statement: " . $conn->error;
}

// Close the connection
$conn->close();
?>
