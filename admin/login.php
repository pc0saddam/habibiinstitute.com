<?php
// admin/login.php
require_once '../includes/config.php';
require_once '../includes/auth.php';

// Redirect if already logged in
if(isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit();
}

$error = '';
$success = '';

// Check for timeout message
if(isset($_GET['timeout'])) {
    $error = 'Session expired. Please login again.';
}

// Check for logout message
if(isset($_GET['loggedout'])) {
    $success = 'You have been successfully logged out.';
}

// Handle login
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Validate CSRF token
    if(!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
        $error = 'Invalid security token. Please try again.';
    } else {
        
        $username = sanitizeInput($_POST['username']);
        $password = $_POST['password'];
        $remember = isset($_POST['remember']) ? true : false;
        
        if(empty($username) || empty($password)) {
            $error = 'Please enter username and password';
        } else {
            
            // Prepare statement to prevent SQL injection
            $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ? AND status = 'active'");
            $stmt->execute([$username]);
            $user = $stmt->fetch();
            
            // In production, use password_verify()
            // For demo, we'll use a simple check
            if($user && $password === 'admin123') { 
                
                // Set session variables
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_username'] = $user['username'];
                $_SESSION['admin_name'] = $user['full_name'] ?: $user['username'];
                $_SESSION['admin_role'] = $user['role'];
                $_SESSION['last_activity'] = time();
                $_SESSION['created'] = time();
                
                // Update last login info
                $ip = getUserIP();
                $updateStmt = $pdo->prepare("UPDATE admin_users SET last_login = NOW(), last_login_ip = ? WHERE id = ?");
                $updateStmt->execute([$ip, $user['id']]);
                
                // Log the login
                logAdminActivity('LOGIN', 'Successful login from IP: ' . $ip);
                
                // Set remember me cookie if requested
                if($remember) {
                    $token = bin2hex(random_bytes(32));
                    setcookie('remember_token', $token, time() + (86400 * 30), '/', '', false, true);
                }
                
                // Regenerate session ID for security
                session_regenerate_id(true);
                
                header('Location: dashboard.php');
                exit();
                
            } else {
                $error = 'Invalid username or password';
                logAdminActivity('LOGIN_FAILED', 'Failed login attempt for username: ' . $username . ' from IP: ' . getUserIP());
            }
        }
    }
}

