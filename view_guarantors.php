<?php
session_start();
$conn = new mysqli("localhost", "root", "", "sacco");

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
// Retrieve loan ID, guarantor name, and guarantee amount from URL parameters
$loanID = $_GET['loanID'];
$guarantorName = $_GET['guarantorName'];
$guaranteeAmount = $_GET['guaranteeAmount'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Guarantor Details</title>
    <!-- Add your CSS links here -->
    <link href="fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="asset/images/favicon2.jpg">
    <link rel="icon" type="image/png" href="asset/images/favicon2.jpg">
</head>

<<body id="page-top">
    <div id="wrapper">
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
                <div class="sidebar-brand-text mx-3">Menu Dashboard</div>
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

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Guarantor Details</h1>
                    <!-- Content Row -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Details for Loan ID: <?php echo $loanID; ?></h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Loan ID</th>
                                                    <th>Guarantor Name</th>
                                                    <th>Guarantee Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><?php echo $loanID; ?></td>
                                                    <td><?php echo $guarantorName; ?></td>
                                                    <td><?php echo $guaranteeAmount; ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->
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


</html>
