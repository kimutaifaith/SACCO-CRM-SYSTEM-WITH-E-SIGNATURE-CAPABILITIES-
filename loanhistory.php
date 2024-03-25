<?php
session_start();
$conn = new mysqli("localhost", "root", "", "sacco");

// Check if member is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if member is not logged in
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

// Prepare SQL query to retrieve user information based on username
$sql_user = "SELECT * FROM user_info WHERE username = '$username'";

// Execute the query to retrieve user information
$result_user = $conn->query($sql_user);

// Check if the query executed successfully
if ($result_user === false) {
    die("Error executing query: " . $conn->error);
}

// Check if a row is returned
if ($result_user->num_rows > 0) {
    // Fetch the first row (assuming there is only one row for each username)
    $row_user = $result_user->fetch_assoc();
} else {
    echo "User account not found.";
}

date_default_timezone_set("Etc/GMT+8");

// Get member ID from session
$username = $_SESSION['username'];

// SQL query to retrieve loan details for the logged-in user
$sql_loan = "SELECT * FROM final_loan WHERE memberName = ?";
// Prepare the SQL statement
$stmt = $conn->prepare($sql_loan);
// Bind parameters
$stmt->bind_param("s", $username);
// Execute the query
$stmt->execute();
// Get result set
$result_loan = $stmt->get_result();
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
   <style>
        
    
        h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
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
                    <i class="fas fa-calculator"></i>
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
                                <span class="mr-2 d-none d-lg-inline text-gray-800 small"><?= $row_user['username'] ?></span>
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
    <h2>Loan Details</h2>
    <table>
        <tr>
            <th>loanID</th>
            <th>Loan Amount</th>
            <th>Loan Term</th>
            <th>Interest Rate</th>
            <th>Guarantor Name 1</th>
            <th>Reason for Loan</th>
            <th>Loan Status</th>
            <!-- Add more table headers if needed -->
        </tr>
        <?php
        if ($result_loan->num_rows > 0) {
            // Output data of each row
            while ($row_loan = $result_loan->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row_loan["loanID"] . "</td>";            
                echo "<td>" . $row_loan["loanAmount"] . "</td>";
                echo "<td>" . $row_loan["loanTerm"] . " months</td>";
                echo "<td>" . $row_loan["interestRate"] . "%</td>";
                echo "<td>" . $row_loan["guarantor_name"] . "</td>";
                
                echo "<td>" . $row_loan["reason"] . "</td>";
                $loanStatus = !empty($row_loan["loanStatus"]) ? $row_loan["loanStatus"] : "Pending";
                echo "<td>" . $row_loan["loanStatus"] . "</td>"; // Display Loan Status
                
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No loan details found.</td></tr>";
        }
        ?>
    </table>
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
								
							