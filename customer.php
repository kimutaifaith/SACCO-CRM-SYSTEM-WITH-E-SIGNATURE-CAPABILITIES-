<?php
session_start();
date_default_timezone_set("Etc/GMT+8");
	// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

    $conn = new mysqli("localhost", "root", "", "sacco");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $username = $_SESSION['username'];

        // Prepare SQL query to retrieve user information based on username
        $sql = "SELECT * FROM user_info WHERE username = '$username'"; // Modify the table and column names as per your database schema
        
        // Execute the query
        $result3 = $conn->query($sql);
        
        // Check if the query executed successfully
        if ($result3 === false) {
            die("Error executing query: " . $conn->error);
        }
        
        // Check if a row is returned
        if ($result3->num_rows > 0) {
                        // Fetch the first row (assuming there is only one row for each username)
            $row = $result3->fetch_assoc();
        } else {
            echo "User account not found.";
        }
// Assuming you have a function to retrieve user information based on username

// Function to retrieve user information from the database



    // Close database connection
$conn->close();


?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Jitahidi Sacco</title>

    <link href="fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="icon" type="image/png" href="asset/images/favicon2.jpg">
   
    <link href="css/sb-admin-2.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .profile-section {
            margin-bottom: 20px;
            border: 1px solid #ccc;
            padding: 10px;
            background-color: #fff;
        }

        .profile-section h2 {
            margin-top: 0;
        }

        .profile-section button {
            margin-right: 10px;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .profile-section button:hover {
            background-color: #0056b3;
        }
    </style>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
                <div class="sidebar-brand-text mx-3">Menu Dashboard</div>
            </a>


            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="home.php">
                    <i class="fas fa-fw fa-home"></i>
                    <span>Home</span></a>
            </li>
			<li class="nav-item">
                <a class="nav-link" href="customer.php">
                    <i class="fas fa-user"></i>
                    <span class="align-middle" style="color:white">Personal Profile</span></a>
            </li>

			<li class="nav-item">
                <a class="nav-link" href="loanapplication.php">
                 <i class="fas fa-copy" ></i>
                    <span class="align-middle" style="color:white">Loan Application</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="gurantor.php">
                    <i class="fas fa-file"></i>
                    <span class="align-middle" style="color:white">Guarantors</span></a>
            </li>

			<li class="nav-item">
                <a class="nav-link" href="loanhistory.php">
                    <i class="fas fa-file"></i>
                    <span class="align-middle" style="color:white">Loan History</span></a>
            </li>

			<li class="nav-item">
                <a class="nav-link" href="calculator.php">
                    <i class="fas fa-file"></i>
                    <span class="align-middle" style="color:white">Calculator</span></a>
            </li>

			<li class="nav-item">
                <a class="nav-link" href="payment.php">
                    <i class="fas fa-credit-card"></i>
                    <span class="align-middle" style="color:white">Payments</span></a>
            </li>

            </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
	
                   
					<!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-800 small"><?= $row['username'] ?></span>
                                <img class="img-profile rounded-circle"
                                    src="asset/images/image/admin_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                
                <div class="container">  
                <?php if (isset($row) && is_array($row)): ?>
              <div class="profile-section">  
                     <h2 >Personal Profile</h2>
                     <p>Full Name: <?php echo $row['full_name']; ?></p>
        <p>Username: <?php echo $row['username']; ?></p>
        <p>Email: <?php echo $row['email']; ?></p>
        <p>Date of Birth: <?php echo $row['dob']; ?></p>
    </div>

    <div class="profile-section">
        <h2>Contact Information</h2>
        <p>Contact Number: <?php echo $row['contact_number']; ?></p>
    </div>

    <div class="profile-section">
        <h2>Account Details</h2>
        <p>Account Number: <?php echo $row['account_number']; ?></p>
        <p>Account Type: <?php echo $row['account_type']; ?></p>
    </div>
    <?php else: ?>
        <p>No user information available.</p>
    <?php endif; ?>
        <!-- Action Buttons Section -->
        <div class="profile-section">
            
            <button onclick="window.location.href = 'edit_profile.php';">Edit Profile</button>
            <button onclick="window.location.href = 'change_password.php';">Change Password</button>
            <button onclick="window.location.href = 'logout.php';">Logout</button>
        </div>
    </div>
</body>
</html>