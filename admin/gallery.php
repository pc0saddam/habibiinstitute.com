<?php
// admin/gallery.php
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
        $stmt = $pdo->prepare("SELECT image_path FROM gallery WHERE id = ?");
        $stmt->execute([$id]);
        $image = $stmt->fetch();
        
        if($image && !empty($image['image_path']) && file_exists('../' . $image['image_path'])) {
            unlink('../' . $image['image_path']);
        }
        
        $stmt = $pdo->prepare("DELETE FROM gallery WHERE id = ?");
        $stmt->execute([$id]);
        
        logAdminActivity('DELETE_GALLERY', 'Deleted gallery image ID: ' . $id);
        $message = 'Image deleted successfully!';
    } catch(PDOException $e) {
        $error = 'Error deleting image: ' . $e->getMessage();
    }
    $action = 'list';
}

// Handle bulk upload
if(isset($_POST['upload_images'])) {
    $title = trim($_POST['title']);
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    
    $uploaded = 0;
    $failed = 0;
    
    // Create upload directory if not exists
    $upload_dir = '../assets/uploads/gallery/';
    if(!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    // Handle multiple file upload
    if(isset($_FILES['images'])) {
        $files = $_FILES['images'];
        $file_count = count($files['name']);
        
        for($i = 0; $i < $file_count; $i++) {
            if($files['error'][$i] == 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $filename = $files['name'][$i];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                
                if(in_array($ext, $allowed)) {
                    $new_filename = 'gallery_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                    $upload_path = $upload_dir . $new_filename;
                    
                    if(move_uploaded_file($files['tmp_name'][$i], $upload_path)) {
                        $image_path = 'assets/uploads/gallery/' . $new_filename;
                        
                        // Insert into database
                        $stmt = $pdo->prepare("INSERT INTO gallery (title, category, description, image_path) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$title ?: 'Gallery Image', $category, $description, $image_path]);
                        
                        $uploaded++;
                    } else {
                        $failed++;
                    }
                } else {
                    $failed++;
                }
            }
        }
    }
    
    if($uploaded > 0) {
        $message = "$uploaded images uploaded successfully!";
        if($failed > 0) {
            $message .= " $failed images failed.";
        }
        logAdminActivity('UPLOAD_GALLERY', "Uploaded $uploaded images to gallery");
    } else {
        $error = 'No images were uploaded. Please check file types and try again.';
    }
}

// Handle single image edit
if(isset($_POST['update_image'])) {
    $id = (int)$_POST['id'];
    $title = trim($_POST['title']);
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    $sort_order = (int)$_POST['sort_order'];
    
    try {
        $stmt = $pdo->prepare("UPDATE gallery SET title=?, category=?, description=?, sort_order=? WHERE id=?");
        $stmt->execute([$title, $category, $description, $sort_order, $id]);
        $message = 'Image details updated successfully!';
        logAdminActivity('UPDATE_GALLERY', 'Updated gallery image ID: ' . $id);
    } catch(PDOException $e) {
        $error = 'Error updating image: ' . $e->getMessage();
    }
}

// Fetch single image for editing
$edit_image = null;
if($action == 'edit' && $id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM gallery WHERE id = ?");
    $stmt->execute([$id]);
    $edit_image = $stmt->fetch();
}

// Fetch all gallery images
$gallery = $pdo->query("SELECT * FROM gallery ORDER BY sort_order ASC, uploaded_at DESC")->fetchAll();

// Get categories
$categories = $pdo->query("SELECT DISTINCT category FROM gallery WHERE category IS NOT NULL AND category != ''")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery Management - Habibi Institute Admin</title>
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

        .upload-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border: 2px dashed #C9A227;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .gallery-item {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            position: relative;
        }

        .gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(139, 0, 0, 0.1);
        }

        .gallery-image {
            height: 180px;
            overflow: hidden;
            position: relative;
        }

        .gallery-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .gallery-item:hover .gallery-image img {
            transform: scale(1.1);
        }

        .gallery-info {
            padding: 15px;
        }

        .gallery-info h5 {
            color: #6D0000;
            margin-bottom: 5px;
            font-size: 1rem;
        }

        .gallery-info .category {
            color: #8B0000;
            font-size: 0.8rem;
            margin-bottom: 5px;
        }

        .gallery-info .date {
            color: #666;
            font-size: 0.7rem;
        }

        .gallery-actions {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            gap: 5px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .gallery-item:hover .gallery-actions {
            opacity: 1;
        }

        .gallery-actions .btn {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            color: #8B0000;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .gallery-actions .btn:hover {
            background: #8B0000;
            color: #C9A227;
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

        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                overflow: hidden;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
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
                <a href="gallery.php" class="active"><i class="fas fa-camera"></i> Gallery</a>
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
                    <h2>Gallery Management</h2>
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

            <!-- Upload Form -->
            <div class="upload-card">
                <h4 class="mb-4" style="color: #6D0000;">
                    <i class="fas fa-cloud-upload-alt me-2" style="color: #C9A227;"></i>
                    Upload New Images
                </h4>
                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Image Title (Optional)</label>
                            <input type="text" name="title" class="form-control" placeholder="e.g., Campus View">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select">
                                <option value="">Select Category</option>
                                <option value="campus">Campus</option>
                                <option value="facilities">Facilities</option>
                                <option value="events">Events</option>
                                <option value="labs">Laboratories</option>
                                <option value="sports">Sports</option>
                                <option value="cultural">Cultural</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Description (Optional)</label>
                            <input type="text" name="description" class="form-control" placeholder="Brief description">
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Select Images (Multiple allowed)</label>
                            <input type="file" name="images[]" class="form-control" multiple accept="image/*" required>
                            <small class="text-muted">Allowed: JPG, PNG, GIF, WEBP. Max size: 5MB each</small>
                        </div>
                        <div class="col-md-4 mb-3 d-flex align-items-end">
                            <button type="submit" name="upload_images" class="btn btn-primary w-100">
                                <i class="fas fa-upload me-2"></i>Upload Images
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Edit Modal -->
            <?php if($edit_image): ?>
            <div class="modal fade show" id="editModal" tabindex="-1" style="display: block; background: rgba(0,0,0,0.5);">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Image Details</h5>
                            <a href="?action=list" class="btn-close"></a>
                        </div>
                        <form method="POST">
                            <input type="hidden" name="id" value="<?php echo $edit_image['id']; ?>">
                            <div class="modal-body">
                                <div class="text-center mb-3">
                                    <img src="../<?php echo $edit_image['image_path']; ?>" class="img-fluid rounded" style="max-height: 200px;">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Title</label>
                                    <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($edit_image['title']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <select name="category" class="form-select">
                                        <option value="">Select Category</option>
                                        <option value="campus" <?php echo $edit_image['category'] == 'campus' ? 'selected' : ''; ?>>Campus</option>
                                        <option value="facilities" <?php echo $edit_image['category'] == 'facilities' ? 'selected' : ''; ?>>Facilities</option>
                                        <option value="events" <?php echo $edit_image['category'] == 'events' ? 'selected' : ''; ?>>Events</option>
                                        <option value="labs" <?php echo $edit_image['category'] == 'labs' ? 'selected' : ''; ?>>Laboratories</option>
                                        <option value="sports" <?php echo $edit_image['category'] == 'sports' ? 'selected' : ''; ?>>Sports</option>
                                        <option value="cultural" <?php echo $edit_image['category'] == 'cultural' ? 'selected' : ''; ?>>Cultural</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($edit_image['description']); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Sort Order</label>
                                    <input type="number" name="sort_order" class="form-control" value="<?php echo $edit_image['sort_order'] ?? 0; ?>" min="0">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="?action=list" class="btn btn-secondary">Cancel</a>
                                <button type="submit" name="update_image" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Category Filter -->
            <div class="mb-4">
                <div class="btn-group" role="group">
                    <button class="btn btn-outline-primary active" onclick="filterGallery('all')">All</button>
                    <?php foreach($categories as $cat): ?>
                    <button class="btn btn-outline-primary" onclick="filterGallery('<?php echo $cat['category']; ?>')">
                        <?php echo ucfirst($cat['category']); ?>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Gallery Grid -->
            <div class="gallery-grid">
                <?php if(!empty($gallery)): ?>
                <?php foreach($gallery as $image): ?>
                <div class="gallery-item" data-category="<?php echo $image['category']; ?>">
                    <div class="gallery-image">
                        <img src="../<?php echo $image['image_path']; ?>" alt="<?php echo htmlspecialchars($image['title']); ?>" onerror="this.src='https://via.placeholder.com/300x200/8B0000/FFFFFF?text=Image+Error'">
                    </div>
                    <div class="gallery-info">
                        <h5><?php echo htmlspecialchars($image['title'] ?: 'Untitled'); ?></h5>
                        <div class="category">
                            <i class="fas fa-tag me-1"></i><?php echo ucfirst($image['category'] ?: 'General'); ?>
                        </div>
                        <div class="date">
                            <i class="far fa-calendar me-1"></i><?php echo date('d M Y', strtotime($image['uploaded_at'])); ?>
                        </div>
                    </div>
                    <div class="gallery-actions">
                        <a href="?action=edit&id=<?php echo $image['id']; ?>" class="btn" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="?action=delete&id=<?php echo $image['id']; ?>" class="btn" title="Delete" onclick="return confirm('Are you sure you want to delete this image?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <div class="col-12 text-center py-5">
                    <i class="fas fa-images fa-4x mb-3" style="color: #ccc;"></i>
                    <h5>No images in gallery</h5>
                    <p class="text-muted">Upload your first image using the form above</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function filterGallery(category) {
            var items = document.querySelectorAll('.gallery-item');
            var buttons = document.querySelectorAll('.btn-group .btn');
            
            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            items.forEach(item => {
                if(category === 'all' || item.dataset.category === category) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>