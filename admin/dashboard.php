<?php
// admin/dashboard.php
require_once '../includes/config.php';
require_once '../includes/auth.php';

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

// Get statistics with error handling
try {
    $totalAdmissions = $pdo->query("SELECT COUNT(*) FROM admissions")->fetchColumn() ?: 0;
    $pendingAdmissions = $pdo->query("SELECT COUNT(*) FROM admissions WHERE status = 'pending'")->fetchColumn() ?: 0;
    $totalCourses = $pdo->query("SELECT COUNT(*) FROM courses")->fetchColumn() ?: 0;
    $totalMessages = $pdo->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn() ?: 0;
    $unreadMessages = $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE is_read = 0")->fetchColumn() ?: 0;
    $totalGallery = $pdo->query("SELECT COUNT(*) FROM gallery")->fetchColumn() ?: 0;
    $totalSlides = $pdo->query("SELECT COUNT(*) FROM carousel_slides")->fetchColumn() ?: 0;

    // Get recent admissions
    $recentAdmissions = $pdo->query("SELECT a.*, c.name as course_name FROM admissions a LEFT JOIN courses c ON a.course_id = c.id ORDER BY a.submitted_at DESC LIMIT 5")->fetchAll();

    // Get recent messages
    $recentMessages = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5")->fetchAll();

    // Get monthly admission trends
    $monthlyStats = $pdo->query("
        SELECT DATE_FORMAT(submitted_at, '%Y-%m') as month, COUNT(*) as count 
        FROM admissions 
        WHERE submitted_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
        GROUP BY DATE_FORMAT(submitted_at, '%Y-%m')
        ORDER BY month DESC
    ")->fetchAll();
    
    // Prepare chart data
    $chartLabels = [];
    $chartData = [];
    foreach(array_reverse($monthlyStats) as $stat) {
        $chartLabels[] = date('M Y', strtotime($stat['month'] . '-01'));
        $chartData[] = $stat['count'];
    }
    
    // Status chart data
    $statusStats = $pdo->query("
        SELECT status, COUNT(*) as count 
        FROM admissions 
        GROUP BY status
    ")->fetchAll();
    
    $statusLabels = [];
    $statusData = [];
    $statusColors = [
        'pending' => '#ffc107',
        'contacted' => '#17a2b8',
        'confirmed' => '#28a745',
        'rejected' => '#dc3545'
    ];
    $statusColorArray = [];
    
    foreach($statusStats as $stat) {
        $statusLabels[] = ucfirst($stat['status']);
        $statusData[] = (int)$stat['count'];
        $statusColorArray[] = $statusColors[$stat['status']] ?? '#6c757d';
    }
    
} catch(PDOException $e) {
    // If tables don't exist, set default values
    $totalAdmissions = $pendingAdmissions = $totalCourses = $totalMessages = $unreadMessages = $totalGallery = $totalSlides = 0;
    $recentAdmissions = $recentMessages = $monthlyStats = [];
    $chartLabels = $chartData = $statusLabels = $statusData = $statusColorArray = [];
    $db_error = "Database error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>Dashboard - Habibi Institute Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <style>
        /* ===== ROOT VARIABLES ===== */
        :root {
            --deep-red: #8B0000;
            --maroon: #6D0000;
            --gold: #C9A227;
            --light-gold: #E6C55C;
            --sidebar-width: 280px;
            --sidebar-collapsed: 0;
            --header-height: 70px;
            --mobile-breakpoint: 768px;
        }

        /* ===== RESET & GLOBAL ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        /* ===== WRAPPER ===== */
        .wrapper {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(135deg, var(--deep-red), var(--maroon));
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 30px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(201, 162, 39, 0.3);
        }

        .sidebar-header img {
            width: 100px;
            height: auto;
            margin-bottom: 15px;
            transition: transform 0.3s ease;
        }

        .sidebar-header img:hover {
            transform: scale(1.05);
        }

        .sidebar-header h3 {
            color: var(--gold);
            font-size: 1.2rem;
            margin: 0;
            font-weight: 600;
        }

        .sidebar-header p {
            color: rgba(255,255,255,0.7);
            font-size: 0.8rem;
            margin-top: 5px;
        }

        .sidebar-menu {
            padding: 15px 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #fff;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
            font-size: 0.95rem;
        }

        .sidebar-menu a i {
            width: 25px;
            margin-right: 10px;
            font-size: 1.1rem;
            color: var(--gold);
            transition: all 0.3s ease;
        }

        .sidebar-menu a:hover {
            background: rgba(255,255,255,0.1);
            border-left-color: var(--gold);
        }

        .sidebar-menu a.active {
            background: rgba(255,255,255,0.15);
            border-left-color: var(--gold);
        }

        .sidebar-menu a.active i {
            color: #fff;
        }

        /* Mobile Sidebar Toggle */
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1100;
            background: var(--maroon);
            color: var(--gold);
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            font-size: 1.2rem;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 20px;
            transition: all 0.3s ease;
            width: 100%;
        }

        /* ===== TOP NAVIGATION ===== */
        .top-nav {
            background: white;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .page-title h2 {
            color: var(--maroon);
            margin: 0;
            font-size: 1.5rem;
        }

        .page-title p {
            color: #666;
            margin: 5px 0 0;
            font-size: 0.85rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-info .notification {
            position: relative;
            cursor: pointer;
        }

        .user-info .notification i {
            font-size: 1.2rem;
            color: var(--deep-red);
        }

        .user-info .notification .badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--gold);
            color: var(--maroon);
            font-size: 0.6rem;
            padding: 3px 6px;
            border-radius: 50%;
            min-width: 18px;
            text-align: center;
        }

        .user-info .user-dropdown {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            padding: 5px 10px;
            background: #F8F9FA;
            border-radius: 30px;
        }

        .user-info .user-dropdown img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
        }

        .user-info .user-dropdown span {
            color: var(--maroon);
            font-weight: 600;
            font-size: 0.9rem;
        }

        /* ===== STATS CARDS ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3px solid transparent;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(139, 0, 0, 0.1);
        }

        .stat-card.red { border-bottom-color: var(--deep-red); }
        .stat-card.maroon { border-bottom-color: var(--maroon); }
        .stat-card.gold { border-bottom-color: var(--gold); }

        .stat-info h3 {
            color: #333;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 3px;
        }

        .stat-info p {
            color: #666;
            margin: 0;
            font-size: 0.85rem;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--deep-red), var(--maroon));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-icon i {
            font-size: 1.5rem;
            color: var(--gold);
        }

        /* ===== CHARTS ROW ===== */
        .charts-row {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }

        .chart-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .chart-card h4 {
            color: var(--maroon);
            margin-bottom: 15px;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
        }

        .chart-container {
            position: relative;
            height: 250px;
            width: 100%;
        }

        /* ===== RECENT TABLES ===== */
        .recent-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }

        .recent-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .recent-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .recent-header h4 {
            color: var(--maroon);
            margin: 0;
            font-size: 1.1rem;
        }

        .recent-header a {
            color: var(--deep-red);
            text-decoration: none;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table {
            margin-bottom: 0;
            min-width: 500px;
        }

        .table th {
            border-top: none;
            color: #666;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 12px 8px;
        }

        .table td {
            vertical-align: middle;
            color: #333;
            padding: 12px 8px;
            font-size: 0.9rem;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }

        .status-pending { background: #fff3cd; color: #856404; }
        .status-contacted { background: #d4edda; color: #155724; }
        .status-confirmed { background: #cce5ff; color: #004085; }
        .status-rejected { background: #f8d7da; color: #721c24; }

        /* ===== QUICK ACTIONS ===== */
        .quick-actions {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .quick-actions h4 {
            color: var(--maroon);
            margin-bottom: 15px;
            font-size: 1.1rem;
        }

        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 12px;
        }

        .action-btn {
            background: #F8F9FA;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 12px;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            background: var(--deep-red);
            transform: translateY(-2px);
        }

        .action-btn:hover i,
        .action-btn:hover span {
            color: var(--gold);
        }

        .action-btn i {
            font-size: 1.3rem;
            color: var(--deep-red);
            margin-bottom: 5px;
            display: block;
        }

        .action-btn span {
            color: #333;
            font-size: 0.85rem;
            font-weight: 500;
        }

        /* ===== RESPONSIVE DESIGN ===== */
        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 992px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .charts-row {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .sidebar-toggle {
                display: block;
            }

            .sidebar {
                transform: translateX(-100%);
                width: 250px;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 15px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .recent-section {
                grid-template-columns: 1fr;
            }

            .top-nav {
                flex-direction: column;
                align-items: flex-start;
            }

            .user-info {
                width: 100%;
                justify-content: space-between;
            }

            .page-title h2 {
                font-size: 1.3rem;
            }

            .stat-card {
                padding: 15px;
            }

            .stat-info h3 {
                font-size: 1.5rem;
            }

            .stat-icon {
                width: 45px;
                height: 45px;
            }

            .stat-icon i {
                font-size: 1.3rem;
            }

            .action-buttons {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .sidebar-header img {
                width: 80px;
            }

            .sidebar-header h3 {
                font-size: 1rem;
            }

            .user-info .user-dropdown span {
                display: none;
            }

            .user-info .user-dropdown {
                padding: 5px;
            }

            .chart-container {
                height: 200px;
            }

            .action-buttons {
                grid-template-columns: 1fr;
            }

            .quick-actions {
                padding: 15px;
            }

            .recent-card {
                padding: 15px;
            }

            .table td, .table th {
                padding: 8px 5px;
                font-size: 0.8rem;
            }
        }

        /* Touch-friendly improvements */
        @media (max-width: 768px) {
            .sidebar-menu a {
                padding: 15px 20px;
                font-size: 1rem;
            }

            .action-btn {
                padding: 15px;
            }

            .action-btn i {
                font-size: 1.5rem;
            }

            .action-btn span {
                font-size: 0.9rem;
            }

            .user-dropdown {
                min-height: 44px;
            }

            .notification {
                min-height: 44px;
                min-width: 44px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }

        /* Landscape Mode */
        @media (max-height: 500px) and (orientation: landscape) {
            .sidebar {
                overflow-y: auto;
            }

            .sidebar-menu a {
                padding: 10px 15px;
            }

            .stats-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .chart-container {
                height: 150px;
            }
        }

        /* Print Styles */
        @media print {
            .sidebar,
            .top-nav .user-info,
            .quick-actions {
                display: none;
            }

            .main-content {
                margin-left: 0;
                padding: 0;
            }

            .stat-card {
                break-inside: avoid;
            }
        }

        /* Loading State */
        .loading {
            position: relative;
            opacity: 0.7;
            pointer-events: none;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 30px;
            height: 30px;
            border: 3px solid var(--gold);
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Error Alert */
        .error-alert {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
            font-size: 0.9rem;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 15px;
            color: #ddd;
        }

        .empty-state p {
            margin: 0;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <!-- Mobile Sidebar Toggle -->
    <button class="sidebar-toggle" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <img src="../assets/img/logo.png" alt="Habibi Institute" onerror="this.src='https://via.placeholder.com/100x100/8B0000/FFFFFF?text=HABIBI'">
                <h3>Habibi Institute</h3>
                <p>Admin Panel</p>
            </div>
            
            <div class="sidebar-menu">
                <a href="dashboard.php" class="active">
                    <i class="fas fa-dashboard"></i>
                    <span>Dashboard</span>
                </a>
                <a href="carousel.php">
                    <i class="fas fa-images"></i>
                    <span>Carousel Slides</span>
                </a>
                <a href="courses.php">
                    <i class="fas fa-book"></i>
                    <span>Courses</span>
                </a>
                <a href="admissions.php">
                    <i class="fas fa-users"></i>
                    <span>Admissions</span>
                </a>
                <a href="gallery.php">
                    <i class="fas fa-camera"></i>
                    <span>Gallery</span>
                </a>
                <a href="messages.php">
                    <i class="fas fa-envelope"></i>
                    <span>Messages</span>
                </a>
                <a href="settings.php">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
                <a href="logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Navigation -->
            <div class="top-nav">
                <div class="page-title">
                    <h2>Dashboard</h2>
                    <p>Welcome back, <?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?>!</p>
                </div>
                <div class="user-info">
                    <div class="notification">
                        <i class="fas fa-bell"></i>
                        <span class="badge"><?php echo $unreadMessages; ?></span>
                    </div>
                    <div class="user-dropdown">
                        <img src="https://via.placeholder.com/40x40/8B0000/FFFFFF?text=Admin" alt="Admin">
                        <span><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></span>
                        <i class="fas fa-chevron-down" style="color: #8B0000;"></i>
                    </div>
                </div>
            </div>

            <!-- Error Display -->
            <?php if(isset($db_error)): ?>
            <div class="error-alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php echo $db_error; ?>
            </div>
            <?php endif; ?>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card red">
                    <div class="stat-info">
                        <h3><?php echo $totalAdmissions; ?></h3>
                        <p>Total Admissions</p>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                </div>

                <div class="stat-card maroon">
                    <div class="stat-info">
                        <h3><?php echo $pendingAdmissions; ?></h3>
                        <p>Pending</p>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>

                <div class="stat-card gold">
                    <div class="stat-info">
                        <h3><?php echo $totalCourses; ?></h3>
                        <p>Courses</p>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                </div>

                <div class="stat-card red">
                    <div class="stat-info">
                        <h3><?php echo $unreadMessages; ?></h3>
                        <p>Unread</p>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>

                <div class="stat-card maroon">
                    <div class="stat-info">
                        <h3><?php echo $totalGallery; ?></h3>
                        <p>Gallery</p>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-image"></i>
                    </div>
                </div>

                <div class="stat-card gold">
                    <div class="stat-info">
                        <h3><?php echo $totalSlides; ?></h3>
                        <p>Slides</p>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-sliders-h"></i>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="charts-row">
                <div class="chart-card">
                    <h4>
                        <i class="fas fa-chart-line me-2" style="color: #8B0000;"></i>
                        Admission Trends (Last 6 Months)
                    </h4>
                    <div class="chart-container">
                        <canvas id="admissionChart"></canvas>
                    </div>
                </div>
                <div class="chart-card">
                    <h4>
                        <i class="fas fa-chart-pie me-2" style="color: #8B0000;"></i>
                        Admission Status
                    </h4>
                    <div class="chart-container">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Tables -->
            <div class="recent-section">
                <!-- Recent Admissions -->
                <div class="recent-card">
                    <div class="recent-header">
                        <h4>
                            <i class="fas fa-user-graduate me-2" style="color: #8B0000;"></i>
                            Recent Admissions
                        </h4>
                        <a href="admissions.php">
                            View All <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Course</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($recentAdmissions)): ?>
                                <tr>
                                    <td colspan="4" class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <p>No admissions yet</p>
                                    </td>
                                </tr>
                                <?php else: ?>
                                <?php foreach($recentAdmissions as $admission): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($admission['student_name']); ?></td>
                                    <td><?php echo htmlspecialchars($admission['course_name'] ?? 'N/A'); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo $admission['status']; ?>">
                                            <?php echo ucfirst($admission['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d M Y', strtotime($admission['submitted_at'])); ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Recent Messages -->
                <div class="recent-card">
                    <div class="recent-header">
                        <h4>
                            <i class="fas fa-envelope me-2" style="color: #8B0000;"></i>
                            Recent Messages
                        </h4>
                        <a href="messages.php">
                            View All <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($recentMessages)): ?>
                                <tr>
                                    <td colspan="4" class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <p>No messages yet</p>
                                    </td>
                                </tr>
                                <?php else: ?>
                                <?php foreach($recentMessages as $message): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($message['name']); ?></td>
                                    <td><?php echo htmlspecialchars(substr($message['subject'] ?: 'No Subject', 0, 20)) . '...'; ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $message['is_read'] ? 'success' : 'warning'; ?>" style="font-size: 0.75rem; padding: 4px 8px;">
                                            <?php echo $message['is_read'] ? 'Read' : 'Unread'; ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d M Y', strtotime($message['created_at'])); ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <h4>
                    <i class="fas fa-bolt me-2" style="color: #8B0000;"></i>
                    Quick Actions
                </h4>
                <div class="action-buttons">
                    <a href="admissions.php?action=export" class="action-btn">
                        <i class="fas fa-file-excel"></i>
                        <span>Export Admissions</span>
                    </a>
                    <a href="carousel.php?action=add" class="action-btn">
                        <i class="fas fa-plus-circle"></i>
                        <span>Add Slide</span>
                    </a>
                    <a href="courses.php?action=add" class="action-btn">
                        <i class="fas fa-plus-circle"></i>
                        <span>Add Course</span>
                    </a>
                    <a href="gallery.php?action=upload" class="action-btn">
                        <i class="fas fa-upload"></i>
                        <span>Upload Images</span>
                    </a>
                    <a href="settings.php" class="action-btn">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Sidebar Toggle for Mobile
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('sidebarToggle');
            
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                    
                    // Change icon
                    const icon = this.querySelector('i');
                    if (sidebar.classList.contains('active')) {
                        icon.classList.remove('fa-bars');
                        icon.classList.add('fa-times');
                    } else {
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }
                });
            }
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 768) {
                    const isClickInside = sidebar.contains(event.target) || toggleBtn.contains(event.target);
                    
                    if (!isClickInside && sidebar.classList.contains('active')) {
                        sidebar.classList.remove('active');
                        const icon = toggleBtn.querySelector('i');
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }
                }
            });

            // Charts Initialization
            <?php if(!empty($chartLabels) && !empty($chartData)): ?>
            // Admission Trends Chart
            const ctx1 = document.getElementById('admissionChart').getContext('2d');
            new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($chartLabels); ?>,
                    datasets: [{
                        label: 'Admissions',
                        data: <?php echo json_encode($chartData); ?>,
                        borderColor: '#8B0000',
                        backgroundColor: 'rgba(139, 0, 0, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#C9A227',
                        pointBorderColor: '#6D0000',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#6D0000',
                            titleColor: '#C9A227',
                            bodyColor: '#fff',
                            borderColor: '#C9A227',
                            borderWidth: 1
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
            <?php endif; ?>

            <?php if(!empty($statusLabels) && !empty($statusData)): ?>
            // Status Chart
            const ctx2 = document.getElementById('statusChart').getContext('2d');
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: <?php echo json_encode($statusLabels); ?>,
                    datasets: [{
                        data: <?php echo json_encode($statusData); ?>,
                        backgroundColor: <?php echo json_encode($statusColorArray); ?>,
                        borderWidth: 0,
                        hoverOffset: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                padding: 15,
                                font: {
                                    size: 11
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: '#6D0000',
                            titleColor: '#C9A227',
                            bodyColor: '#fff'
                        }
                    },
                    cutout: '60%'
                }
            });
            <?php endif; ?>

            // Handle window resize for responsive charts
            window.addEventListener('resize', function() {
                // Charts will automatically resize due to maintainAspectRatio: false
            });
        });
    </script>
</body>
</html>