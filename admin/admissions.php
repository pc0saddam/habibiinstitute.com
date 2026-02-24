<?php
// admin/admissions.php
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

// Handle status update
if(isset($_POST['update_status'])) {
    $id = (int)$_POST['id'];
    $status = $_POST['status'];
    
    try {
        $stmt = $pdo->prepare("UPDATE admissions SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        $message = 'Application status updated successfully!';
        logAdminActivity('UPDATE_ADMISSION_STATUS', 'Updated admission ID: ' . $id . ' to status: ' . $status);
    } catch(PDOException $e) {
        $error = 'Error updating status: ' . $e->getMessage();
    }
}

// Handle delete
if($action == 'delete' && $id > 0) {
    try {
        $stmt = $pdo->prepare("DELETE FROM admissions WHERE id = ?");
        $stmt->execute([$id]);
        $message = 'Application deleted successfully!';
        logAdminActivity('DELETE_ADMISSION', 'Deleted admission ID: ' . $id);
    } catch(PDOException $e) {
        $error = 'Error deleting application: ' . $e->getMessage();
    }
    $action = 'list';
}

// Handle export to CSV
if($action == 'export') {
    $filename = 'admissions_' . date('Y-m-d') . '.csv';
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $output = fopen('php://output', 'w');
    
    // Add headers
    fputcsv($output, ['ID', 'Student Name', 'Father Name', 'Mobile', 'Email', 'Course', 'Qualification', 'Address', 'Status', 'Applied Date']);
    
    // Fetch data
    $stmt = $pdo->query("SELECT a.*, c.name as course_name FROM admissions a LEFT JOIN courses c ON a.course_id = c.id ORDER BY a.submitted_at DESC");
    
    while($row = $stmt->fetch()) {
        fputcsv($output, [
            $row['id'],
            $row['student_name'],
            $row['father_name'],
            $row['mobile'],
            $row['email'],
            $row['course_name'],
            $row['qualification'],
            $row['address'],
            $row['status'],
            $row['submitted_at']
        ]);
    }
    
    fclose($output);
    exit();
}

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

// Search and filter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$course_filter = isset($_GET['course']) ? (int)$_GET['course'] : 0;

$where_clauses = [];
$params = [];

if(!empty($search)) {
    $where_clauses[] = "(student_name LIKE ? OR father_name LIKE ? OR mobile LIKE ? OR email LIKE ?)";
    $search_term = "%$search%";
    $params[] = $search_term;
    $params[] = $search_term;
    $params[] = $search_term;
    $params[] = $search_term;
}

if(!empty($status_filter)) {
    $where_clauses[] = "status = ?";
    $params[] = $status_filter;
}

if($course_filter > 0) {
    $where_clauses[] = "course_id = ?";
    $params[] = $course_filter;
}

$where_sql = !empty($where_clauses) ? "WHERE " . implode(" AND ", $where_clauses) : "";

// Get total records for pagination
$count_sql = "SELECT COUNT(*) FROM admissions a " . $where_sql;
$count_stmt = $pdo->prepare($count_sql);
$count_stmt->execute($params);
$total_records = $count_stmt->fetchColumn();
$total_pages = ceil($total_records / $limit);

// Fetch admissions with course names
$sql = "SELECT a.*, c.name as course_name FROM admissions a 
        LEFT JOIN courses c ON a.course_id = c.id 
        $where_sql 
        ORDER BY a.submitted_at DESC 
        LIMIT $offset, $limit";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$admissions = $stmt->fetchAll();

// Fetch courses for filter
$courses = $pdo->query("SELECT id, name FROM courses WHERE status = 1 ORDER BY name")->fetchAll();

// Get statistics
$stats = $pdo->query("
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
        SUM(CASE WHEN status = 'contacted' THEN 1 ELSE 0 END) as contacted,
        SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
        SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
    FROM admissions
")->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admissions Management - Habibi Institute Admin</title>
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

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-left: 4px solid;
        }

        .stat-card.pending { border-left-color: #ffc107; }
        .stat-card.contacted { border-left-color: #17a2b8; }
        .stat-card.confirmed { border-left-color: #28a745; }
        .stat-card.rejected { border-left-color: #dc3545; }

        .stat-info h3 {
            font-size: 1.8rem;
            margin-bottom: 5px;
            color: #333;
        }

        .stat-info p {
            color: #666;
            margin: 0;
        }

        .filter-section {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }

        .table th {
            background: #8B0000;
            color: #C9A227;
            font-weight: 500;
            border: none;
        }

        .table td {
            vertical-align: middle;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-contacted {
            background: #d4edda;
            color: #155724;
        }

        .status-confirmed {
            background: #cce5ff;
            color: #004085;
        }

        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }

        .action-btns {
            display: flex;
            gap: 5px;
        }

        .btn-view {
            background: #8B0000;
            color: #C9A227;
            border: none;
        }

        .btn-view:hover {
            background: #6D0000;
            color: #C9A227;
        }

        .modal-header {
            background: #8B0000;
            color: #C9A227;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .detail-label {
            font-weight: 600;
            color: #6D0000;
            margin-bottom: 5px;
        }

        .detail-value {
            background: #F8F9FA;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .pagination .page-link {
            color: #8B0000;
        }

        .pagination .active .page-link {
            background: #8B0000;
            border-color: #8B0000;
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
                <a href="admissions.php" class="active"><i class="fas fa-users"></i> Admissions</a>
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
                    <h2>Admissions Management</h2>
                </div>
                <div>
                    <a href="?action=export" class="btn btn-success me-2">
                        <i class="fas fa-file-excel me-2"></i>Export to CSV
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

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card pending">
                    <div class="stat-info">
                        <h3><?php echo $stats['pending'] ?? 0; ?></h3>
                        <p>Pending</p>
                    </div>
                    <i class="fas fa-clock fa-2x" style="color: #ffc107;"></i>
                </div>
                <div class="stat-card contacted">
                    <div class="stat-info">
                        <h3><?php echo $stats['contacted'] ?? 0; ?></h3>
                        <p>Contacted</p>
                    </div>
                    <i class="fas fa-phone-alt fa-2x" style="color: #17a2b8;"></i>
                </div>
                <div class="stat-card confirmed">
                    <div class="stat-info">
                        <h3><?php echo $stats['confirmed'] ?? 0; ?></h3>
                        <p>Confirmed</p>
                    </div>
                    <i class="fas fa-check-circle fa-2x" style="color: #28a745;"></i>
                </div>
                <div class="stat-card rejected">
                    <div class="stat-info">
                        <h3><?php echo $stats['rejected'] ?? 0; ?></h3>
                        <p>Rejected</p>
                    </div>
                    <i class="fas fa-times-circle fa-2x" style="color: #dc3545;"></i>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="filter-section">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search by name, mobile, email..." value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" <?php echo $status_filter == 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="contacted" <?php echo $status_filter == 'contacted' ? 'selected' : ''; ?>>Contacted</option>
                            <option value="confirmed" <?php echo $status_filter == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                            <option value="rejected" <?php echo $status_filter == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="course" class="form-select">
                            <option value="0">All Courses</option>
                            <?php foreach($courses as $course): ?>
                            <option value="<?php echo $course['id']; ?>" <?php echo $course_filter == $course['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($course['name']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Admissions Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Student Name</th>
                            <th>Father Name</th>
                            <th>Contact</th>
                            <th>Course</th>
                            <th>Applied On</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($admissions)): ?>
                        <?php foreach($admissions as $admission): ?>
                        <tr>
                            <td>#<?php echo $admission['id']; ?></td>
                            <td><?php echo htmlspecialchars($admission['student_name']); ?></td>
                            <td><?php echo htmlspecialchars($admission['father_name']); ?></td>
                            <td>
                                <i class="fas fa-phone me-1" style="color: #8B0000;"></i> <?php echo $admission['mobile']; ?><br>
                                <small><?php echo $admission['email']; ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($admission['course_name'] ?? 'N/A'); ?></td>
                            <td><?php echo date('d M Y', strtotime($admission['submitted_at'])); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $admission['status']; ?>">
                                    <?php echo ucfirst($admission['status']); ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-btns">
                                    <button type="button" class="btn btn-sm btn-view" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $admission['id']; ?>">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="?action=delete&id=<?php echo $admission['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this application?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <!-- View Modal -->
                        <div class="modal fade" id="viewModal<?php echo $admission['id']; ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Application Details #<?php echo $admission['id']; ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="detail-label">Student Name</div>
                                                <div class="detail-value"><?php echo htmlspecialchars($admission['student_name']); ?></div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="detail-label">Father's Name</div>
                                                <div class="detail-value"><?php echo htmlspecialchars($admission['father_name']); ?></div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="detail-label">Mobile Number</div>
                                                <div class="detail-value"><?php echo $admission['mobile']; ?></div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="detail-label">Email</div>
                                                <div class="detail-value"><?php echo $admission['email']; ?></div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="detail-label">Course Applied</div>
                                                <div class="detail-value"><?php echo htmlspecialchars($admission['course_name'] ?? 'N/A'); ?></div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="detail-label">Qualification</div>
                                                <div class="detail-value"><?php echo htmlspecialchars($admission['qualification']); ?></div>
                                            </div>
                                            <div class="col-12">
                                                <div class="detail-label">Address</div>
                                                <div class="detail-value"><?php echo nl2br(htmlspecialchars($admission['address'])); ?></div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="detail-label">Applied On</div>
                                                <div class="detail-value"><?php echo date('d M Y h:i A', strtotime($admission['submitted_at'])); ?></div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="detail-label">Current Status</div>
                                                <div class="detail-value">
                                                    <span class="status-badge status-<?php echo $admission['status']; ?>">
                                                        <?php echo ucfirst($admission['status']); ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <hr>

                                        <form method="POST" class="mt-3">
                                            <input type="hidden" name="id" value="<?php echo $admission['id']; ?>">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <label class="form-label">Update Status</label>
                                                    <select name="status" class="form-select">
                                                        <option value="pending" <?php echo $admission['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                        <option value="contacted" <?php echo $admission['status'] == 'contacted' ? 'selected' : ''; ?>>Contacted</option>
                                                        <option value="confirmed" <?php echo $admission['status'] == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                                        <option value="rejected" <?php echo $admission['status'] == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 d-flex align-items-end">
                                                    <button type="submit" name="update_status" class="btn btn-primary w-100">
                                                        <i class="fas fa-save me-2"></i>Update
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-users fa-4x mb-3" style="color: #ccc;"></i>
                                <p class="h5">No admissions found</p>
                                <p class="text-muted">Try adjusting your search filters</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if(isset($total_pages) && $total_pages > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $page-1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo $status_filter; ?>&course=<?php echo $course_filter; ?>">Previous</a>
                    </li>
                    
                    <?php for($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo $status_filter; ?>&course=<?php echo $course_filter; ?>"><?php echo $i; ?></a>
                    </li>
                    <?php endfor; ?>
                    
                    <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $page+1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo $status_filter; ?>&course=<?php echo $course_filter; ?>">Next</a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>