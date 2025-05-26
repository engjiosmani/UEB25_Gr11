<?php
require_once 'db_conn.php';
require_once 'error_handler.php';
require_once 'klasat/User.php';
require_once 'klasat/Admin.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Numërimi i vizitave me SESSION
if (!isset($_SESSION['visit_count'])) {
    $_SESSION['visit_count'] = 1;
} else {
    $_SESSION['visit_count']++;
}

// Ndryshimi i temës përmes COOKIE
if (isset($_POST['theme_toggle'])) {
    $theme = (isset($_COOKIE['theme']) && $_COOKIE['theme'] === 'dark') ? 'light' : 'dark';
    setcookie('theme', $theme, time() + (86400 * 30), "/");
    header("Refresh:0");
    exit();
}
$theme = $_COOKIE['theme'] ?? 'light';

// Përdorur për dropdown sorting
$sort_preference = 'default';
function set_sort_preference() {
    global $sort_preference;
    if (isset($_GET['sort'])) {
        $sort_preference = $_GET['sort'];
    }
}
set_sort_preference();

// Lexon fjalët e ndaluara
function get_bad_words() {
    $bad_words = [];
    if (file_exists('bad_words.txt')) {
        $bad_words = file('bad_words.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $bad_words = array_map('trim', $bad_words);
    }
    return $bad_words;
}

// Procesimi i formës së kontaktit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["contact-message"])) {

    // Mos lejo pa login
    if (!isset($_SESSION['user'])) {
        $_SESSION['contact_error'] = "Ju duhet të jeni të kyçur për të dërguar mesazh.";
        header("Location: index.php#section_6");
        exit;
    }

    $user_id = $_SESSION['user']->id;
    $company = $_POST["contact-company"];
    $message = $_POST["contact-message"];

    // Pastrimi i mesazhit
    $message_cleaned = strip_tags($message);
    $message_cleaned = preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}]/u', '', $message_cleaned);
    $message_cleaned = preg_replace("/[#@]\w+/", "", $message_cleaned);

    $bad_words = get_bad_words();
    if (!empty($bad_words)) {
        $pattern = '/\\b(' . implode('|', array_map('preg_quote', $bad_words)) . ')\\b/iu';
        $message_cleaned = preg_replace($pattern, "***", $message_cleaned);
    }

    $message_cleaned = preg_replace('/(https?:\/\/[^\s]+)/', '<a href="$1" target="_blank" rel="noopener">$1</a>', $message_cleaned);
    $message_cleaned = preg_replace('/\s+/', ' ', trim($message_cleaned));

    // INSERT në databazë
    $stmt = $conn->prepare("INSERT INTO contact_messages (user_id, company, message) VALUES (?, ?, ?)");
if ($stmt) {
    $stmt->bind_param("iss", $user_id, $company, $message_cleaned);
    if ($stmt->execute()) {
        $_SESSION['message_sent'] = true;

        // Nëse është selektuar checkbox për email
        if (isset($_POST['also_send_email']) && $_POST['also_send_email'] === 'on') {
            $to = $_SESSION['user']->email;
            $subject = "Mesazhi juaj te Festava Live";
            $body = "Faleminderit për mesazhin tuaj nga kompania: $company\n\n"
                  . "Mesazhi: $message_cleaned";
            $headers = "From: no-reply@festava.com";

            if (!mail($to, $subject, $body, $headers)) {
                error_log("Dërgimi i emailit DËSHTOI për: $to");
            }
        }

        header("Location: index.php#section_6");
        exit;
    }
}
}
?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="">
    <meta name="author" content="">

    <title>Festava Live</title>
    <link rel="icon" href="images/icon.png">

    <!-- CSS FILES -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;400;700&display=swap" rel="stylesheet">

    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="css/bootstrap-icons.css" rel="stylesheet">

    <link href="css/templatemo-festava-live.css" rel="stylesheet">
<style>
<?php if ($theme === 'dark'): ?>
    body { background-color: #222; color: #fff; }
    .section-padding, .hero-section { background-color: #333 !important; }
<?php endif; ?>
<?php if ($theme === 'dark'): ?>
<style>
  #section_8 {
    background-color: #1e1e1e;
  }
  #section_8 h2, #section_8 p, #section_8 label {
    color: #fff;
  }
  .btn-festava {
    background-color: #ff6126;
    color: white;
    border: none;
    border-radius: 6px;
    font-weight: 500;
    transition: background-color 0.3s ease;
}

