<?php
session_start();
// Ruaj preferencën e sortimit në sesion
if (isset($_GET['sort'])) {
    $_SESSION['sort_preference'] = $_GET['sort'];
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


</head>

<body>

    <main>

        <header class="site-header">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-12 d-flex align-items-center">
                    
                        <p class="d-flex mb-0 align-items-center">
                            <strong class="text-dark">Welcome to Music Festival 2023</strong>
                        </p>
        
                        
                        <a href="login.php" class="ms-auto d-flex align-items-center text-dark text-decoration-none">
                            <i class="bi bi-person-circle me-1 fs-5"></i>
                            <span class="fw-bold">Login</span>
                        </a>
        
        
                    </div>
                </div>
            </div>
        </header>
        
        


        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="index.php">
                    Festava Live
                </a>

                <a href="ticket.php" class="btn custom-btn d-lg-none ms-auto me-4">Buy Ticket</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav align-items-lg-center ms-auto me-lg-5">
                        <li class="nav-item">
                            <a class="nav-link click-scroll" href="#section_1">Home</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link click-scroll" href="#section_2">About</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link click-scroll" href="#section_3">Artists</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link click-scroll" href="#section_4">Schedule</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link click-scroll" href="#section_5">Pricing</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link click-scroll" href="#section_6">Contact</a>
                        </li>

                    </ul>
                    

                    <a href="ticket.php" class="btn custom-btn d-lg-block d-none">Buy Ticket</a>
                    

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
                        <h2 class="mb-4">Meet Artists</h1>

                        <!-- Forma për sortim -->
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
                     // Vargu i artistëve me të dhëna të plota (emër, moshë, zhanr, foto)
            $artists = [
                [
                    'name' => 'Madona',
                    'age' => 65,
                    'genre' => 'Pop',
                    'image' => 'madonna.webp'
                ],
                [
                    'name' => 'Rihana',
                    'age' => 35,
                    'genre' => 'R&B',
                    'image' => 'rihannaa.avif'
                ],
                [
                    'name' => 'Bruno Mars',
                    'age' => 38,
                    'genre' => 'Pop',
                    'image' => 'brunomars.jpg'
                ]
                
            ];
                // Sortimi bazuar në zgjedhjen e përdoruesit
            $sort_type = $_GET['sort'] ?? 'name_asc';
            usort($artists, function($a, $b) use ($sort_type) {
                switch ($sort_type) {
                    case 'name_asc':  return strcmp($a['name'], $b['name']);
                    case 'name_desc': return strcmp($b['name'], $a['name']);
                    case 'age_asc':   return $a['age'] - $b['age'];
                    case 'age_desc':  return $b['age'] - $a['age'];
                    case 'genre_asc': return strcmp($a['genre'], $b['genre']);
                    case 'genre_desc':return strcmp($b['genre'], $a['genre']);
                    default:          return 0;
                }
            });

                    ?>
                    <!-- Shfaqja e artistëve (me strukturë të njëjtë si origjinali) -->
            <div class="row">
                <?php foreach ($artists as $artist): ?>
                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                        <div class="artists-thumb">
                            <div class="artists-image-wrap">
                                <img src="images/artists/<?= $artist['image'] ?>" 
                                     class="artists-image img-fluid" 
                                     alt="<?= $artist['name'] ?>">
                            </div>

                            <div class="artists-hover">
                                <p><strong>Name:</strong> <?= $artist['name'] ?></p>
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
                            <div class="nav nav-tabs align-items-baseline justify-content-center" id="nav-tab"
                                role="tablist">
                                <button class="nav-link active" id="nav-ContactForm-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-ContactForm" type="button" role="tab"
                                    aria-controls="nav-ContactForm" aria-selected="false">
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
                            <div class="tab-pane fade show active" id="nav-ContactForm" role="tabpanel"
                                aria-labelledby="nav-ContactForm-tab">
                                <form class="custom-form contact-form mb-5 mb-lg-0" action="#" method="post"
                                    role="form">
                                    <div class="contact-form-body">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-12">
                                                <input type="text" name="contact-name" id="contact-name"
                                                    class="form-control" placeholder="Full name" required>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-12">
                                                <input type="email" name="contact-email" id="contact-email"
                                                    pattern="[^ @]*@[^ @]*" class="form-control"
                                                    placeholder="Email address" required>
                                            </div>
                                        </div>

                                        <input type="text" name="contact-company" id="contact-company"
                                            class="form-control" placeholder="Company" required>

                                        <textarea name="contact-message" rows="3" class="form-control"
                                            id="contact-message" placeholder="Message"></textarea>

                                        <div class="col-lg-4 col-md-10 col-8 mx-auto">
                                            <button type="submit" class="form-control">Send message</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="nav-ContactMap" role="tabpanel"
                                aria-labelledby="nav-ContactMap-tab">
                                <iframe class="google-map"
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d29974.469402870927!2d120.94861466021855!3d14.106066818082482!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd777b1ab54c8f%3A0x6ecc514451ce2be8!2sTagaytay%2C%20Cavite%2C%20Philippines!5e1!3m2!1sen!2smy!4v1670344209509!5m2!1sen!2smy"
                                    width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"></iframe>
                                <!-- You can easily copy the embed code from Google Maps -> Share -> Embed a map // -->
                            </div>
                        </div>
                    </div>

                </div>
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
                            <a href="#" class="site-footer-link">Home</a>
                        </li>

                        <li class="site-footer-link-item">
                            <a href="#" class="site-footer-link">About</a>
                        </li>

                        <li class="site-footer-link-item">
                            <a href="#" class="site-footer-link">Artists</a>
                        </li>

                        <li class="site-footer-link-item">
                            <a href="#" class="site-footer-link">Schedule</a>
                        </li>

                        <li class="site-footer-link-item">
                            <a href="#" class="site-footer-link">Pricing</a>
                        </li>

                        <li class="site-footer-link-item">
                            <a href="#" class="site-footer-link">Contact</a>
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
    </footer>

    <!--

T e m p l a t e M o

-->

    <!-- JAVASCRIPT FILES -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.sticky.js"></script>
    <script src="js/click-scroll.js"></script>
    <script src="js/custom.js"></script>

    <script>
        function showMapTab() {
            var mapTab = new bootstrap.Tab(document.querySelector('#nav-ContactMap-tab'));
            mapTab.show();
        }
    </script>

</body>

</html>