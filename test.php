<?php
// test.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>System Check</h2>";

// Check PHP
echo "<p>PHP Version: " . phpversion() . "</p>";

// Check config file
if (file_exists('includes/config.php')) {
    require_once 'includes/config.php';
    echo "<p style='color:green'>✅ config.php loaded</p>";
} else {
    echo "<p style='color:red'>❌ config.php not found</p>";
}

// Check database
if (isset($pdo)) {
    echo "<p style='color:green'>✅ Database connected</p>";
    
    // Check tables
    $tables = ['courses', 'admissions', 'carousel_slides', 'gallery', 'contact_messages'];
    foreach ($tables as $table) {
        try {
            $result = $pdo->query("SHOW TABLES LIKE '$table'");
            if ($result->rowCount() > 0) {
                echo "<p style='color:green'>✅ Table '$table' exists</p>";
            } else {
                echo "<p style='color:orange'>⚠️ Table '$table' not found</p>";
            }
        } catch (Exception $e) {
            echo "<p style='color:red'>❌ Error checking table '$table'</p>";
        }
    }
} else {
    echo "<p style='color:red'>❌ Database not connected</p>";
}

// Check assets
$assets = [
    'assets/css/style.css',
    'assets/js/main.js',
    'assets/img/logo.png'
];

foreach ($assets as $asset) {
    if (file_exists($asset)) {
        echo "<p style='color:green'>✅ $asset found</p>";
    } else {
        echo "<p style='color:orange'>⚠️ $asset not found</p>";
    }
}
?>