.btn-festava:hover {
    background-color: #e0521f;
}

</style>
<?php endif; ?>

</style>

</head>

<body class="<?= ($theme === 'dark') ? 'dark-mode' : '' ?>">

    <script>
$(".update-form").submit(function (e) {
    e.preventDefault();
    $.post("update_ticket.php", $(this).serialize(), function (response) {
        if (response.trim() === "success") {
            alert("Bileta u përditësua me sukses.");
            location.reload();
        } else {
            alert("Gabim: " + response);
        }
    });
});

$(".delete-btn").click(function () {
    if (confirm("A jeni i sigurt që dëshironi ta anuloni këtë biletë?")) {
        const ticketId = $(this).data("id");
        $.post("delete_ticket.php", { id: ticketId }, function (response) {
            if (response.trim() === "success") {
                alert("Bileta u anulua.");
                location.reload();
            } else {
                alert("Gabim: " + response);
            }
        });
    }
});
</script>

<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (isset($_SESSION['ticket_success'])) {
    echo "<div class='alert alert-success text-center'>" . $_SESSION['ticket_success'] . "</div>";
    unset($_SESSION['ticket_success']);
}

if (isset($_SESSION['ticket_error'])) {
    echo "<div class='alert alert-danger text-center'>" . $_SESSION['ticket_error'] . "</div>";
    unset($_SESSION['ticket_error']);
}
?>

    <main>

        <header class="site-header">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-12 d-flex align-items-center">
                    
                        <p class="d-flex mb-0 align-items-center">
                            <strong class="text-dark">Welcome to Music Festival 2025</strong>
                        </p>
        <form method="post" class="ms-auto me-3">
    <button type="submit" name="theme_toggle" class="btn btn-sm btn-outline-secondary">
        <i class="bi <?= ($theme === 'dark' ? 'bi-sun' : 'bi-moon') ?>"></i>
    </button>
</form>
                        
                       <?php if (isset($_SESSION['fullname'])): ?>
    <div class="ms-auto d-flex align-items-center">
        <span class="text-dark me-2">Hello, <?php echo $_SESSION['fullname']; ?></span>
<?php if (isset($_COOKIE['user_email'])): ?>
   
<?php endif; ?>
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


        <section class="hero-section" id="section_1">
            <div class="section-overlay"></div>

            <div class="container d-flex justify-content-center align-items-center">
                <div class="row">

                    <div class="col-12 mt-auto mb-5 text-center">
                        <small>Festava Live Presents</small>

                        <h1 class="text-white mb-5">Night Live 2025</h1>

                        <a class="btn custom-btn smoothscroll" href="#section_2">Let's begin</a>
                    </div>
                    <?php 
    $festival_days = ['Friday, August 10', 'Saturday, August 11', 'Sunday, August 12'];
?>
<div class="col-lg-12 col-12 mt-4">
    <div class="hero-info-wrapper">
        <div class="festival-days-box">
            <h5>Festival Days</h5>
            <ul>
                <?php foreach ($festival_days as $index => $day): ?>
                    <li><?= ($index + 1) ?>. <?= $day ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div id="weather-info">
            <h5>Moti në vendin e festivalit:</h5>
            <p id="weather">Duke u ngarkuar...</p>
        </div>
    </div>
