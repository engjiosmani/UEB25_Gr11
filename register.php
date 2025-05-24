<?php
require_once 'klasat/User.php';
require_once 'klasat/Admin.php';
require_once 'db_conn.php';

$messages = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"] ?? '';
    $name = $_POST["name"] ?? '';
    $password = $_POST["password"] ?? '';
    $dob = $_POST["dob"] ?? '';
    $phone = $_POST["phone"] ?? '';
    $errors = [];

    if (empty($name)) {
        $errors[] = "Name is required.";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $errors[] = "Name can only contain letters and spaces.";
    }

    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters.";
    } elseif (!preg_match("/[A-Z]/", $password)) {
        $errors[] = "Password must include at least one uppercase letter.";
    } elseif (!preg_match("/[a-z]/", $password)) {
        $errors[] = "Password must include at least one lowercase letter.";
    } elseif (!preg_match("/[0-9]/", $password)) {
        $errors[] = "Password must include at least one number.";
    } elseif (!preg_match("/[\W]/", $password)) {
        $errors[] = "Password must include at least one special character.";
    }

    if (empty($dob)) {
        $errors[] = "Date of birth is required.";
    } elseif (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $dob)) {
        $errors[] = "Date of birth must be in the format: YYYY-MM-DD.";
    } else {
        $birthDate = new DateTime($dob);
        $today = new DateTime('today');
        $age = $birthDate->diff($today)->y;
        if ($age < 18) {
            $errors[] = "You must be at least 18 years old to register.";
        }
    }

    if (empty($phone)) {
        $errors[] = "Phone number is required.";
    } elseif (!preg_match("/^\+?[0-9]{8,15}$/", $phone)) {
        $errors[] = "Phone number must contain only digits (8-15 characters, optional +).";
    }

    // Kontrollo nese email ekziston para INSERT
    if (empty($errors)) {
        $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $errors[] = "This email is already registered.";
        }
        $checkStmt->close();
    }

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $role = (isset($_POST['is_admin']) && $_POST['is_admin'] == 'on') ? 'admin' : 'user';

        if ($role === 'admin') {
            $user = new Admin($name, $email, $hashedPassword, $dob);
        } else {
            $user = new User($name, $email, $hashedPassword, $dob);
        }

        // INSERT me telefon
        $stmt = $conn->prepare("INSERT INTO users (fullname, email, password, dob, phone, role) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $email, $hashedPassword, $dob, $phone, $role);

        if ($stmt->execute()) {
            ob_start();
            $user->displayInfo();
            $output = ob_get_clean();
            $messages = "<div class='alert alert-success'>$output</div>";
            echo $messages;
            header("Refresh: 3; url=login.php");
            exit();
        } else {
            $messages = "<div class='alert alert-danger'>Gabim: " . $stmt->error . "</div>";
        }
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
      color: #fecd1a;
    }
    .form-label { color: #fff; }
    .form-control {
      border-radius: 10px;
      background-color: #222;
      color: #fff;
      border: 1px solid #444;
    }
    .form-control::placeholder { color: #ccc; }
    .btn-register {
      background-color: #f2541b;
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
    .extra-links a:hover { text-decoration: underline; }
  </style>
</head>
<body>
  <div class="register-box">
    <h2>Register</h2>
    <?php if (!empty($messages)) echo $messages; ?>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <div class="mb-3">
        <label for="name" class="form-label">Full Name</label>
        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" placeholder="Enter your full name">
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="Enter your email">
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password">
      </div>
      <div class="mb-3">
        <label for="dob" class="form-label">Date of Birth</label>
        <input type="date" class="form-control" id="dob" name="dob" value="<?= htmlspecialchars($_POST['dob'] ?? '') ?>">
      </div>
      <div class="mb-3">
        <label for="phone" class="form-label">Phone Number</label>
        <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" placeholder="+38344123456">
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
