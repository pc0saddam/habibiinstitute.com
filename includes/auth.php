<?php
/**
 * Authentication Helper Functions
 * Habibi Institute of Higher Education
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============================================
// CSRF PROTECTION
// ============================================

/**
 * Generate CSRF token for forms
 * @return string CSRF token
 */
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validate CSRF token from form submission
 * @param string $token Token to validate
 * @return boolean True if valid
 */
function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && 
           hash_equals($_SESSION['csrf_token'], $token);
}

// ============================================
// INPUT SANITIZATION
// ============================================

/**
 * Sanitize user input to prevent XSS attacks
 * @param string $data Raw input data
 * @return string Sanitized data
 */
function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Sanitize email input
 * @param string $email Raw email
 * @return string|false Sanitized email or false if invalid
 */
function sanitizeEmail($email) {
    $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Sanitize phone number (allow only digits)
 * @param string $phone Raw phone number
 * @return string Clean phone number
 */
function sanitizePhone($phone) {
    return preg_replace('/[^0-9]/', '', $phone);
}

// ============================================
// USER IP ADDRESS
// ============================================

/**
 * Get real user IP address
 * @return string IP address
 */
function getUserIP() {
    $ipaddress = '';
    
    // Check for proxy IPs
    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    } else if (isset($_SERVER['HTTP_FORWARDED'])) {
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    } else if (isset($_SERVER['REMOTE_ADDR'])) {
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    } else {
        $ipaddress = 'UNKNOWN';
    }
    
    // If multiple IPs (proxy), take the first one
    if (strpos($ipaddress, ',') !== false) {
        $ipaddress = explode(',', $ipaddress)[0];
    }
    
    return trim($ipaddress);
}

// ============================================
// ADMIN ACTIVITY LOGGING
// ============================================

/**
 * Log admin activities to database
 * @param string $action Action performed
 * @param string $details Additional details
 * @return boolean True if logged successfully
 */
function logAdminActivity($action, $details = '') {
    global $pdo;
    
    // Check if PDO is available and user is logged in
    if (!isset($pdo) || !isset($_SESSION['admin_id'])) {
        return false;
    }
    
    try {
        // Check if admin_logs table exists
        $checkTable = $pdo->query("SHOW TABLES LIKE 'admin_logs'");
        if ($checkTable->rowCount() == 0) {
            // Create table if it doesn't exist
            createAdminLogsTable();
        }
        
        // Insert log
        $stmt = $pdo->prepare("
            INSERT INTO admin_logs 
            (admin_id, username, action, details, ip_address, user_agent) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        return $stmt->execute([
            $_SESSION['admin_id'] ?? null,
            $_SESSION['admin_username'] ?? 'system',
            $action,
            $details,
            getUserIP(),
            $_SERVER['HTTP_USER_AGENT'] ?? ''
        ]);
        
    } catch (PDOException $e) {
        // Silently fail - don't interrupt main process
        error_log("Admin log failed: " . $e->getMessage());
        return false;
    }
}

/**
 * Create admin_logs table if it doesn't exist
 */
function createAdminLogsTable() {
    global $pdo;
    
    $sql = "CREATE TABLE IF NOT EXISTS admin_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        admin_id INT,
        username VARCHAR(50),
        action VARCHAR(255) NOT NULL,
        details TEXT,
        ip_address VARCHAR(45),
        user_agent TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_admin_id (admin_id),
        INDEX idx_username (username),
        INDEX idx_created_at (created_at),
        INDEX idx_action (action)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    
    try {
        $pdo->exec($sql);
    } catch (PDOException $e) {
        // Table might already exist
        error_log("Failed to create admin_logs table: " . $e->getMessage());
    }
}

// ============================================
// AUTHENTICATION CHECKS
// ============================================

/**
 * Check if user is logged in
 * @return boolean True if logged in
 */
function isLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && 
           $_SESSION['admin_logged_in'] === true;
}

/**
 * Require authentication - redirect if not logged in
 */