</div>

                    <div class="col-lg-12 col-12 mt-auto d-flex flex-column flex-lg-row text-center">
                        <div class="date-wrap">
                            <h5 class="text-white">
                                <i class="custom-icon bi-clock me-2"></i>
                                10 - 12<sup>th</sup>, Aug 2025
                            </h5>
                        </div>

                        <div class="location-wrap mx-auto py-3 py-lg-0">
                            <h5 class="text-white">
                                <i class="custom-icon bi-geo-alt me-2"></i>
                                National Center, United States
                            </h5>
                        </div>

                        <div class="social-share">
                            <ul class="social-icon d-flex align-items-center justify-content-center">
                                <span class="text-white me-3">Share:</span>

                                <li class="social-icon-item">
                                    <a href="https://www.facebook.com" class="social-icon-link">
                                        <span class="bi-facebook"></span>
                                    </a>
                                </li>

                                <li class="social-icon-item">
                                    <a href="https://www.twitter.com" class="social-icon-link">
                                        <span class="bi-twitter"></span>
                                    </a>
                                </li>

                                <li class="social-icon-item">
                                    <a href="https://www.instagram.com" class="social-icon-link">
                                        <span class="bi-instagram"></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="video-wrap">
                <video autoplay="" loop="" muted="" class="custom-video" poster="">
                    <source src="video/pexels-2022395.mp4" type="video/mp4">

                    Your browser does not support the video tag.
                </video>
            </div>
        </section>


        <section class="about-section section-padding" id="section_2">
            <div class="container">
                <div class="row">

                    <div class="col-lg-6 col-12 mb-4 mb-lg-0 d-flex align-items-center">
                        <div class="services-info">
                            <h2 class="text-white mb-4">About Festava 2025</h2>

                            <p class="text-white">Festava 2025 is the ultimate summer festival experience, bringing together music lovers from around the world for an unforgettable weekend of live performances, art, and community. Festava is where memories are made.</p>

                            <h6 class="text-white mt-4">Once in Lifetime Experience</h6>

                            <p class="text-white">Festava 2025 offers a unique blend of relaxation and energy. Expect surprise performances, immersive installations, and a vibe that stays with you long after the festival ends.</p>

                            <h6 class="text-white mt-4">Whole Night Party</h6>

                            <p class="text-white">As the sun sets, the real magic begins. Dance under the stars with world-renowned DJs, dazzling light shows, and a crowd that’s ready to celebrate all night long.</p>
                        </div>
                    </div>

                    <div class="col-lg-6 col-12">
                        <div class="about-text-wrap">
                            <img src="images/pexels-alexander-suhorucov-6457579.jpg" class="about-image img-fluid">
                            
                            <div class="about-text-info d-flex">
                                <div class="d-flex">
                                    <i class="about-text-icon bi-person"></i>
                                </div>


                                <div class="ms-4">
                                    <h3>a happy moment</h3>

                                    <p class="mb-0">your amazing festival experience with us</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

          <!--Përdorimi i funksioneve për sortime të vargjeve (ksort(), arsort(), krsort())-->

          <section class="artists-section section-padding" id="section_3">
    <div class="container">
        <div class="row justify-content-center">

            <div class="col-12 text-center">
                <h2 class="mb-4">Meet Artists</h2>

                <form method="GET" class="mb-5">
                    <select name="sort" class="form-select mx-auto" style="width: 200px;" onchange="this.form.submit()">
                        <option value="name_asc" <?= ($_GET['sort'] ?? '') === 'name_asc' ? 'selected' : '' ?>>A-Z (Name)</option>
                        <option value="name_desc" <?= ($_GET['sort'] ?? '') === 'name_desc' ? 'selected' : '' ?>>Z-A (Name)</option>
                        <option value="age_asc" <?= ($_GET['sort'] ?? '') === 'age_asc' ? 'selected' : '' ?>>Age (Youngest First)</option>
                        <option value="age_desc" <?= ($_GET['sort'] ?? '') === 'age_desc' ? 'selected' : '' ?>>Age (Oldest First)</option>
                        <option value="genre_asc" <?= ($_GET['sort'] ?? '') === 'genre_asc' ? 'selected' : '' ?>>Genre (A-Z)</option>
                        <option value="genre_desc" <?= ($_GET['sort'] ?? '') === 'genre_desc' ? 'selected' : '' ?>>Genre (Z-A)</option>
                    </select>
                </form>
            </div>

            <?php 
        
            $artists = [
                'Madona' => ['age' => 65, 'genre' => 'Pop', 'image' => 'madonna.webp'],
                'Rihana' => ['age' => 35, 'genre' => 'R&B', 'image' => 'rihannaa.avif'],
                'Bruno Mars' => ['age' => 38, 'genre' => 'Pop', 'image' => 'brunomars.jpg']
            ];
            
            $firstArtist = &$artists['Madona'];
            $firstArtist['age'] = 70;

            unset($firstArtist);

            $sort_type = $_GET['sort'] ?? 'name_asc';

            switch ($sort_type) {
                case 'name_asc':
                    ksort($artists); 
                    break;
                    
                case 'name_desc':
                    krsort($artists); 
                    break;
                    
                case 'age_asc':
                    
                    $ages = array_column($artists, 'age');
                    asort($ages); 
                    
                    
                    $sorted_artists = [];
                    foreach ($ages as $key => $age) {
                        $name = array_keys($artists)[$key];
                        $sorted_artists[$name] = $artists[$name];
                    }
                    $artists = $sorted_artists;
                    break;
                    
                case 'age_desc':
                    $ages = array_column($artists, 'age');
                    arsort($ages); 
                    
                    $sorted_artists = [];
                    foreach ($ages as $key => $age) {
                        $name = array_keys($artists)[$key];
                        $sorted_artists[$name] = $artists[$name];
                    }
                    $artists = $sorted_artists;
                    break;
                    
                case 'genre_asc':
                    $genres = array_column($artists, 'genre');
                    asort($genres); 
                    
                    $sorted_artists = [];
                    foreach ($genres as $key => $genre) {
                        $name = array_keys($artists)[$key];
                        $sorted_artists[$name] = $artists[$name];
                    }
                    $artists = $sorted_artists;
                    break;
                    
                case 'genre_desc':
                    $genres = array_column($artists, 'genre');
                    arsort($genres); 
                    
                    $sorted_artists = [];
                    foreach ($genres as $key => $genre) {
                        $name = array_keys($artists)[$key];
                        $sorted_artists[$name] = $artists[$name];
                    }
                    $artists = $sorted_artists;
                    break;
            }
            ?>

        
            <div class="row">
                <?php foreach ($artists as $name => $artist): ?>
                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                        <div class="artists-thumb">
                            <div class="artists-image-wrap">
                                <img src="images/artists/<?= $artist['image'] ?>" 
                                     class="artists-image img-fluid" 
                                     alt="<?= $name ?>">
                            </div>

                            <div class="artists-hover">
                                <p><strong>Name:</strong> <?= $name ?></p>
                                <p><strong>Age:</strong> <?= $artist['age'] ?> years</p>
                                <p><strong>Genre:</strong> <?= $artist['genre'] ?></p>
                                <hr>
                                <p class="mb-0">
                                    <strong>Music:</strong> 
                                    <a href="#"><?= $artist['genre'] ?> Hits</a>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="text-center my-4">
    <h4>Vote for Your Favorite Artist</h4>
    <button class="btn btn-outline-primary vote-btn" data-artist="Madona">Madona</button>
    <button class="btn btn-outline-primary vote-btn" data-artist="Rihana">Rihana</button>
    <button class="btn btn-outline-primary vote-btn" data-artist="Bruno Mars">Bruno Mars</button>
