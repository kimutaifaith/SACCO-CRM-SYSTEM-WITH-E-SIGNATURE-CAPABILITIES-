<?php
session_start();
date_default_timezone_set('Africa/Nairobi');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'C:\xampp\htdocs\Finalesigncode\vendor\phpmailer\phpmailer\src/Exception.php';
require 'C:\xampp\htdocs\Finalesigncode\vendor\phpmailer\phpmailer\src/PHPMailer.php';
require 'C:\xampp\htdocs\Finalesigncode\vendor\phpmailer\phpmailer\src/SMTP.php';

// Include the Twilio PHP library
require __DIR__ . '/vendor/autoload.php';

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

$loan_details = $_SESSION['loan_application'] ?? [];
$guarantor_name = $_SESSION['loan_application']['guarantor_name'];

// Fetch guarantor details from the database based on their name
$guarantor_query = "SELECT * FROM user_info WHERE full_name = '$guarantor_name'";
$guarantor_result = $conn->query($guarantor_query);

if ($guarantor_result->num_rows > 0) {
    $guarantor_row = $guarantor_result->fetch_assoc();

    // Autofill guarantor email and contact fields in the form
    $guarantor_email = $guarantor_row['email'];
    $guarantor_contact = $guarantor_row['contact_number'];
}

// Function to send OTP to the user's email
function sendEmail($email, $loan_details) {
    $mail = new PHPMailer();

    // Configure PHPMailer settings (SMTP, authentication, etc.)
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';  // Specify your SMTP server
    $mail->SMTPAuth   = true;                 // Enable SMTP authentication
    $mail->Username   = 'kimutaifaith21@gmail.com';     // SMTP username
    $mail->Password   = 'ykfmpkxwynxtkwmz';     // SMTP password
    $mail->SMTPSecure = 'ssl';  // Enable TLS encryption, `PHPMailer::ENCRYPTION_SMTPS` also accepted
    $mail->Port       = 465;   // SMTP port

    // Compose the email
    $mail->setFrom('kimutaifaith21@gmail.com', 'Jitahidi Sacco');
    $mail->addAddress($email);
    $mail->Subject = 'Loan Application Details';
    $otpLink = 'http://localhost/finalesigncode/otp_verification.php';

    $mail->Body = "Below are the loan details:" . PHP_EOL;
    $mail->Body .= '- Loan ID: ' . $loan_details['loanID'] . PHP_EOL;
    $mail->Body .= '- Member Name: ' . $loan_details['memberName'] . PHP_EOL;
    $mail->Body .= '- Loan Amount: ' . $loan_details['loanAmount'] . PHP_EOL;
    $mail->Body .= '- Loan Term: ' . $loan_details['loanTerm'] . ' months' . PHP_EOL;
    $mail->Body .= '- Interest Rate: ' . $loan_details['interestRate']  . PHP_EOL;
    $mail->Body .= PHP_EOL;
    $mail->Body .= 'As a guarantor, your responsibilities include ensuring repayment of the loan if the borrower defaults. Please review the loan details carefully and confirm your agreement to act as a guarantor.' . PHP_EOL;
    $mail->Body .= PHP_EOL;
    $mail->Body .= 'If you wish to be added as a guarantor for the above member, click the link to enter the OTP sent to your phone: ' . $otpLink;
    $mail->Body .= PHP_EOL;
    $mail->Body .= 'For any inquiries or assistance, please contact us at +254 724456299.' . PHP_EOL;
    
    // Send the email
    return $mail->send();
}

// Function to generate OTP
function generateOTP() {
    return sprintf('%06d', mt_rand(0, 999999));
}

// Function to send OTP via SMS and save details in the database
function sendOTP($client, $to, $guarantor_name, $otp, $conn) {
    try {
        // Send OTP via Twilio
        $message = $client->messages->create(
            $to,
            array(
                'from' => $GLOBALS['twilio_number'],
                'body' => 'Your OTP is: ' . $otp
            )
        );

        // OTP sent successfully, insert details into the database
        $create_at = date("Y-m-d H:i:s");
        $is_expired = 0;

        $stmt = $conn->prepare("INSERT INTO otp_expiry (guarantor_name, otp, create_at, is_expired) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $guarantor_name, $otp, $create_at, $is_expired);
        $stmt->execute();
        $stmt->close();

        return true;
    } catch (Exception $e) {
        // Failed to send OTP
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // User submitted the form
    $guarantor_name = $_POST["guarantor_name"];
    $guarantor_email = $_POST["guarantor_email"];
    $guarantor_contact = $_POST['guarantor_contact']; // Corrected name attribute

    // Check if the guarantor's name exists in the database
    $check_guarantor_query = "SELECT * FROM user_info WHERE full_name = '$guarantor_name'";
    $check_guarantor_result = $conn->query($check_guarantor_query);

    if ($check_guarantor_result->num_rows > 0) {
        // Guarantor found in the database, proceed with sending email and OTP
        $_SESSION['loan_guarantor'] = [
            'guarantor_name' => $guarantor_name,
            'guarantor_email' => $guarantor_email,
        ];

        // Send email
        if (sendEmail($guarantor_email, $loan_details)) {
            // Email sent successfully, now send OTP via SMS
            // Your Twilio Account SID and Auth Token
            $account_sid = 'ACcf1a089de7e82745e6d1a26e2ffa4f79';
            $auth_token = '93ad69a52e464ca8e1fb534bda23bcca';

            // Twilio phone number
            $twilio_number = '+1 334 781 4799';

            // Create a Twilio client
            $client = new Twilio\Rest\Client($account_sid, $auth_token);

            // Generate OTP
            $otp = generateOTP();

            // Send OTP via SMS
            if (sendOTP($client, $guarantor_contact, $guarantor_name, $otp, $conn)) {
                // Both email and SMS sent successfully
                header('Location: loansummaryexample.php');
                exit;
            } else {
                // Error sending OTP via SMS
                $error_message = "Error sending OTP via SMS. Please try again.";
            }
        } else {
            // Error sending email
            $error_message = "Error sending email. Please try again.";
        }
    } else {
        // Guarantor not found in the database
        $error_message = "User not found. Please enter a member in the Sacco.";
    }
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
    <link rel="icon" type="image/png" href="asset/images/favicon2.jpg">
   
    <link href="css/sb-admin-2.css" rel="stylesheet"><!-- Head content here -->
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

        <!-- HTML form for inputting guarantor details -->
        <div class="container1">
            <?php if (isset($error_message)) { ?>
                <p style="color: red;"><?php echo $error_message; ?></p>
            <?php } ?>
<h5>Confirm below are the correct details of the guarantor you have selected to guarantee you!</h5>
            <form method="post" action="">
                <!-- Add input fields for guarantor details here -->
                <label for="guarantor_name">Guarantor Name:</label>
                <input type="text" id="guarantor_name" name="guarantor_name" value="<?php echo isset($_SESSION['loan_application']['guarantor_name']) ? $_SESSION['loan_application']['guarantor_name'] : ''; ?>" required><br>

                <label for="guarantor_email">Guarantor Email:</label>
                <input type="email" id="guarantor_email" name="guarantor_email" value="<?php echo isset($guarantor_email) ? $guarantor_email : ''; ?>"  required><br>

                <label for="guarantor_number">Guarantor Number:</label>
<input type="text" id="guarantor_number" name="guarantor_contact" value="<?php echo isset($guarantor_contact) ? $guarantor_contact : ''; ?>" required><br>

                <button type="submit">Send Email and OTP</button>
            </form>
        </div>
        <!-- End of Content Wrapper -->

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
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

</body>

</html>