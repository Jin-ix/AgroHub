// Assuming product upload logic is here
$sellerName = $_POST['sellerName'];
$productName = $_POST['productName'];
$productType = $_POST['productType'];

// After inserting the product, log it to the history
$sql_history = "INSERT INTO product_history (seller_name, product_name, product_type) VALUES ('$sellerName', '$productName', '$productType')";
if ($conn->query($sql_history) === TRUE) {
    echo "Product added to history";
} else {
    echo "Error: " . $conn->error;
}
