<?php
session_start();

// Prefixed credentials
$prefix_username = "counselor1";
$prefix_password = "pass123"; // Plain password

if(isset($_POST['submit'])){
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if($username === $prefix_username && $password === $prefix_password){
        $_SESSION['counselor_id'] = 1; // assign a fixed ID
        $_SESSION['counselor_name'] = $prefix_username;
        header("Location: counselor_dashboard.php");
        exit();
    } else {
        $error = "Invalid counselor username or password!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Counselor Login</title>
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

/* Inputs */
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

/* Button */
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

/* Error */
.error {
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 10px;
    text-align: center;
    font-weight: 500;
    background: #fee2e2;
    color: #b91c1c;
}

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
    <h2>Counselor Login</h2>
    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="submit">Login</button>
    </form>
</div>

<a href="javascript:history.back()" class="back-btn" title="Go Back">&#8592;</a>
</body>
</html>
