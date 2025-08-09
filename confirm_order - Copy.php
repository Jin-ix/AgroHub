<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f1f1f1;
        }
        header {
            background-color:  #006633;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .container {
            padding: 20px;
        }
        .order-details {
            display: flex;
            flex-wrap: wrap;
            background-color: white;
            border: 1px solid lightgray;
            padding: 20px;
            margin-bottom: 20px;
        }
        .order-item {
            flex: 1 1 calc(20% - 10px);
            box-sizing: border-box;
            padding: 10px;
            margin: 5px;
            background-color: #fafafa;
            border: 1px solid #ddd;
            text-align: center;
        }
        .order-item img {
            width: 80px;
            height: auto;
        }
        .order-item h2 {
            margin: 10px 0;
            font-size: 16px;
        }
        .success-message {
            font-size: 24px;
            font-weight: bold;
            color: green;
            text-align: center;
            margin-top: 20px;
        }
        .address-details {
            font-size: 18px;
            margin-top: 20px;
            text-align: center;
        }
        .total-price {
            font-size: 20px;
            font-weight: bold;
            margin-top: 20px;
            text-align: center;
        }
        .button-container {
            text-align: center;
            margin-top: 20px;
        }
        .button-container button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            background-color:  #006633;
            color: white;
            border: none;
            border-radius: 5px;
        }
        .button-container button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<header>
    <h1>Order Confirmation</h1>
</header>

