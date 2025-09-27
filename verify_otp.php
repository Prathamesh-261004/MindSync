<?php
session_start();
include 'db.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if(!isset($_SESSION['email'])){
    header("Location: register.php");
    exit();
}

$email = $_SESSION['email'];

// Handle OTP verification
if(isset($_POST['verify'])){
    $otp_input = trim($_POST['otp']);
    $res = mysqli_query($conn,"SELECT * FROM otp_verification WHERE email='$email' ORDER BY id DESC LIMIT 1");
    if(mysqli_num_rows($res) > 0){
        $row = mysqli_fetch_assoc($res);
        if($row['otp'] === $otp_input && strtotime($row['expires_at']) > time()){
            // Mark student as verified
            mysqli_query($conn,"UPDATE students SET verified=1 WHERE email='$email'");
            // Delete OTP
            mysqli_query($conn,"DELETE FROM otp_verification WHERE email='$email'");
            unset($_SESSION['email']);
            header("Location: login.php");
            exit();
        } else {
            $error = "Invalid or expired OTP!";
        }
    } else {
        $error = "OTP not found. Please request a new one.";
    }
}

// Handle resend OTP
if(isset($_POST['resend'])){
    $otp = rand(100000,999999);
    $expires = date("Y-m-d H:i:s", strtotime('+10 minutes'));
    mysqli_query($conn,"INSERT INTO otp_verification(email, otp, expires_at) VALUES('$email','$otp','$expires')");

    // Fetch student name for email
    $student = mysqli_query($conn,"SELECT name FROM students WHERE email='$email'");
    $student_name = mysqli_fetch_assoc($student)['name'];

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
                    $mail->Username = 'psrane26@gmail.com';
        $mail->Password = 'yqwo ksns fvzk uicu';    // SMTP password/app password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('no-reply@schoolportal.com','School Portal');
        $mail->addAddress($email,$student_name);

        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code - Resend';
        $mail->Body = "Hello <b>$student_name</b>,<br>Your new OTP for registration is <b>$otp</b>.<br>It expires in 10 minutes.";

        $mail->send();
        $msg = "A new OTP has been sent to your email.";
    } catch (Exception $e) {
        $error = "Could not resend OTP. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Verify OTP</title>
<style>
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #f0f8ff, #dbeafe);
    overflow: hidden;
}

/* Container */
.container {
    max-width: 400px;
    margin: 100px auto;
    background: #fff;
    padding: 35px;
    border-radius: 15px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.15);
    position: relative;
    z-index: 10;
    animation: fadeIn 1s ease;
}
.container h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #2563eb;
}

/* Input */
input {
    width: 100%;
    padding: 12px;
    margin: 12px 0;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 15px;
    transition: 0.3s;
}
input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 8px rgba(37,99,235,0.4);
    outline: none;
}

/* Buttons */
button {
    width: 100%;
    padding: 12px;
    background: #2563eb;
    color: #fff;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
    transition: 0.3s;
    margin-top: 8px;
}
button:hover {
    background: #1d4ed8;
    transform: scale(1.05);
    box-shadow: 0 6px 15px rgba(37,99,235,0.4);
}

/* Messages */
.error, .success {
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 10px;
    text-align: center;
    font-weight: 500;
}
.error { background: #fee2e2; color: #b91c1c; }
.success { background: #dcfce7; color: #15803d; }

/* Back Button */
.back-btn {
    position: fixed;
    bottom: 25px;
    right: 25px;
    width: 55px;
    height: 55px;
    background: rgba(37,99,235,0.9);
    color: #fff;
    font-size: 26px;
    text-align: center;
    line-height: 55px;
    border-radius: 50%;
    box-shadow: 0 6px 15px rgba(0,0,0,0.3);
    text-decoration: none;
    transition: 0.3s;
    z-index: 1000;
}
.back-btn:hover {
    background: #1d4ed8;
    transform: translateY(-3px) scale(1.1);
}

/* Floating Particles */
.particle {
    position: absolute;
    bottom: -20px;
    background: rgba(37,99,235,0.7);
    border-radius: 50%;
    animation: rise linear infinite;
}
@keyframes rise {
    from { transform: translateY(0) scale(1); opacity: 1; }
    to { transform: translateY(-120vh) scale(0.3); opacity: 0; }
}
@keyframes fadeIn {
    from {opacity:0; transform: translateY(20px);}
    to {opacity:1; transform: translateY(0);}
}
</style>
</head>
<body>
<!-- Floating Particles -->
<script>
for(let i=0; i<105; i++){
    let p = document.createElement("div");
    p.className = "particle";
    let size = Math.random()*6+4;
    p.style.width = size+"px";
    p.style.height = size+"px";
    p.style.left = Math.random()*100+"vw";
    p.style.animationDuration = (Math.random()*10+10)+"s";
    document.body.appendChild(p);
}
</script>

<div class="container">
    <h2>Verify OTP</h2>
    <?php
    if(isset($error)) echo "<p class='error'>$error</p>";
    if(isset($msg)) echo "<p class='success'>$msg</p>";
    ?>
    <form method="POST">
        <input type="text" name="otp" placeholder="Enter OTP" required>
        <button type="submit" name="verify">Verify OTP</button>
        <button type="submit" name="resend">Resend OTP</button>
    </form>
    <p style="text-align:center;margin-top:15px;">
        <a href="register.php" style="color:#2563eb;text-decoration:none;">← Back to Registration</a>
    </p>
</div>

<a href="javascript:history.back()" class="back-btn" title="Go Back">&#8592;</a>
</body>
</html>