function requireAuth() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
    
    // Check session timeout (30 minutes)
    $timeout = 1800; // 30 minutes in seconds
    if (isset($_SESSION['last_activity']) && 
        (time() - $_SESSION['last_activity']) > $timeout) {
        
        // Session expired
        session_unset();
        session_destroy();
        header('Location: login.php?timeout=1');
        exit();
    }
    
    // Update last activity time
    $_SESSION['last_activity'] = time();
}

/**
 * Get current admin name
 * @return string Admin name
 */
function getCurrentAdmin() {
    return $_SESSION['admin_name'] ?? $_SESSION['admin_username'] ?? 'Admin';
}

/**
 * Get current admin ID
 * @return int|null Admin ID
 */
function getCurrentAdminId() {
    return $_SESSION['admin_id'] ?? null;
}

/**
 * Get current admin role
 * @return string Admin role
 */
function getCurrentAdminRole() {
    return $_SESSION['admin_role'] ?? 'admin';
}

/**
 * Check if current admin has specific role
 * @param string|array $roles Required role(s)
 * @return boolean True if has permission
 */
function hasRole($roles) {
    if (!isLoggedIn()) {
        return false;
    }
    
    $currentRole = getCurrentAdminRole();
    
    if (is_array($roles)) {
        return in_array($currentRole, $roles);
    }
    
    return $currentRole === $roles;
}

// ============================================
// PASSWORD HANDLING
// ============================================

/**
 * Hash password securely
 * @param string $password Plain text password
 * @return string Hashed password
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verify password against hash
 * @param string $password Plain text password
 * @param string $hash Stored hash
 * @return boolean True if password matches
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Generate random password
 * @param int $length Password length
 * @return string Random password
 */
function generateRandomPassword($length = 10) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $password;
}

// ============================================
// SESSION MANAGEMENT
// ============================================

/**
 * Regenerate session ID securely
 */
function regenerateSession() {
    session_regenerate_id(true);
}

/**
 * Destroy session completely
 */
function destroySession() {
    $_SESSION = array();
    
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }
    
    session_destroy();
}

// ============================================
// REMEMBER ME FUNCTIONALITY
// ============================================

/**
 * Set remember me cookie
 * @param int $userId User ID
 * @return string Token
 */
function setRememberMe($userId) {
    $token = bin2hex(random_bytes(32));
    $expires = time() + (86400 * 30); // 30 days
    
    setcookie(
        'remember_token',
        $token,
        $expires,
        '/',
        '',
        false, // Set to true if using HTTPS
        true   // HttpOnly
    );
    
    // Store token in database (implement if needed)
    // storeRememberToken($userId, $token, $expires);
    
    return $token;
}

/**
 * Clear remember me cookie
 */
function clearRememberMe() {
    if (isset($_COOKIE['remember_token'])) {
        setcookie('remember_token', '', time() - 3600, '/');
    }
}

// ============================================
// RATE LIMITING
// ============================================

/**
 * Simple rate limiting for login attempts
 * @param string $key Identifier (username or IP)
 * @param int $maxAttempts Maximum attempts allowed
 * @param int $timeWindow Time window in seconds
 * @return boolean True if allowed
 */
function checkRateLimit($key, $maxAttempts = 5, $timeWindow = 900) {
    $rateLimitFile = sys_get_temp_dir() . '/rate_limit_' . md5($key);
    
    $attempts = [];
    if (file_exists($rateLimitFile)) {
        $attempts = unserialize(file_get_contents($rateLimitFile));
        // Remove old attempts
        $attempts = array_filter($attempts, function($time) use ($timeWindow) {
            return $time > (time() - $timeWindow);
        });
    }
    
    if (count($attempts) >= $maxAttempts) {
        return false;
    }
    
    $attempts[] = time();
    file_put_contents($rateLimitFile, serialize($attempts));
    
    return true;
}

// ============================================
// INITIALIZATION
// ============================================

// Auto-require authentication for admin pages
$currentPage = basename($_SERVER['PHP_SELF'] ?? '');
$adminPages = ['dashboard.php', 'carousel.php', 'courses.php', 'admissions.php', 
               'gallery.php', 'messages.php', 'settings.php'];

if (in_array($currentPage, $adminPages)) {
    requireAuth();
}

// Generate CSRF token for current session if not exists
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>