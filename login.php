<?php
session_start();
include 'db.php';

if(isset($_POST['submit'])){
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $password = $_POST['password'];

    $res = mysqli_query($conn,"SELECT * FROM students WHERE email='$email' AND verified=1");
    if(mysqli_num_rows($res) > 0){
        $row = mysqli_fetch_assoc($res);
        if(password_verify($password,$row['password'])){
            $_SESSION['student_id'] = $row['id'];
            $_SESSION['student_name'] = $row['name'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "Email not verified or not registered!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Student Login</title>
<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #ffffff; /* white background */
    overflow: hidden;
}
/* floating glowing particles */
.particle {
    position: absolute;
    border-radius: 50%;
    background: rgba(0,123,255,0.35); /* soft glowing blue */
    box-shadow: 0 0 8px rgba(0,123,255,0.6);
    animation: floatUp linear infinite;
}
@keyframes floatUp {
    from {transform: translateY(100vh);}
    to {transform: translateY(-10vh);}
}
.container {
    background: #fff;
    padding: 40px 30px;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    width: 350px;
    text-align: center;
    color: #333;
    z-index: 10;
    position: relative;
}
h2 {
    margin-bottom: 20px;
    font-size: 24px;
    letter-spacing: 1px;
    color: #0056b3;
}
input {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border-radius: 8px;
    border: 1px solid #ccc;
    outline: none;
    background: #f9f9f9;
    color: #333;
    font-size: 14px;
    transition: all 0.3s ease;
}
input:focus {
    background: #fff;
    border-color: #007bff;
    transform: scale(1.03);
}
/* Blue login button with pulse animation */
button {
    width: 100%;
    padding: 12px;
    margin-top: 15px;
    border: none;
    border-radius: 8px;
    background: #007bff;
    color: #fff;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 10px rgba(0,123,255,0.4);
    animation: pulse 2s infinite;
}
button:hover {
    background: #0056b3;
    transform: translateY(-2px) scale(1.05);
}
@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(0,123,255,0.6); }
    70% { box-shadow: 0 0 0 12px rgba(0,123,255,0); }
    100% { box-shadow: 0 0 0 0 rgba(0,123,255,0); }
}
.error {
    color: #ff3b3b;
    background: rgba(255,0,0,0.1);
    padding: 8px;
    border-radius: 6px;
    margin-bottom: 15px;
    font-size: 14px;
}
a {
    color: #0056b3;
    text-decoration: none;
    font-size: 14px;
    display: inline-block;
    margin-top: 12px;
    transition: 0.3s;
}
a:hover {
    text-decoration: underline;
}
.back-btn {
    position: fixed;
    bottom: 25px;
    right: 25px;
    width: 55px;
    height: 55px;
    background: rgba(0,123,255,0.9);
    color: #fff;
    font-size: 26px;
    text-align: center;
    line-height: 55px;
    border-radius: 50%;
    box-shadow: 0 6px 15px rgba(0,0,0,0.3);
    text-decoration: none;
    transition: all 0.3s ease;
    z-index: 1000;
}
.back-btn:hover {
    background: #0056b3;
    transform: scale(1.15) rotate(-10deg);
    box-shadow: 0 8px 20px rgba(0,0,0,0.4);
}
</style>
</head>
<body>

<!-- floating particles -->
<script>
for(let i=0; i<105; i++){
    let p = document.createElement("div");
    p.className = "particle";
    let size = Math.random()*10+6; // 6px - 16px
    p.style.width = size+"px";
    p.style.height = size+"px";
    p.style.left = Math.random()*100+"vw";
    p.style.animationDuration = (Math.random()*12+6)+"s";
    p.style.opacity = Math.random()*0.5+0.3;
    document.body.appendChild(p);
}
</script>

<div class="container">
    <h2>Student Login</h2>
    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Enter your Email" required>
        <input type="password" name="password" placeholder="Enter your Password" required>
        <button type="submit" name="submit">Login</button>
    </form>
    <p><a href="register.php">New user? Register here</a></p>
</div>

<a href="javascript:history.back()" class="back-btn" title="Go Back">&#8592;</a>
</body>
</html>
