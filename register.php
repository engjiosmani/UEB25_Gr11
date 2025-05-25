<?php
require_once 'klasat/User.php';
require_once 'klasat/Admin.php';
require_once 'db_conn.php';
require_once 'error_handler.php';

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
          setcookie('new_user', '1', time() + (86400 * 1), "/");

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
  <!-- CSS FILES -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;400;700&display=swap" rel="stylesheet">

    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="css/bootstrap-icons.css" rel="stylesheet">

    <link href="css/templatemo-festava-live.css" rel="stylesheet">
  <style>
    body {
     background: 
        linear-gradient(135deg, rgba(245,207,151,0.75), rgba(255,97,38,0.75)),
        url('images/nicholas-green-unsplash-blur.jpg') no-repeat center center fixed;
    background-size: cover;
    min-height: 100vh;
    font-family: 'Arial', sans-serif;
    }
    
.register-box {
    background-color: #111;
    padding: 40px 30px 32px 30px;
    border-radius: 22px;
    box-shadow: 0 8px 32px rgba(255, 84, 27, 0.10), 0 2px 8px #ffb4804a;
    width: 100%;
    max-width: 400px;
    color: white;
    margin: 60px auto 0 auto; 
}
.register-box h2 {
    margin-bottom: 28px;
    font-weight: bold;
    text-align: center;
    color: #fecd1a;
    font-size: 2.2rem;
    background-color: #111;
}
.form-label {
    color: #fff;
    font-weight: 500;
    letter-spacing: 0.01em;
}
.form-control {
    border-radius: 10px;
    background-color: #222;
    color: #fff;
    border: 1px solid #444;
    font-size: 1.05rem;
    padding: 10px 12px;
    margin-bottom: 4px;
    transition: border 0.2s;
}
.form-control:focus {
    border: 1.5px solid #ffae47;
    background-color: #222;
    color: #fff;
    box-shadow: 0 0 0 0.08rem #fecd1a4d;
}
.form-control::placeholder {
    color: #ccc;
    opacity: 1;
    font-style: italic;
}
.btn-register {
    background-color: #f2541b;
    color: white;
    font-weight: bold;
    border-radius: 10px;
    border: none;
    transition: background 0.18s;
    font-size: 1.1rem;
    margin-top: 10px;
}
.btn-register:hover {
    background-color: #d94710;
}
.extra-links {
    text-align: center;
    margin-top: 17px;
}
.extra-links a {
    color: #f2541b;
    text-decoration: none;
    display: block;
    margin-top: 6px;
    font-size: 0.98rem;
    transition: color 0.15s;
}
.extra-links a:hover {
    text-decoration: underline;
    color: #ff7e00;
}
#is_admin {
    width: 18px;
    height: 18px;
    vertical-align: middle;
    margin-left: 7px;
}
  </style>
</head>
<body>
  <!-- ================= HEADER & NAV ================= -->
<header class="site-header">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12 d-flex align-items-center">
                <p class="d-flex mb-0 align-items-center">
                    <strong class="text-dark">Welcome to Music Festival 2023</strong>
                </p>
                <?php if (isset($_SESSION['fullname'])): ?>
                    <div class="ms-auto d-flex align-items-center">
                        <span class="text-dark me-2">Hello, <?php echo $_SESSION['fullname']; ?></span>
                        <a href="logout.php" class="text-danger text-decoration-none fw-bold">Logout</a>
                    </div>
                <?php else: ?>
            
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="index.php" >Festava Live</a>
        <a href="ticket.php" class="btn custom-btn d-lg-none ms-auto me-4">Buy Ticket</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav align-items-lg-center ms-auto me-lg-5">
                <li class="nav-item"><a class="nav-link" href="index.php#section_1">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#section_2">About</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#section_3">Artists</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#section_4">Schedule</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#section_5">Pricing</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#section_6">Contact</a></li>
            </ul>
            <a href="ticket.php" class="btn custom-btn d-lg-block d-none">Buy Ticket</a>
            <?php if (!isset($_SESSION['fullname'])): ?>
                <a href="login.php" class="ms-3 d-flex align-items-center text-white text-decoration-none">
                    <i class="bi bi-person-circle me-1 fs-5"></i>
                    <span class="fw-bold">Login</span>
                </a>
            <?php else: ?>
                <a href="logout.php" class="ms-3 d-flex align-items-center text-danger text-decoration-none fw-bold">
                    <i class="bi bi-box-arrow-right me-1 fs-5"></i>
                    <span class="fw-bold">Logout</span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<!-- =============== END HEADER & NAV =============== -->
  <div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-5">
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
          <div class="mb-3 d-flex align-items-center">
            <label for="is_admin" class="form-label mb-0">Register as Admin</label>
            <input type="checkbox" id="is_admin" name="is_admin" value="on">
          </div>
          <button type="submit" class="btn btn-register w-100">Register</button>
          <div class="extra-links">
            <a href="login.php">Already have an account? Login</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
  <!-- =================== FOOTER ==================== -->