</div>

<div class="mt-4" id="voteResults">
    <h5 class="text-center">Live Voting Results:</h5>
    <ul class="list-group w-50 mx-auto" id="voteList"></ul>
</div>

</section>

        <section class="schedule-section section-padding" id="section_4">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h2 class="text-white mb-4">Event Schedule</h2>

                <?php
                // 1. Definimi i të dhënave të orarit si multidimensional array
                $schedule = [
                    'Day 1' => [
                        'Wednesday' => [
                            'title' => 'Pop Night',
                            'time' => '5:00 - 7:00 PM',
                            'artist' => 'Adele',
                            'background' => 'pop-background-image'
                        ],
                        'Thursday' => [
                            'background' => '#F3DCD4'
                        ],
                        'Friday' => [
                            'title' => 'Rock & Roll',
                            'time' => '7:00 - 11:00 PM',
                            'artist' => 'Rihana',
                            'background' => 'rock-background-image'
                        ]
                    ],
                    'Day 2' => [
                        'Wednesday' => [
                            'background' => '#ECC9C7'
                        ],
                        'Thursday' => [
                            'title' => 'DJ Night',
                            'time' => '6:30 - 9:30 PM',
                            'artist' => 'Rihana'
                        ],
                        'Friday' => [
                            'background' => '#D9E3DA'
                        ]
                    ],
                    'Day 3' => [
                        'Wednesday' => [
                            'title' => 'Country Music',
                            'time' => '4:30 - 7:30 PM',
                            'artist' => 'Rihana',
                            'background' => 'country-background-image'
                        ],
                        'Thursday' => [
                            'background' => '#D1CFC0'
                        ],
                        'Friday' => [
                            'title' => 'Free Styles',
                            'time' => '6:00 - 10:00 PM',
                            'artist' => 'By Members'
                        ]
                    ]
                ];
                ?>

                <!-- 2. Tabela dinamike -->
                <div class="table-responsive">
                    <table class="schedule-table table table-dark">
                        <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Wednesday</th>
                                <th scope="col">Thursday</th>
                                <th scope="col">Friday</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($schedule as $day => $events): ?>
                                <tr>
                                    <th scope="row"><?= $day ?></th>
                                    <?php 
                                    // Renditja e ditëve sipas renditjes së dëshiruar
                                    $daysOrder = ['Wednesday', 'Thursday', 'Friday'];
                                    foreach ($daysOrder as $dayName): 
                                        $event = $events[$dayName] ?? [];
                                    ?>
                                        <td
                                      <?php if (isset($event['background'])): ?>
                                      <?php if (strpos($event['background'], '-background-image') !== false): ?>
                                     class="table-background-image-wrap <?= $event['background'] ?>"
                                     <?php else: ?>
                                     style="background-color: <?= $event['background'] ?>"
                                      <?php endif; ?>
                                       <?php endif; ?>
                                     >
                                      <?php if (!empty($event['title'])): ?>
                                      <h3><?= $event['title'] ?></h3>
                                       <p class="mb-2"><?= $event['time'] ?></p>
                                       <p>By <?= $event['artist'] ?></p>
                                        <?php if (strpos($event['background'] ?? '', '-background-image') !== false): ?>
                                        <div class="section-overlay"></div> 
                                        <?php endif; ?>
                                        <?php endif; ?>
                                     </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>


        <section class="pricing-section section-padding section-bg" id="section_5">
            <div class="container">
                <div class="row">

                    <div class="col-lg-8 col-12 mx-auto">
                        <h2 class="text-center mb-4">Plans you'll love</h2>
                    </div>

                    <div class="col-lg-6 col-12">
                        <div class="pricing-thumb">
                            <div class="d-flex">
                                <div>
                                    <h3><small>Early Bird</small> $120</h3>

                                    <p>Includes access to:</p>
                                </div>

                                <p class="pricing-tag ms-auto">Save up to <span>50%</span></h2>
                            </div>

                            <ul class="pricing-list mt-3">
                                <li class="pricing-list-item">All-day festival access</li>

                                <li class="pricing-list-item">Exclusive early entry</li>

                                <li class="pricing-list-item">Main stage performances</li>

                                <li class="pricing-list-item">High-quality sound and lighting</li>
                            </ul>

                            <a class="link-fx-1 color-contrast-higher mt-4" href="ticket.php">
                                <span>Buy Ticket</span>
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

                    <div class="col-lg-6 col-12 mt-4 mt-lg-0">
                        <div class="pricing-thumb">
                            <div class="d-flex">
                                <div>
                                    <h3><small>Standard</small> $240</h3>

                                    <p>What's included in the full Festava experience:</p>
                                </div>
                            </div>

                            <ul class="pricing-list mt-3">
                                <li class="pricing-list-item">Full weekend festival access</li>

                                <li class="pricing-list-item">Priority entry and VIP zone access</li>

                                <li class="pricing-list-item">Premium stage views</li>

                                <li class="pricing-list-item">High-quality sound and visuals</li>

                                <li class="pricing-list-item">Live chat support and concierge</li>
                            </ul>

                            <a class="link-fx-1 color-contrast-higher mt-4" href="ticket.php">
                                <span>Buy Ticket</span>
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
            </div>
        </section>


       <section class="contact-section section-padding" id="section_6">
    <div class="container">
        <div class="row">

            <div class="col-lg-8 col-12 mx-auto">
                <h2 class="text-center mb-4">Interested? Let's talk</h2>

                <nav class="d-flex justify-content-center">
                    <div class="nav nav-tabs align-items-baseline justify-content-center" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="nav-ContactForm-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-ContactForm" type="button" role="tab"
                            aria-controls="nav-ContactForm" aria-selected="true">
                            <h5>Contact Form</h5>
                        </button>

                        <button class="nav-link" id="nav-ContactMap-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-ContactMap" type="button" role="tab"
                            aria-controls="nav-ContactMap" aria-selected="false">
                            <h5>Google Maps</h5>
                        </button>
                    </div>
                </nav>

                <div class="tab-content shadow-lg mt-5" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-ContactForm" role="tabpanel" aria-labelledby="nav-ContactForm-tab">
                        
                        <?php if (isset($_SESSION['message_sent']) && $_SESSION['message_sent']): ?>
                            <div class="alert alert-success text-center" style="margin: 20px auto; width: 60%;">
                                We have received your message. You will be informed via email soon. Thank you!
                            </div>
                            <?php unset($_SESSION['message_sent']); ?>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['contact_error'])): ?>
                            <div class="alert alert-danger text-center" style="margin: 20px auto; width: 60%;">
                                <?= $_SESSION['contact_error']; ?>
                            </div>
                            <?php unset($_SESSION['contact_error']); ?>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['user'])): ?>
                        <!-- FORMULARI PËR PËRDORUES TË KYÇUR -->
                        <form class="custom-form contact-form mb-5 mb-lg-0" action="contact.php" method="post" role="form">
                            <div class="contact-form-body">

                                <!-- KOMPANIA -->
                                <div class="row">
                                    <div class="col-12">
                                        <input type="text" name="contact-company" id="contact-company" class="form-control" placeholder="Company" required>
                                    </div>
                                </div>

                                <!-- MESAZHI -->
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <textarea name="contact-message" rows="3" class="form-control" id="contact-message" placeholder="Message" required></textarea>
                                    </div>
                                </div>

                                <!-- BUTONI -->
                                <div class="col-lg-4 col-md-10 col-8 mx-auto mt-4">
                                    <button type="submit" class="form-control">Send message</button>
                                </div>
                            </div>
                            <div class="form-check mt-3">
    <input class="form-check-input" type="checkbox" name="also_send_email" id="also_send_email">
    <label class="form-check-label label-black" for="also_send_email" >
        Dërgo edhe përmes email
    </label>
