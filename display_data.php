<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('connection.php'); // Include your database connection file

// Initialize variables to avoid undefined variable warnings
$complaints = array(); 
$sellerCounts = array(); 

// Retrieve data from the database
try {
    // Prepare SQL query (correct column names)
    $sql = "SELECT customerName, contactInfo, orderID, complaintCategory, complaintDescription, attachments FROM process_complaint";
    $stmt = $con->prepare($sql);

    if ($stmt === false) {
        throw new Exception('Failed to prepare SQL statement');
    }

    $stmt->execute();

    // Bind variables to the result set
    $stmt->bind_result($customerName, $contactInfo, $orderID, $complaintCategory, $complaintDescription, $attachments);

    // Fetch data and store it in an array
    while ($stmt->fetch()) {
        $complaints[] = array(
            'customerName' => $customerName,
            'contactInfo' => $contactInfo,
            'orderID' => $orderID,
            'complaintCategory' => $complaintCategory,
            'complaintDescription' => $complaintDescription,
            'attachments' => $attachments
        );

        // Count the occurrences of each customer name (was previously seller name)
        if (isset($sellerCounts[$customerName])) {
            $sellerCounts[$customerName]++;
        } else {
            $sellerCounts[$customerName] = 1;
        }
    }

    $stmt->close();

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

// Find customer names with 5 or more complaints
$repeatOffenders = array();
foreach ($sellerCounts as $name => $count) {
    if ($count >= 5) {
        $repeatOffenders[] = $name;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaints List</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Roboto', sans-serif;
        }
        h2 {
            text-align: center;
            margin-top: 20px;
            color: #343a40;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
        }
        .complaint-table {
            width: 90%;
            margin: 20px auto;
            background-color: #fff;
            border-collapse: collapse;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .complaint-table th, .complaint-table td {
            text-align: center;
            padding: 15px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
        }
        .complaint-table th {
            background-color: #007bff;
            color: #fff;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .complaint-table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .complaint-table tbody tr:hover {
            background-color: #e9ecef;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn {
            margin: 5px;
            font-family: 'Poppins', sans-serif;
            transition: transform 0.2s ease;
        }
        .btn:hover {
            transform: scale(1.05);
        }
        #getStartedBtn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        #getStartedBtn:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
        .alert-box {
            width: 90%;
            margin: 20px auto;
            background-color: #ffcccc;
            border: 1px solid #ff6666;
            padding: 15px;
            border-radius: 5px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            color: #cc0000;
        }
        .modal img {
            max-width: 100%;
            height: auto;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</head>
<body>

    <h2>Complaints List</h2>

    <?php if (!empty($repeatOffenders)): ?>
        <div class="alert-box">
            <strong>Repeated Offences Detected:</strong>
            <ul>
                <?php foreach ($repeatOffenders as $offender): ?>
                    <li><?= htmlspecialchars($offender) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <table class="complaint-table table table-bordered">
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Contact Info</th>
                <th>Order ID</th>
                <th>Complaint Category</th>
                <th>Complaint Description</th>
                <th>Attachments</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($complaints)) : ?>
                <?php foreach ($complaints as $complaint) : ?>
                    <tr>
                        <td><?= htmlspecialchars($complaint['customerName']) ?></td>
                        <td><?= htmlspecialchars($complaint['contactInfo']) ?></td>
                        <td><a href="orderDetails.php?orderID=<?= htmlspecialchars($complaint['orderID']) ?>"><?= htmlspecialchars($complaint['orderID']) ?></a></td>
                        <td><?= htmlspecialchars($complaint['complaintCategory']) ?></td>
                        <td><?= htmlspecialchars($complaint['complaintDescription']) ?></td>
                        <td>
                            <?php if (!empty($complaint['attachments'])): ?>
                                <a href="#" class="view-image-link" data-toggle="modal" data-target="#imageModal" data-image="<?= base64_encode($complaint['attachments']) ?>">View Image</a>
                            <?php else: ?>
                                No Attachment
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr><td colspan="6">No complaints found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <center><a href="menu.html" id="getStartedBtn">Return Home</a></center>

    <!-- Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Attachment Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img id="modalImage" src="" class="img-fluid" alt="Attachment Image">
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).on('click', '.view-image-link', function() {
            var imageUrl = $(this).data('image');
            $('#modalImage').attr('src', 'data:image/jpeg;base64,' + imageUrl); // Adjust MIME type as per your image format
        });
    </script>

</body>
</html>