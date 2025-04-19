<?php
require_once 'klasat/User.php';
require_once 'klasat/Admin.php';

$messages = '';
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
} elseif (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $dob)) {
    $errors[] = "Date of birth must be in the format: YYYY-MM-DD.";
} else {
    $dob_parts = explode('-', $dob);
    if (count($dob_parts) === 3) {
        $year = (int)$dob_parts[0];
        $month = (int)$dob_parts[1];
        $day = (int)$dob_parts[2];

        if (!checkdate($month, $day, $year)) {
            $errors[] = "Invalid date of birth.";
        } else {
            $birthDate = new DateTime($dob);
            $today = new DateTime('today');
            $age = $birthDate->diff($today)->y;

            if ($age < 18) {
                $errors[] = "You must be at least 18 years old to register.";
            }
        }
    } else {
        $errors[] = "Invalid date format.";
    }
}


  
  if (empty($errors)) {
    
        // regjistrimi i perdoruesit
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $user = new User($name, $email, $hashedPassword, $dob);

        // e merr outputin nga metoda displayinfo te klasa user
        ob_start();
        $user->displayInfo();
        $output = ob_get_clean();

        $messages = "<div class='alert alert-success'>$output</div>";

        // e shfaq mesazhin edhe ridrejton pas 3 sekondave
        echo $messages;
        header("Refresh: 3; url=login.php");
        exit();
} else {
    // shfaq gabim nese ndodh gjate regjistrimit te perdoruesit
    foreach ($errors as $error) {
        $messages .= "<div class='alert alert-danger'>$error</div>";
    }
}

//testimi per regjistrimin e Admin
if (isset($_POST['is_admin']) && $_POST['is_admin'] == 'on' && empty($errors)) {
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
  $admin = new Admin($name, $email, $hashedPassword, $dob);

  ob_start();
  $admin->displayAdminInfo();
  $output = ob_get_clean();

  $messages = "<div class='alert alert-success'>$output</div>";
  echo $messages;
  header("Refresh: 3; url=login.php");
  exit();
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
        <input type="date" class="form-control" id="dob" name="dob" value="<?php echo isset($_POST['dob']) ? $_POST['dob'] : ''; ?>" placeholder="YYYY-MM-DD">

      </div>

      <div class="mb-3">
        <label for="is_admin" class="form-label">Register as Admin</label>
        <input type="checkbox" id="is_admin" name="is_admin" value="on">
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