<div class="container">
    <?php
    session_start();
    include('connection.php');

    $email = $_SESSION['emailidb'];
    $selectedAddress = $_POST['selected_address'];

    // Product categories with keywords
    $fruits = ["Apple", "Banana", "Orange", "Mango", "Grapes", "Pineapple", "Watermelon", "Strawberry", "Blueberry", "Raspberry", "Kiwi", "Peach", "Pear", "Pomegranate", "Cherry", "Blackberry", "Dragon Fruit", "Papaya", "Apricot", "Lychee", "Cantaloupe", "Honeydew", "Lime", "Lemon", "Guava", "Coconut", "Fig", "Passion Fruit", "Tangerine", "Persimmon", "Date", "Cranberry", "Mulberry", "Starfruit", "Soursop", "Kumquat", "Jackfruit", "Rambutan", "Nashi Pear", "Quince"];
    
    $vegetables = ["Carrot", "Potato", "Tomato", "Onion", "Garlic", "Spinach", "Broccoli", "Cauliflower", "Cabbage", "Lettuce", "Bell Pepper", "Zucchini", "Eggplant", "Beetroot", "Radish", "Cucumber", "Green Bean", "Peas", "Kale", "Brussels Sprouts", "Asparagus", "Artichoke", "Sweet Potato", "Butternut Squash", "Celery", "Leek", "Corn", "Fennel", "Bok Choy", "Swiss Chard", "Arugula", "Mustard Greens", "Turnip", "Chayote", "Jicama", "Okra", "Taro", "Rutabaga", "Napa Cabbage", "Daikon"];
    
    $homemades = ["Homemade Jam", "Homemade Pickles", "Homemade Salsa", "Homemade Bread", "Homemade Cookies", "Homemade Cakes", "Homemade Granola", "Homemade Pasta", "Homemade Pancakes", "Homemade Muffins", "Homemade Sauces", "Homemade Ravioli", "Homemade Pies", "Homemade Ice Cream", "Homemade Fudge", "Homemade Tarts", "Homemade Energy Bars", "Homemade Nut Butter", "Homemade Dressings", "Homemade Marshmallows", "Homemade Brownies", "Homemade Soup", "Homemade Curry Paste", "Homemade Fermented Vegetables", "Homemade Candies", "Homemade Herbal Tea", "Homemade Vinegar", "Homemade Hummus", "Homemade Scones", "Homemade Rice Cakes", "Homemade Cheesecake", "Homemade Nut Milk", "Homemade Sausages", "Homemade Chocolate", "Homemade Meatballs", "Homemade Granola Bars", "Homemade Ketchup", "Homemade Mustard", "Homemade Nut Milk Yogurt", "Homemade Fish Sauce"];
    
    $oils = ["Olive Oil", "Coconut oil", "Sunflower oil", "Canola Oil", "Peanut Oil", "Avocado Oil", "Sesame Oil", "Grapeseed Oil", "Walnut Oil", "Almond Oil", "Hemp Seed Oil", "Flaxseed Oil", "Pumpkin Seed Oil", "Safflower Oil", "Corn Oil", "Rice Bran Oil", "Soybean Oil", "Hazelnut Oil", "Moringa Oil", "Poppyseed Oil", "Essential Oil (Lavender)", "Essential Oil (Peppermint)", "Essential Oil (Tea Tree)", "Essential Oil (Lemon)", "Essential Oil (Eucalyptus)", "Essential Oil (Frankincense)", "Essential Oil (Rosemary)", "Essential Oil (Cinnamon)", "Essential Oil (Oregano)", "Essential Oil (Bergamot)", "Castor Oil", "Macadamia Nut Oil", "Sweet Almond Oil", "Argan Oil", "Red Palm Oil", "Black Cumin Seed Oil", "Cacao Butter", "Jojoba Oil", "Borage Oil", "Marula Oil"];
    
    $powders = ["Turmeric Powder", "Chili Powder", "Garlic Powder", "Onion Powder", "Ginger Powder", "Mustard Powder", "Cinnamon Powder", "Nutmeg Powder", "Clove Powder", "Paprika Powder", "Cumin Powder", "Coriander Powder", "Cardamom Powder", "Black Pepper Powder", "Coconut Flour", "Almond Flour", "Oat Flour", "Whole Wheat Flour", "Rice Flour", "Cornstarch", "Baking Powder", "Baking Soda", "Matcha Powder", "Acai Powder", "Spirulina Powder", "Protein Powder", "Beetroot Powder", "Maca Powder", "Moringa Powder", "Green Tea Powder", "Arrowroot Powder", "Saffron Powder", "Dandelion Root Powder", "Soy Protein Powder", "Peanut Butter Powder", "Coconut Milk Powder", "Chia Seed Powder", "Flaxseed Powder", "Oregano Powder", "Curry Powder"];

    // Fetch BuyerID based on email
    $sql = "SELECT BuyerID FROM buyer WHERE email = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $BuyerID = $row['BuyerID'];

        // Fetch cart details
        $query = "SELECT cart.ProductID, cart.SellerName, cart.Quantity, cart.ProductName, cart.ProductPrice, cart.ProductImage 
                  FROM cart 
                  WHERE cart.BuyerID = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("i", $BuyerID);
        $stmt->execute();
        $result = $stmt->get_result();

        $orderDetails = [];
        $totalPrice = 0;

        while ($row = $result->fetch_assoc()) {
            $orderDetails[] = $row;
            $totalPrice += $row['ProductPrice'] * $row['Quantity'];
        }

        // Insert order into order_history
        $insertQuery = "INSERT INTO order_history (BuyerID, ProductID, SellerName, ProductName, Price, ProductImage, Quantity, DeliveryAddress) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($insertQuery);

        foreach ($orderDetails as $order) {
            $null = null; // placeholder for ProductImage
            $stmt->bind_param("iisssbis", $BuyerID, $order['ProductID'], $order['SellerName'], $order['ProductName'], $order['ProductPrice'], $null, $order['Quantity'], $selectedAddress);
            $stmt->send_long_data(5, $order['ProductImage']);
            $stmt->execute();
        }

        // Display order details
        echo "<div class='order-details'>";
        foreach ($orderDetails as $order) {
            echo "<div class='order-item'>";
            echo "<img src='data:image/jpeg;base64," . base64_encode($order['ProductImage']) . "' alt='" . $order['ProductName'] . "'>";
            echo "<h2>" . $order['ProductName'] . "</h2>";
            echo "<p>Seller: " . $order['SellerName'] . "</p>";
            echo "<p>Price: ₹" . number_format($order['ProductPrice'], 2) . "</p>";
            echo "<p>Quantity: " . $order['Quantity'] . "</p>";
            echo "</div>";
        }
        echo "</div>";

        // Display success message
        echo "<div class='success-message'>Your order has been placed successfully!</div>";

        // Display total price
        echo "<div class='total-price'>Total Price: ₹" . number_format($totalPrice, 2) . "</div>";

        // Display address details
        echo "<div class='address-details'>Delivery Address: " . htmlspecialchars($selectedAddress) . "</div>";

        // Redirect to home button
        echo "<div class='button-container'>";
        echo "<button onclick=\"window.location.href='buyerhome1.php'\">Continue Shopping</button>";
        echo "</div>";

        // Update product quantities in respective tables based on ProductType
        foreach ($orderDetails as $order) {
            $productName = $order['ProductName'];
            $productType = '';

            // Determine product type
            if (in_array($productName, $fruits)) {
                $productType = 'fruits';
            } elseif (in_array($productName, $vegetables)) {
                $productType = 'vegetables';
            } elseif (in_array($productName, $homemades)) {
                $productType = 'homemades';
            } elseif (in_array($productName, $oils)) {
                $productType = 'oil';
            } elseif (in_array($productName, $powders)) {
                $productType = 'powder';
            } else {
                echo "<div class='error-message'>Error: Invalid product type for $productName.</div>";
                continue; // Skip this product if no matching case
            }

            // Update query based on ProductType
            $updateQuery = "";
            switch ($productType) {
                case 'fruits':
                    $updateQuery = "UPDATE fruits SET Quantity = Quantity - ? WHERE SellerName = ? AND ProductName = ?";
                    break;
                case 'vegetables':
                    $updateQuery = "UPDATE vegetables SET Quantity = Quantity - ? WHERE SellerName = ? AND ProductName = ?";
                    break;
                case 'homemades':
                    $updateQuery = "UPDATE homemade SET Quantity = Quantity - ? WHERE SellerName = ? AND ProductName = ?";
                    break;
                case 'oil':
                    $updateQuery = "UPDATE oil SET Quantity = Quantity - ? WHERE SellerName = ? AND ProductName = ?";
                    break;
                case 'powder':
                    $updateQuery = "UPDATE powder SET Quantity = Quantity - ? WHERE SellerName = ? AND ProductName = ?";
                    break;
                default:
                    continue; // Skip if no valid product type
            }

            // Prepare and execute the update statement
            if (!empty($updateQuery)) {
                $stmt = $con->prepare($updateQuery);
                $stmt->bind_param("iss", $order['Quantity'], $order['SellerName'], $order['ProductName']);
                $stmt->execute();
            }
        }

        // Clear the cart after order placement
        $query = "DELETE FROM cart WHERE BuyerID = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("i", $BuyerID);
        $stmt->execute();

    } else {
        echo "<div class='error-message'>Error: Buyer not found.</div>";
    }

    // Close the database connection
    $stmt->close();
    $con->close();
    ?>
</div>

<h4>Your orders will be delivered within 2 working days.</h4>
<h5>Cancellation is only allowed within 10 minutes of ordering.</h5>

<script>
    function continueShopping() {
        window.location.href = 'buyerhome1.php';
    }
</script>
</body>
</html>