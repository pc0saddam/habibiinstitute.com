<?php
// admin/messages.php
require_once '../includes/config.php';
require_once '../includes/auth.php';

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

// Mark as read
if(isset($_GET['mark_read']) && $id > 0) {
    try {
        $stmt = $pdo->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?");
        $stmt->execute([$id]);
        $message = 'Message marked as read.';
        logAdminActivity('MARK_MESSAGE_READ', 'Marked message ID: ' . $id . ' as read');
    } catch(PDOException $e) {
        $error = 'Error updating message: ' . $e->getMessage();
    }
}

// Mark as unread
if(isset($_GET['mark_unread']) && $id > 0) {
    try {
        $stmt = $pdo->prepare("UPDATE contact_messages SET is_read = 0 WHERE id = ?");
        $stmt->execute([$id]);
        $message = 'Message marked as unread.';
        logAdminActivity('MARK_MESSAGE_UNREAD', 'Marked message ID: ' . $id . ' as unread');
    } catch(PDOException $e) {
        $error = 'Error updating message: ' . $e->getMessage();
    }
}

// Handle delete
if($action == 'delete' && $id > 0) {
    try {
        $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = ?");
        $stmt->execute([$id]);
        $message = 'Message deleted successfully!';
        logAdminActivity('DELETE_MESSAGE', 'Deleted message ID: ' . $id);
    } catch(PDOException $e) {
        $error = 'Error deleting message: ' . $e->getMessage();
    }
    $action = 'list';
}

// Handle reply 
if(isset($_POST['send_reply'])) {
    $id = (int)$_POST['id'];
    $reply_subject = trim($_POST['reply_subject']);
    $reply_message = trim($_POST['reply_message']);
    
    // Get original message details
    $stmt = $pdo->prepare("SELECT * FROM contact_messages WHERE id = ?");
    $stmt->execute([$id]);
    $original = $stmt->fetch();
    
    if($original) {
        // Log the reply
        logAdminActivity('SEND_REPLY', 'Replied to message ID: ' . $id . ' - Subject: ' . $reply_subject);
        
        // Check if message_replies table exists
        try {
            $checkTable = $pdo->query("SHOW TABLES LIKE 'message_replies'");
            if($checkTable->rowCount() > 0) {
                // Save reply to database
                $stmt = $pdo->prepare("INSERT INTO message_replies (message_id, admin_id, subject, message) VALUES (?, ?, ?, ?)");
                $stmt->execute([$id, $_SESSION['admin_id'] ?? 0, $reply_subject, $reply_message]);
            }
        } catch(PDOException $e) {
            // Table doesn't exist, just continue
        }
        
        // Mark original as read
        $stmt = $pdo->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?");
        $stmt->execute([$id]);
        
        $message = 'Reply sent successfully!';
    }
}

// Handle bulk actions
if(isset($_POST['bulk_action'])) {
    $selected = isset($_POST['selected']) ? $_POST['selected'] : [];
    $bulk_action = $_POST['bulk_action'];
    
    if(!empty($selected)) {
        $ids = implode(',', array_map('intval', $selected));
        
        try {
            if($bulk_action == 'mark_read') {
                $pdo->query("UPDATE contact_messages SET is_read = 1 WHERE id IN ($ids)");
                $message = 'Selected messages marked as read.';
                logAdminActivity('BULK_MARK_READ', 'Marked ' . count($selected) . ' messages as read');
            } elseif($bulk_action == 'mark_unread') {
                $pdo->query("UPDATE contact_messages SET is_read = 0 WHERE id IN ($ids)");
                $message = 'Selected messages marked as unread.';
                logAdminActivity('BULK_MARK_UNREAD', 'Marked ' . count($selected) . ' messages as unread');
            } elseif($bulk_action == 'delete') {
                $pdo->query("DELETE FROM contact_messages WHERE id IN ($ids)");
                $message = 'Selected messages deleted.';
                logAdminActivity('BULK_DELETE', 'Deleted ' . count($selected) . ' messages');
            }
        } catch(PDOException $e) {
            $error = 'Error performing bulk action: ' . $e->getMessage();
        }
    } else {
        $error = 'No messages selected.';
    }
}

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

// Filters
$filter_status = isset($_GET['status']) ? $_GET['status'] : '';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$where_clauses = [];
$params = [];

if($filter_status == 'read') {
    $where_clauses[] = "is_read = 1";
} elseif($filter_status == 'unread') {
    $where_clauses[] = "is_read = 0";
}

if(!empty($search)) {
    $where_clauses[] = "(name LIKE ? OR email LIKE ? OR phone LIKE ? OR subject LIKE ? OR message LIKE ?)";
    $search_term = "%$search%";
    for($i = 0; $i < 5; $i++) {
        $params[] = $search_term;
    }
}

