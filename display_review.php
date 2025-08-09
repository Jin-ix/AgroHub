<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews with Star Rating Less Than 2</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h2 {
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .reviews-table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border-radius: 10px;
            background-color: #fff;
        }
        .reviews-table th, .reviews-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .reviews-table th {
            background-color: #f2f2f2;
            color: #333;
            font-weight: bold;
        }
        .reviews-table td img {
            max-width: 100px;
            border-radius: 5px;
        }
        .no-reviews {
            text-align: center;
            color: #777;
            font-style: italic;
        }
        .animate-slide-in {
            animation: slide-in 0.5s ease-out;
        }
        @keyframes slide-in {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .return-home-btn {
            display: block;
            width: 150px;
            margin: 20px auto;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            text-align: center;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
        }
        .return-home-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Reviews with Star Rating Less Than 2</h2>

    <?php
    // Include the connection file
    include('connection.php');

    // Fetch data from the database where star rating is less than 2
    $sql = "SELECT SellerName, star, review, image FROM product_review WHERE star < 2";
    $result = mysqli_query($con, $sql);

    // Check if there are any rows returned
    if (mysqli_num_rows($result) > 0) {
        echo "<table class='reviews-table'>";
        echo "<tr>";
        echo "<th>Seller Name</th>";
        echo "<th>Star</th>";
        echo "<th>Review</th>";
        echo "<th>Image</th>";
        echo "</tr>";

        // Output data of each row
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr class='animate-slide-in'>";
            echo "<td>" . $row['SellerName'] . "</td>";
            echo "<td>" . $row['star'] . "</td>";
            echo "<td>" . $row['review'] . "</td>";
            // Assuming the image column is a BLOB, we can save it as a file and then display it
            $imageData = $row['image'];
            if ($imageData) {
                $imageName = 'image_' . uniqid() . '.jpg'; // Or any appropriate extension
                file_put_contents($imageName, $imageData);
                echo "<td><img src='$imageName' alt='Review Image'></td>";
            } else {
                echo "<td>No Image</td>";
            }
            echo "</tr>";
        }

        echo "</table>";

        // Check for repetitive seller names and remove if necessary
        $sellerCount = array(); // Array to store seller counts
        mysqli_data_seek($result, 0); // Reset result pointer
        while ($row = mysqli_fetch_assoc($result)) {
            $sellerName = $row['SellerName'];
            // If the seller name exists in the array, increment the count
            if (array_key_exists($sellerName, $sellerCount)) {
                $sellerCount[$sellerName]++;
            } else {
                $sellerCount[$sellerName] = 1; // Initialize count to 1
            }
        }

        // Loop through the seller counts
        foreach ($sellerCount as $sellerName => $count) {
            // If the count is greater than or equal to 6, remove the user
            if ($count >= 6) {
                // Remove user from the review table
                $removeSql = "DELETE FROM product_review WHERE SellerName='$sellerName'";
                if (mysqli_query($con, $removeSql)) {
                    echo "<p class='no-reviews'>Seller '$sellerName' removed successfully due to repetitive reviews.</p>";
                } else {
                    echo "<p class='no-reviews'>Error removing seller '$sellerName'.</p>";
                }
            }
        }
    } else {
        echo "<p class='no-reviews'>No reviews found with star rating less than 2.</p>";
    }

    // Close the database connection
    mysqli_close($con);
    ?>

    <button class="return-home-btn" onclick="window.location.href='menu.html'">Return Home</button>
</div>

<script>
    function fetchImage(imageName) {
        // Implement your logic to fetch and display the image here
        alert("Image fetching functionality will be implemented here.");
    }
</script>

</body>
</html>