<?php
session_start();



// Check if the loan application data is stored in the session
if (!isset($_SESSION['loan_application'])) {
    header("Location: loanapplication.php"); // Redirect to the loan application page if no data found
    exit();
}

$loanData = $_SESSION['loan_application'];

// Include your database connection code here
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

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the loan details from the session
$loanID = $loanData['loanID'];
$memberName = $loanData['memberName'];
$memberID = $loanData['memberID'];
$accountNumber = $loanData['accountNumber'];
$loanAmount = $loanData['loanAmount'];
$loanTerm = $loanData['loanTerm'];
$interestRate = $loanData['interestRate'];
$guarantor_name = $loanData['guarantor_name'];
$reason = $loanData['reason'];

// Check if the guarantor has added the guarantee amount
$guaranteeAmount = "Pending";
$checkGuaranteeAmountQuery = "SELECT guarantee_amount FROM guarantor WHERE loanID = '$loanID' AND guarantor_name = '$guarantor_name'";
$guaranteeAmountResult = $conn->query($checkGuaranteeAmountQuery);

if ($guaranteeAmountResult->num_rows > 0) {
    $guaranteeAmountRow = $guaranteeAmountResult->fetch_assoc();
    $guaranteeAmount = $guaranteeAmountRow['guarantee_amount'];
}


if (isset($_POST['submitLoan'])) {
// Assuming $conn is your database connection
$checkGuarantor1 = "SELECT * FROM employee_sign WHERE guarantor_name = '{$loanData['guarantor_name']}' AND signature_img IS NOT NULL LIMIT 1";

$guarantorResult1 = mysqli_query($conn, $checkGuarantor1);

if ($guarantorResult1 && mysqli_num_rows($guarantorResult1) > 0 ) {
   

    // Save the loan form details to the database
    $query = "INSERT into final_loan (loanID, memberName, memberID, accountNumber, loanAmount, loanTerm, interestRate, guarantor_name,  reason)
    VALUES ('$loanID', '$memberName', '$memberID', '$accountNumber', '$loanAmount', '$loanTerm', '$interestRate', '$guarantor_name', '$reason')";

    $result1 = mysqli_query($conn, $query);

    if ($result1) {
        // Loan application submitted successfully, redirect to loansummary.php
        echo '<script>alert("Loan application submitted successfully!"); window.location.href = "home.php";</script>';
    } else {
        // Failed to save the loan application
        echo '<script>alert("Error in saving the loan application. Please try again.");</script>';
    }
} else {
    // Guarantor name or signature_img is missing, ask the user to verify guarantor
    echo '<script>alert("Please verify your guarantors.");</script>';
}

// Close the database connection
$conn->close();
}

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
    <link rel="icon" type="image/png" href="asset/images/favicon2.jpg">

    

</head>

<body id="page-top">
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
        <h2>Loan Summary:</h2>

        <table class="table">
            
            <tbody>
            <tr>
                    <td>Loan ID</td>
                    <td><?php echo $loanID; ?></td>
                </tr>
                <tr>
                    <td>Member Name</td>
                    <td><?php echo $memberName; ?></td>
                </tr>
                <tr>
                    <td>Member ID</td>
                    <td><?php echo $memberID; ?></td>
                </tr>
                <!-- Add other loan details here -->
                <tr>
                    <td>Account Number</td>
                    <td><?php echo $accountNumber; ?></td>
                </tr>

                <tr>
                    <td>Loan Amount</td>
                    <td><?php echo $loanAmount; ?></td>
                </tr>

                <tr>
                    <td>Loan Term</td>
                    <td><?php echo $loanTerm; ?></td>
                </tr>
                <tr>
                    <td>Interest Rate</td>
                    <td><?php echo $interestRate; ?></td>
                </tr>
                <tr>
                <td>Guarantor 1</td>
                <td><?php echo $guarantor_name; ?></td>
            </tr>
            <tr>
                                <td>Guarantee Amount</td>
                                <td><?php echo $guaranteeAmount; ?></td>
                            </tr>
            <tr>
                <td>Reason</td>
                <td><?php echo $reason; ?></td>
            </tr>
            </tbody>
        </table>

        <form method="post" action="">
<!-- Button for submitting the loan -->
<button type="submit" name="submitLoan">Submit Loan</button>
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
