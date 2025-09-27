<?php
session_start();
include 'db.php';

// Determine sender type
$sender_type = $_POST['sender'] ?? $_GET['sender'] ?? '';

if($sender_type=='student' && isset($_SESSION['student_id'])){
    $sender_id = $_SESSION['student_id'];
    $receiver_id = 1; // assign a default counselor_id for demo
} elseif($sender_type=='counselor' && isset($_SESSION['counselor_id'])){
    $sender_id = $_SESSION['counselor_id'];
    $receiver_id = $_GET['student_id'] ?? 0;
} else { exit; }

// Sending message
if(isset($_POST['message'])){
    $msg = mysqli_real_escape_string($conn,$_POST['message']);
    mysqli_query($conn,"INSERT INTO messages(sender_id,receiver_id,message,timestamp) VALUES('$sender_id','$receiver_id','$msg',NOW())");
}

// Fetch messages
if(isset($_GET['fetch']) && $_GET['fetch']==1){
    $res = mysqli_query($conn,"SELECT * FROM messages WHERE (sender_id='$sender_id' AND receiver_id='$receiver_id') OR (sender_id='$receiver_id' AND receiver_id='$sender_id') ORDER BY timestamp ASC");
    while($row=mysqli_fetch_assoc($res)){
        $side = ($row['sender_id']==$sender_id) ? 'right' : 'left';
        echo "<div style='text-align:$side;margin:5px;'><b>".htmlspecialchars($row['message'])."</b> <small>".$row['timestamp']."</small></div>";
    }
    exit();
}
?>
