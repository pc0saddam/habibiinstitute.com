<?php
// submit-contact.php
require_once 'includes/config.php';

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Validate inputs
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message']);
    
    $errors = [];
    
    if(empty($name)) {
        $errors[] = 'Name is required';
    }
    
    if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email is required';
    }
    
    if(!empty($phone) && !preg_match('/^[0-9]{10}$/', $phone)) {
        $errors[] = 'Valid 10-digit phone number is required';
    }
    
    if(empty($message)) {
        $errors[] = 'Message is required';
    }
    
    if(empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
            
            $stmt->execute([$name, $email, $phone, $subject, $message]);
            
            // Send email notification to admin
            $to = $settings['email'];
            $headers = "From: " . $email . "\r\n";
            $headers .= "Reply-To: " . $email . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            
            $email_subject = "New Contact Form Message: " . ($subject ?: 'No Subject');
            $email_body = "
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; }
                    .message-box { background: #f5f5f5; padding: 20px; border-radius: 5px; }
                </style>
            </head>
            <body>
                <h2>New Contact Form Submission</h2>
                <p><strong>Name:</strong> $name</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Phone:</strong> $phone</p>
                <p><strong>Subject:</strong> $subject</p>
                <p><strong>Message:</strong></p>
                <div class='message-box'>$message</div>
            </body>
            </html>
            ";
            
            mail($to, $email_subject, $email_body, $headers);
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Message sent successfully! We will get back to you soon.'
            ]);
        } catch(PDOException $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Database error occurred. Please try again.'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => implode("\n", $errors)
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
}
?>