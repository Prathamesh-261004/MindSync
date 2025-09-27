<?php
session_start();
include 'db.php';

if(isset($_SESSION['student_id'])){
    $user_id = $_SESSION['student_id'];
    $user_type = 'student';
    $other_id = 1; // counselor ID
    $other_type = 'counselor';
} elseif(isset($_SESSION['counselor_id'])){
    $user_id = $_SESSION['counselor_id'];
    $user_type = 'counselor';
    $other_id = $_GET['student_id'] ?? 0;
    $other_type = 'student';
} else {
    exit;
}

$res = mysqli_query($conn,"SELECT * FROM messages 
WHERE (sender_id='$user_id' AND sender_type='$user_type' AND receiver_id='$other_id' AND receiver_type='$other_type')
   OR (sender_id='$other_id' AND sender_type='$other_type' AND receiver_id='$user_id' AND receiver_type='$user_type')
ORDER BY timestamp ASC");

while($row=mysqli_fetch_assoc($res)){
    $align = ($row['sender_id']==$user_id && $row['sender_type']==$user_type) ? 'right' : 'left';
    echo "<div style='text-align:$align;margin:5px;'>
          <span style='background:".($align=='right'?'#4CAF50':'#ccc').";color:".($align=='right'?'#fff':'#000').";padding:5px 10px;border-radius:10px;display:inline-block;'>".
          htmlspecialchars($row['message'])."</span>
          <br><small>".$row['timestamp']."</small>
          </div>";
}
?>
