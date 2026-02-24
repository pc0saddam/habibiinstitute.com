<?php
// submit-admission.php
require_once 'includes/config.php';

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Validate inputs
    $student_name = trim($_POST['student_name']);
    $father_name = trim($_POST['father_name']);
    $mobile = trim($_POST['mobile']);
    $email = trim($_POST['email']);
    $course_id = (int)$_POST['course_id'];
    $qualification = trim($_POST['qualification']);
    $address = trim($_POST['address']);
    
    $errors = [];
    
    if(empty($student_name)) {
        $errors[] = 'Student name is required';
    }
    
    if(empty($father_name)) {
        $errors[] = 'Father name is required';
    }
    
    if(empty($mobile) || !preg_match('/^[0-9]{10}$/', $mobile)) {
        $errors[] = 'Valid 10-digit mobile number is required';
    }
    
    if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email is required';
    }
    
    if($course_id <= 0) {
        $errors[] = 'Please select a course';
    }
    
    if(empty($qualification)) {
        $errors[] = 'Qualification is required';
    }
    
    if(empty($address)) {
        $errors[] = 'Address is required';
    }
    
    if(empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO admissions (student_name, father_name, mobile, email, course_id, qualification, address) VALUES (?, ?, ?, ?, ?, ?, ?)");
            
            $stmt->execute([$student_name, $father_name, $mobile, $email, $course_id, $qualification, $address]);
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Application submitted successfully! We will contact you soon.'
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