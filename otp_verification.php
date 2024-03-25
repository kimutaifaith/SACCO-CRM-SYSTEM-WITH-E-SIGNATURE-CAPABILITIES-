<?php
date_default_timezone_set('Africa/Nairobi');

session_start();

// Function to verify OTP
function verifyOTP($otp, $conn) {
    $stmt = $conn->prepare("SELECT * FROM otp_expiry WHERE otp = ? AND is_expired = 0");
    $stmt->bind_param("s", $otp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // OTP is valid, update is_expired to mark it as used
        $updateStmt = $conn->prepare("UPDATE otp_expiry SET is_expired = 1 WHERE otp = ?");
        $updateStmt->bind_param("s", $otp);
        $updateStmt->execute();
        $updateStmt->close();

        return true;
    }

    return false;
}

// Handle form submission for OTP verification
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $otp = $_POST["otp"];

    // Assuming you have established a valid database connection
    $conn = new mysqli("localhost", "root", "", "sacco");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Verify the provided OTP
    if (verifyOTP($otp, $conn)) {
        // Redirect to success page
        header('Location: esignature.php');
        exit;
    } else {
        // OTP verification failed, display an error message or redirect to an error page
        echo "OTP verification failed. Please check your credentials.";
    }

    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Add your head content here -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <style>
        body {
            font-family: Calibri, sans-serif;
            background-image: url('asset/images/Background3.png'); /* Replace 'path/to/your/image.jpg' with the path to your image */
            background-size: cover; /* Cover the entire background */
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.8); 
        }
    </style>
        <link href="css/sb-admin-2.css" rel="stylesheet">
        <link rel="icon" type="image/png" href="asset/images/favicon2.jpg">

</head>

<body>
    <div class="container">
        <form method="post" action="">
            <div space=></div>
            
            <label for="otp">OTP:</label>
            <input type="text" id="otp" name="otp" required><br>

            <button type="submit">Verify OTP</button>
        </form>
    </div>
</body>

</html>
