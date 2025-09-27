<?php
session_start();
include 'db.php';

if(!isset($_SESSION['student_id'])){
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Fetch student name
$result = mysqli_query($conn, "SELECT name FROM students WHERE id='$student_id'");
if($result && mysqli_num_rows($result) > 0){
    $student_name = mysqli_fetch_assoc($result)['name'];
} else {
    $student_name = "Student";
}

// Handle mood submission
if(isset($_POST['mood'])){
    $mood = $_POST['mood'];
    mysqli_query($conn, "INSERT INTO mood_checkins(student_id, mood, date) VALUES('$student_id','$mood',NOW())");
}

// Fetch past moods
$moods_result = mysqli_query($conn, "SELECT mood, date FROM mood_checkins WHERE student_id='$student_id' ORDER BY date DESC");
$moods = [];
if($moods_result){
    while($row = mysqli_fetch_assoc($moods_result)){
        $moods[] = $row;
    }
}

// Get assigned counselor (for simplicity, first counselor)
$counselor_result = mysqli_query($conn, "SELECT id, username FROM counselors LIMIT 1");
$counselor = mysqli_fetch_assoc($counselor_result);
$counselor_id = $counselor['id'];
$counselor_name = $counselor['username'];
?>
<!DOCTYPE html>
<html>
<head>
<title>Student Dashboard</title>
<style>
:root {
    --primary: #4361ee;
    --primary-dark: #3a56d4;
    --secondary: #7209b7;
    --light: #f8f9fa;
    --dark: #212529;
    --gray: #6c757d;
    --success: #4cc9f0;
    --border-radius: 12px;
    --shadow: 0 4px 20px rgba(0,0,0,0.08);
    --transition: all 0.3s ease;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #f0f2ff 0%, #e6f0ff 100%);
    color: var(--dark);
    line-height: 1.6;
    min-height: 100vh;
    padding: 20px;
}

.container {
    max-width: 1100px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-gap: 25px;
}

.card {
    background: #fff;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    padding: 25px;
    transition: var(--transition);
    border: 1px solid rgba(0,0,0,0.05);
}

.card:hover {
    box-shadow: 0 6px 25px rgba(0,0,0,0.12);
    transform: translateY(-3px);
}

.header {
    grid-column: 1 / -1;
    text-align: center;
    padding: 30px 20px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    border-radius: var(--border-radius);
    margin-bottom: 10px;
    box-shadow: var(--shadow);
}

.header h1 {
    font-size: 2.2rem;
    margin-bottom: 5px;
    font-weight: 700;
}

.header p {
    opacity: 0.9;
    font-size: 1.1rem;
}

h2 {
    color: var(--primary);
    margin-bottom: 20px;
    font-size: 1.5rem;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 10px;
}

h3 {
    color: var(--primary-dark);
    margin: 20px 0 15px;
    font-size: 1.2rem;
}

.mood-section {
    grid-column: 1;
}

.chat-section {
    grid-column: 2;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.mood-buttons {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
}

.mood-btn {
    flex: 1;
    padding: 15px 10px;
    border: none;
    border-radius: var(--border-radius);
    font-size: 1.8rem;
    cursor: pointer;
    transition: var(--transition);
    background: #f8f9fa;
    box-shadow: 0 3px 10px rgba(0,0,0,0.08);
}

.mood-btn:hover {
    transform: translateY(-5px) scale(1.05);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.mood-btn.happy { background: linear-gradient(135deg, #4cc9f0, #4361ee); color: white; }
.mood-btn.neutral { background: linear-gradient(135deg, #ffd166, #ff9e00); color: white; }
.mood-btn.sad { background: linear-gradient(135deg, #f15bb5, #7209b7); color: white; }

.past-moods {
    max-height: 200px;
    overflow-y: auto;
    padding-right: 10px;
}

.past-moods ul {
    list-style: none;
}

.past-moods li {
    padding: 12px 15px;
    margin-bottom: 10px;
    background: #f8f9fa;
    border-radius: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-left: 4px solid var(--primary);
}

.mood-date {
    font-size: 0.85rem;
    color: var(--gray);
}

#chat-box {
    flex: 1;
    height: 250px;
    overflow-y: auto;
    border: 1px solid #e0e0e0;
    border-radius: var(--border-radius);
    padding: 15px;
    margin-bottom: 15px;
    background: #fafbff;
    display: flex;
    flex-direction: column;
}

.message {
    margin-bottom: 15px;
    padding: 10px 15px;
    border-radius: 18px;
    max-width: 80%;
    word-wrap: break-word;
}

.message.user {
    align-self: flex-end;
    background: var(--primary);
    color: white;
    border-bottom-right-radius: 5px;
}

.message.counselor {
    align-self: flex-start;
    background: #e9ecef;
    color: var(--dark);
    border-bottom-left-radius: 5px;
}

.chat-input {
    display: flex;
    gap: 10px;
}

#message {
    flex: 1;
    padding: 12px 15px;
    border: 1px solid #e0e0e0;
    border-radius: 30px;
    outline: none;
    transition: var(--transition);
}

#message:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
}

#send {
    padding: 12px 25px;
    background: var(--primary);
    color: white;
    border: none;
    border-radius: 30px;
    cursor: pointer;
    transition: var(--transition);
    font-weight: 600;
}

#send:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
}

.links {
    grid-column: 1 / -1;
    text-align: center;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #e0e0e0;
}

.links a {
    display: inline-block;
    padding: 10px 20px;
    margin: 0 10px;
    background: var(--primary);
    color: white;
    text-decoration: none;
    border-radius: 30px;
    transition: var(--transition);
    font-weight: 500;
}

.links a:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.back-btn {
    position: fixed;
    bottom: 25px;
    right: 25px;
    width: 60px;
    height: 60px;
    background: var(--primary);
    color: white;
    font-size: 24px;
    text-align: center;
    line-height: 60px;
    border-radius: 50%;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    text-decoration: none;
    transition: var(--transition);
    z-index: 1000;
}

.back-btn:hover {
    background: var(--primary-dark);
    transform: translateY(-3px) scale(1.1);
    box-shadow: 0 8px 20px rgba(0,0,0,0.25);
}

.empty-state {
    text-align: center;
    padding: 30px;
    color: var(--gray);
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 15px;
    opacity: 0.5;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .container {
        grid-template-columns: 1fr;
    }
    
    .mood-section, .chat-section {
        grid-column: 1;
    }
    
    .mood-buttons {
        flex-direction: column;
    }
    
    .header h1 {
        font-size: 1.8rem;
    }
}

/* Animation for new messages */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.message {
    animation: fadeIn 0.3s ease;
}

/* Scrollbar styling */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: var(--primary);
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: var(--primary-dark);
}
</style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Welcome back, <?php echo htmlspecialchars($student_name); ?>!</h1>
        <p>How are you feeling today?</p>
    </div>
    
    <div class="card mood-section">
        <h2>Daily Mood Check-in</h2>
        <form method="POST" class="mood-form">
            <div class="mood-buttons">
                <button type="submit" name="mood" value="😊" class="mood-btn happy">😊 Happy</button>
                <button type="submit" name="mood" value="😐" class="mood-btn neutral">😐 Neutral</button>
                <button type="submit" name="mood" value="😔" class="mood-btn sad">😔 Sad</button>
            </div>
        </form>
        
        <h3>Your Mood History</h3>
        <div class="past-moods">
            <?php if(!empty($moods)): ?>
                <ul>
                    <?php foreach($moods as $m): ?>
                        <li>
                            <span class="mood-emoji"><?php echo htmlspecialchars($m['mood']); ?></span>
                            <span class="mood-date"><?php echo htmlspecialchars($m['date']); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <div class="empty-state">
                    <div>📊</div>
                    <p>No moods recorded yet</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="card chat-section">
        <h2>Chat with Counselor</h2>
        <p>You're chatting with: <strong><?php echo htmlspecialchars($counselor_name); ?></strong></p>
        
        <div id="chat-box">
            <div class="empty-state">
                <div>💬</div>
                <p>Start a conversation with your counselor</p>
            </div>
        </div>
        
        <div class="chat-input">
            <input type="text" id="message" placeholder="Type your message here...">
            <button id="send">Send</button>
        </div>
    </div>
    
    <div class="links">
        <a href="resources.php">Help Resources</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<a href="javascript:history.back()" class="back-btn" title="Go Back">←</a>

<script>
// Auto-fetch messages every 2s
function fetchMessages(){
    fetch('fetch_messages.php?counselor_id=<?php echo $counselor_id; ?>')
    .then(res=>res.text())
    .then(data=>{
        if(data.trim() !== '') {
            document.getElementById('chat-box').innerHTML = data;
        }
        document.getElementById('chat-box').scrollTop = document.getElementById('chat-box').scrollHeight;
    });
}
setInterval(fetchMessages,2000);
fetchMessages();

// Send message
document.getElementById('send').addEventListener('click',()=>{
    let msg = document.getElementById('message').value;
    if(msg=='') return;
    let formData = new FormData();
    formData.append('message',msg);
    formData.append('counselor_id','<?php echo $counselor_id; ?>');
    fetch('send_message.php',{method:'POST',body:formData}).then(()=>{
        document.getElementById('message').value='';
    });
});

// Send message on Enter key
document.getElementById('message').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        document.getElementById('send').click();
    }
});
</script>

</body>
</html>