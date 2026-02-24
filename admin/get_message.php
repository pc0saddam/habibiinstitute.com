<?php
// admin/get_message.php
require_once '../includes/config.php';
require_once 'auth.php';

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if($id > 0) {
    // Fetch message
    $stmt = $pdo->prepare("SELECT * FROM contact_messages WHERE id = ?");
    $stmt->execute([$id]);
    $message = $stmt->fetch();
    
    if($message) {
        // Mark as read
        $stmt = $pdo->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?");
        $stmt->execute([$id]);
        ?>
        <div class="message-detail">
            <div class="row">
                <div class="col-md-6">
                    <div class="detail-label">Name</div>
                    <div class="detail-value"><?php echo htmlspecialchars($message['name']); ?></div>
                </div>
                <div class="col-md-6">
                    <div class="detail-label">Email</div>
                    <div class="detail-value">
                        <a href="mailto:<?php echo $message['email']; ?>"><?php echo $message['email']; ?></a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-label">Phone</div>
                    <div class="detail-value">
                        <?php if($message['phone']): ?>
                        <a href="tel:<?php echo $message['phone']; ?>"><?php echo $message['phone']; ?></a>
                        <?php else: ?>
                        Not provided
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-label">Received On</div>
                    <div class="detail-value"><?php echo date('d M Y, h:i A', strtotime($message['created_at'])); ?></div>
                </div>
                <div class="col-12">
                    <div class="detail-label">Subject</div>
                    <div class="detail-value"><?php echo htmlspecialchars($message['subject'] ?: '(No Subject)'); ?></div>
                </div>
                <div class="col-12">
                    <div class="detail-label">Message</div>
                    <div class="detail-value" style="background: white; padding: 15px; border-radius: 5px;">
                        <?php echo nl2br(htmlspecialchars($message['message'])); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="reply-section">
            <h5 style="color: #6D0000; margin-bottom: 20px;">Send Reply</h5>
            <form method="POST" action="messages.php" id="replyForm">
                <input type="hidden" name="id" value="<?php echo $message['id']; ?>">
                <div class="mb-3">
                    <label class="form-label">To: <?php echo $message['email']; ?></label>
                </div>
                <div class="mb-3">
                    <input type="text" name="reply_subject" class="form-control" placeholder="Subject" value="Re: <?php echo $message['subject']; ?>" required>
                </div>
                <div class="mb-3">
                    <textarea name="reply_message" class="form-control" rows="5" placeholder="Type your reply here..." required></textarea>
                </div>
                <div class="text-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="send_reply" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i>Send Reply
                    </button>
                </div>
            </form>
        </div>
        <?php
    } else {
        echo '<div class="alert alert-danger">Message not found.</div>';
    }
} else {
    echo '<div class="alert alert-danger">Invalid request.</div>';
}
?>