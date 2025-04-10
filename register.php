<?php
require_once 'klasat/User.php';
session_start();

$messages = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"] ?? '';
    $name = $_POST["name"] ?? '';
    $password = $_POST["password"] ?? '';
    $dob = $_POST["dob"] ?? '';
    $errors = [];

  if (empty($name)) {
    $errors[] = "Name is required.";
  }

  if (empty($email)) {
    $errors[] = "Email is required.";
} elseif (!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)) {
    $errors[] = "Please enter a valid email address.";
}

  if (empty($password)) {
    $errors[] = "Password is required.";
  }

  if (empty($dob)) {
    $errors[] = "Date of birth is required.";
} elseif (!preg_match("/^(0[1-9]|[12][0-9]|3[01])-(0[1-9]|1[0-2])-\d{4}$/", $dob)) {
    $errors[] = "Date of birth must be in the format: dd-mm-yyyy.";
} else {
    $dob_parts = explode('-', $dob);
    if (count($dob_parts) == 3) {
      $day = (int)$dob_parts[0];
      $month = (int)$dob_parts[1];
      $year = (int)$dob_parts[2];
      
      if (!checkdate($month, $day, $year)) {
        $errors[] = "Invalid date of birth.";
      } else {
        $age = date_diff(date_create($dob), date_create('today'))->y;
        if ($age < 18) {
          $errors[] = "You must be at least 18 years old to register.";
        }
      }
    } else {
      $errors[] = "Invalid date format.";
    }
  }


  if (empty($errors)) {
    $user = new User($name, $email, $password, $dob);
    $_SESSION['user'] = $user;
    $messages = "<div class='alert alert-success'>" . $user->register() . "</div>";
    echo "<div class='alert alert-success'>Regjistrimi ka qenë i suksesshëm! Do të ridrejtoheni në faqen e login-it pas disa sekondash.</div>";
    header("Refresh: 3; url=login.php");
    exit();
} else {
    foreach ($errors as $error) {
        $messages .= "<div class='alert alert-danger'>$error</div>";
    }
}
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Music Festival</title>
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

    .register-box {
      background-color: #111;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 8px 20px rgba(255, 84, 27, 0.2);
      width: 100%;
      max-width: 400px;
      color: white;
    }

    .register-box h2 {
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

    .btn-register {
      background-color: #f2541b; /* Bright orange */
      color: white;
      font-weight: bold;
      border-radius: 10px;
      border: none;
    }

    .btn-register:hover {
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

  <div class="register-box">
    <h2>Register</h2>
    <?php if (!empty($messages)) echo $messages; ?>

    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <div class="mb-3">
        <label for="name" class="form-label">Full Name</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Enter your full name">
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" placeholder="Enter your email">

      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password">
      </div>

      <div class="mb-3">
        <label for="dob" class="form-label">Date of Birth</label>
        <input type="text" class="form-control" id="dob" name="dob" placeholder="Enter your date of birth (dd-mm-yyyy)">
      </div>

      <button type="submit" class="btn btn-register w-100">Register</button>

      <div class="extra-links">
        <a href="index.php">← Back to Home</a>
        <a href="login.php">Already have an account? Login</a>
      </div>
    </form>
  </div>

</body>
</html>
