<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ahproject";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle notification storage when the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buyerID']) && isset($_POST['message'])) {
    $buyerID = $_POST['buyerID'];
    $message = $_POST['message'];

    // Insert notification into notifications table
    $sql = "INSERT INTO notifications (BuyerId, message, isread, timestamp) 
            VALUES ('$buyerID', '$message', 0, NOW())";

    if ($conn->query($sql) === TRUE) {
        echo "Notification stored successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    exit(); // Stop further execution after handling the POST request
}

// SQL query to select all rows from the buyer table
$sql = "SELECT BuyerID, fname, lname, email, phonenumber FROM buyer";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyer List</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
            background-image: url('desert-isolated-3840x2160-16931.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .container {
            width: 90%;
            margin: 0 auto;
        }
        .search-container {
            text-align: center;
            margin-top: 20px;
        }
        .search-container input[type=text] {
            width: 50%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: rgba(255, 255, 255, 0.8);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            overflow: hidden;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
            position: sticky;
            top: 0;
            z-index: 2;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        tr:hover td {
            background-color: #ffeb3b;
            color: #333;
        }
        .btn {
            padding: 6px 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s, opacity 0.2s;
            animation: none;
        }
        .btn:hover {
            background-color: #45a049;
        }
        @keyframes send-notification {
            0% { transform: scale(1); background-color: #4CAF50; }
            25% { transform: scale(1.1); background-color: #66BB6A; }
            50% { transform: scale(1); background-color: #4CAF50; }
            75% { transform: scale(1.05); background-color: #66BB6A; }
            100% { transform: scale(1); background-color: #4CAF50; }
        }
    </style>
    <script>
        function filterTable() {
            const input = document.getElementById("searchInput");
            const filter = input.value.toLowerCase();
            const table = document.getElementById("buyersTable");
            const tr = table.getElementsByTagName("tr");

            for (let i = 1; i < tr.length; i++) {
                tr[i].style.display = "none";
                const td = tr[i].getElementsByTagName("td");
                for (let j = 0; j < td.length; j++) {
                    if (td[j]) {
                        if (td[j].innerHTML.toLowerCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                            break;
                        }
                    }
                }
            }
        }

        function sendNotification(buyerID) {
            const btn = document.getElementById("btn-" + buyerID);
            btn.style.animation = "send-notification 1s ease";
            setTimeout(function() {
                btn.style.animation = "none";
            }, 1000);

            // AJAX request to store the notification in the database
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "", true);  // Empty URL since this script handles both display and notification storage
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    alert(xhr.responseText);  // Show server response
                }
            };
            xhr.send("buyerID=" + buyerID + "&message=" + encodeURIComponent("The complaint has been received and appropriate action will be taken within 15 days."));
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Buyer List</h1>
        <div class="search-container">
            <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Search for buyers..">
        </div>
        <table id="buyersTable">
            <thead>
                <tr>
                    <th>BuyerID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row["BuyerID"]. "</td>
                                <td>" . $row["fname"]. "</td>
                                <td>" . $row["lname"]. "</td>
                                <td>" . $row["email"]. "</td>
                                <td>" . $row["phonenumber"]. "</td>
                                <td><button id='btn-" . $row["BuyerID"] . "' class='btn' onclick='sendNotification(" . $row["BuyerID"] . ")'>Send Notification</button></td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' style='text-align: center;'>No results found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php $conn->close(); ?>