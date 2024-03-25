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
	 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
    
    
    // Check if the loan form submit button is clicked
    if (isset($_POST['submitLoanButton'])) {
        // User submitted the loan form
        $memberName = $_POST['memberName'];
        $memberID = $_POST['memberID'];
        $accountNumber = $_POST['accountNumber'];
        $loanAmount = $_POST['loanAmount'];
        $loanTerm = $_POST['loanTerm'];
        $interestRate = $_POST['interestRate'];
        $guarantor_name = $_POST['guarantor_name'];
        $reason = $_POST['reason'];

// Check if the guarantor's name exists in the database
$check_guarantor_query = "SELECT * FROM user_info WHERE full_name = '$guarantor_name'";
$check_guarantor_result = $conn->query($check_guarantor_query);

if ($check_guarantor_result->num_rows > 0) {
    // Guarantor found in the database, proceed with saving loan details
        
// Generate a unique Loan ID
$loanID = uniqid('LN_');

        // Save the loan form details to the session
        $_SESSION['loan_application'] = [
            'loanID' => $loanID,
            'memberName' => $memberName,
            'memberID' => $memberID,
            'accountNumber' => $accountNumber,
            'loanAmount' => $loanAmount,
            'loanTerm' => $loanTerm,
            'interestRate' => $interestRate,
            'guarantor_name' =>$guarantor_name,
            'reason' => $reason,
        ];

            // Save the loan form details to the database
        $stmt = $conn->prepare("INSERT INTO loanform (loanID, memberName, memberID, accountNumber, loanAmount, loanTerm, interestRate, guarantor_name,  reason)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $loanID, $memberName, $memberID, $accountNumber, $loanAmount, $loanTerm, $interestRate, $guarantor_name, $reason);
    $stmt->execute();
    $stmt->close();

    // Redirect to the guarantor page
    header('Location: sms_email.php');
    exit;
} else {
    // Guarantor not found in the database
    echo "<script>alert('User not found. Please enter a member in the Sacco.');</script>";}
}

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
                    <i class="fas fa-file"></i>
                    <span class="align-middle" style="color:white">Calculator</span></a>
            </li>

			<li class="nav-item">
                <a class="nav-link" href="payment.php">
                    <i class="fas fa-credit-card"></i>
                    <span class="align-middle" style="color:white">Payments</span></a>
            </li>

        </ul>
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

                <div class="content-wrapper">
                    <section class="content">
                        <div class="main-content">
                            <div id="LoanFormSection">
                            <form class="form-horizontal" action="<?= $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data" role="form">
                                
                            <h3 class="text-center well w3-text-blue">LOAN APPLICATION & AGREEMENT FORM</h3>
                                                        

                            <div class="form-group">
                                <label class="col-sm-3" for="form-field-1-1"> Member Name: </label>

                                <div class="col-sm-9">
                                <input type="text" class="form-control" name="memberName" required>
                                </div>
                            </div>

                            <div class="space-4"></div>

                            <div class="form-group">
                                <label class="col-sm-3" for="form-field-1-1"> Membership ID: </label>
                            
                                <div class="col-sm-9">
                                <input type="text" class="form-control" name="memberID" required>
                                </div>
                            </div>

                            <div class="space-4"></div>

                            <div class="form-group">
                                <label class="col-sm-3" for="form-field-1-1"> Account Number: </label>

                                <div class="col-sm-9">
                                <input type="text" class="form-control" name="accountNumber" required>
                                </div>
                            </div>

                            <div class="space-4"></div>

                            <div class="form-group">
                                <label class="col-sm-3" for="form-field-1-1">Loan Amount: </label>

                                <div class="col-sm-9">
                                    <input type="text" id="form-field-1-1" name="loanAmount" pattern="[0-9]+" title="Months (example 1)" placeholder="Amount of loan needed(Integers)"class="form-control" />
                                </div>
                            </div>

                            <div class="space-4"></div>

                            <div class="form-group">
                                <label class="col-sm-3" for="form-field-1-1">Repayment Period: </label>

                                <div class="col-sm-9">
                                    <input type="text" id="form-field-1-1" name="loanTerm" pattern="[0-9]+" title="Integer numbers only" placeholder="Period of the loan(Months)"class="form-control" />
                                </div>
                            </div>
                            <div class="space-4"></div>

                            <div class="form-group">
                                <label class="col-sm-3" for="form-field-1-1">Interest Rate: </label>

                                <div class="col-sm-9">
                                    <input type="text" id="form-field-1-1" name="interestRate" readonly value="5 % Per Month" class="form-control" />
                                </div>
                            </div>
                            <div class="space-4"></div>

                            
                            <div class="space-4"></div>

                            <div class="form-group">
                                <label class="col-sm-3" for="form-field-1-1"> Guarantor Name: </label>

                                <div class="col-sm-9">
                                <input type="text" class="form-control" name="guarantor_name" required>
                                </div>
                            </div>
                                                                
                                <div class="space-4"></div>

                 <div class="form-group">
                    <label class="col-sm-3" for="form-field-1-1">Reason: </label>

                     <div class="col-sm-9">
                           <textarea class="form-control" name="reason" rows="5" id="comment"></textarea>
                    </div>
                    </div>

                            <div class="space-4"></div>

                            <div class="clearfix form-actions w3-card-4 w3-light-grey">
                                <div class="col-md-offset-3 col-md-9">
                                    <button class="btn btn-info" type="submit" name="submitLoanButton">
                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                        Next
                                    </button>

                                    &nbsp; &nbsp; &nbsp;
                                    <button class="btn" type="reset">
                                        <i class="ace-icon fa fa-undo bigger-110"></i>
                                        Reset
                                    </button>
                                </div>
                                <!-- ... (your existing loan form fields) ... -->
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
	
                                </div>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