</div>

                        </form>
                        <?php else: ?>
                            <!-- NUK LEJOHET PA LOGIN -->
                            <div class="alert alert-warning text-center" style="margin: 20px auto; width: 60%;">
                                You must be <a href="login.php">logged in</a> to send a message.
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- GOOGLE MAPS -->
                    <div class="tab-pane fade" id="nav-ContactMap" role="tabpanel" aria-labelledby="nav-ContactMap-tab">
                        <iframe class="google-map"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d29974.469402870927!2d120.94861466021855!3d14.106066818082482!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd777b1ab54c8f%3A0x6ecc514451ce2be8!2sTagaytay%2C%20Cavite%2C%20Philippines!5e1!3m2!1sen!2smy!4v1670344209509!5m2!1sen!2smy"
                            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
<?php
$tickets = [];
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $stmt = $conn->prepare("SELECT * FROM tickets WHERE user_id = ?");
    $stmt->bind_param("i", $user->id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $tickets[] = $row;
    }
}
?>

<section class="section-padding section-bg" id="section_7">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h2 id="my-tickets-title">My Tickets</h2>
            </div>

            <?php if (!isset($_SESSION['user'])): ?>
                <div class="alert alert-warning text-center">You must be logged in to view your tickets.</div>
            <?php elseif (empty($tickets)): ?>
                <div class="alert alert-info text-center">You haven't purchased any tickets yet.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered bg-white rounded shadow">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>ID</th>
                                <th>Type</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tickets as $ticket): ?>
                                <tr class="align-middle text-center">
                                    <form class="update-form" method="post">
                                        <td><?= $ticket['id'] ?></td>
                                        <td>
                                            <select name="ticket_type" class="form-select form-select-sm">
                                                <option value="Early Bird" <?= $ticket['ticket_type'] === 'Early Bird' ? 'selected' : '' ?>>Early Bird</option>
                                                <option value="Standard" <?= $ticket['ticket_type'] === 'Standard' ? 'selected' : '' ?>>Standard</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="num_tickets" value="<?= $ticket['num_tickets'] ?>" min="1" max="10" class="form-control form-control-sm">
                                        </td>
                                        <td>$<?= number_format($ticket['total_price'], 2) ?></td>
                                        <td>
                                            <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">
                                           <button type="submit" class="btn btn-festava btn-sm me-2">
                                           <i class="bi bi-arrow-repeat"></i> Update
                                           </button>
                                           <button type="button" class="btn btn-festava-outline btn-sm delete-btn" data-id="<?= $ticket['id'] ?>">
                                            <i class="bi bi-x-circle"></i> Cancel
                                            </button>


                                        </td>
                                    </form>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<br>
