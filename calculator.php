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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Repayment Calculator</title>
    <link href="fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  
   
    <link href="css/sb-admin-2.css" rel="stylesheet">
    
	<!-- Custom styles for this page -->
    <link href="css/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="css/select2.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="asset/images/favicon2.jpg">

</head>
<body>
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
    <h2>Loan Repayment Calculator</h2>
    <form id="loanForm">
        <label for="loanAmount">Loan Amount:</label>
        <input type="number" id="loanAmount" name="loanAmount" required><br><br>

        <label for="interestRate">Interest Rate:</label>
        <input type="number" id="interestRate" name="interestRate" required><br><br>

        <label for="loanTerm">Loan Term (months):</label>
        <input type="number" id="loanTerm" name="loanTerm" required><br><br>

        <!-- Input area to display monthly repayment (read-only) -->
        <label for="monthlyRepayment">Monthly Repayment:</label>
        <input type="text" id="monthlyRepayment" name="monthlyRepayment" readonly><br><br>

        <button type="button" id="calculateBtn">Calculate</button>
    </form>

    <script>
        // Function to calculate monthly repayment amount
        function calculateMonthlyRepayment(loanAmount, interestRate, loanTermMonths) {
            // Convert annual interest rate to monthly interest rate
            var monthlyInterestRate = interestRate / 100 / 12;

            // Calculate monthly repayment amount using the formula for an amortizing loan
            var monthlyRepayment = (loanAmount * monthlyInterestRate) / (1 - Math.pow(1 + monthlyInterestRate, -loanTermMonths));

            // Round the monthly repayment amount to two decimal places
            monthlyRepayment = Math.round(monthlyRepayment * 100) / 100;

            return monthlyRepayment;
        }

        // Function to update the monthly repayment input field
        function updateMonthlyRepayment() {
            var loanAmount = parseFloat(document.getElementById('loanAmount').value);
            var interestRate = parseFloat(document.getElementById('interestRate').value);
            var loanTermMonths = parseFloat(document.getElementById('loanTerm').value);

            var monthlyRepayment = calculateMonthlyRepayment(loanAmount, interestRate, loanTermMonths);

            // Update the value of the monthly repayment input field
            document.getElementById('monthlyRepayment').value = monthlyRepayment.toFixed(2);
        }

        // Add event listener to the Calculate button
        document.getElementById('calculateBtn').addEventListener('click', updateMonthlyRepayment);
    </script>
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
