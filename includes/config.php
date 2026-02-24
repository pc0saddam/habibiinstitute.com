<?php
// includes/config.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Database configuration - YEH VALUES SAHI HAIN?
define('DB_HOST', 'localhost');
define('DB_NAME', 'habibi_institute');
define('DB_USER', 'root');
define('DB_PASS', ''); // XAMPP mein blank

// ✅ BASE_URL - YEH SAHI HONA CHAHIYE
define('BASE_URL', 'http://localhost/habibi-institute/');
define('SITE_NAME', 'Habibi Institute of Higher Education');

try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}

// Get settings
$settings = [];
try {
    $stmt = $pdo->query("SELECT setting_key, setting_value FROM website_settings");
    while($row = $stmt->fetch()) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
} catch(PDOException $e) {
    // Table might not exist yet
    $settings = [
        'admission_status' => 'ADMISSION OPEN 2026-27',
        'phone_1' => '9720229697',
        'phone_2' => '9410066786',
        'phone_3' => '9756666480',
        'email' => 'institute.habibi@gmail.com',
        'address' => '2 km Dingerpur Kundarki Road, Vill. Guiller, Tehsil Bilari, Distt. Moradabad-244301 (U.P.)'
    ];
}
?>