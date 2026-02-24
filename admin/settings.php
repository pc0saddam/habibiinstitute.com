<?php
// admin/settings.php
require_once '../includes/config.php';
require_once '../includes/auth.php'; // ✅ Fixed: Correct path to auth.php

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

$message = '';
$error = '';

// Fetch current settings first
$stmt = $pdo->query("SELECT setting_key, setting_value FROM website_settings");
$settings = [];
while($row = $stmt->fetch()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

// Handle form submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Update each setting
        foreach($_POST as $key => $value) {
            if(strpos($key, 'setting_') === 0) {
                $setting_key = substr($key, 8); // Remove 'setting_' prefix
                $setting_value = trim($value);
                
                $stmt = $pdo->prepare("UPDATE website_settings SET setting_value = ? WHERE setting_key = ?");
                $stmt->execute([$setting_value, $setting_key]);
            }
        }
        
        // Handle file upload for logo
        if(isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $filename = $_FILES['logo']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if(in_array($ext, $allowed)) {
                $upload_dir = '../assets/img/';
                if(!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                $new_filename = 'logo.' . $ext;
                $upload_path = $upload_dir . $new_filename;
                
                if(move_uploaded_file($_FILES['logo']['tmp_name'], $upload_path)) {
                    // Update logo path in settings
                    $stmt = $pdo->prepare("UPDATE website_settings SET setting_value = ? WHERE setting_key = 'logo_path'");
                    $stmt->execute(['assets/img/' . $new_filename]);
                }
            }
        }
        
        // Handle favicon upload
        if(isset($_FILES['favicon']) && $_FILES['favicon']['error'] == 0) {
            $allowed = ['ico', 'png'];
            $filename = $_FILES['favicon']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if(in_array($ext, $allowed)) {
                $upload_dir = '../';
                $new_filename = 'favicon.ico';
                $upload_path = $upload_dir . $new_filename;
                
                move_uploaded_file($_FILES['favicon']['tmp_name'], $upload_path);
            }
        }
        
        $message = 'Settings updated successfully!';
        logAdminActivity('UPDATE_SETTINGS', 'Updated website settings');
        
        // Refresh settings
        $stmt = $pdo->query("SELECT setting_key, setting_value FROM website_settings");
        $settings = [];
        while($row = $stmt->fetch()) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        
    } catch(PDOException $e) {
        $error = 'Error updating settings: ' . $e->getMessage();
    }
}

// Fetch all settings for grouping
$stmt = $pdo->query("SELECT * FROM website_settings ORDER BY setting_key");
$all_settings = $stmt->fetchAll();

