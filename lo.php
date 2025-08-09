<?php
session_start();

// Handle logout if confirmed
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: loginadmin1.php");
    exit();
}

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: loginadmin1.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
            margin: 0;
            font-family: 'Arial', sans-serif;
        }
        .container {
            text-align: center;
            background: #ffffff;
            padding: 50px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 30px;
        }
        .btn-warning {
            background-color: #f0ad4e;
            border-color: #f0ad4e;
            color: white;
            font-size: 18px;
            padding: 10px 20px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        .btn-warning:hover {
            background-color: #ec971f;
            border-color: #ec971f;
        }
        .modal-content {
            border-radius: 10px;
            animation: fadeIn 0.5s;
        }
        .modal-body h3 {
            color: #333;
            margin-bottom: 20px;
        }
        .btn-danger {
            font-size: 16px;
            padding: 10px 20px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        .btn-danger:hover {
            background-color: #c9302c;
            border-color: #ac2925;
        }
        .btn-secondary {
            font-size: 16px;
            padding: 10px 20px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        .btn-secondary:hover {
            background-color: #6c757d;
            border-color: #5a6268;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
    <script>
        $(document).ready(function() {
            $('#logoutButton').click(function() {
                $('#confirmationModal').modal('show');
            });

            $('#confirmLogout').click(function() {
                $('#logoutForm').submit();
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <h1>Welcome to your Dashboard</h1>
        <!-- Logout link -->
        <button id="logoutButton" class="btn btn-warning">Logout</button>
    </div>

    <!-- Logout Confirmation Modal -->
    <div id="confirmationModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h3>Are you sure you want to logout?</h3>
                </div>
                <div class="modal-footer justify-content-center">
                    <form id="logoutForm" method="post" style="display:inline;">
                        <input type="hidden" name="logout" value="1">
                        <button type="button" id="confirmLogout" class="btn btn-danger">Yes, Logout</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