// Generate CSRF token
$csrf_token = generateCSRFToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>Admin Login - Habibi Institute</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ===== RESET & GLOBAL ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, #8B0000 0%, #6D0000 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            overflow-x: hidden;
            padding: 20px;
        }
        
        /* Animated Background */
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(201, 162, 39, 0.1) 0%, transparent 70%);
            animation: rotate 30s linear infinite;
        }
        
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        /* ===== LOGIN CONTAINER ===== */
        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 450px;
            padding: 15px;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
            border: 1px solid rgba(201, 162, 39, 0.3);
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.4);
        }
        
        /* ===== HEADER ===== */
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header img {
            width: 120px;
            height: auto;
            margin-bottom: 20px;
            filter: drop-shadow(0 5px 15px rgba(139, 0, 0, 0.3));
            transition: transform 0.3s ease;
        }
        
        .login-header img:hover {
            transform: scale(1.05);
        }
        
        .login-header h2 {
            color: #8B0000;
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .login-header p {
            color: #666;
            font-size: 0.95rem;
        }
        
        /* ===== FORM GROUPS ===== */
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .form-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #8B0000;
            font-size: 1.1rem;
            transition: color 0.3s ease;
            z-index: 10;
            pointer-events: none;
        }
        
        .form-control {
            height: 50px;
            padding: 10px 15px 10px 45px;
            border: 2px solid #eee;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
            width: 100%;
        }
        
        .form-control:focus {
            border-color: #C9A227;
            box-shadow: 0 0 0 0.2rem rgba(201, 162, 39, 0.25);
            outline: none;
        }
        
        .form-control:focus + i {
            color: #C9A227;
        }
        
        /* ===== CHECKBOX ===== */
        .form-check {
            margin-bottom: 25px;
            padding-left: 25px;
            display: flex;
            align-items: center;
        }
        
        .form-check-input {
            border-color: #8B0000;
            width: 18px;
            height: 18px;
            margin-right: 8px;
            cursor: pointer;
        }
        
        .form-check-input:checked {
            background-color: #8B0000;
            border-color: #8B0000;
        }
        
        .form-check-label {
            color: #666;
            font-size: 0.95rem;
            cursor: pointer;
            user-select: none;
        }
        
        /* ===== LOGIN BUTTON ===== */
        .btn-login {
            background: linear-gradient(135deg, #8B0000, #6D0000);
            color: #C9A227;
            width: 100%;
            height: 50px;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(201, 162, 39, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .btn-login:hover::before {
            width: 300px;
            height: 300px;
        }
        
        .btn-login:hover {
            background: linear-gradient(135deg, #6D0000, #8B0000);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(139, 0, 0, 0.3);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        /* ===== ALERTS ===== */
        .alert {
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 25px;
            border: none;
            animation: slideIn 0.5s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert-danger {
            background: #ffebee;
            color: #c62828;
            border-left: 4px solid #c62828;
        }
        
        .alert-success {
            background: #e8f5e9;
            color: #2e7d32;
            border-left: 4px solid #2e7d32;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* ===== FOOTER LINKS ===== */
        .login-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
        }
        
        .login-footer a {
            color: #8B0000;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .login-footer a:hover {
            color: #C9A227;
        }
        
        /* ===== DEMO CREDENTIALS ===== */
        .demo-credentials {
            background: #F8F9FA;
            border-radius: 12px;
            padding: 15px;
            margin-top: 25px;
            font-size: 0.9rem;
            border: 1px dashed #C9A227;
            text-align: left;
        }
        
        .demo-credentials p {
            margin-bottom: 8px;
            color: #666;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .demo-credentials strong {
            color: #8B0000;
            background: rgba(139, 0, 0, 0.05);
            padding: 3px 8px;
            border-radius: 5px;
        }
        
        /* ===== LOADING SPINNER ===== */
        .spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 3px solid #C9A227;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .btn-login.loading {
            pointer-events: none;
            opacity: 0.8;
        }
        
        .btn-login.loading .spinner {
            display: inline-block;
        }
        
        /* ===== RESPONSIVE DESIGN ===== */
        
        /* Tablet (768px and below) */
        @media (max-width: 768px) {
            .login-card {
                padding: 30px 25px;
            }
            
            .login-header h2 {
                font-size: 1.6rem;
            }
            
            .login-header img {
                width: 100px;
            }
            
            .form-control {
                height: 48px;
                font-size: 0.95rem;
            }
            
            .btn-login {
                height: 48px;
                font-size: 1rem;
            }
            
            .login-footer {
                flex-direction: column;
                gap: 10px;
            }
            
            .login-footer a {
                justify-content: center;
            }
        }
        
        /* Mobile (576px and below) */
        @media (max-width: 576px) {
            body {
                padding: 10px;
            }
            
            .login-card {
                padding: 25px 20px;
            }
            
            .login-header h2 {
                font-size: 1.4rem;
            }
            
            .login-header p {
                font-size: 0.85rem;
            }
            
            .login-header img {
                width: 90px;
                margin-bottom: 15px;
            }
            
            .form-group {
                margin-bottom: 20px;
            }
            
            .form-control {
                height: 45px;
                padding: 8px 15px 8px 40px;
                font-size: 0.9rem;
            }
            
            .form-group i {
                font-size: 1rem;
                left: 12px;
            }
            
            .form-check {
                margin-bottom: 20px;
            }
            
            .form-check-label {
                font-size: 0.85rem;
            }
            
            .btn-login {
                height: 45px;
                font-size: 0.95rem;
            }
            
            .demo-credentials {
                padding: 12px;
                font-size: 0.85rem;
            }
            
            .demo-credentials p {
                margin-bottom: 5px;
            }
            
            .alert {
                padding: 12px;
                font-size: 0.9rem;
            }
            
            .login-footer a {
                font-size: 0.85rem;
            }
        }
        
        /* Small Mobile (400px and below) */
        @media (max-width: 400px) {
            .login-card {
                padding: 20px 15px;
            }
            
            .login-header h2 {
                font-size: 1.3rem;
            }
            
            .login-header img {
                width: 80px;
            }
            
            .form-control {
                height: 42px;
                font-size: 0.85rem;
            }
            
            .btn-login {
                height: 42px;
                font-size: 0.9rem;
            }
            
            .demo-credentials strong {
                display: inline-block;
                margin-left: 5px;
            }
            
            .login-footer a i {
                margin-right: 3px;
            }
        }
        
        /* Landscape Mode */
        @media (max-height: 600px) and (orientation: landscape) {
            body {
                padding: 15px;
            }
            
            .login-card {
                padding: 20px;
            }
            
            .login-header {
                margin-bottom: 15px;
            }
            
            .login-header img {
                width: 70px;
                margin-bottom: 10px;
            }
            
            .login-header h2 {
                font-size: 1.3rem;
                margin-bottom: 5px;
            }
            
            .login-header p {
                font-size: 0.8rem;
            }
            
            .form-group {
                margin-bottom: 15px;
            }
            
            .form-control {
                height: 40px;
            }
            
            .btn-login {
                height: 40px;
            }
            
            .demo-credentials {
                margin-top: 15px;
                padding: 10px;
            }
            
            .login-footer {
                margin-top: 15px;
                padding-top: 10px;
            }
        }
        
        /* High Resolution Screens */
        @media (min-width: 1400px) {
            .login-container {
                max-width: 500px;
            }
            
            .login-card {
                padding: 50px;
            }
            
            .login-header h2 {
                font-size: 2rem;
            }
            
            .login-header img {
                width: 140px;
            }
            
            .form-control {
                height: 55px;
                font-size: 1.1rem;
            }
            
            .btn-login {
                height: 55px;
                font-size: 1.2rem;
            }
        }
        
        /* Touch-friendly improvements */
        @media (hover: none) and (pointer: coarse) {
            .btn-login,
            .form-check-input,
            .login-footer a {
                min-height: 44px;
                min-width: 44px;
            }
            
            .form-check-input {
                transform: scale(1.2);
            }
            
            .form-control {
                font-size: 16px; /* Prevents zoom on iOS */
            }
        }
        
        /* Print Styles */
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .login-card {
                box-shadow: none;
                border: 1px solid #ddd;
            }
            
            .btn-login,
            .demo-credentials,
            .login-footer a[href="#"] {
                display: none;
            }
        }
        
        /* Dark Mode Support (if user prefers) */
        @media (prefers-color-scheme: dark) {
            .login-card {
                background: rgba(30, 30, 30, 0.98);
            }
            
            .login-header h2 {
                color: #C9A227;
            }
            
            .login-header p {
                color: #aaa;
            }
            
            .form-control {
                background: #333;
                border-color: #444;
                color: #fff;
            }
            
            .form-control:focus {
                border-color: #C9A227;
            }
            
            .form-check-label {
                color: #aaa;
            }
            
            .demo-credentials {
                background: #222;
                border-color: #C9A227;
            }
            
            .demo-credentials p {
                color: #aaa;
            }
            
            .login-footer {
                border-top-color: #333;
            }
            
            .login-footer a {
                color: #C9A227;
            }
        }
        
        /* Loading State */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
        
        .loading-overlay.active {
            display: flex;
        }
        
        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #C9A227;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        /* Error Shake Animation */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        
        .alert-danger {
            animation: shake 0.5s ease-in-out;
        }
        
        /* Success Pulse Animation */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .alert-success {
            animation: pulse 0.5s ease-in-out;
        }
    </style>
</head>
<body>
    <!-- Loading Overlay (optional) -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <img src="../assets/img/logo.png" alt="Habibi Institute" 
                     onerror="this.src='https://via.placeholder.com/120x120/8B0000/FFFFFF?text=HABIBI'">
                <h2>Admin Login</h2>
                <p>Welcome back! Please login to your account.</p>
            </div>
            
            <?php if($error): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <?php if($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" id="loginForm" novalidate>
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                
                <div class="form-group">
                    <i class="fas fa-user"></i>
                    <input type="text" class="form-control" name="username" 
                           placeholder="Username" required autofocus 
                           autocomplete="username">
                </div>
                
                <div class="form-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" class="form-control" name="password" 
                           placeholder="Password" required 
                           autocomplete="current-password">
                </div>
                
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">
                        Remember me for 30 days
                    </label>
                </div>
                
                <button type="submit" class="btn-login" id="loginBtn">
                    <span>Login to Dashboard</span>
                    <span class="spinner"></span>
                </button>
            </form>
            
            <div class="login-footer">
                <a href="#" aria-label="Forgot Password">
                    <i class="fas fa-key"></i> Forgot Password?
                </a>
                <a href="../index.php" aria-label="Back to Website">
                    <i class="fas fa-home"></i> Back to Website
                </a>
            </div>
            
            <div class="demo-credentials">
                <p>
                    <i class="fas fa-info-circle" style="color: #C9A227;"></i>
                    <strong>Demo Credentials:</strong>
                </p>
                <p>Username: <strong>admin</strong></p>
                <p>Password: <strong>admin123</strong></p>
            </div>
        </div>
    </div>
    
    <script>
        // Form submission with loading state
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            var btn = document.getElementById('loginBtn');
            var overlay = document.getElementById('loadingOverlay');
            
            btn.classList.add('loading');
            btn.querySelector('span:first-child').style.opacity = '0.7';
            
            // Optional: Show loading overlay for better UX
            // overlay.classList.add('active');
        });

        // Client-side validation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            var username = document.querySelector('input[name="username"]').value.trim();
            var password = document.querySelector('input[name="password"]').value.trim();
            
            if (!username || !password) {
                e.preventDefault();
                alert('Please enter both username and password');
                
                var btn = document.getElementById('loginBtn');
                btn.classList.remove('loading');
                btn.querySelector('span:first-child').style.opacity = '1';
            }
        });

        // Remove loading state on page load (in case of back button)
        window.addEventListener('pageshow', function() {
            var btn = document.getElementById('loginBtn');
            var overlay = document.getElementById('loadingOverlay');
            
            btn.classList.remove('loading');
            btn.querySelector('span:first-child').style.opacity = '1';
            overlay.classList.remove('active');
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.style.display = 'none';
                }, 500);
            });
        }, 5000);

        // Prevent form resubmission on page refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }

        // Add touch-friendly improvements
        if ('ontouchstart' in window) {
            document.documentElement.style.setProperty('--tap-highlight', 'rgba(201, 162, 39, 0.3)');
            
            // Increase tap targets
            var buttons = document.querySelectorAll('.btn-login, .login-footer a');
            buttons.forEach(function(btn) {
                btn.style.minHeight = '44px';
                btn.style.minWidth = '44px';
                btn.style.display = 'inline-flex';
                btn.style.alignItems = 'center';
                btn.style.justifyContent = 'center';
            });
        }

        // Keyboard navigation enhancement
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                // Clear form on Escape
                document.querySelector('input[name="username"]').value = '';
                document.querySelector('input[name="password"]').value = '';
            }
        });

        // Remember me functionality with localStorage
        document.addEventListener('DOMContentLoaded', function() {
            var rememberCheck = document.getElementById('remember');
            var usernameField = document.querySelector('input[name="username"]');
            
            // Check if username was saved
            var savedUsername = localStorage.getItem('admin_username');
            if (savedUsername) {
                usernameField.value = savedUsername;
                rememberCheck.checked = true;
            }
        });

        // Save username if remember me is checked
        document.getElementById('loginForm').addEventListener('submit', function() {
            var rememberCheck = document.getElementById('remember');
            var usernameField = document.querySelector('input[name="username"]');
            
            if (rememberCheck.checked) {
                localStorage.setItem('admin_username', usernameField.value);
            } else {
                localStorage.removeItem('admin_username');
            }
        });
    </script>
</body>
</html>