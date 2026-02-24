<?php
// admin/carousel.php
require_once '../includes/config.php';
require_once '../includes/auth.php'; // ✅ Fixed: Correct path to auth.php

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

// Handle actions
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$message = '';
$error = '';

// Handle delete
if($action == 'delete' && $id > 0) {
    try {
        // Get image path before deleting
        $stmt = $pdo->prepare("SELECT image_path FROM carousel_slides WHERE id = ?");
        $stmt->execute([$id]);
        $slide = $stmt->fetch();
        
        if($slide && !empty($slide['image_path']) && file_exists('../' . $slide['image_path'])) {
            unlink('../' . $slide['image_path']);
        }
        
        $stmt = $pdo->prepare("DELETE FROM carousel_slides WHERE id = ?");
        $stmt->execute([$id]);
        
        logAdminActivity('DELETE_SLIDE', 'Deleted carousel slide ID: ' . $id);
        $message = 'Slide deleted successfully!';
    } catch(PDOException $e) {
        $error = 'Error deleting slide: ' . $e->getMessage();
    }
    $action = 'list';
}

// Handle status toggle
if($action == 'toggle' && $id > 0) {
    try {
        $stmt = $pdo->prepare("UPDATE carousel_slides SET status = NOT status WHERE id = ?");
        $stmt->execute([$id]);
        $message = 'Slide status updated!';
        logAdminActivity('TOGGLE_SLIDE', 'Toggled slide status ID: ' . $id);
    } catch(PDOException $e) {
        $error = 'Error updating slide: ' . $e->getMessage();
    }
    $action = 'list';
}

