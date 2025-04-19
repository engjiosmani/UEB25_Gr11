<?php
require_once 'klasat/User.php';
require_once 'klasat/Admin.php';

// testim i nje user apo admin 
$dummyUser = new User("Edonita Gashi", "edonita@example.com", password_hash("1234", PASSWORD_DEFAULT), "2003-05-20");
$dummyAdmin = new Admin("Engji Osmani", "engji@example.com", password_hash("admin123", PASSWORD_DEFAULT), "2000-10-04");

$email = $password = '';
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $loginSuccess = false;

    // test user login
    if ($dummyUser->getEmail() === $email && password_verify($password, $dummyUser->getPassword())) {
      $loginSuccess = true;
      $message = "<div style='color:green; text-align:center;'>User login sukses! Mirë se vini, {$dummyUser->fullname}</div>";
  }

    // test admin login nese user ka fail
    elseif ($dummyAdmin->getEmail() === $email && password_verify($password, $dummyAdmin->getPassword())) {
      $loginSuccess = true;
      $adminInfo = $dummyAdmin->displayAdminInfo(true); // modifiko funksionin që të kthejë tekst në vend që ta printojë
      $message = "<div style='color:green; text-align:center;'>Admin login sukses! Mirë se vini, {$dummyAdmin->fullname}</div><br>$adminInfo";
  }

    // nese login fail edhe per user edhe per admin
    if (!$loginSuccess) {
      $message = "<div style='color:red; text-align:center;'>Email ose fjalëkalim i gabuar.</div>";
  }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Music Festival</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
        background: linear-gradient(135deg, #f5cf97, #ff6126);
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Arial', sans-serif;
    }

    .login-box {
        background-color: #111;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 8px 20px rgba(255, 84, 27, 0.2);
        width: 100%;
        max-width: 400px;
        color: white;
    }

    .login-box h2 {
        margin-bottom: 30px;
        font-weight: bold;
        text-align: center;
        color: #fecd1a; /* Yellow tone */
    }

    .form-label {
        color: #fff;
    }

    .form-control {
        border-radius: 10px;
        background-color: #222;
        color: #fff;
        border: 1px solid #444;
    }

    .form-control::placeholder {
        color: #ccc;
    }

    .btn-login {
        background-color: #f2541b; /* Bright orange */
        color: white;
        font-weight: bold;
        border-radius: 10px;
        border: none;
    }

    .btn-login:hover {
        background-color: #d94710;
    }

    .extra-links {
        text-align: center;
        margin-top: 15px;
    }

    .extra-links a {
        color: #f2541b;
        text-decoration: none;
        display: block;
        margin-top: 5px;
    }

    .extra-links a:hover {
        text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="login-box">
    <h2>Login</h2>
    <?php if (!empty($message)) echo $message; ?>
    <form method="POST" action="">
      <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
      </div>

      <button type="submit" name="login" class="btn btn-login w-100">Login</button>

      <div class="extra-links">
        <a href="index.php">← Back to Home</a>
        <a href="register.php">Create Account</a>
      </div>
    </form>
  </div>

</body>
</html>
