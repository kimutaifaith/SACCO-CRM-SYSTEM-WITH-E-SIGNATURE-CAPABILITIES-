<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Overview Form</title>
</head>
<body>
    <h2>Account Overview Form</h2>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Database connection
        $conn = new mysqli("localhost", "root", "", "sacco");

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Retrieve form data
        $username = $_POST['username'];
        $memberID = $_POST['memberID'];
        $accountNumber = $_POST['accountNumber'];
        $accountBalance = $_POST['accountBalance'];
        $totalSavings = $_POST['totalSavings'];
        $membershipStatus = $_POST['membershipStatus'];

        // Prepare SQL statement
        $sql = "INSERT INTO account_overview (username, memberID, accountNumber, accountBalance, totalSavings, membershipStatus)
                VALUES ('$username', '$memberID', '$accountNumber', '$accountBalance', '$totalSavings', '$membershipStatus')";

        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        // Close connection
        $conn->close();
    }
    ?>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="member_id">Member name:</label><br>
        <input type="text" id="member_id" name="username" required><br><br>

    <label for="member_id">Member ID:</label><br>
        <input type="text" id="member_id" name="memberID" required><br><br>
        
        <label for="account_number">Account Number:</label><br>
        <input type="text" id="account_number" name="accountNumber" required><br><br>
        
        <label for="account_balance">Account Balance:</label><br>
        <input type="text" id="account_balance" name="accountBalance" required><br><br>
        
        <label for="total_savings">Total Savings:</label><br>
        <input type="text" id="total_savings" name="totalSavings" required><br><br>
        
        <label for="membership_status">Membership Status:</label><br>
        <select id="membership_status" name="membershipStatus" required>
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
        </select><br><br>
        
        <input type="submit" value="Submit">
    </form>
</body>
</html>
