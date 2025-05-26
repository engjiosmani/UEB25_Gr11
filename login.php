<?php
require_once 'db_conn.php';
require_once 'klasat/User.php';
require_once 'klasat/Admin.php';
require_once 'error_handler.php';

session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT id, fullname, email, password, dob, phone, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            $id = $row['id'];
            $fullname = $row['fullname'];
            $dob = $row['dob'];
            $phone = $row['phone'];
            $role = $row['role'];

            setcookie('user_email', $email, time() + (86400 * 7), "/"); // kujton emailin për 1 javë

            if ($role === 'admin') {
                $user = new Admin($fullname, $email, $row['password'], $dob, $phone);
                $user->id = $id;
                $_SESSION['role'] = 'admin';
            } else {
                $user = new User($fullname, $email, $row['password'], $dob, $phone, $row['role']);
                $user->id = $id;
                $_SESSION['role'] = 'user';
            }

            $_SESSION['fullname'] = $fullname;
            $_SESSION['user'] = &$user;

            header("Location: " . ($role === 'admin' ? "admin_dashboard.php" : "index.php"));
            exit();
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

    .login-box {
    background-color: #111;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 8px 20px rgba(255, 84, 27, 0.2);
    width: 100%;
    max-width: 400px;
    color: white;
    margin: 60px auto 0 auto;  
}
.login-box h2 {
    margin-bottom: 30px;
    font-weight: bold;
    text-align: center;
    color: #fecd1a;
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
    background-color: #f2541b;
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
    
<!-- ================= HEADER & NAV ================= -->
<header class="site-header">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12 d-flex align-items-center">
                <p class="d-flex mb-0 align-items-center">
                    <strong class="text-dark">Welcome to Music Festival 2025</strong>
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
            <a href="register.php">Create Account</a>
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
