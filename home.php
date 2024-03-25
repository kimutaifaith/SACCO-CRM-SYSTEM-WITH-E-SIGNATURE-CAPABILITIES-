<?php
session_start();

	date_default_timezone_set("Etc/GMT+8");
	
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
?>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Jitahidi Sacco</title>

    <link href="fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <link rel="icon" type="image/png" href="asset/images/favicon2.jpg">

    <link href="css/sb-admin-2.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">


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
                    <i class="fas fa-copy"></i>
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
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div class="d-flex">
        <h1 class="h5 mb-0 text-gray-1200 mr-3">Welcome, <?php echo $_SESSION['username']; ?></h1>
    </div>
    <div class="text-center">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>
</div>
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Account Overview Section -->
                    <div class="row">

                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="card-title text-primary mb-3">
                                                Account Overview
                                            </div>
                                            <div class="card-text text-gray-800">
                                                <?php
                                                $sql = "SELECT * FROM account_overview WHERE username = '$username'";
                                                $result = $conn->query($sql);
                                                if ($result && $result->num_rows > 0) {
                                                    // Display account overview details if found
                                                    $row = $result->fetch_assoc();
                                                    echo "Account Balance: " . $row['accountBalance'] . "<br>";
                                                    echo "Total Savings: " . $row['totalSavings'] . "<br>";
                                                    echo "Membership Status: " . $row['membershipStatus'];
                                                } else {
                                                    // Display default values if no account overview found
                                                    echo "Account Balance: 0<br>";
                                                    echo "Total Savings: 0<br>";
                                                    echo "Membership Status: 0";
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End of Account Overview Section -->

                    <!-- Graph section -->
                    <div class="card mt-4">
                        <div class="card-body">
                            <h5 class="card-title">Account Balance Over Time</h5>
                            <!-- Chart.js canvas -->
                            <canvas id="accountBalanceChart" width="100" height="80"></canvas>
                        </div>
                    </div>
                    <!-- End of Graph section -->

                    <!-- Other Sections -->
                    <div class="row mt-4">
                        <!-- Active Loans -->
                        <div class="col-xl-4 col-md-4 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Active Loans</div>
                                            <div class="h1 mb-0 font-weight-bold text-gray-800">
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-fw fas fa-comment-dollar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small stretched-link" href="loanhistory.php">View Loan List</a>
                                    <div class="small">
                                        <i class="fa fa-angle-right"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payments Today -->
                        <div class="col-xl-4 col-md-4 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Payments Today</div>
                                            <div class="h1 mb-0 font-weight-bold text-gray-800">
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-fw fas fa-coins fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small stretched-link" href="payment.php">View Payments</a>
                                    <div class="small">
                                        <i class="fa fa-angle-right"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Loan Application -->
                        <div class="col-xl-4 col-md-4 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Loan
                                                Application
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h1 mb-0 mr-3 font-weight-bold text-gray-800">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-fw fas fa-book fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small stretched-link" href="loanapplication.php">Apply for a loan
                                        today!!</a>
                                    <div class="small">
                                        <i class="fa fa-angle-right"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End of Other Sections -->
                </div>
                <!-- End of Container Fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="stocky-footer">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Jitahidi Sacco <?php echo date("Y") ?></span>
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

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white">System Information</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Are you sure you want to logout?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-danger" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="asset/js/jquery.js"></script>
    <script src="asset/js/bootstrap.bundle.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="asset/js/jquery.easing.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="asset/js/sb-admin-2.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.querySelector('.dropdown').addEventListener('show.bs.dropdown', function () {
            var dropdownMenu = this.querySelector('.dropdown-menu');
            var rect = dropdownMenu.getBoundingClientRect();
            var isEntirelyVisible = (rect.top >= 0) && (rect.bottom <= window.innerHeight);

            if (!isEntirelyVisible) {
                dropdownMenu.classList.add('dropdown-menu-left');
            }
        });
    </script>

    <script>
        // Dummy data for the chart (replace with actual data)
        const dates = ['2024-01-01', '2024-01-02', '2024-01-03', '2024-01-04', '2024-01-05'];
        const accountBalances = [5000, 5200, 4800, 5500, 5300];

        // Create chart
        const ctx = document.getElementById('accountBalanceChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Account Balance',
                    data: accountBalances,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html>