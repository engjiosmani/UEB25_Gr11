<?php
require_once 'db_conn.php';
require_once 'klasat/User.php';
require_once 'klasat/Admin.php';
require_once 'error_handler.php';

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}


$user = $_SESSION['user'];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Festava Live - Ticket HTML Form</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;400;700&display=swap" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-icons.css" rel="stylesheet">
    <link href="css/templatemo-festava-live.css" rel="stylesheet">
</head>
<body>
<main>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="index.php">Festava Live</a>
            <a href="ticket.php" class="btn custom-btn d-lg-none ms-auto me-4">Buy Ticket</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
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
                <a href="logout.php" class="ms-3 d-flex align-items-center text-danger text-decoration-none fw-bold">
                    <i class="bi bi-box-arrow-right me-1 fs-5"></i>
                    <span class="fw-bold">Logout</span>
                </a>
            </div>
        </div>
    </nav>

    <section class="ticket-section section-padding">
        <div class="section-overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-10 mx-auto">
                    <form class="custom-form ticket-form" action="ticket_process.php" method="post">
                        <h2 class="text-center mb-4">Get started here</h2>
                        <div class="ticket-form-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user->fullname) ?>" readonly>
                                </div>
                                <div class="col-lg-6">
                                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user->getEmail()) ?>" readonly>
                                </div>
                            </div>
                            <input type="text" name="phone" class="form-control mt-3" value="<?= htmlspecialchars($user->phone ?? '') ?>" readonly>

                            <h6 class="mt-4">Choose Ticket Type</h6>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-check form-control">
                                        <input class="form-check-input" type="radio" name="ticket_type" value="Early Bird" required checked>
                                        <label class="form-check-label">Early bird $120</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check form-control">
                                        <input class="form-check-input" type="radio" name="ticket_type" value="Standard" required>
                                        <label class="form-check-label">Standard $240</label>
                                    </div>
                                </div>
                            </div>

                            <input type="number" name="num_tickets" class="form-control mt-3" placeholder="Number of Tickets" min="1" max="10" required>
                            <textarea name="message" class="form-control mt-3" rows="3" placeholder="Additional Request"></textarea>
                            <button type="submit" class="form-control mt-4">Buy Ticket</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.sticky.js"></script>
<script src="js/custom.js"></script>
</body>
</html>