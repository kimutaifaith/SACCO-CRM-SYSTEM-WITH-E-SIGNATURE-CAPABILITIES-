<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>
<style>
  body {
    font-family: Arial, sans-serif;
    background-color: #f2f2f2;
  }

  .container {
    width: 300px;
    margin: 0 auto;
    background-color: #fff;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin-top: 100px;
  }

  .header {
    text-align: center;
    margin-bottom: 20px;
  }

  .header img {
    width: 150px;
    height: auto;
  }

  .form-group {
    margin-bottom: 15px;
  }

  label {
    display: block;
    margin-bottom: 5px;
  }

  input[type="text"],
  input[type="password"] {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
  }

  input[type="submit"] {
    width: 100%;
    padding: 8px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }

  input[type="submit"]:hover {
    background-color: #0056b3;
  }
</style>
</head>
<body>

<div class="container">
  <div class="header">
    <img src="asset/images/favicon1.jpg" alt="Logo">
    <h2>Login</h2>
  </div>

  <form action="login.php" method="post">
    <div class="form-group">
      <label for="username">Username:</label>
      <input type="text" id="username" name="username">
    </div>
    <div class="form-group">
      <label for="password">Password:</label>
      <input type="password" id="password" name="password">
    </div>
    <div class="form-group">
      <input type="submit" value="Log In">
    </div>
  </form>
</div>

</body>
</html>