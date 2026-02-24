<?php
// index.php - Main Page (FIXED)
// ✅ Headers - SABSE PEHLE, before ANY output or includes
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/config.php';
require_once 'includes/header.php';  // ✅ Header include - isme HTML output start hota hai
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Habibi Institute of Higher Education - Best College in Moradabad</title>
    
    <!-- Meta Tags for SEO -->
    <meta name="description" content="<?php echo $settings['meta_description'] ?? 'Habibi Institute of Higher Education offers B.A., B.Sc., D.Pharma, LLB, B.Ed courses. Affiliated to MJPRU, AICTE, BCI. Apply for admission 2026-27.'; ?>">
    <meta name="keywords" content="<?php echo $settings['meta_keywords'] ?? 'college, higher education, Moradabad, MJPRU, pharmacy college, law college, B.Ed college'; ?>">
    <meta name="author" content="Habibi Institute">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        /* ===== COLOR THEME ===== */
        :root {
            --deep-red: #8B0000;
            --maroon: #6D0000;
            --gold: #C9A227;
            --light-gold: #E6C55C;
            --white: #FFFFFF;
            --light-grey: #F8F9FA;
        }
        
        /* ===== GLOBAL STYLES ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            color: #333;
            overflow-x: hidden;
            background: var(--white);
        }
        
        /* ===== NAVBAR ===== */
        .navbar {
            background: linear-gradient(135deg, var(--deep-red), var(--maroon));
            padding: 15px 0;
            box-shadow: 0 2px 20px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }
        
        .navbar-brand img {
            height: 50px;
            transition: transform 0.3s ease;
        }
        
        .navbar-brand img:hover {
            transform: scale(1.05);
        }
        
        .navbar-nav .nav-link {
            color: var(--white) !important;
            font-weight: 500;
            padding: 8px 16px !important;
            margin: 0 5px;
            position: relative;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .navbar-nav .nav-link:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: var(--gold);
            transition: width 0.3s ease;
        }
        
        .navbar-nav .nav-link:hover:after,
        .navbar-nav .nav-link.active:after {
            width: 80%;
        }
        
        .navbar-nav .nav-link:hover {
            color: var(--gold) !important;
        }
        
        .navbar-toggler {
            border: 2px solid var(--gold);
            padding: 8px 12px;
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23C9A227' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        /* ===== ADMISSION STATUS BAR ===== */
        .admission-bar {
            background: var(--gold);
            color: var(--maroon);
            padding: 12px 0;
            font-weight: 600;
            text-align: center;
            position: relative;
            z-index: 999;
            font-size: 1.1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .admission-bar .btn {
            background: var(--maroon);
            color: var(--gold);
            border-radius: 50px;
            padding: 8px 30px;
            font-weight: 600;
            margin-left: 15px;
            border: none;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 1rem;
        }
        
        .admission-bar .btn:hover {
            background: var(--deep-red);
            color: var(--light-gold);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(109,0,0,0.3);
        }
        
        /* ===== CAROUSEL STYLES ===== */
        .carousel {
            margin-top: 0;
        }
        
        .carousel-item {
            height: 80vh;
            min-height: 500px;
            background: #000;
        }
        
        .carousel-item img {
            object-fit: cover;
            height: 100%;
            width: 100%;
            opacity: 0.8;
        }
        
        .carousel-caption {
            background: rgba(0,0,0,0.6);
            padding: 40px;
            border-radius: 15px;
            bottom: 50%;
            transform: translateY(50%);
            max-width: 800px;
            margin: 0 auto;
            left: 0;
            right: 0;
        }
        
        .carousel-caption .admission-badge {
            background: var(--gold);
            color: var(--maroon);
            padding: 8px 25px;
            border-radius: 50px;
            display: inline-block;
            margin-bottom: 20px;
            font-weight: 600;
            font-size: 1rem;
        }
        
        .carousel-caption h1 {
            color: var(--gold);
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .carousel-caption p {
            color: var(--white);
            font-size: 1.2rem;
            margin-bottom: 25px;
        }
        
        .carousel-caption .btn-gold,
        .carousel-caption .btn-maroon {
            padding: 12px 30px;
            font-size: 1rem;
        }
        
        .carousel-indicators button {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: var(--gold);
            opacity: 0.5;
        }
        
        .carousel-indicators button.active {
            opacity: 1;
        }
        
        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: var(--deep-red);
            border-radius: 50%;
            padding: 20px;
        }
        
        /* ===== BUTTONS ===== */
        .btn-gold {
            background: var(--gold);
            color: var(--maroon);
            padding: 14px 35px;
            border-radius: 50px;
            text-decoration: none;
            display: inline-block;
            margin: 8px;
            font-weight: 600;
            font-size: 1.1rem;
            border: none;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .btn-gold:hover {
            background: var(--light-gold);
            color: var(--deep-red);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(201,162,39,0.4);
        }
        
        .btn-maroon {
            background: var(--maroon);
            color: var(--gold);
            padding: 14px 35px;
            border-radius: 50px;
            text-decoration: none;
            display: inline-block;
            margin: 8px;
            font-weight: 600;
            font-size: 1.1rem;
            border: none;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .btn-maroon:hover {
            background: var(--deep-red);
            color: var(--light-gold);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(109,0,0,0.4);
        }
        
        /* ===== SECTION TITLE ===== */
        .section-title {
            text-align: center;
            margin-bottom: 60px;
            color: var(--maroon);
            font-size: 2.5rem;
            font-weight: 700;
            position: relative;
        }
        
        .section-title span {
            color: var(--gold);
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, var(--gold), var(--maroon));
            border-radius: 2px;
        }
        
        /* ===== COURSE CARDS ===== */
        .course-card {
            background: var(--white);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            height: 100%;
            border: 1px solid var(--light-gold);
            transition: all 0.3s ease;
            margin-bottom: 25px;
        }
        
        .course-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(139,0,0,0.2);
            border-color: var(--gold);
        }
        
        .course-header {
            background: linear-gradient(135deg, var(--deep-red), var(--maroon));
            color: var(--gold);
            padding: 25px 20px;
            text-align: center;
        }
        
        .course-header h3 {
            color: var(--gold);
            margin: 0;
            font-size: 1.3rem;
            font-weight: 600;
        }
        
        .course-body {
            padding: 25px 20px;
        }
        
        .course-duration {
            color: var(--deep-red);
            font-weight: 600;
            margin-bottom: 15px;
            font-size: 1rem;
        }
        
        .course-duration i {
            color: var(--gold);
        }
        
        /* ===== WHY CHOOSE CARDS ===== */
        .why-card {
            text-align: center;
            padding: 35px 25px;
            background: var(--white);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            height: 100%;
            border-bottom: 4px solid var(--gold);
            transition: all 0.3s ease;
        }
        
        .why-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(139,0,0,0.1);
        }
        
        .why-card i {
            font-size: 3rem;
            color: var(--deep-red);
            margin-bottom: 20px;
        }
        
        .why-card h3 {
            color: var(--maroon);
            margin-bottom: 15px;
            font-size: 1.3rem;
            font-weight: 600;
        }
        
        .why-card p {
            color: #666;
            margin: 0;
            font-size: 0.95rem;
            line-height: 1.6;
        }
        
        /* ===== OFFER CARDS ===== */
        .offer-card {
            background: linear-gradient(135deg, var(--gold), var(--light-gold));
            border-radius: 15px;
            padding: 35px 25px;
            text-align: center;
            height: 100%;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .offer-card:hover {
            transform: scale(1.05);
            box-shadow: 0 20px 40px rgba(201,162,39,0.3);
        }
        
        .offer-card i {
            font-size: 3rem;
            color: var(--maroon);
            margin-bottom: 20px;
        }
        
        .offer-card h3 {
            color: var(--maroon);
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .offer-card .discount {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--deep-red);
            margin: 20px 0;
        }
        
        .offer-badge {
            background: var(--maroon);
            color: var(--gold);
            padding: 8px 20px;
            border-radius: 50px;
            display: inline-block;
            font-weight: 500;
        }
        
        /* ===== MISSION SECTION ===== */
        .mission-section {
            background: linear-gradient(rgba(109,0,0,0.95), rgba(139,0,0,0.95)), 
                        url('https://images.unsplash.com/photo-1523580494863-6f3031224c94?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80');
            background-size: cover;
            background-attachment: fixed;
            padding: 80px 0;
            color: var(--white);
        }
        
        .mission-section h2 {
            color: var(--gold);
            font-size: 2.2rem;
            margin-bottom: 30px;
        }
        
        .mission-points {
            list-style: none;
            padding: 0;
        }
        
        .mission-points li {
            margin-bottom: 20px;
            padding-left: 35px;
            position: relative;
            font-size: 1.1rem;
        }
        
        .mission-points li:before {
            content: '✓';
            background: var(--gold);
            color: var(--maroon);
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            left: 0;
            font-weight: bold;
        }
        
        /* ===== PHARMACY HIGHLIGHT ===== */
        .pharmacy-highlight {
            background: linear-gradient(135deg, var(--maroon), var(--deep-red));
            padding: 80px 0;
            text-align: center;
            color: var(--white);
            clip-path: polygon(0 0, 100% 0, 100% 90%, 0 100%);
        }
        
        .pharmacy-highlight h2 {
            color: var(--gold);
            font-size: 3rem;
            font-weight: 700;
            margin: 25px 0;
        }
        
        .pharmacy-highlight .badge {
            background: var(--gold);
            color: var(--maroon);
            padding: 12px 35px;
            border-radius: 50px;
            font-size: 1.2rem;
            font-weight: 600;
        }
        
        /* ===== CONTACT CTA ===== */
        .contact-cta {
            background: var(--gold);
            padding: 80px 0;
            text-align: center;
        }
        
        .contact-cta h2 {
            color: var(--maroon);
            font-size: 2.5rem;
            margin-bottom: 20px;
        }
        
        .contact-cta .lead {
            color: var(--deep-red);
            font-size: 1.2rem;
            margin-bottom: 30px;
        }
        
        /* ===== FOOTER ===== */
        footer {
            background: var(--maroon);
            color: var(--white);
            padding: 60px 0 20px;
        }
        
        footer h5 {
            color: var(--gold);
            margin-bottom: 25px;
            font-weight: 600;
        }
        
        footer a {
            color: var(--white);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        footer a:hover {
            color: var(--gold);
        }
        
        footer ul {
            list-style: none;
            padding: 0;
        }
        
        footer ul li {
            margin-bottom: 12px;
        }
        
        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(201,162,39,0.2);
            border-radius: 50%;
            margin-right: 10px;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background: var(--gold);
            color: var(--maroon);
            transform: translateY(-3px);
        }
        
        .footer-bottom {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid rgba(201,162,39,0.3);
            text-align: center;
        }
        
        /* ===== WHATSAPP FLOAT ===== */
        .whatsapp-float {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: var(--gold);
            color: var(--maroon);
            width: 65px;
            height: 65px;
            border-radius: 50%;
            text-align: center;
            font-size: 35px;
            line-height: 65px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            z-index: 1000;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .whatsapp-float:hover {
            transform: scale(1.1) translateY(-5px);
            background: var(--light-gold);
            color: var(--deep-red);
        }
        
        /* ===== MODAL ===== */
        .modal-header {
            background: var(--deep-red);
            color: var(--gold);
            border: none;
        }
        
        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }
        
        .modal .btn-primary {
            background: var(--deep-red);
            border-color: var(--deep-red);
            color: var(--gold);
            padding: 12px 30px;
        }
        
        .modal .btn-primary:hover {
            background: var(--maroon);
        }
        
        /* ===== RESPONSIVE ===== */
        @media (max-width: 991px) {
            .navbar-nav {
                padding: 20px 0;
            }
            
            .navbar-nav .nav-link {
                padding: 12px 20px !important;
                margin: 5px 0;
            }
        }
        
        @media (max-width: 768px) {
            .carousel-item {
                height: 60vh;
            }
            
            .carousel-caption {
                padding: 20px;
            }
            
            .carousel-caption h1 {
                font-size: 2rem;
            }
            
            .carousel-caption p {
                font-size: 1rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .pharmacy-highlight h2 {
                font-size: 2rem;
            }
            
            .pharmacy-highlight {
                clip-path: polygon(0 0, 100% 0, 100% 95%, 0 100%);
            }
            
            .whatsapp-float {
                width: 55px;
                height: 55px;
                font-size: 30px;
                line-height: 55px;
                bottom: 20px;
                right: 20px;
            }
        }
        
        @media (max-width: 576px) {
            .navbar-brand img {
                height: 40px;
            }
            
            .admission-bar {
                font-size: 0.9rem;
            }
            
            .admission-bar .btn {
                display: block;
                margin: 10px auto 0;
                width: fit-content;
            }
            
            .carousel-item {
                height: 50vh;
            }
            
            .carousel-caption h1 {
                font-size: 1.5rem;
            }
            
            .btn-gold, .btn-maroon {
                padding: 10px 25px;
                font-size: 0.9rem;
            }
            
            .section-title {
                font-size: 1.8rem;
            }
            
            .course-header h3 {
                font-size: 1.1rem;
            }
            
            .pharmacy-highlight h2 {
                font-size: 1.6rem;
            }
            
            .contact-cta h2 {
                font-size: 1.8rem;
            }
        }
        
        /* Landscape Mode */
        @media (max-height: 500px) and (orientation: landscape) {
            .carousel-item {
                height: 100vh;
            }
            
            .whatsapp-float {
                width: 45px;
                height: 45px;
                font-size: 25px;
                line-height: 45px;
            }
        }
    </style>
</head>
<body>

<!-- ===== HERO CAROUSEL ===== -->
<div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
    <!-- Indicators -->
    <div class="carousel-indicators">
        <?php
        try {
            // Fetch active slides
            $stmt = $pdo->query("SELECT * FROM carousel_slides WHERE status = 1 ORDER BY sort_order ASC");
            $slides = $stmt->fetchAll();
            
            if (!empty($slides)) {
                foreach($slides as $index => $slide):
        ?>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?php echo $index; ?>" 
                class="<?php echo $index == 0 ? 'active' : ''; ?>" 
                aria-label="Slide <?php echo $index + 1; ?>"></button>
        <?php 
                endforeach;
            }
        } catch(Exception $e) {
            // Silently fail - will show default slide
        }
        ?>
    </div>
    
    <!-- Carousel Inner -->
    <div class="carousel-inner">
        <?php
        try {
            if (!empty($slides)):
                foreach($slides as $index => $slide):
                    // Fix image path
                    $image_path = $slide['image_path'] ?? '';
                    if (!empty($image_path)) {
                        // Remove any leading './' or '../'
                        $image_path = ltrim($image_path, './');
                        // Ensure path starts with assets/
                        if (strpos($image_path, 'assets/') !== 0) {
                            $image_path = 'assets/uploads/carousel/' . basename($image_path);
                        }
                        $image_url = BASE_URL . $image_path;
                    } else {
                        $image_url = BASE_URL . 'assets/img/default-slide.jpg';
                    }
        ?>
        <div class="carousel-item <?php echo $index == 0 ? 'active' : ''; ?>">
            <img src="<?php echo $image_url; ?>" class="d-block w-100" alt="<?php echo htmlspecialchars($slide['title']); ?>">
            <div class="carousel-caption d-none d-md-block">
                <span class="admission-badge"><?php echo $settings['admission_status'] ?? 'ADMISSION OPEN 2026-27'; ?></span>
                <h1><?php echo htmlspecialchars($slide['title'] ?? 'Welcome to Habibi Institute'); ?></h1>
                <p><?php echo htmlspecialchars($slide['subtitle'] ?? 'Excellence in Higher Education'); ?></p>
                <div class="mt-4">
                    <?php if(!empty($slide['button1_text'])): ?>
                    <a href="<?php echo htmlspecialchars($slide['button1_link'] ?? '#'); ?>" class="btn-gold">
                        <?php echo htmlspecialchars($slide['button1_text']); ?>
                    </a>
                    <?php endif; ?>
                    
                    <?php if(!empty($slide['button2_text'])): ?>
                    <a href="#admissionModal" data-bs-toggle="modal" class="btn-maroon">
                        <?php echo htmlspecialchars($slide['button2_text']); ?>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php 
                endforeach;
            else:
        ?>
        <!-- Default Slide -->
        <div class="carousel-item active">
            <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80" class="d-block w-100" alt="Welcome">
            <div class="carousel-caption d-none d-md-block">
                <span class="admission-badge">ADMISSION OPEN 2026-27</span>
                <h1>Welcome to Habibi Institute</h1>
                <p>Affiliated to MJPRU, NCTE, AICTE, BCI, BTE Lucknow</p>
                <div class="mt-4">
                    <a href="#admissionModal" data-bs-toggle="modal" class="btn-gold">Apply Now</a>
                    <a href="courses.php" class="btn-maroon">View Courses</a>
                </div>
            </div>
        </div>
        <?php 
            endif;
        } catch(Exception $e) {
            echo "<!-- Carousel Error: " . $e->getMessage() . " -->";
        ?>
        <!-- Fallback Slide -->
        <div class="carousel-item active">
            <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80" class="d-block w-100" alt="Welcome">
            <div class="carousel-caption d-none d-md-block">
                <span class="admission-badge">ADMISSION OPEN 2026-27</span>
                <h1>Welcome to Habibi Institute</h1>
                <p>Affiliated to MJPRU, NCTE, AICTE, BCI, BTE Lucknow</p>
                <div class="mt-4">
                    <a href="#admissionModal" data-bs-toggle="modal" class="btn-gold">Apply Now</a>
                    <a href="courses.php" class="btn-maroon">View Courses</a>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
    
    <!-- Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<!-- ===== COURSES SECTION ===== -->
<section class="py-5">
    <div class="container">
        <h2 class="section-title" data-aos="fade-up">Our <span>Programs</span></h2>
        
        <div class="row g-4">
            <?php
            try {
                $stmt = $pdo->query("SELECT * FROM courses WHERE status = 1 ORDER BY name LIMIT 6");
                $courses = $stmt->fetchAll();
                
                if (!empty($courses)):
                    foreach($courses as $index => $course):
            ?>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                <div class="course-card">
                    <div class="course-header">
                        <h3><?php echo htmlspecialchars($course['name']); ?></h3>
                    </div>
                    <div class="course-body">
                        <div class="course-duration">
                            <i class="far fa-clock me-2"></i>
                            Duration: <?php echo htmlspecialchars($course['duration'] ?? '3 Years'); ?>
                        </div>
                        
                        <?php if($course['slug'] == 'ba'): ?>
                        <p class="mb-3">Hindi, English, Urdu, Sociology, Geography, Home Science</p>
                        <?php elseif($course['slug'] == 'bsc'): ?>
                        <p class="mb-3">Zoology, Botany, Chemistry, Physics, Mathematics</p>
                        <?php else: ?>
                        <p class="mb-3"><?php echo htmlspecialchars(substr($course['description'] ?? 'Comprehensive program with excellent career prospects.', 0, 100)); ?>...</p>
                        <?php endif; ?>
                        
                        <a href="course-detail.php?slug=<?php echo $course['slug']; ?>" class="btn btn-maroon w-100">
                            View Details <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php 
                    endforeach;
                else:
                    // Fallback courses if no data
                    $fallback = [
                        ['name' => 'B.A. (Bachelor of Arts)', 'desc' => 'Hindi, English, Urdu, Sociology, Geography, Home Science'],
                        ['name' => 'B.Sc. (Bachelor of Science)', 'desc' => 'Zoology, Botany, Chemistry, Physics, Mathematics'],
                        ['name' => 'D.Pharma (Diploma in Pharmacy)', 'desc' => 'After Class 12, Duration 2 Years'],
                    ];
                    
                    foreach($fallback as $index => $course):
            ?>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                <div class="course-card">
                    <div class="course-header">
                        <h3><?php echo $course['name']; ?></h3>
                    </div>
                    <div class="course-body">
                        <p class="mb-3"><?php echo $course['desc']; ?></p>
                        <a href="#" class="btn btn-maroon w-100">View Details</a>
                    </div>
                </div>
            </div>
            <?php 
                    endforeach;
                endif;
            } catch(Exception $e) {
                echo "<!-- Courses Error: " . $e->getMessage() . " -->";
            } 
            ?>
        </div>
        
        <div class="text-center mt-5" data-aos="fade-up">
            <a href="courses.php" class="btn-gold btn-lg">
                View All Programs <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- ===== WHY CHOOSE US ===== -->
<section class="py-5" style="background: var(--light-grey);">
    <div class="container">
        <h2 class="section-title" data-aos="fade-up">Why Choose <span>Habibi Institute?</span></h2>
        
        <div class="row g-4">
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="0">
                <div class="why-card">
                    <i class="fas fa-tree"></i>
                    <h3>Lush Green Campus</h3>
                    <p>Spread across acres of greenery with modern facilities and peaceful environment.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="why-card">
                    <i class="fas fa-flask"></i>
                    <h3>Modern Laboratories</h3>
                    <p>Fully equipped laboratories with latest equipment for practical learning.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="why-card">
                    <i class="fas fa-wifi"></i>
                    <h3>Wi-Fi Campus</h3>
                    <p>High-speed internet connectivity across the entire campus.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="why-card">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <h3>Expert Faculty</h3>
                    <p>Highly qualified and experienced teaching staff dedicated to student success.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="why-card">
                    <i class="fas fa-bus"></i>
                    <h3>Transport Facility</h3>
                    <p>Reliable bus service covering major routes for student convenience.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                <div class="why-card">
                    <i class="fas fa-book"></i>
                    <h3>Rich Library</h3>
                    <p>Extensive collection of books, journals, and digital resources.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== SPECIAL OFFERS ===== -->
<section class="py-5">
    <div class="container">
        <h2 class="section-title" data-aos="fade-up">Special <span>Offers</span></h2>
        
        <div class="row g-4">
            <div class="col-md-4" data-aos="zoom-in" data-aos-delay="0">
                <div class="offer-card">
                    <i class="fas fa-graduation-cap"></i>
                    <h3>B.A. (First Year)</h3>
                    <div class="discount">₹500 OFF</div>
                    <p>Tuition Fees Discount</p>
                    <span class="offer-badge">Limited Time</span>
                </div>
            </div>
            <div class="col-md-4" data-aos="zoom-in" data-aos-delay="200">
                <div class="offer-card">
                    <i class="fas fa-flask"></i>
                    <h3>B.Sc. Programs</h3>
                    <div class="discount">₹500 OFF</div>
                    <p>Tuition Fees Discount</p>
                    <span class="offer-badge">Limited Time</span>
                </div>
            </div>
            <div class="col-md-4" data-aos="zoom-in" data-aos-delay="400">
                <div class="offer-card">
                    <i class="fas fa-capsules"></i>
                    <h3>D.Pharma</h3>
                    <div class="discount">5% OFF</div>
                    <p>On Total Fees</p>
                    <span class="offer-badge">Limited Time</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== MISSION SECTION ===== -->
<section class="mission-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
                <h2>Our Mission</h2>
                <ul class="mission-points">
                    <li>To deliver high-quality education across diverse fields.</li>
                    <li>To create a supportive and inspiring environment for holistic student growth.</li>
                    <li>To ensure our graduates excel academically, socially and professionally.</li>
                </ul>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <img src="assets/img/campus.jpeg" 
                     alt="Our Mission" 
                     class="img-fluid rounded-3 shadow"
                     style="border: 5px solid var(--gold);">
            </div>
        </div>
    </div>
</section>

<!-- ===== PHARMACY HIGHLIGHT ===== -->
<section class="pharmacy-highlight">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto" data-aos="fade-up">
                <span class="badge">
                    <i class="fas fa-trophy me-2"></i>#1 in Region
                </span>
                <h2>BEST PHARMACY COLLEGE<br>OF D.PHARMA</h2>
                <p class="lead mb-5">Recognized as the leading institution for pharmaceutical education with 100% placement record.</p>
                <a href="course-detail.php?slug=d-pharma" class="btn-gold btn-lg">
                    Know More <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ===== CONTACT CTA ===== -->
<section class="contact-cta">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto" data-aos="fade-up">
                <h2>Ready to Start Your Journey?</h2>
                <p class="lead">Take the first step towards a bright future. Apply now for admission 2026-27.</p>
                <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                    <button class="btn-gold" data-bs-toggle="modal" data-bs-target="#admissionModal">
                        <i class="fas fa-edit me-2"></i>Apply Now
                    </button>
                    <a href="contact.php" class="btn-maroon">
                        <i class="fas fa-headset me-2"></i>Contact Us
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>

<!-- ===== WHATSAPP FLOAT ===== -->
<a href="https://wa.me/<?php echo $settings['whatsapp_number'] ?? '9720229697'; ?>" class="whatsapp-float" target="_blank">
    <i class="fab fa-whatsapp"></i>
</a>

<!-- ===== ADMISSION MODAL ===== -->
<div class="modal fade" id="admissionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-graduation-cap me-2"></i>
                    Apply for Admission 2026-27
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="submit-admission.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Student Name *</label>
                            <input type="text" name="student_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Father's Name *</label>
                            <input type="text" name="father_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mobile Number *</label>
                            <input type="tel" name="mobile" class="form-control" pattern="[0-9]{10}" maxlength="10" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Select Course *</label>
                            <select name="course_id" class="form-select" required>
                                <option value="">Choose...</option>
                                <?php
                                try {
                                    $stmt = $pdo->query("SELECT id, name FROM courses WHERE status = 1");
                                    while($course = $stmt->fetch()) {
                                        echo '<option value="'.$course['id'].'">'.htmlspecialchars($course['name']).'</option>';
                                    }
                                } catch(Exception $e) {
                                    echo '<option value="1">B.A.</option>';
                                    echo '<option value="2">B.Sc.</option>';
                                    echo '<option value="3">D.Pharma</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Qualification *</label>
                            <input type="text" name="qualification" class="form-control" placeholder="e.g., 12th Pass" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Address *</label>
                            <textarea name="address" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit Application</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ===== SCRIPTS ===== -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // Initialize AOS
    AOS.init({
        duration: 1000,
        once: true,
        offset: 50
    });
    
    // Form submission handling
    document.querySelector('form')?.addEventListener('submit', function(e) {
        // Uncomment for testing without actual submission
        // e.preventDefault();
        // alert('Application submitted successfully! (Demo)');
    });
    
    // Force carousel to initialize properly
    document.addEventListener('DOMContentLoaded', function() {
        var myCarousel = document.getElementById('heroCarousel');
        if (myCarousel) {
            new bootstrap.Carousel(myCarousel, {
                interval: 5000,
                ride: 'carousel'
            });
        }
    });
</script>

</body>
</html>