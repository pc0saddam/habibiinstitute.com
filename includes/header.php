<?php
/**
 * Header Template - Habibi Institute of Higher Education
 * Fully Responsive with Brand Image
 */
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="<?php echo $settings['meta_description'] ?? 'Habibi Institute of Higher Education - Affiliated to MJPRU, NCTE, AICTE. Offering B.A., B.Sc., D.Pharma, LLB, B.Ed programs.'; ?>">
    <meta name="keywords" content="<?php echo $settings['meta_keywords'] ?? 'college, higher education, pharmacy, law, education, Moradabad, MJPRU'; ?>">
    <meta name="author" content="Habibi Institute of Higher Education">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph Tags for Social Media -->
    <meta property="og:title" content="<?php echo SITE_NAME; ?>">
    <meta property="og:description" content="Premier Institute of Higher Education in Moradabad">
    <meta property="og:image" content="<?php echo BASE_URL; ?>assets/img/logo.png">
    <meta property="og:url" content="<?php echo BASE_URL; ?>">
    <meta property="og:type" content="website">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>favicon.ico">
    <link rel="apple-touch-icon" href="<?php echo BASE_URL; ?>assets/img/logo.png">
    
    <title><?php echo SITE_NAME; ?> - <?php echo $settings['college_tagline'] ?? 'Excellence in Higher Education'; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    
    <style>
        /* Additional Header-Specific Styles */
        .navbar {
            transition: all 0.3s ease;
            padding: 15px 0;
            background: linear-gradient(135deg, #8B0000 0%, #6D0000 100%) !important;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }
        
        .navbar.scrolled {
            padding: 10px 0;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 0;
            margin: 0;
            text-decoration: none;
        }
        
        .navbar-brand img {
            transition: transform 0.3s ease;
            max-height: 60px;
            width: auto;
        }
        
        .navbar-brand:hover img {
            transform: scale(1.05);
        }
        
        .brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }
        
        .brand-main {
            color: #C9A227;
            font-weight: 700;
            font-size: 1.4rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        
        .brand-sub {
            color: #FFFFFF;
            font-weight: 400;
            font-size: 0.9rem;
            letter-spacing: 0.3px;
        }
        
        .navbar-nav .nav-link {
            color: #FFFFFF !important;
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
            background: #C9A227;
            transition: width 0.3s ease;
        }
        
        .navbar-nav .nav-link:hover:after,
        .navbar-nav .nav-link.active:after {
            width: 80%;
        }
        
        .navbar-nav .nav-link:hover {
            color: #C9A227 !important;
        }
        
        .dropdown-menu {
            background: #6D0000;
            border: none;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            padding: 10px 0;
            margin-top: 10px;
        }
        
        .dropdown-item {
            color: #FFFFFF;
            padding: 10px 20px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .dropdown-item:hover {
            background: #C9A227;
            color: #6D0000;
            padding-left: 25px;
        }
        
        .navbar-toggler {
            border: 2px solid #C9A227;
            padding: 8px 12px;
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23C9A227' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        .admission-status-bar {
            background: #C9A227;
            color: #6D0000;
            padding: 12px 0;
            font-weight: 600;
            text-align: center;
            position: relative;
            z-index: 999;
            font-size: 1rem;
        }
        
        .admission-status-bar .btn {
            background: #6D0000;
            color: #C9A227;
            border-radius: 30px;
            padding: 8px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-left: 15px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        
        .admission-status-bar .btn:hover {
            background: #8B0000;
            color: #E6C55C;
            transform: translateY(-2px);
        }
        
        /* Responsive Design */
        @media (max-width: 991px) {
            .navbar-nav {
                padding: 20px 0;
            }
            
            .navbar-nav .nav-link {
                padding: 12px 20px !important;
                margin: 5px 0;
                border-radius: 5px;
            }
            
            .navbar-nav .nav-link:hover {
                background: rgba(201, 162, 39, 0.2);
            }
            
            .navbar-nav .nav-link:after {
                display: none;
            }
            
            .dropdown-menu {
                background: rgba(0,0,0,0.2);
                margin-left: 20px;
                margin-right: 20px;
            }
            
            .navbar-brand img {
                max-height: 50px;
            }
            
            .brand-main {
                font-size: 1.2rem;
            }
            
            .brand-sub {
                font-size: 0.8rem;
            }
        }
        
        @media (max-width: 768px) {
            .navbar {
                padding: 10px 0;
            }
            
            .navbar-brand {
                gap: 10px;
            }
            
            .navbar-brand img {
                max-height: 45px;
            }
            
            .brand-main {
                font-size: 1.1rem;
            }
            
            .brand-sub {
                font-size: 0.75rem;
            }
            
            .admission-status-bar {
                font-size: 0.9rem;
                padding: 10px;
            }
            
            .admission-status-bar .btn {
                display: block;
                margin: 10px auto 0;
                width: fit-content;
            }
        }
        
        @media (max-width: 576px) {
            .navbar-brand {
                gap: 8px;
            }
            
            .navbar-brand img {
                max-height: 40px;
            }
            
            .brand-main {
                font-size: 1rem;
            }
            
            .brand-sub {
                font-size: 0.7rem;
            }
            
            .navbar-brand {
                max-width: 80%;
            }
        }
        
        @media (max-width: 400px) {
            .brand-text {
                display: none; /* Hide text on very small devices */
            }
        }
        
        /* Landscape Mode */
        @media (max-height: 500px) and (orientation: landscape) {
            .navbar {
                padding: 5px 0;
            }
            
            .navbar-nav .nav-link {
                padding: 8px 15px !important;
            }
        }
        
        /* High Resolution Screens */
        @media (min-width: 1400px) {
            .navbar-brand img {
                max-height: 70px;
            }
            
            .brand-main {
                font-size: 1.6rem;
            }
            
            .brand-sub {
                font-size: 1rem;
            }
            
            .navbar-nav .nav-link {
                font-size: 1.1rem;
                padding: 10px 20px !important;
            }
        }
        
        /* Print Styles */
        @media print {
            .navbar,
            .admission-status-bar {
                display: none !important;
            }
        }
    </style>
</head>
<body>

<!-- Sticky Header -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top" id="mainNav">
    <div class="container">
        <!-- Brand Logo with Name - Clickable to Home -->
        <a class="navbar-brand" href="<?php echo BASE_URL; ?>">
            <img src="<?php echo BASE_URL; ?>assets/img/logo.png" 
                 alt="<?php echo SITE_NAME; ?>" 
                 class="img-fluid"
                 onerror="this.src='https://via.placeholder.com/180x60/8B0000/FFFFFF?text=HABIBI'">
            
            <!-- ✅ Brand Name Added Here -->
            <div class="brand-text">
                <span class="brand-main">HABIBI INSTITUTE</span>
                <span class="brand-sub">OF HIGHER EDUCATION</span>
            </div>
        </a>
        
        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" 
                aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Navigation Menu -->
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>">
                        <i class="fas fa-home me-1 d-lg-none"></i>Home
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>about.php">
                        <i class="fas fa-info-circle me-1 d-lg-none"></i>About
                    </a>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo in_array(basename($_SERVER['PHP_SELF']), ['courses.php', 'course-detail.php']) ? 'active' : ''; ?>" 
                       href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-book me-1 d-lg-none"></i>Courses
                    </a>
                    <ul class="dropdown-menu">
                        <?php
                        try {
                            $stmt = $pdo->query("SELECT slug, name FROM courses WHERE status = 1 ORDER BY name LIMIT 6");
                            while($course = $stmt->fetch()) {
                                echo '<li><a class="dropdown-item" href="'.BASE_URL.'course/'.$course['slug'].'">'.
                                     '<i class="fas fa-chevron-right me-2" style="font-size: 0.8rem;"></i>'.
                                     htmlspecialchars($course['name']).'</a></li>';
                            }
                        } catch (PDOException $e) {
                            // Table might not exist yet
                        }
                        ?>
                        <li><hr class="dropdown-divider" style="border-color: #C9A227;"></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>courses.php">
                            <i class="fas fa-eye me-2"></i>View All Courses
                        </a></li>
                    </ul>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'admission.php' ? 'active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>admission.php">
                        <i class="fas fa-graduation-cap me-1 d-lg-none"></i>Admission
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'gallery.php' ? 'active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>gallery.php">
                        <i class="fas fa-images me-1 d-lg-none"></i>Gallery
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>contact.php">
                        <i class="fas fa-envelope me-1 d-lg-none"></i>Contact
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Admission Status Bar -->
<div class="admission-status-bar">
    <div class="container">
        <i class="fas fa-graduation-cap me-2"></i>
        <strong><?php echo $settings['admission_status'] ?? 'ADMISSION OPEN 2026-27'; ?></strong>
        <a href="#admissionModal" data-bs-toggle="modal" class="btn">
            <i class="fas fa-edit me-2"></i>Apply Now
        </a>
    </div>
</div>

<script>
    // Navbar scroll effect
    window.addEventListener('scroll', function() {
        const nav = document.getElementById('mainNav');
        if (window.scrollY > 50) {
            nav.classList.add('scrolled');
        } else {
            nav.classList.remove('scrolled');
        }
    });
</script>