<section class="section-padding section-bg" id="section_8">
    <div class="container">
        <h2 class="mb-4">Sugjero artistin për vitin 2026</h2>
        <form id="suggestionForm">
            <input type="text" name="artist_name" placeholder="Shkruaj emrin e artistit..." required>
            <br><br>
            <button type="submit">Dërgo Sugjerimin</button>
        </form>
        <div id="suggestionMessage"></div>
    </div>
</section>




    </main>


    <footer class="site-footer">
        <div class="site-footer-top">
            <div class="container">
                <div class="row">

                    <div class="col-lg-6 col-12">
                        <h2 class="text-white mb-lg-0">Festava Live</h2>
                    </div>

                    <div class="col-lg-6 col-12 d-flex justify-content-lg-end align-items-center">
                        <ul class="social-icon d-flex justify-content-lg-end">
                            <li class="social-icon-item">
                                <a href="https://twitter.com" class="social-icon-link">
                                    <span class="bi-twitter"></span>
                                </a>
                            </li>

                            <li class="social-icon-item">
                                <a href="https://www.facebook.com" class="social-icon-link">
                                    <span class="bi-facebook"></span>
                                </a>
                            </li>

                            <li class="social-icon-item">
                                <a href="https://www.instagram.com" class="social-icon-link">
                                    <span class="bi-instagram"></span>
                                </a>
                            </li>

                            <li class="social-icon-item">
                                <a href="https://www.youtube.com" class="social-icon-link">
                                    <span class="bi-youtube"></span>
                                </a>
                            </li>

                            <li class="social-icon-item">
                                <a href="https://www.pinterest.com" class="social-icon-link">
                                    <span class="bi-pinterest"></span>
                                </a>
                            </li>
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
                        <li class="site-footer-link-item">
                        <a class="nav-link click-scroll site-footer-link" href="#section_1">Home</a>
                        </li>

                        <li class="site-footer-link-item">
                            <a class="nav-link click-scroll site-footer-link" href="#section_2">About</a>
                        </li>

                        <li class="site-footer-link-item">
                            <a class="nav-link click-scroll site-footer-link" href="#section_3">Artists</a>
                        </li>

                        <li class="site-footer-link-item">
                            <a class="nav-link click-scroll site-footer-link" href="#section_4">Schedule</a>
                        </li>

                        <li class="site-footer-link-item">
                            <a class="nav-link click-scroll site-footer-link" href="#section_5">Pricing</a>
                        </li>

                        <li class="site-footer-link-item">
                            <a class="nav-link click-scroll site-footer-link" href="#section_6">Contact</a>
                        </li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 col-12 mb-4 mb-lg-0">
                    <h5 class="site-footer-title mb-3">Have a question?</h5>

                    <p class="text-white d-flex mb-1">
                        <a href="tel: 090-080-0760" class="site-footer-link">
                            090-080-0760
                        </a>
                    </p>

                    <p class="text-white d-flex">
                        <a href="mailto:hello@company.com" class="site-footer-link">
                            hello@company.com
                        </a>
                    </p>
                </div>
            
                <div class="col-lg-3 col-md-6 col-11 mb-4 mb-lg-0 mb-md-0">
                    <h5 class="site-footer-title mb-3">Location</h5>

                    <p class="text-white d-flex mt-3 mb-2">
                        Silang Junction South, Tagaytay, Cavite, Philippines</p>

                    <a class="link-fx-1 color-contrast-higher mt-3" href="#section_6"  onclick="showMapTab()">
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
    Kjo faqe është vizituar <?= $_SESSION['visit_count'] ?> herë