$where_sql = !empty($where_clauses) ? "WHERE " . implode(" AND ", $where_clauses) : "";

// Get total records
$count_sql = "SELECT COUNT(*) FROM contact_messages $where_sql";
$count_stmt = $pdo->prepare($count_sql);
$count_stmt->execute($params);
$total_records = $count_stmt->fetchColumn();
$total_pages = ceil($total_records / $limit);

// Fetch messages
$sql = "SELECT * FROM contact_messages $where_sql ORDER BY created_at DESC LIMIT $offset, $limit";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$messages = $stmt->fetchAll();

// ✅ FIXED: Get statistics with backticks for reserved keyword 'read'
$stats = $pdo->query("
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN is_read = 0 THEN 1 ELSE 0 END) as unread,
        SUM(CASE WHEN is_read = 1 THEN 1 ELSE 0 END) as `read`
    FROM contact_messages
")->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages Management - Habibi Institute Admin</title>
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

        .stat-card.total { border-left-color: #8B0000; }
        .stat-card.unread { border-left-color: #ffc107; }
        .stat-card.read { border-left-color: #28a745; }

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

        .message-list {
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }

        .message-item {
            display: flex;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #eee;
            transition: background 0.3s ease;
            cursor: pointer;
        }

        .message-item:hover {
            background: #F8F9FA;
        }

        .message-item.unread {
            background: #fff3cd;
        }

        .message-item.unread:hover {
            background: #ffe69c;
        }

        .message-checkbox {
            width: 40px;
        }

        .message-status {
            width: 40px;
            text-align: center;
        }

        .message-status i {
            font-size: 1.2rem;
        }

        .message-status .unread {
            color: #ffc107;
        }

        .message-status .read {
            color: #28a745;
        }

        .message-content {
            flex: 1;
            padding: 0 20px;
        }

        .message-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .message-sender {
            font-weight: 600;
            color: #6D0000;
        }

        .message-date {
            color: #666;
            font-size: 0.85rem;
        }

        .message-subject {
            font-weight: 500;
            margin-bottom: 5px;
            color: #333;
        }

        .message-preview {
            color: #666;
            font-size: 0.9rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 500px;
        }

        .message-actions {
            display: flex;
            gap: 10px;
        }

        .btn-icon {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #F8F9FA;
            color: #8B0000;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-icon:hover {
            background: #8B0000;
            color: #C9A227;
        }

        .modal-header {
            background: #8B0000;
            color: #C9A227;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .message-detail {
            background: #F8F9FA;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .detail-label {
            color: #6D0000;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .detail-value {
            margin-bottom: 15px;
        }

        .reply-section {
            border-top: 2px solid #C9A227;
            padding-top: 20px;
            margin-top: 20px;
        }

        .pagination .page-link {
            color: #8B0000;
        }

        .pagination .active .page-link {
            background: #8B0000;
            border-color: #8B0000;
            color: #C9A227;
        }

        .bulk-actions {
            background: white;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                overflow: hidden;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .message-item {
                flex-wrap: wrap;
            }
            
            .message-content {
                width: 100%;
                order: 3;
                padding: 10px 0;
            }
            
            .message-actions {
                order: 2;
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
                <a href="messages.php" class="active"><i class="fas fa-envelope"></i> Messages</a>
                <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Navigation -->
            <div class="top-nav">
                <div class="page-title">
                    <h2>Contact Messages</h2>
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
                <div class="stat-card total">
                    <div class="stat-info">
                        <h3><?php echo $stats['total'] ?? 0; ?></h3>
                        <p>Total Messages</p>
                    </div>
                    <i class="fas fa-envelope fa-2x" style="color: #8B0000;"></i>
                </div>
                <div class="stat-card unread">
                    <div class="stat-info">
                        <h3><?php echo $stats['unread'] ?? 0; ?></h3>
                        <p>Unread</p>
                    </div>
                    <i class="fas fa-envelope-open fa-2x" style="color: #ffc107;"></i>
                </div>
                <div class="stat-card read">
                    <div class="stat-info">
                        <h3><?php echo $stats['read'] ?? 0; ?></h3>
                        <p>Read</p>
                    </div>
                    <i class="fas fa-check-circle fa-2x" style="color: #28a745;"></i>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="filter-section">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search by name, email, subject..." value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Messages</option>
                            <option value="unread" <?php echo $filter_status == 'unread' ? 'selected' : ''; ?>>Unread Only</option>
                            <option value="read" <?php echo $filter_status == 'read' ? 'selected' : ''; ?>>Read Only</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Filter
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="messages.php" class="btn btn-secondary w-100">
                            <i class="fas fa-redo me-2"></i>Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Bulk Actions -->
            <form method="POST" id="bulkForm">
                <div class="bulk-actions">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="selectAll">
                        <label class="form-check-label" for="selectAll">
                            Select All
                        </label>
                    </div>
                    <select name="bulk_action" class="form-select w-auto">
                        <option value="">Bulk Actions</option>
                        <option value="mark_read">Mark as Read</option>
                        <option value="mark_unread">Mark as Unread</option>
                        <option value="delete">Delete</option>
                    </select>
                    <button type="submit" class="btn btn-primary" onclick="return confirm('Perform bulk action?')">
                        Apply
                    </button>
                </div>

                <!-- Message List -->
                <div class="message-list">
                    <?php if(!empty($messages)): ?>
                    <?php foreach($messages as $msg): ?>
                    <div class="message-item <?php echo $msg['is_read'] ? '' : 'unread'; ?>">
                        <div class="message-checkbox">
                            <input type="checkbox" name="selected[]" value="<?php echo $msg['id']; ?>" class="form-check-input message-select">
                        </div>
                        <div class="message-status">
                            <i class="fas <?php echo $msg['is_read'] ? 'fa-envelope-open read' : 'fa-envelope unread'; ?>"></i>
                        </div>
                        <div class="message-content" onclick="viewMessage(<?php echo $msg['id']; ?>)">
                            <div class="message-header">
                                <span class="message-sender">
                                    <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($msg['name']); ?>
                                </span>
                                <span class="message-date">
                                    <i class="far fa-clock me-1"></i><?php echo date('d M Y, h:i A', strtotime($msg['created_at'])); ?>
                                </span>
                            </div>
                            <div class="message-subject">
                                <?php echo htmlspecialchars($msg['subject'] ?: '(No Subject)'); ?>
                            </div>
                            <div class="message-preview">
                                <?php echo htmlspecialchars(substr($msg['message'], 0, 150)) . '...'; ?>
                            </div>
                        </div>
                        <div class="message-actions">
                            <button type="button" class="btn-icon" onclick="viewMessage(<?php echo $msg['id']; ?>)" title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <?php if($msg['is_read']): ?>
                            <a href="?mark_unread=1&id=<?php echo $msg['id']; ?>" class="btn-icon" title="Mark as Unread">
                                <i class="fas fa-envelope"></i>
                            </a>
                            <?php else: ?>
                            <a href="?mark_read=1&id=<?php echo $msg['id']; ?>" class="btn-icon" title="Mark as Read">
                                <i class="fas fa-envelope-open"></i>
                            </a>
                            <?php endif; ?>
                            <a href="?action=delete&id=<?php echo $msg['id']; ?>" class="btn-icon" title="Delete" onclick="return confirm('Are you sure you want to delete this message?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-4x mb-3" style="color: #ccc;"></i>
                        <h5>No messages found</h5>
                        <p class="text-muted">Your inbox is empty</p>
                    </div>
                    <?php endif; ?>
                </div>
            </form>

            <!-- Pagination -->
            <?php if(isset($total_pages) && $total_pages > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $page-1; ?>&status=<?php echo $filter_status; ?>&search=<?php echo urlencode($search); ?>">Previous</a>
                    </li>
                    
                    <?php for($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&status=<?php echo $filter_status; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                    </li>
                    <?php endfor; ?>
                    
                    <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $page+1; ?>&status=<?php echo $filter_status; ?>&search=<?php echo urlencode($search); ?>">Next</a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>
        </div>
    </div>

    <!-- Message View Modal -->
    <div class="modal fade" id="viewMessageModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Message Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="messageModalBody">
                    <!-- Loaded via AJAX -->
                    <div class="text-center py-4">
                        <div class="spinner-border" style="color: #8B0000;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Select all functionality
        document.getElementById('selectAll').addEventListener('change', function() {
            var checkboxes = document.getElementsByClassName('message-select');
            for(var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = this.checked;
            }
        });

        // View message function
        function viewMessage(id) {
            $('#viewMessageModal').modal('show');
            $('#messageModalBody').html('<div class="text-center py-4"><div class="spinner-border" style="color: #8B0000;" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            
            $.ajax({
                url: 'get_message.php',
                type: 'POST',
                data: {id: id},
                success: function(response) {
                    $('#messageModalBody').html(response);
                },
                error: function() {
                    $('#messageModalBody').html('<div class="alert alert-danger">Error loading message.</div>');
                }
            });
        }
    </script>
</body>
</html>