<?php
session_start();
include 'db.php';

$message = trim($_POST['message'] ?? '');
if($message=='') exit;

if(isset($_SESSION['student_id'])){
    $sender_id = $_SESSION['student_id'];
    $sender_type = 'student';
    $receiver_id = 1; // default counselor ID or assign dynamically
    $receiver_type = 'counselor';
} elseif(isset($_SESSION['counselor_id'])){
    $sender_id = $_SESSION['counselor_id'];
    $sender_type = 'counselor';
    $receiver_id = $_POST['student_id'] ?? 0;
    $receiver_type = 'student';
} else {
    exit;
}

$msg_safe = mysqli_real_escape_string($conn,$message);

mysqli_query($conn,"INSERT INTO messages(sender_id,sender_type,receiver_id,receiver_type,message,timestamp) 
VALUES('$sender_id','$sender_type','$receiver_id','$receiver_type','$msg_safe',NOW())");

echo "ok";
?>