<footer class="site-footer mt-5">
    <div class="site-footer-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-12">
                    <h2 class="text-white mb-lg-0">Festava Live</h2>
                </div>
                <div class="col-lg-6 col-12 d-flex justify-content-lg-end align-items-center">
                    <ul class="social-icon d-flex justify-content-lg-end">
                        <li class="social-icon-item"><a href="https://twitter.com" class="social-icon-link"><span class="bi-twitter"></span></a></li>
                        <li class="social-icon-item"><a href="https://www.facebook.com" class="social-icon-link"><span class="bi-facebook"></span></a></li>
                        <li class="social-icon-item"><a href="https://www.instagram.com" class="social-icon-link"><span class="bi-instagram"></span></a></li>
                        <li class="social-icon-item"><a href="https://www.youtube.com" class="social-icon-link"><span class="bi-youtube"></span></a></li>
                        <li class="social-icon-item"><a href="https://www.pinterest.com" class="social-icon-link"><span class="bi-pinterest"></span></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-12 mb-4 pb-2">
                <h5 class="site-footer-title mb-3">Links</h5>
                <ul class="site-footer-links">
                    <li class="site-footer-link-item"><a class="nav-link site-footer-link" href="index.php#section_1">Home</a></li>
                    <li class="site-footer-link-item"><a class="nav-link site-footer-link" href="index.php#section_2">About</a></li>
                    <li class="site-footer-link-item"><a class="nav-link site-footer-link" href="index.php#section_3">Artists</a></li>
                    <li class="site-footer-link-item"><a class="nav-link site-footer-link" href="index.php#section_4">Schedule</a></li>
                    <li class="site-footer-link-item"><a class="nav-link site-footer-link" href="index.php#section_5">Pricing</a></li>
                    <li class="site-footer-link-item"><a class="nav-link site-footer-link" href="index.php#section_6">Contact</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6 col-12 mb-4 mb-lg-0">
                <h5 class="site-footer-title mb-3">Have a question?</h5>
                <p class="text-white d-flex mb-1">
                    <a href="tel:090-080-0760" class="site-footer-link">090-080-0760</a>
                </p>
                <p class="text-white d-flex">
                    <a href="mailto:hello@company.com" class="site-footer-link">hello@company.com</a>
                </p>
            </div>
            <div class="col-lg-3 col-md-6 col-11 mb-4 mb-lg-0 mb-md-0">
                <h5 class="site-footer-title mb-3">Location</h5>
                <p class="text-white d-flex mt-3 mb-2">Silang Junction South, Tagaytay, Cavite, Philippines</p>
                <a class="link-fx-1 color-contrast-higher mt-3" href="index.php#section_6">
                    <span>Our Maps</span>
                    <svg class="icon" viewBox="0 0 32 32" aria-hidden="true">
                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="16" cy="16" r="15.5"></circle>
                            <line x1="10" y1="18" x2="16" y2="12"></line>
                            <line x1="16" y1="12" x2="22" y2="18"></line>
                        </g>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    <div class="site-footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-12 mt-5">
                    <p class="copyright-text">Copyright © Festava Live Company</p>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center small text-muted">
        <?php if (isset($_SESSION['visit_count'])): ?>
            Kjo faqe është vizituar <?= $_SESSION['visit_count'] ?> herë
        <?php endif; ?>
    </div>
</footer>

<!-- JS FILES -->
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.sticky.js"></script>
<script src="js/click-scroll.js"></script>
<script src="js/custom.js"></script>
</body>
</html>
