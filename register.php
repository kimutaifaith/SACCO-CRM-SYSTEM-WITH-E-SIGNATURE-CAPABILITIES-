<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Establish database connection
    $conn = new mysqli("localhost", "root", "", "sacco");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve form data
    $full_name = $_POST['full_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $dob = $_POST['dob'];
    $contact_number = $_POST['contact_number'];
    $account_number = $_POST['account_number'];
    $account_type = $_POST['account_type'];

    // Prepare SQL statement to insert data into user_info table
    $sql = "INSERT INTO user_info (full_name, username, email, password, dob, contact_number, account_number, account_type)
            VALUES ('$full_name', '$username', '$email', '". md5($password) ."', '$dob', '$contact_number', '$account_number', '$account_type')";

    if ($conn->query($sql) === TRUE) {
        // Registration successful
        echo '<script>alert("Registration successful. Click OK to log in."); window.location.href = "login.php";</script>';
        exit;
    } else {
        // Registration failed
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Registration</title>
<link rel="stylesheet" href="styles.css">
<link rel="icon" type="image/png" href="asset/images/favicon2.jpg">
<style>
    body {
        font-family: Calibri, sans-serif;
        background-image: url('asset/images/Background3.png');
        background-size: cover;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .container {
        width: 400px;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .container h1 {
        text-align: center;
    }

    .container form label {
        display: block;
        margin-bottom: 10px;
    }

    .container form input,
    .container form select {
        width: 100%;
        padding: 8px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .container form input[type="submit"] {
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .container form input[type="submit"]:hover {
        background-color: #0056b3;
    }
</style>
</head>
<body>

<div class="container">
    <h1>User Registration</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <!-- Personal Information -->
        <label for="full_name">Full Name:</label>
        <input type="text" id="full_name" name="full_name" required><br>
        
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" required><br>
        
        <label for="contact_number">Contact Number:</label>
        <input type="text" id="contact_number" name="contact_number"><br>
        
        <!-- Account Details -->
        <label for="account_number">Account Number:</label>
        <input type="text" id="account_number" name="account_number" required><br>
        
        <label for="account_type">Account Type:</label>
        <select id="account_type" name="account_type" required>
            <option value="Savings">Savings</option>
            <option value="Checking">Checking</option>
            <option value="Investment">Investment</option>
            <option value="Admin">Admin</option>

        </select><br>
        
        <input type="submit" value="Register">
    </form>
</div>

</body>
</html>
