<?php
session_start();
include 'db.php';

if(!isset($_SESSION['counselor_id'])){
    header("Location: counselor_login.php");
    exit();
}

$counselor_name = $_SESSION['counselor_name'];

// Fetch all verified students
$sql = "SELECT id, name FROM students WHERE verified=1";
$students = mysqli_query($conn, $sql);
if(!$students){
    die("Database query failed: " . mysqli_error($conn));
}

// Store students in an array to use in multiple places
$student_list = [];
while($row = mysqli_fetch_assoc($students)){
    $student_list[] = $row;
}

$student_id = $_GET['student_id'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Counselor Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --secondary: #7209b7;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --light-gray: #e9ecef;
            --success: #4cc9f0;
            --border-radius: 12px;
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f0f2f5 0%, #e6e9f0 100%);
            min-height: 100vh;
            padding: 20px;
            color: var(--dark);
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 25px;
        }

        /* Sidebar Styles */
        .sidebar {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 25px;
            height: fit-content;
            position: sticky;
            top: 20px;
        }

        .profile-section {
            text-align: center;
            padding-bottom: 20px;
            margin-bottom: 20px;
            border-bottom: 1px solid var(--light-gray);
        }

        .profile-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            color: white;
            font-size: 32px;
        }

        .profile-section h2 {
            color: var(--primary);
            font-size: 1.4rem;
            margin-bottom: 5px;
        }

        .profile-section p {
            color: var(--gray);
            font-size: 0.9rem;
        }

        .student-list {
            list-style: none;
        }

        .student-list h3 {
            color: var(--dark);
            margin-bottom: 15px;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .student-list h3 i {
            color: var(--primary);
        }

        .student-item {
            padding: 12px 15px;
            margin-bottom: 10px;
            background: var(--light);
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: var(--transition);
            border-left: 4px solid transparent;
        }

        .student-item:hover {
            background: #edf2ff;
            border-left-color: var(--primary);
            transform: translateX(5px);
        }

        .student-item.active {
            background: #e3f2fd;
            border-left-color: var(--primary);
        }

        .student-name {
            font-weight: 500;
            color: var(--dark);
        }

        .chat-btn {
            padding: 6px 12px;
            background: var(--primary);
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.85rem;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .chat-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(67, 97, 238, 0.3);
        }

        /* Main Content Styles */
        .main-content {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 30px;
            min-height: 600px;
        }

        .welcome-section {
            margin-bottom: 30px;
        }

        .welcome-section h1 {
            color: var(--primary);
            font-size: 1.8rem;
            margin-bottom: 10px;
        }

        .welcome-section p {
            color: var(--gray);
            line-height: 1.6;
        }

        .chat-section {
            display: <?php echo $student_id ? 'block' : 'none'; ?>;
        }

        .chat-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--light-gray);
        }

        .chat-header .avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--secondary), #b5179e);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }

        .chat-header h3 {
            color: var(--dark);
            font-size: 1.3rem;
        }

        #chat-box {
            height: 350px;
            overflow-y: auto;
            border: 1px solid var(--light-gray);
            padding: 20px;
            margin-bottom: 20px;
            border-radius: var(--border-radius);
            background: #fafbff;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .message {
            max-width: 75%;
            padding: 12px 16px;
            border-radius: 18px;
            line-height: 1.4;
            position: relative;
        }

        .counselor-message {
            align-self: flex-end;
            background: var(--primary);
            color: white;
            border-bottom-right-radius: 5px;
        }

        .student-message {
            align-self: flex-start;
            background: var(--light-gray);
            color: var(--dark);
            border-bottom-left-radius: 5px;
        }

        .message-time {
            font-size: 0.7rem;
            opacity: 0.7;
            margin-top: 5px;
            text-align: right;
        }

        .message-input-container {
            display: flex;
            gap: 10px;
        }

        #message {
            flex: 1;
            padding: 12px 15px;
            border-radius: 30px;
            border: 1px solid var(--light-gray);
            outline: none;
            transition: var(--transition);
        }

        #message:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }

        #send {
            padding: 12px 25px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
        }

        #send:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }

        .no-chat-selected {
            text-align: center;
            padding: 60px 20px;
            color: var(--gray);
        }

        .no-chat-selected i {
            font-size: 48px;
            margin-bottom: 15px;
            color: var(--light-gray);
        }

        .no-chat-selected h3 {
            margin-bottom: 10px;
            color: var(--dark);
        }

        /* Navigation */
        .nav-links {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--light-gray);
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 15px;
            color: var(--dark);
            text-decoration: none;
            border-radius: 8px;
            transition: var(--transition);
            margin-bottom: 8px;
        }

        .nav-link:hover {
            background: var(--light);
            color: var(--primary);
        }

        .nav-link.logout {
            color: #e63946;
        }

        .nav-link.logout:hover {
            background: #ffeaea;
        }

        /* Responsive Design */
        @media (max-width: 968px) {
            .dashboard-container {
                grid-template-columns: 1fr;
            }
            
            .sidebar {
                position: static;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .main-content, .sidebar {
            animation: fadeIn 0.5s ease;
        }

        /* Scrollbar Styling */
        #chat-box::-webkit-scrollbar {
            width: 6px;
        }

        #chat-box::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        #chat-box::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 10px;
        }

        /* Status Indicator */
        .status-indicator {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 8px;
        }

        .status-online {
            background: #4ade80;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="profile-section">
                <div class="profile-icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h2><?php echo $counselor_name; ?></h2>
                <p>Counselor</p>
                <div style="margin-top: 8px;">
                    <span class="status-indicator status-online"></span>
                    <span style="color: var(--gray); font-size: 0.85rem;">Online</span>
                </div>
            </div>

            <ul class="student-list">
                <h3><i class="fas fa-users"></i> Student List</h3>
                <?php
                foreach($student_list as $row){
                    $active_class = ($row['id'] == $student_id) ? 'active' : '';
                    echo "<li class='student-item $active_class'>
                            <span class='student-name'>{$row['name']}</span>
                            <a href='counselor_dashboard.php?student_id={$row['id']}' class='chat-btn'>
                                <i class='fas fa-comment-dots'></i> Chat
                            </a>
                          </li>";
                }
                ?>
            </ul>

            <div class="nav-links">
                <a href="javascript:history.back()" class="nav-link">
                    <i class="fas fa-arrow-left"></i> Go Back
                </a>
                <a href="logout.php" class="nav-link logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="welcome-section">
                <h1>Welcome, <?php echo $counselor_name; ?></h1>
                <p>You can chat with verified students using this dashboard. Select a student from the list to start a conversation.</p>
            </div>

            <?php if($student_id): ?>
            <div class="chat-section">
                <div class="chat-header">
                    <div class="avatar">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h3>
                        <?php
                        foreach($student_list as $s){
                            if($s['id']==$student_id){ 
                                echo $s['name']; 
                                break; 
                            }
                        }
                        ?>
                    </h3>
                </div>

                <div id="chat-box">
                    <!-- Messages will be loaded here -->
                </div>

                <div class="message-input-container">
                    <input type="text" id="message" placeholder="Type your message...">
                    <button id="send"><i class="fas fa-paper-plane"></i> Send</button>
                </div>
            </div>
            <?php else: ?>
            <div class="no-chat-selected">
                <i class="fas fa-comments"></i>
                <h3>No Conversation Selected</h3>
                <p>Please select a student from the list to start chatting</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if($student_id): ?>
    <script>
        // Auto-fetch messages every 2s
        function fetchMessages(){
            fetch('fetch_messages.php?student_id=<?php echo $student_id; ?>')
            .then(res=>res.text())
            .then(data=>{
                document.getElementById('chat-box').innerHTML = data;
                document.getElementById('chat-box').scrollTop = document.getElementById('chat-box').scrollHeight;
            });
        }
        setInterval(fetchMessages,2000);
        fetchMessages();

        document.getElementById('send').addEventListener('click',()=>{
            let msg = document.getElementById('message').value;
            if(msg=='') return;
            let formData = new FormData();
            formData.append('message',msg);
            formData.append('student_id','<?php echo $student_id; ?>');
            fetch('send_message.php',{method:'POST',body:formData}).then(()=>{
                document.getElementById('message').value='';
            });
        });

        // Also send message on Enter key
        document.getElementById('message').addEventListener('keypress', (e) => {
            if(e.key === 'Enter') {
                document.getElementById('send').click();
            }
        });
    </script>
    <?php endif; ?>
</body>
</html>