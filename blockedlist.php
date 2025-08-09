<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Seller Information</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f0f0f0;
        padding: 20px;
        background-image: url('desert-isolated-3840x2160-16931.jpg'); /* Replace with your desired background image path */
        background-size: cover;
        background-position: center;
    }
    h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
        text-transform: uppercase;
        letter-spacing: 2px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    table, th, td {
        border: 1px solid #ccc;
    }
    th {
        background-color: #f2f2f2;
        text-transform: uppercase;
    }
    tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    tr:hover {
        background-color: #e0e0e0;
    }
</style>
</head>
<body>

<h2>Blocked Users</h2>

<table id="sellerTable">
    <thead>
        <tr>
            <th>Seller Name</th>
            <th>Email</th>
            <th>Phone Number</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // PHP code to fetch data from MySQL database
        $servername = "localhost";
        $username = "root"; // Replace with your MySQL username
        $password = ""; // Replace with your MySQL password
        $dbname = "ahproject";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL query to fetch data from Seller table
        $sql = "SELECT SellerName, email, phonenumber FROM Seller WHERE block_end_date IS NOT NULL";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["SellerName"] . "</td>";
                echo "<td>" . $row["email"] . "</td>";
                echo "<td>" . $row["phonenumber"] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No sellers found.</td></tr>";
        }
        $conn->close();
        ?>
    </tbody>
</table>

</body>
</html>
