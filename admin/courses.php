<?php
// admin/courses.php
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
        $stmt = $pdo->prepare("DELETE FROM courses WHERE id = ?");
        $stmt->execute([$id]);
        
        logAdminActivity('DELETE_COURSE', 'Deleted course ID: ' . $id);
        $message = 'Course deleted successfully!';
    } catch(PDOException $e) {
        $error = 'Error deleting course: ' . $e->getMessage();
    }
    $action = 'list';
}

// Handle status toggle
if($action == 'toggle' && $id > 0) {
    try {
        $stmt = $pdo->prepare("UPDATE courses SET status = NOT status WHERE id = ?");
        $stmt->execute([$id]);
        $message = 'Course status updated!';
        logAdminActivity('TOGGLE_COURSE', 'Toggled course status ID: ' . $id);
    } catch(PDOException $e) {
        $error = 'Error updating course: ' . $e->getMessage();
    }
    $action = 'list';
}

// Handle add/edit form submission
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_course'])) {
    $name = trim($_POST['name']);
    $slug = trim($_POST['slug']);
    $duration = trim($_POST['duration']);
    $eligibility = trim($_POST['eligibility']);
    $description = trim($_POST['description']);
    $curriculum = trim($_POST['curriculum']);
    $career_opportunities = trim($_POST['career_opportunities']);
    $fee_structure = trim($_POST['fee_structure']);
    $status = isset($_POST['status']) ? 1 : 0;
    
    // Generate slug if empty
    if(empty($slug)) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
    }
    
    // Check if slug exists
    $checkStmt = $pdo->prepare("SELECT id FROM courses WHERE slug = ? AND id != ?");
    $checkStmt->execute([$slug, $id]);
    if($checkStmt->fetch()) {
        $error = 'Slug already exists. Please use a different slug.';
    }
    
    if(empty($error)) {
        try {
            if($id > 0) {
                // Update
                $stmt = $pdo->prepare("UPDATE courses SET name=?, slug=?, duration=?, eligibility=?, description=?, curriculum=?, career_opportunities=?, fee_structure=?, status=? WHERE id=?");
                $stmt->execute([$name, $slug, $duration, $eligibility, $description, $curriculum, $career_opportunities, $fee_structure, $status, $id]);
                logAdminActivity('UPDATE_COURSE', 'Updated course: ' . $name);
                $message = 'Course updated successfully!';
            } else {
                // Insert
                $stmt = $pdo->prepare("INSERT INTO courses (name, slug, duration, eligibility, description, curriculum, career_opportunities, fee_structure, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name, $slug, $duration, $eligibility, $description, $curriculum, $career_opportunities, $fee_structure, $status]);
                logAdminActivity('ADD_COURSE', 'Added new course: ' . $name);
                $message = 'Course added successfully!';
            }
            $action = 'list';
        } catch(PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}

// Fetch course for editing
$course = null;
if(($action == 'edit' || $action == 'add') && $id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
    $stmt->execute([$id]);
    $course = $stmt->fetch();
}

// Fetch all courses
$courses = $pdo->query("SELECT * FROM courses ORDER BY name ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses Management - Habibi Institute Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
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

        /* Summernote customization */
        .note-editor.note-frame {
            border: 1px solid #ddd;
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
                <a href="courses.php" class="active"><i class="fas fa-book"></i> Courses</a>
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
                    <h2>Courses Management</h2>
                </div>
                <div>
                    <a href="?action=add" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i>Add New Course
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
                                    <th>Course Name</th>
                                    <th>Duration</th>
                                    <th>Slug</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($courses as $course): ?>
                                <tr>
                                    <td>#<?php echo $course['id']; ?></td>
                                    <td><?php echo htmlspecialchars($course['name']); ?></td>
                                    <td><?php echo htmlspecialchars($course['duration'] ?: 'N/A'); ?></td>
                                    <td><code><?php echo htmlspecialchars($course['slug']); ?></code></td>
                                    <td>
                                        <span class="status-badge <?php echo $course['status'] ? 'status-active' : 'status-inactive'; ?>">
                                            <?php echo $course['status'] ? 'Active' : 'Inactive'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-btns">
                                            <a href="?action=toggle&id=<?php echo $course['id']; ?>" class="btn btn-sm btn-warning" title="Toggle Status">
                                                <i class="fas fa-sync-alt"></i>
                                            </a>
                                            <a href="?action=edit&id=<?php echo $course['id']; ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="?action=delete&id=<?php echo $course['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this course?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if(empty($courses)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-book fa-3x mb-3" style="color: #ccc;"></i>
                                        <p>No courses found. Click "Add New Course" to create your first course.</p>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <!-- Add/Edit Form -->
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Course Name *</label>
                                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($course['name'] ?? ''); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Slug</label>
                                    <input type="text" name="slug" class="form-control" value="<?php echo htmlspecialchars($course['slug'] ?? ''); ?>">
                                    <small class="text-muted">URL-friendly name. Leave empty to auto-generate.</small>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Duration</label>
                                            <input type="text" name="duration" class="form-control" value="<?php echo htmlspecialchars($course['duration'] ?? ''); ?>" placeholder="e.g., 3 Years">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Eligibility</label>
                                            <input type="text" name="eligibility" class="form-control" value="<?php echo htmlspecialchars($course['eligibility'] ?? ''); ?>" placeholder="e.g., 10+2 with Science">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control summernote" rows="5"><?php echo htmlspecialchars($course['description'] ?? ''); ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Curriculum</label>
                                    <textarea name="curriculum" class="form-control summernote" rows="5"><?php echo htmlspecialchars($course['curriculum'] ?? ''); ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Career Opportunities</label>
                                    <textarea name="career_opportunities" class="form-control summernote" rows="5"><?php echo htmlspecialchars($course['career_opportunities'] ?? ''); ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Fee Structure</label>
                                    <textarea name="fee_structure" class="form-control summernote" rows="3"><?php echo htmlspecialchars($course['fee_structure'] ?? ''); ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="status" id="status" <?php echo (!isset($course['status']) || $course['status']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="status">
                                            Active
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-info-circle me-2"></i>Course URL</h6>
                                    <p class="small mb-2">Students can view this course at:</p>
                                    <code><?php echo BASE_URL; ?>course/<?php echo $course['slug'] ?? 'course-slug'; ?></code>
                                </div>

                                <div class="alert alert-warning">
                                    <h6><i class="fas fa-lightbulb me-2"></i>SEO Tips</h6>
                                    <ul class="small mb-0">
                                        <li>Use descriptive course names</li>
                                        <li>Include key specializations</li>
                                        <li>Mention career prospects</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="text-end">
                            <a href="?action=list" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" name="save_course" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Course
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.summernote').summernote({
                height: 200,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });
    </script>
</body>
</html>