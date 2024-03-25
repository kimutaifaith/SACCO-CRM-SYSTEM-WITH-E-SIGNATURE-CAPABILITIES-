<?php
// Start session
session_start();

// Database connection
$conn = mysqli_connect("localhost", "root", "", "sacco");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];

    // Check if username exists in database
    $sql = "SELECT * FROM user_info WHERE username='$username'";
    $result = mysqli_query($conn, $sql);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
    
        // Retrieve current password from the database
        $username = $_SESSION['username'];
        $sql = "SELECT password FROM user_info WHERE username = '$username'";
        $result = $conn->query($sql);
    
        if ($result->num_rows > 0) {
            $row1 = $result->fetch_assoc();
            $db_password_hash = $row1['password'];
    
            // Verify if the current password matches the one in the database
            
                // Check if the new password and confirm password match
                if ($new_password == $confirm_password) {
                    // Update the password in the database
                    $hashed_password = md5($new_password);
                    $update_sql = "UPDATE user_info SET password = '$hashed_password' WHERE username = '$username'";
                    if ($conn->query($update_sql) === TRUE) {
                        // Password updated successfully
                        echo '<script>alert("Password changed successfully."); window.location.href = "login.php";</script>';
                        exit();
                    } else {
                        // Error updating password
                        echo "Error updating password: " . $conn->error;
                    }
                } else {
                    echo '<script>alert("New password and confirm password do not match.");</script>';
                }
            } 
        } else {
            echo "User not found.";
        }}


// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="icon" type="image/png" href="asset/images/favicon2.jpg">
    <style>
        body {
            font-family: Calibri, sans-serif;
            background-image: url('asset/images/Background3.png'); /* Replace 'path/to/your/image.jpg' with the path to your image */
            background-size: cover; /* Cover the entire background */
         }
          .container1 {
            background-color: white; /* Set background color to white */
            padding: 20px; /* Add padding for better appearance */
            border-radius: 5px; /* Add border radius for rounded corners */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Add box shadow for depth */
            max-width: 400px; /* Limit width for better readability */
            margin: 0 auto; /* Center horizontally */
            margin-top: 50px; /* Add top margin for spacing */
        }
        

       </style> 
</head>
<body>
    <div class= container1>
    <h2>Forgot Password</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required><br>
        
        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br>
        
        <input type="submit" value="Reset Password">
    </div></form>
</body>
</html>
