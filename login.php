<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Login</title>
    <link rel="stylesheet" href="style.css"/>
    <link rel="icon" type="image/png" href="asset/images/favicon2.jpg">

    <style>
        body {
            font-family: Calibri, sans-serif;
            background-image: url('asset/images/Background3.png'); /* Replace 'path/to/your/image.jpg' with the path to your image */
            background-size: cover; /* Cover the entire background */
        }
       </style> 
</head>
<body>
<?php
$con = mysqli_connect("localhost", "root", "", "sacco");
// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit; // Exit script if connection fails
}

session_start();
// When form submitted, check and create user session.
if (isset($_POST['username'])) {
    $username = stripslashes($_REQUEST['username']);    // removes backslashes
    $username = mysqli_real_escape_string($con, $username);
    $password = stripslashes($_REQUEST['password']);
    $password = mysqli_real_escape_string($con, $password);
    // Check if user exists in the database
    $query = "SELECT * FROM `user_info` WHERE username='$username'
                     AND password='" . md5($password) . "'";
    $result = mysqli_query($con, $query) or die(mysqli_error($con));
    $rows = mysqli_num_rows($result);
    if ($rows == 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['username'] = $username;
        if ($row['username'] == 'admin') {
            // Redirect admin to adminhome.php
            echo "User is an admin. Redirecting...";
            header("Location: adminhome.php");
            exit; // Ensure script stops executing after redirection
        } else {
            // Redirect regular user to home.php
            echo "User is not an admin. Redirecting...";
            header("Location: home.php");
            exit; // Ensure script stops executing after redirection
        }
    } else {
        echo "<div class='form'>
                  <h3>Incorrect Username/password.</h3><br/>
                  <p class='link'>Click here to <a href='login.php'>Login</a> again.</p>
                  </div>";
    }
} else {
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Login</title>
    <link rel="stylesheet" href="style.css"/>
    <link rel="icon" type="image/png" href="asset/images/favicon2.jpg">
    <style>
        body {
            font-family: Calibri, sans-serif;
            background-image: url('asset/images/Background3.png'); /* Replace 'path/to/your/image.jpg' with the path to your image */
            background-size: cover; /* Cover the entire background */
        }
    </style> 
</head>
<body>
    <form class="form" method="post" name="login">
        <h1 class="login-title">Login</h1>
        <input type="text" class="login-input" name="username" placeholder="Username" autofocus="true"/>
        <input type="password" class="login-input" name="password" placeholder="Password"/>
        <input type="submit" value="Login" name="submit" class="login-button"/>
        <p class="link"><a href="register.php">New Registration</a></p>
        <p class="link"><a href="forgots_password.php">Forgot passowrd?</a></p>

    </form>
</body>
</html>
<?php
}
?>

</body>
</html>