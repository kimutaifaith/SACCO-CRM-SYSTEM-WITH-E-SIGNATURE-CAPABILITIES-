<?php
session_start();

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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Retrieve current password from the database
    $username = $_SESSION['username'];
    $sql = "SELECT password FROM user_info WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row1 = $result->fetch_assoc();
        $db_password_hash = $row1['password'];

        // Verify if the current password matches the one in the database
        if (md5($current_password) == $db_password_hash) {
            // Check if the new password and confirm password match
            if ($new_password == $confirm_password) {
                // Update the password in the database
                $hashed_password = md5($new_password);
                $update_sql = "UPDATE user_info SET password = '$hashed_password' WHERE username = '$username'";
                if ($conn->query($update_sql) === TRUE) {
                    // Password updated successfully
                    echo '<script>alert("Password changed successfully."); window.location.href = "customer.php";</script>';
                    exit();
                } else {
                    // Error updating password
                    echo "Error updating password: " . $conn->error;
                }
            } else {
                echo '<script>alert("New password and confirm password do not match.");</script>';
            }
        } else {
            echo '<script>alert("Incorrect current password.");</script>';
        }
    } else {
        echo "User not found.";
    }
}

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
    <link href="css/sb-admin-2.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="asset/images/favicon2.jpg">

    

</head>

<body id="page-top">
<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
            <div class="sidebar-brand-text mx-3">ADMIN PANEL</div>
        </a>


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
            <div class="container1">
    <h2>Change Password</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="current_password">Current Password:</label>
        <input type="password" id="current_password" name="current_password" required><br>
        
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required><br>
        
        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br>
        
        <input type="submit" value="Change Password">
    </form>
</div>
<!-- Footer -->
<footer class="stocky-footer">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Jitahidi Sacco <?php echo date("Y")?></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
	
</body>
</html>