// Handle add/edit form submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['save_slide'])) {
        $title = trim($_POST['title']);
        $subtitle = trim($_POST['subtitle']);
        $button1_text = trim($_POST['button1_text']);
        $button1_link = trim($_POST['button1_link']);
        $button2_text = trim($_POST['button2_text']);
        $button2_link = trim($_POST['button2_link']);
        $sort_order = (int)$_POST['sort_order'];
        $status = isset($_POST['status']) ? 1 : 0;
        
        // Handle image upload
        $image_path = $_POST['existing_image'] ?? '';
        
        if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $filename = $_FILES['image']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if(in_array($ext, $allowed)) {
                $new_filename = 'slide_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                $upload_path = '../assets/uploads/carousel/' . $new_filename;
                
                // Create directory if not exists
                if(!is_dir('../assets/uploads/carousel/')) {
                    mkdir('../assets/uploads/carousel/', 0777, true);
                }
                
                if(move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                    // Delete old image if updating
                    if(!empty($_POST['existing_image']) && file_exists('../' . $_POST['existing_image'])) {
                        @unlink('../' . $_POST['existing_image']);
                    }
                    $image_path = 'assets/uploads/carousel/' . $new_filename;
                } else {
                    $error = 'Error uploading image.';
                }
            } else {
                $error = 'Invalid file type. Only JPG, PNG, GIF and WEBP allowed.';
            }
        }
        
        if(empty($error)) {
            try {
                if($id > 0) {
                    // Update
                    $stmt = $pdo->prepare("UPDATE carousel_slides SET title=?, subtitle=?, image_path=?, button1_text=?, button1_link=?, button2_text=?, button2_link=?, sort_order=?, status=? WHERE id=?");
                    $stmt->execute([$title, $subtitle, $image_path, $button1_text, $button1_link, $button2_text, $button2_link, $sort_order, $status, $id]);
                    logAdminActivity('UPDATE_SLIDE', 'Updated slide ID: ' . $id);
                    $message = 'Slide updated successfully!';
                } else {
                    // Insert
                    $stmt = $pdo->prepare("INSERT INTO carousel_slides (title, subtitle, image_path, button1_text, button1_link, button2_text, button2_link, sort_order, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$title, $subtitle, $image_path, $button1_text, $button1_link, $button2_text, $button2_link, $sort_order, $status]);
                    logAdminActivity('ADD_SLIDE', 'Added new slide: ' . $title);
                    $message = 'Slide added successfully!';
                }
                $action = 'list';
            } catch(PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
}

// Fetch slide for editing
$slide = null;
if(($action == 'edit' || $action == 'add') && $id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM carousel_slides WHERE id = ?");
    $stmt->execute([$id]);
    $slide = $stmt->fetch();
}

// Fetch all slides
$slides = $pdo->query("SELECT * FROM carousel_slides ORDER BY sort_order ASC, id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carousel Management - Habibi Institute Admin</title>
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

        .content-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .btn-primary {
            background: #8B0000;
            border-color: #8B0000;
            color: #C9A227;
        }

        .btn-primary:hover {
            background: #6D0000;
            border-color: #6D0000;
            color: #C9A227;
        }

        .btn-warning {
            background: #C9A227;
            border-color: #C9A227;
            color: #6D0000;
        }

        .btn-warning:hover {
            background: #E6C55C;
            border-color: #E6C55C;
            color: #6D0000;
        }

        .table th {
            border-top: none;
            color: #666;
            font-weight: 600;
        }

        .preview-image {
            width: 100px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .action-btns {
            display: flex;
            gap: 5px;
        }

        .action-btns .btn {
            padding: 5px 10px;
            font-size: 0.8rem;
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
                <a href="carousel.php" class="active"><i class="fas fa-images"></i> Carousel Slides</a>
                <a href="courses.php"><i class="fas fa-book"></i> Courses</a>
                <a href="admissions.php"><i class="fas fa-users"></i> Admissions</a>
                <a href="gallery.php"><i class="fas fa-camera"></i> Gallery</a>
                <a href="messages.php"><i class="fas fa-envelope"></i> Messages</a>
                <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Navigation -->
            <div class="top-nav">
                <div class="page-title">
                    <h2>Carousel Slides Management</h2>
                </div>
                <div>
                    <a href="?action=add" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i>Add New Slide
                    </a>
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

            <!-- Content Card -->
            <div class="content-card">
                <?php if($action == 'list'): ?>
                    <!-- List View -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Preview</th>
                                    <th>Title</th>
                                    <th>Subtitle</th>
                                    <th>Sort Order</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($slides as $slide): ?>
                                <tr>
                                    <td>#<?php echo $slide['id']; ?></td>
                                    <td>
                                        <?php if(!empty($slide['image_path'])): ?>
                                        <img src="<?php echo '../' . $slide['image_path']; ?>" class="preview-image" onerror="this.src='https://via.placeholder.com/100x60/8B0000/FFFFFF?text=No+Image'">
                                        <?php else: ?>
                                        <span class="text-muted">No image</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($slide['title']); ?></td>
                                    <td><?php echo htmlspecialchars(substr($slide['subtitle'] ?? '', 0, 50)) . '...'; ?></td>
                                    <td><?php echo $slide['sort_order']; ?></td>
                                    <td>
                                        <span class="status-badge <?php echo $slide['status'] ? 'status-active' : 'status-inactive'; ?>">
                                            <?php echo $slide['status'] ? 'Active' : 'Inactive'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-btns">
                                            <a href="?action=toggle&id=<?php echo $slide['id']; ?>" class="btn btn-sm btn-warning" title="Toggle Status">
                                                <i class="fas fa-sync-alt"></i>
                                            </a>
                                            <a href="?action=edit&id=<?php echo $slide['id']; ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="?action=delete&id=<?php echo $slide['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this slide?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if(empty($slides)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-images fa-3x mb-3" style="color: #ccc;"></i>
                                        <p>No slides found. Click "Add New Slide" to create your first slide.</p>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <!-- Add/Edit Form -->
                    <form method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Slide Title *</label>
                                    <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($slide['title'] ?? ''); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Subtitle</label>
                                    <textarea name="subtitle" class="form-control" rows="3"><?php echo htmlspecialchars($slide['subtitle'] ?? ''); ?></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Button 1 Text</label>
                                            <input type="text" name="button1_text" class="form-control" value="<?php echo htmlspecialchars($slide['button1_text'] ?? ''); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Button 1 Link</label>
                                            <input type="text" name="button1_link" class="form-control" value="<?php echo htmlspecialchars($slide['button1_link'] ?? ''); ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Button 2 Text</label>
                                            <input type="text" name="button2_text" class="form-control" value="<?php echo htmlspecialchars($slide['button2_text'] ?? ''); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Button 2 Link</label>
                                            <input type="text" name="button2_link" class="form-control" value="<?php echo htmlspecialchars($slide['button2_link'] ?? ''); ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Sort Order</label>
                                            <input type="number" name="sort_order" class="form-control" value="<?php echo $slide['sort_order'] ?? 0; ?>" min="0">
                                            <small class="text-muted">Lower numbers appear first</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="form-check mt-4">
                                                <input class="form-check-input" type="checkbox" name="status" id="status" <?php echo (!isset($slide['status']) || $slide['status']) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="status">
                                                    Active
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Slide Image</label>
                                    <?php if(!empty($slide['image_path'])): ?>
                                    <div class="mb-3">
                                        <img src="<?php echo '../' . $slide['image_path']; ?>" class="img-fluid rounded" style="max-height: 150px;">
                                        <input type="hidden" name="existing_image" value="<?php echo $slide['image_path']; ?>">
                                    </div>
                                    <?php endif; ?>
                                    <input type="file" name="image" class="form-control" accept="image/*" <?php echo empty($slide) ? 'required' : ''; ?>>
                                    <small class="text-muted">Recommended size: 1920x1080px</small>
                                </div>

                                <div class="alert alert-info">
                                    <h6><i class="fas fa-info-circle me-2"></i>Preview</h6>
                                    <p class="small mb-0">The slide will appear on the homepage with your specified content and styling.</p>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="text-end">
                            <a href="?action=list" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" name="save_slide" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Slide
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>