</div>
    </footer>

    <!-- JAVASCRIPT FILES -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.sticky.js"></script>
    <script src="js/click-scroll.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/vote.js"></script>


    <script>
        function showMapTab() {
            var mapTab = new bootstrap.Tab(document.querySelector('#nav-ContactMap-tab'));
            mapTab.show();
        }
        
  $("#suggestionForm").submit(function(e) {
    e.preventDefault();
    var artist = $("input[name='artist_name']").val().trim();

    $.post("submit_suggestion.php", { artist_name: artist }, function(response) {
      $("#suggestionMessage").html(response);
      $("#suggestionForm")[0].reset();
    });
  });

    </script>

<?php 
 if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['last_order'])) {
    echo "<div class='alert alert-success text-center'>";
echo "Porosia e fundit: " . $_SESSION['last_order']['quantity'] . " x " . $_SESSION['last_order']['type'] . " për $" . $_SESSION['last_order']['total'];
    echo "</div>";
}
if (isset($_COOKIE['last_ticket'])) {
    echo "<div class='alert alert-info text-center'>";
    echo "Bileta e fundit e porositur ishte: " . htmlspecialchars($_COOKIE['last_ticket']);
    echo "</div>";
} ?>
<script>
    setTimeout(function () {
        const alerts = document.querySelectorAll('.alert-success'); 
        alerts.forEach(function (alert) {
            alert.style.transition = 'opacity 1s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 1000);
        });
    }, 5000);
    $(document).ready(function () {
    $(".update-form").submit(function (e) {
        e.preventDefault();
        $.post("update_ticket.php", $(this).serialize(), function (response) {
            if (response.trim() === "success") {
                alert("Bileta u përditësua me sukses.");
                location.reload();
            } else {
                alert("Gabim: " + response);
            }
        });
    });

    $(".delete-btn").click(function () {
        if (confirm("A jeni i sigurt që dëshironi ta anuloni këtë biletë?")) {
            const ticketId = $(this).data("id");
            $.post("delete_ticket.php", { id: ticketId }, function (response) {
                if (response.trim() === "success") {
                    alert("Bileta u anulua.");
                    location.reload();
                } else {
                    alert("Gabim: " + response);
                }
            });
        }
    });
});
    // Sugjerimi i artistit
    $("#suggestionForm").submit(function(e) {
        e.preventDefault();
        const artist = $("input[name='artist_name']").val().trim();

        if (artist.length < 2) {
            $("#suggestionMessage").html("<div class='alert alert-danger'>Emri i artistit është shumë i shkurtër.</div>");
            return;
        }

        $.post("ajax_handler.php", { artist_name: artist }, function(response) {
            $("#suggestionMessage").html(response);
            $("#suggestionForm")[0].reset();
        });
    });

    // Votimi për artistin
    $(".vote-btn").click(function () {
        const artist = $(this).data("artist");

        $.post("ajax_handler.php", { vote_artist: artist }, function (response) {
            if (response === "success") {
                alert(" Votimi u ruajt me sukses!");
                loadVotes();
            } else if (response === "already_voted") {
                alert(" Ju keni votuar tashmë!");
            } else {
                alert(" Gabim gjatë votimit.");
            }
        });
    

    // Leximi i votave (me AJAX)
    function loadVotes() {
        $.get("ajax_handler.php", { get_votes: true }, function(data) {
            let html = "";
            $.each(data, function(artist, count) {
                html += `<li class='list-group-item d-flex justify-content-between'>
                            <strong>${artist}</strong> <span>${count} vota</span>
                         </li>`;
            });
            $("#voteList").html(html);
        });
    }

    // Ngarko votat sapo të hapet faqja
    loadVotes();
});
document.addEventListener("DOMContentLoaded", function () {
    fetch("weather_api.php")
        .then(res => res.json())
        .then(data => {
            if (data && data.main && data.weather) {
                const temp = data.main.temp;
                const desc = data.weather[0].description;
                document.getElementById("weather").innerText =
                    `Temperatura: ${temp}°C, ${desc}`;
            } else {
                document.getElementById("weather").innerText = "Gabim gjatë kërkesës.";
            }
        })
        .catch(err => {
            document.getElementById("weather").innerText = "Gabim gjatë ngarkimit të motit.";
            console.error("Gabim në fetch motin:", err);
        });
});

</script>

</body>

</html>