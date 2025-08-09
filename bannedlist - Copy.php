<?php
// Database connection parameters
$servername = "localhost"; // Assuming MySQL server is on the same machine
$username = "root"; // MySQL username
$password = ""; // MySQL password
$dbname = "ahproject"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch SellerName, Email, PhoneNumber where is_banned = 1
$sql = "SELECT SellerName, Email, PhoneNumber FROM seller WHERE is_banned = 1";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banned Users</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            background-image: url('wallhaven-l8pr8q.png'); /* Replace with your image URL */
            background-size: cover;
            background-position: center;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 20px auto;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
            font-size: 28px;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #333;
            text-transform: uppercase;
        }
        table td {
            color: #666;
        }
        table tr {
            transition: background-color 0.3s ease;
        }
        table tr:hover {
            background-color: rgba(255, 255, 255, 0.6);
            transform: scale(1.02);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            cursor: pointer;
        }
        .back-btn {
            display: inline-block;
            margin: 20px 0;
            padding: 10px 15px;
            text-align: center;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: bold;
        }
        .back-btn:hover {
            background-color: #45a049;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('table tbody tr');

            rows.forEach(row => {
                row.addEventListener('mouseover', function() {
                    this.style.backgroundColor = '#f9f9f9';
                });

                row.addEventListener('mouseout', function() {
                    this.style.backgroundColor = '';
                });
            });
        });
    </script>
</head>
<body>

<div class="container">

    <h2>Banned Users</h2>

    <table>
        <thead>
            <tr>
                <th>Seller Name</th>
                <th>Email</th>
                <th>Phone Number</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Check if any rows were returned
            if ($result->num_rows > 0) {
                // Output data of each row
                $count = 0;
                while($row = $result->fetch_assoc()) {
                    $count++;
                    $class = ($count % 2 == 0) ? 'alt-bg' : ''; // Apply alternate background color
                    echo "<tr class='$class'>";
                    echo "<td>" . htmlspecialchars($row["SellerName"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["Email"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["PhoneNumber"]) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>0 results</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <a href="sellerban.php" class="back-btn">Back to Home</a>

</div>

<?php
// Close the connection
$conn->close();
?>

</body>
</html>
