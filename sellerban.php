<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="AgroHub, Seller Information, agriculture, sellers, contact">
    <title>AgroHub Seller Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('aurora-borealis-3840x2160-16979.jpg');
            background-size: cover;
            background-position: center;
            -webkit-backdrop-filter: blur(5px);
            backdrop-filter: blur(5px);
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }
        header {
            background: rgba(80, 179, 162, 0.8);
            color: #ffffff;
            padding-top: 30px;
            min-height: 70px;
            border-bottom: #e8491d 3px solid;
        }
        header a {
            color: #ffffff;
            text-decoration: none;
            text-transform: uppercase;
            font-size: 16px;
        }
        header ul {
            padding: 0;
            list-style: none;
        }
        header li {
            display: inline;
            padding: 0 20px 0 20px;
        }
        header #branding {
            float: left;
        }
        header #branding h1 {
            margin: 0;
        }
        header nav {
            float: right;
            margin-top: 10px;
        }
        table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: rgba(80, 179, 162, 0.8);
            color: white;
            font-weight: bold;
            text-transform: uppercase;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr.banned {
            background-color: #ffcccc;
        }
        .actions {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
        .actions button {
            padding: 8px 16px;
            font-size: 14px;
            background-color: rgba(80, 179, 162, 0.8);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .actions button:hover {
            background-color: #45a089;
        }
        .actions button.banned {
            background-color: #ccc;
            cursor: not-allowed;
        }
        .actions button.banned:hover {
            background-color: #ccc;
        }
        .actions button.disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
        .actions button.disabled:hover {
            background-color: #ccc;
        }
        .search-box {
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .search-box input, .search-box select {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-right: 10px;
            width: 200px;
            transition: all 0.3s ease;
        }
        .search-box button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: rgba(80, 179, 162, 0.8);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .search-box button:hover {
            background-color: #45a089;
        }
        .confirmation {
            padding: 10px;
            background-color: #dff0d8;
            border: 1px solid #d6e9c6;
            color: #3c763d;
            border-radius: 4px;
            margin-top: 20px;
        }
    </style>
    <script>
        function confirmAction(event, action) {
            if (action === 'ban' && !confirm('Are you sure you want to ban this seller?')) {
                event.preventDefault();
            }
            if (action === 'block' && !confirm('Are you sure you want to block this seller?')) {
                event.preventDefault();
            }
        }
    </script>
</head>
<body>
    <header>
        <div class="container">
            <div id="branding">
                <h1>AgroHub Seller Information</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="menu.html">Home</a></li>
                    <li><a href="bannedlist.php">Banned</a></li>
                    <li><a href="blockedlist.php">Blocked</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="container">
        <div class="search-box">
            <form method="POST" action="">
                <input type="text" name="search" placeholder="Search for sellers..." autofocus>
                <select name="search_by">
                    <option value="SellerName">Seller Name</option>
                    <option value="lname">Last Name</option>
                    <option value="email">Email</option>
                    <option value="address">Address</option>
                </select>
                <button type="submit">Search</button>
            </form>
        </div>
        <?php
        // Database configuration
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

        // Handle the ban request
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ban']) && isset($_POST['seller_id'])) {
            $seller_id = $_POST['seller_id'];

            // Fetch seller name before banning
            $stmt_fetch = $conn->prepare("SELECT SellerName FROM seller WHERE SellerID = ?");
            $stmt_fetch->bind_param("i", $seller_id);
            $stmt_fetch->execute();
            $stmt_fetch->bind_result($seller_name);
            $stmt_fetch->fetch();
            $stmt_fetch->close();

            // Perform ban action
            $stmt_ban = $conn->prepare("UPDATE seller SET is_banned = TRUE WHERE SellerID = ?");
            $stmt_ban->bind_param("i", $seller_id);

            if ($stmt_ban->execute()) {
                echo "<div class='confirmation'>Seller '$seller_name' banned successfully.</div>";
            } else {
                echo "<div class='confirmation'>Error banning seller: " . $stmt_ban->error . "</div>";
            }

            $stmt_ban->close();
        }

        // Handle the block request
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['block']) && isset($_POST['seller_id'])) {
            $seller_id = $_POST['seller_id'];

            // Fetch seller name before blocking
            $stmt_fetch = $conn->prepare("SELECT SellerName FROM seller WHERE SellerID = ?");
            $stmt_fetch->bind_param("i", $seller_id);
            $stmt_fetch->execute();
            $stmt_fetch->bind_result($seller_name);
            $stmt_fetch->fetch();
            $stmt_fetch->close();

            // Perform block action
            $stmt_block = $conn->prepare("UPDATE seller SET block_end_date = DATE_ADD(NOW(), INTERVAL 30 DAY) WHERE SellerID = ?");
            $stmt_block->bind_param("i", $seller_id);

            if ($stmt_block->execute()) {
                echo "<div class='confirmation'>Seller '$seller_name' blocked successfully.</div>";
            } else {
                echo "<div class='confirmation'>Error blocking seller: " . $stmt_block->error . "</div>";
            }

            $stmt_block->close();
        }

        // Modify query if search is performed
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search']) && isset($_POST['search_by'])) {
            $search = $conn->real_escape_string($_POST['search']);
            $search_by = $conn->real_escape_string($_POST['search_by']);
            
            // Check if search string is not empty
            if (!empty($search)) {
                $sql = "SELECT SellerID, SellerName, lname, email, address, is_banned, block_end_date FROM seller WHERE $search_by LIKE '%$search%'";
            } else {
                // If search string is empty, retrieve all sellers
                $sql = "SELECT SellerID, SellerName, lname, email, address, is_banned, block_end_date FROM seller";
            }
        } else {
            // Default query to retrieve all sellers
            $sql = "SELECT SellerID, SellerName, lname, email, address, is_banned, block_end_date FROM seller";
        }

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>Seller Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Actions</th>
                    </tr>";
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                $bannedClass = $row["is_banned"] ? "banned" : "";
                echo "<tr class='$bannedClass'>
                        <td>" . $row["SellerName"]. "</td>
                        <td>" . $row["lname"]. "</td>
                        <td>" . $row["email"]. "</td>
                        <td>" . $row["address"]. "</td>
                        <td>
                            <div class='actions'>";
                            
                // Ban button
                echo "<form method='POST' action='' onsubmit='confirmAction(event, \"ban\")'>
                                    <input type='hidden' name='seller_id' value='" . $row['SellerID'] . "'>
                                    <button type='submit' name='ban'" . ($row["is_banned"] ? " class='banned disabled' disabled" : "") . ">" . ($row["is_banned"] ? "Banned" : "Ban") . "</button>
                                </form>";
                
                // Block button
                echo "<form method='POST' action='' onsubmit='confirmAction(event, \"block\")'>
                                    <input type='hidden' name='seller_id' value='" . $row['SellerID'] . "'>
                                    <button type='submit' name='block'" . ($row["block_end_date"] ? " class='banned disabled' disabled" : "") . ">" . ($row["block_end_date"] ? "Blocked" : "Block") . "</button>
                                </form>";
                
                // Send Notification button (assuming functionality here)
                echo "<button>Send Notification</button>
                            </div>
                        </td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<tr><td colspan='5'>No results found</td></tr>";
        }
        $conn->close();
        ?>
    </div>
</body>
</html>