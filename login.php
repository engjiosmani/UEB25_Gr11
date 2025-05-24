<?php
require_once 'db_conn.php';
require_once 'klasat/User.php';
require_once 'klasat/Admin.php';

session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Kontrollo në databazë për përdoruesin me këtë email
    $stmt = $conn->prepare("SELECT fullname, email, password, dob, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Nëse ekziston përdoruesi
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

      
        if (password_verify($password, $row['password'])) {
            $fullname = $row['fullname'];
            $dob = $row['dob'];
            $role = $row['role'];

            
            if ($role === 'admin') {
                $user = new Admin($fullname, $email, $row['password'], $dob);
                $_SESSION['role'] = 'admin';
                $_SESSION['fullname'] = $user->fullname;
                $_SESSION['user'] = &$user; //assign by reference
               header("Location: index.php");
               exit();
            } else {
                $user = new User($fullname, $email, $row['password'], $dob);
                $_SESSION['role'] = 'user';
                $_SESSION['fullname'] = $user->fullname;
                 $_SESSION['user'] = &$user; 
                header("Location: index.php");
                exit();
            }

        
        } else {
            $message = "<div style='color:red; text-align:center;'>Incorrect password.</div>";
        }
    } else {
        $message = "<div style='color:red; text-align:center;'>Email not found.</div>";
    }

    $stmt->close();
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
