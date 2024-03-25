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

// Fetch existing loans from the final_loan table
$loanQuery = "SELECT loanID FROM final_loan WHERE memberName = '$username'";
$loanResult = $conn->query($loanQuery);

if ($loanResult->num_rows > 0) {
    // Populate dropdown with existing loans
    $loanOptions = "";
    while ($row1 = $loanResult->fetch_assoc()) {
        $loanOptions .= "<option value='{$row1['loanID']}'>{$row1['loanID']}</option>";
    }
} else {
    $loanOptions = "<option value=''>No loans found</option>";
}

// Initialize variables to store guarantor and guarantee amount
$selectedLoanID = "";
$guarantorName = "";
$guaranteeAmount = "";

if (isset($_POST['loanID'])) {
    // Retrieve selected loan ID from the form
    $selectedLoanID = $_POST['loanID'];

    // Fetch guarantor and guarantee amount for the selected loan
    $guarantorQuery = "SELECT guarantor_name, guarantee_amount FROM guarantor WHERE loanID = '$selectedLoanID'";
    $guarantorResult = $conn->query($guarantorQuery);

    if ($guarantorResult->num_rows > 0) {
        // Guarantor and guarantee amount found
        $guarantorData = $guarantorResult->fetch_assoc();
        $guarantorName = $guarantorData['guarantor_name'];
        $guaranteeAmount = $guarantorData['guarantee_amount'];
    } else {
        // No guarantor found for the selected loan
        $guarantorName = "No guarantor found";
        $guaranteeAmount = "N/A";
    }
    // Redirect to another page with guarantor details
    header("Location: view_guarantors.php?loanID=$selectedLoanID&guarantorName=$guarantorName&guaranteeAmount=$guaranteeAmount");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Jitahidi Sacco</title>
    <!-- Add your CSS links here -->
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
                <!-- End of Topbar -->
</head>

<body>
    <h2>View Guarantors for Loans</h2>
    <form method="post" action="">
        <label for="loanID">Select Loan:</label>
        <select name="loanID" id="loanID">
            <?php echo $loanOptions; ?>
        </select>
        <button type="submit">View Guarantor</button>
    </form>
    
</body>

</html>
