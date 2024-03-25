<?php

session_start(); // Start the session

// Retrieve the loan details from the session



use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer autoload file
require 'C:\xampp\htdocs\Finalesigncode\vendor\phpmailer\phpmailer\src/Exception.php';
require 'C:\xampp\htdocs\Finalesigncode\vendor\phpmailer\phpmailer\src/PHPMailer.php';
require 'C:\xampp\htdocs\Finalesigncode\vendor\phpmailer\phpmailer\src/SMTP.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $guarantor_name = $_POST['guarantor_name'];
    
    $folderPath = "upload/";
    $image_parts = explode(";base64,", $_POST['signature']);
    $image_type_aux = explode("image/", $image_parts[0]);

    $image_type = $image_type_aux[1];

    $image_base64 = base64_decode($image_parts[1]);

    $file = $folderPath . $guarantor_name . "_" . uniqid() . '.' . $image_type;
   
    $guarantee_amount = $_POST['guarantee_amount'];
    $guarantor_email = $_POST['guarantor_email'];

 file_put_contents($file, $image_base64 );

    // Save the guarantee details to the session

    $loan_details = $_SESSION['loan_application'] ?? [];
$loanID = $_SESSION['loan_application']['loanID'];
    $memberName = $_SESSION['loan_application']['memberName'];
    $loanAmount = $_SESSION['loan_application']['loanAmount'];
    $loanTerm = $_SESSION['loan_application']['loanTerm'];
    $interestRate = $_SESSION['loan_application']['interestRate'];
// Check if guarantee amount is more than 50% of the loan amount
$maxGuaranteeAmount = $loanAmount * 0.5; // 50% of the loan amount
if ($guarantee_amount > $maxGuaranteeAmount) {
    // Guarantee amount exceeds 50% of the loan amount, show error
    echo '<script>alert("Guarantee amount cannot exceed 50% of the loan amount."); window.location.href = "esignature.php"</script>';
} else {

    // Retrieve the loan details from the session
    
  
    
    // Insert the guarantee details into the database (replace with your database logic)
    $conn = new mysqli("localhost", "root", "", "sacco");

    // Check if the connection was successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO guarantor (loanID, guarantor_name, guarantee_amount) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters and execute the statement
    $stmt->bind_param("sss", $loanID, $guarantor_name, $guarantee_amount);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    $sql = "INSERT INTO employee_sign (guarantor_name, signature_img, guarantee_amount, guarantor_email) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("ssis", $guarantor_name, $file, $guarantee_amount, $guarantor_email);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }
}
    
    // Send email to the guarantor
    $mail = new PHPMailer();

    // Configure PHPMailer settings (SMTP, authentication, etc.)
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';  // Specify your SMTP server
    $mail->SMTPAuth   = true;                 // Enable SMTP authentication
    $mail->Username   = 'kimutaifaith21@gmail.com';     // SMTP username
    $mail->Password   = 'ykfmpkxwynxtkwmz';     // SMTP password
    $mail->SMTPSecure = 'ssl';  // Enable TLS encryption, `PHPMailer::ENCRYPTION_SMTPS` also accepted
    $mail->Port       = 465;  
    // Email content
    $mail->setFrom('kimutaifaith21@gmail.com', 'Jitahidi Sacco');
    $mail->addAddress($guarantor_email);
    $mail->Subject = "Successfully Guaranteed";
    $mail->Body = "Dear $guarantor_name,". PHP_EOL;
    $mail->Body .= 'You have successfully been added as a guarantor for the loan: ' . PHP_EOL;
    $mail->Body .= '- Loan ID: ' . $loan_details['loanID'] . PHP_EOL;
    $mail->Body .= '- Member Name: ' . $loan_details['memberName'] . PHP_EOL;
    $mail->Body .= '- Loan Amount: ' . $loan_details['loanAmount'] . PHP_EOL;
    $mail->Body .= '- Loan Term: ' . $loan_details['loanTerm'] . ' months' . PHP_EOL;
    $mail->Body .= '- Interest Rate: ' . $loan_details['interestRate']  . PHP_EOL;
    $mail->Body .= '- Guaranteed Amount Ksh: ' . $guarantee_amount . PHP_EOL;
    $mail->Body .= 'For any inquiries or assistance, please contact us at +254 724456299.' . PHP_EOL;
    $mail->Body .= 'Thank You.' . PHP_EOL;
    // Send email
    if ($mail->send()) {
        // Notify successful addition of guarantor to chepkemoifaith821@gmail.com
        $additional_mail = new PHPMailer();
        $additional_mail->isSMTP();
        $additional_mail->Host       = 'smtp.gmail.com';
        $additional_mail->SMTPAuth   = true;
        $additional_mail->Username   = 'kimutaifaith21@gmail.com';
        $additional_mail->Password   = 'ykfmpkxwynxtkwmz';
        $additional_mail->SMTPSecure = 'ssl';
        $additional_mail->Port       = 465;
    
        $additional_mail->setFrom('kimutaifaith21@gmail.com', 'Jitahidi Sacco');
        $additional_mail->addAddress('chepkemoifaith821@gmail.com');
        $additional_mail->Subject = "New Guarantor Added";
    
        // Personalize the email body with loan details
        $additional_mail->Body = "Dear $memberName,\n\n";
        $additional_mail->Body .= "A new guarantor has successfully guaranteed the loan with the following details:\n\n";
        $additional_mail->Body .= "Loan ID: $loanID\n";
        $additional_mail->Body .= "Guarantor Name: $guarantor_name\n";
        $additional_mail->Body .= "Guaranteed Amount: $guarantee_amount\n";
        $additional_mail->Body .= "\nPlease log in to your portal to complete the loan application process.\n\n";
        $additional_mail->Body .= "Thank you.";
    
        
        // Send additional email notification
        if ($additional_mail->send()) {
            echo '<script>alert("You have successfully been added as a guarantor! A confirmation email has been sent.")</script>';
        } else {
            echo '<script>alert("Failed to send additional email notification.")</script>';
        }
    } else {
        echo '<script>alert("Failed to send email to guarantor. Error: ") . $mail->ErrorInfo</script>';
    }
    
}