// Group settings by category
$grouped_settings = [];
foreach($all_settings as $setting) {
    $category = 'general';
    if(strpos($setting['setting_key'], 'phone') !== false || strpos($setting['setting_key'], 'email') !== false || strpos($setting['setting_key'], 'address') !== false) {
        $category = 'contact';
    } elseif(strpos($setting['setting_key'], 'social') !== false) {
        $category = 'social';
    } elseif(strpos($setting['setting_key'], 'meta') !== false || strpos($setting['setting_key'], 'google') !== false) {
        $category = 'seo';
    }
    
    $grouped_settings[$category][] = $setting;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Habibi Institute Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .wrapper {
            display: flex;
        }

        .sidebar {
            width: 280px;
            background: linear-gradient(135deg, #8B0000, #6D0000);
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 30px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(201, 162, 39, 0.3);
        }

        .sidebar-header img {
            width: 100px;
            margin-bottom: 15px;
        }

        .sidebar-header h3 {
            color: #C9A227;
            font-size: 1.2rem;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: #fff;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 4px solid transparent;
        }

        .sidebar-menu a i {
            width: 25px;
            margin-right: 10px;
            color: #C9A227;
        }

        .sidebar-menu a:hover {
            background: rgba(255,255,255,0.1);
            border-left-color: #C9A227;
        }

        .sidebar-menu a.active {
            background: rgba(255,255,255,0.15);
            border-left-color: #C9A227;
        }

        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 30px;
        }

        .top-nav {
            background: white;
            padding: 15px 30px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title h2 {
            color: #6D0000;
            margin: 0;
        }

        .settings-nav {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .settings-nav .nav-pills .nav-link {
            color: #6D0000;
            border-radius: 30px;
            padding: 10px 20px;
            margin: 0 5px;
        }

        .settings-nav .nav-pills .nav-link.active {
            background: #8B0000;
            color: #C9A227;
        }

        .settings-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }

        .settings-card h4 {
            color: #6D0000;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #C9A227;
        }

        .form-label {
            color: #6D0000;
            font-weight: 500;
        }

        .form-control:focus, .form-select:focus {
            border-color: #C9A227;
            box-shadow: 0 0 0 0.2rem rgba(201, 162, 39, 0.25);
        }

        .btn-primary {
            background: #8B0000;
            border-color: #8B0000;
            color: #C9A227;
            padding: 12px 30px;
        }

        .btn-primary:hover {
            background: #6D0000;
            border-color: #6D0000;
            color: #C9A227;
        }

        .preview-image {
            max-width: 200px;
            max-height: 100px;
            margin-top: 10px;
            border: 1px solid #ddd;
            padding: 5px;
            border-radius: 5px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                overflow: hidden;
            }
            
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <img src="../assets/img/logo.png" alt="Habibi Institute" onerror="this.src='https://via.placeholder.com/100x100/8B0000/FFFFFF?text=HABIBI'">
                <h3>Admin Panel</h3>
            </div>
            <div class="sidebar-menu">
                <a href="dashboard.php"><i class="fas fa-dashboard"></i> Dashboard</a>
                <a href="carousel.php"><i class="fas fa-images"></i> Carousel Slides</a>
                <a href="courses.php"><i class="fas fa-book"></i> Courses</a>
                <a href="admissions.php"><i class="fas fa-users"></i> Admissions</a>
                <a href="gallery.php"><i class="fas fa-camera"></i> Gallery</a>
                <a href="messages.php"><i class="fas fa-envelope"></i> Messages</a>
                <a href="settings.php" class="active"><i class="fas fa-cog"></i> Settings</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Navigation -->
            <div class="top-nav">
                <div class="page-title">
                    <h2>Website Settings</h2>
                </div>
            </div>

            <?php if($message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <?php if($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <!-- Settings Navigation -->
            <div class="settings-nav">
                <ul class="nav nav-pills justify-content-center" id="settingsTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="general-tab" data-bs-toggle="pill" data-bs-target="#general" type="button" role="tab">
                            <i class="fas fa-globe me-2"></i>General
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="contact-tab" data-bs-toggle="pill" data-bs-target="#contact" type="button" role="tab">
                            <i class="fas fa-address-book me-2"></i>Contact Info
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="social-tab" data-bs-toggle="pill" data-bs-target="#social" type="button" role="tab">
                            <i class="fas fa-share-alt me-2"></i>Social Media
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="seo-tab" data-bs-toggle="pill" data-bs-target="#seo" type="button" role="tab">
                            <i class="fas fa-chart-line me-2"></i>SEO
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Settings Forms -->
            <form method="POST" enctype="multipart/form-data">
                <div class="tab-content" id="settingsTabContent">
                    <!-- General Settings -->
                    <div class="tab-pane fade show active" id="general" role="tabpanel">
                        <div class="settings-card">
                            <h4><i class="fas fa-cog me-2" style="color: #C9A227;"></i>General Settings</h4>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">College Name</label>
                                    <input type="text" name="setting_college_name" class="form-control" value="<?php echo htmlspecialchars($settings['college_name'] ?? ''); ?>" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">College Tagline</label>
                                    <input type="text" name="setting_college_tagline" class="form-control" value="<?php echo htmlspecialchars($settings['college_tagline'] ?? ''); ?>">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Affiliation Text</label>
                                    <input type="text" name="setting_college_affiliation" class="form-control" value="<?php echo htmlspecialchars($settings['college_affiliation'] ?? ''); ?>">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Admission Status</label>
                                    <input type="text" name="setting_admission_status" class="form-control" value="<?php echo htmlspecialchars($settings['admission_status'] ?? ''); ?>">
                                </div>
                                
                                <div class="col-12 mb-3">
                                    <label class="form-label">Mission Statement</label>
                                    <textarea name="setting_mission_text" class="form-control" rows="4"><?php echo htmlspecialchars($settings['mission_text'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="settings-card">
                            <h4><i class="fas fa-image me-2" style="color: #C9A227;"></i>Branding</h4>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Website Logo</label>
                                    <input type="file" name="logo" class="form-control" accept="image/*">
                                    <?php if(isset($settings['logo_path']) && !empty($settings['logo_path'])): ?>
                                    <div class="mt-2">
                                        <img src="../<?php echo $settings['logo_path']; ?>" class="preview-image" alt="Current Logo">
                                        <p class="small text-muted mt-1">Current logo</p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Favicon</label>
                                    <input type="file" name="favicon" class="form-control" accept=".ico,.png">
                                    <small class="text-muted">Recommended: 32x32px ICO or PNG</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Settings -->
                    <div class="tab-pane fade" id="contact" role="tabpanel">
                        <div class="settings-card">
                            <h4><i class="fas fa-phone-alt me-2" style="color: #C9A227;"></i>Contact Information</h4>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone Number 1</label>
                                    <input type="text" name="setting_phone_1" class="form-control" value="<?php echo htmlspecialchars($settings['phone_1'] ?? ''); ?>">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone Number 2</label>
                                    <input type="text" name="setting_phone_2" class="form-control" value="<?php echo htmlspecialchars($settings['phone_2'] ?? ''); ?>">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone Number 3</label>
                                    <input type="text" name="setting_phone_3" class="form-control" value="<?php echo htmlspecialchars($settings['phone_3'] ?? ''); ?>">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="setting_email" class="form-control" value="<?php echo htmlspecialchars($settings['email'] ?? ''); ?>">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">WhatsApp Number</label>
                                    <input type="text" name="setting_whatsapp_number" class="form-control" value="<?php echo htmlspecialchars($settings['whatsapp_number'] ?? ''); ?>">
                                </div>
                                
                                <div class="col-12 mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea name="setting_address" class="form-control" rows="3"><?php echo htmlspecialchars($settings['address'] ?? ''); ?></textarea>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Map Embed URL</label>
                                    <input type="text" name="setting_map_url" class="form-control" value="<?php echo htmlspecialchars($settings['map_url'] ?? ''); ?>">
                                    <small class="text-muted">Google Maps embed URL</small>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Working Hours</label>
                                    <input type="text" name="setting_working_hours" class="form-control" value="<?php echo htmlspecialchars($settings['working_hours'] ?? 'Mon-Fri: 9am-5pm'); ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media Settings -->
                    <div class="tab-pane fade" id="social" role="tabpanel">
                        <div class="settings-card">
                            <h4><i class="fas fa-share-alt me-2" style="color: #C9A227;"></i>Social Media Links</h4>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        <i class="fab fa-facebook me-2" style="color: #3b5998;"></i>Facebook
                                    </label>
                                    <input type="url" name="setting_social_facebook" class="form-control" value="<?php echo htmlspecialchars($settings['social_facebook'] ?? ''); ?>" placeholder="https://facebook.com/...">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        <i class="fab fa-twitter me-2" style="color: #1da1f2;"></i>Twitter
                                    </label>
                                    <input type="url" name="setting_social_twitter" class="form-control" value="<?php echo htmlspecialchars($settings['social_twitter'] ?? ''); ?>" placeholder="https://twitter.com/...">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        <i class="fab fa-instagram me-2" style="color: #e4405f;"></i>Instagram
                                    </label>
                                    <input type="url" name="setting_social_instagram" class="form-control" value="<?php echo htmlspecialchars($settings['social_instagram'] ?? ''); ?>" placeholder="https://instagram.com/...">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        <i class="fab fa-linkedin me-2" style="color: #0077b5;"></i>LinkedIn
                                    </label>
                                    <input type="url" name="setting_social_linkedin" class="form-control" value="<?php echo htmlspecialchars($settings['social_linkedin'] ?? ''); ?>" placeholder="https://linkedin.com/...">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        <i class="fab fa-youtube me-2" style="color: #ff0000;"></i>YouTube
                                    </label>
                                    <input type="url" name="setting_social_youtube" class="form-control" value="<?php echo htmlspecialchars($settings['social_youtube'] ?? ''); ?>" placeholder="https://youtube.com/...">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SEO Settings -->
                    <div class="tab-pane fade" id="seo" role="tabpanel">
                        <div class="settings-card">
                            <h4><i class="fas fa-chart-line me-2" style="color: #C9A227;"></i>SEO Settings</h4>
                            
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="form-label">Meta Title</label>
                                    <input type="text" name="setting_meta_title" class="form-control" value="<?php echo htmlspecialchars($settings['meta_title'] ?? ''); ?>" maxlength="60">
                                    <small class="text-muted">Recommended: 50-60 characters</small>
                                </div>
                                
                                <div class="col-12 mb-3">
                                    <label class="form-label">Meta Description</label>
                                    <textarea name="setting_meta_description" class="form-control" rows="3" maxlength="160"><?php echo htmlspecialchars($settings['meta_description'] ?? ''); ?></textarea>
                                    <small class="text-muted">Recommended: 150-160 characters</small>
                                </div>
                                
                                <div class="col-12 mb-3">
                                    <label class="form-label">Meta Keywords</label>
                                    <input type="text" name="setting_meta_keywords" class="form-control" value="<?php echo htmlspecialchars($settings['meta_keywords'] ?? ''); ?>" placeholder="college, education, courses, ...">
                                    <small class="text-muted">Comma separated keywords</small>
                                </div>
                                
                                <div class="col-12 mb-3">
                                    <label class="form-label">Google Analytics ID</label>
                                    <input type="text" name="setting_google_analytics" class="form-control" value="<?php echo htmlspecialchars($settings['google_analytics'] ?? ''); ?>" placeholder="UA-XXXXXXXXX-X">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-save me-2"></i>Save All Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>