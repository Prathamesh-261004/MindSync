<?php
session_start();
include 'db.php';

// ---------- Visitor Tracking ----------
$ip = $_SERVER['REMOTE_ADDR'];
$today = date('Y-m-d');

// Check if this IP has already visited today
$res = mysqli_query($conn,"SELECT * FROM visitors WHERE ip_address='$ip' AND DATE(visit_time)='$today'");
if(mysqli_num_rows($res) == 0){
    mysqli_query($conn,"INSERT INTO visitors(ip_address) VALUES('$ip')");
}

// ---------- Fetch Stats ----------
$res = mysqli_query($conn,"SELECT COUNT(*) as total_students FROM students");
$total_students = mysqli_fetch_assoc($res)['total_students'];

$res = mysqli_query($conn,"SELECT COUNT(*) as total_chats FROM messages");
$total_chats = mysqli_fetch_assoc($res)['total_chats'];

$res = mysqli_query($conn,"SELECT COUNT(*) as total_visitors FROM visitors");
$total_visitors = mysqli_fetch_assoc($res)['total_visitors'];

// Get today's visitors count
$res = mysqli_query($conn,"SELECT COUNT(*) as today_visitors FROM visitors WHERE DATE(visit_time)='$today'");
$today_visitors = mysqli_fetch_assoc($res)['today_visitors'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MindSync - Wellness & Counseling Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #5b67ff;
            --primary-dark: #4451f2;
            --primary-light: #e0e3ff;
            --secondary: #6cdec0;
            --accent: #ff7d7d;
            --light: #f8f9fa;
            --dark: #212121;
            --gray: #757575;
            --light-gray: #e0e0e0;
            --background: #f5f9fc;
            --card-bg: #ffffff;
            --text-primary: #212121;
            --text-secondary: #757575;
            --gradient: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: var(--background);
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }
        
        /* Subtle Background Texture */
        body:before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(91, 103, 255, 0.03) 0%, transparent 25%),
                radial-gradient(circle at 85% 30%, rgba(108, 222, 192, 0.03) 0%, transparent 25%),
                radial-gradient(circle at 50% 80%, rgba(255, 125, 125, 0.03) 0%, transparent 25%);
            background-size: 100% 100%;
            z-index: -2;
            pointer-events: none;
        }
        
        /* Animated Background Elements */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.4;
            overflow: hidden;
        }
        
        .circle {
            position: absolute;
            border-radius: 50%;
            background: var(--primary-light);
            opacity: 0.3;
            animation: float 15s infinite ease-in-out;
            filter: blur(15px);
        }
        
        .circle:nth-child(1) {
            width: 150px;
            height: 150px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
            background: linear-gradient(45deg, var(--primary-light), var(--secondary));
        }
        
        .circle:nth-child(2) {
            width: 200px;
            height: 200px;
            top: 60%;
            left: 80%;
            animation-delay: -5s;
            background: linear-gradient(45deg, var(--secondary), var(--accent));
        }
        
        .circle:nth-child(3) {
            width: 100px;
            height: 100px;
            top: 70%;
            left: 15%;
            animation-delay: -10s;
            background: linear-gradient(45deg, var(--accent), var(--primary));
        }
        
        .circle:nth-child(4) {
            width: 120px;
            height: 120px;
            top: 20%;
            left: 70%;
            animation-delay: -7s;
            background: linear-gradient(45deg, var(--primary), var(--primary-light));
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0) translateX(0) scale(1) rotate(0deg);
                opacity: 0.2;
            }
            33% {
                transform: translateY(-30px) translateX(30px) scale(1.05) rotate(120deg);
                opacity: 0.3;
            }
            66% {
                transform: translateY(20px) translateX(-40px) scale(0.95) rotate(240deg);
                opacity: 0.25;
            }
        }
        
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        /* Header Styles */
        header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 15px 0;
            animation: slideDown 0.5s ease;
        }
        
        @keyframes slideDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        
        .logo-img {
            width: 50px;
            height: 50px;
            background: var(--gradient);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            position: relative;
            overflow: hidden;
            transition: transform 0.5s ease;
        }
        
        .logo:hover .logo-img {
            transform: rotate(15deg) scale(1.1);
        }
        
        .logo-img:before {
            content: '';
            position: absolute;
            width: 150%;
            height: 150%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.2), transparent);
            transform: translateX(-100%) rotate(45deg);
            animation: shine 3s infinite;
        }
        
        @keyframes shine {
            0% { transform: translateX(-100%) rotate(45deg); }
            100% { transform: translateX(200%) rotate(45deg); }
        }
        
        .logo-img i {
            color: white;
            font-size: 24px;
            z-index: 1;
        }
        
        .logo-text {
            display: flex;
            flex-direction: column;
        }
        
        .logo h1 {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
            font-family: 'Poppins', sans-serif;
            line-height: 1;
        }
        
        .logo span {
            font-size: 12px;
            color: var(--gray);
            letter-spacing: 1.5px;
        }
        
        /* Hero Section */
        .hero {
            padding: 100px 0 70px;
            text-align: center;
            position: relative;
        }
        
        .hero-content {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }
        
        .hero h2 {
            font-size: 3.2rem;
            font-weight: 800;
            margin-bottom: 20px;
            color: var(--dark);
            animation: fadeInUp 1s ease, textShine 5s infinite alternate;
            font-family: 'Poppins', sans-serif;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
        }
        
        @keyframes textShine {
            0% {
                background-position: 0% 50%;
            }
            100% {
                background-position: 100% 50%;
            }
        }
        
        .hero p {
            font-size: 1.2rem;
            color: var(--text-secondary);
            margin-bottom: 30px;
            animation: fadeInUp 1.2s ease;
        }
        
        /* Features Section */
        .features {
            padding: 80px 0;
            position: relative;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 60px;
        }
        
        .section-title h2 {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 15px;
            position: relative;
            display: inline-block;
            font-family: 'Poppins', sans-serif;
        }
        
        .section-title h2:after {
            content: '';
            position: absolute;
            width: 60px;
            height: 3px;
            background: var(--gradient);
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            animation: linePulse 2s infinite;
        }
        
        @keyframes linePulse {
            0%, 100% {
                width: 60px;
            }
            50% {
                width: 100px;
            }
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }
        
        .feature-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            transition: all 0.4s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(91, 103, 255, 0.1);
            transform-style: preserve-3d;
            perspective: 1000px;
        }
        
        .feature-card:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--gradient);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-10px) rotateX(5deg);
            box-shadow: 0 15px 35px rgba(91, 103, 255, 0.15);
        }
        
        .feature-card:hover:before {
            transform: scaleX(1);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            background: rgba(91, 103, 255, 0.1);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            transition: all 0.4s ease;
            position: relative;
        }
        
        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
            background: var(--gradient);
            animation: iconPulse 1.5s infinite;
        }
        
        @keyframes iconPulse {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(91, 103, 255, 0.4);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(91, 103, 255, 0);
            }
        }
        
        .feature-card:hover .feature-icon i {
            color: white;
            animation: iconBounce 0.5s ease;
        }
        
        @keyframes iconBounce {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.2);
            }
        }
        
        .feature-icon i {
            font-size: 32px;
            color: var(--primary);
            transition: all 0.4s ease;
        }
        
        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: var(--dark);
            font-family: 'Poppins', sans-serif;
        }
        
        .feature-card p {
            color: var(--text-secondary);
        }
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            margin: 50px 0;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 16px 32px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            font-family: 'Poppins', sans-serif;
            border: none;
            cursor: pointer;
        }
        
        .btn:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.1));
            transform: translateX(-100%);
            transition: transform 0.4s ease;
        }
        
        .btn:hover:before {
            transform: translateX(0);
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(91, 103, 255, 0.3);
        }
        
        .btn-secondary {
            background: var(--secondary);
            color: white;
        }
        
        .btn-secondary:hover {
            background: #4dccad;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(108, 222, 192, 0.3);
        }
        
        .btn i {
            margin-right: 10px;
            transition: transform 0.3s ease;
        }
        
        .btn:hover i {
            transform: translateX(5px);
        }
        
        /* Stats Section */
        .stats {
            padding: 80px 0;
            background: linear-gradient(135deg, var(--primary-light) 0%, #e8f5e9 100%);
            border-radius: 20px;
            margin: 40px 0;
            position: relative;
            overflow: hidden;
        }
        
        .stats:before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%235b67ff' fill-opacity='0.1' fill-rule='evenodd'%3E%3Ccircle cx='3' cy='3' r='3'/%3E%3Ccircle cx='13' cy='13' r='3'/%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.3;
            animation: backgroundMove 30s linear infinite;
        }
        
        @keyframes backgroundMove {
            0% {
                transform: translate(0, 0);
            }
            100% {
                transform: translate(-50%, -50%);
            }
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            position: relative;
            z-index: 2;
        }
        
        .stat-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.4s ease;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(91, 103, 255, 0.1);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(91, 103, 255, 0.1);
        }
        
        .stat-icon {
            width: 70px;
            height: 70px;
            background: rgba(91, 103, 255, 0.1);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            transition: all 0.4s ease;
        }
        
        .stat-card:hover .stat-icon {
            transform: scale(1.1);
            background: var(--gradient);
        }
        
        .stat-card:hover .stat-icon i {
            color: white;
        }
        
        .stat-icon i {
            font-size: 28px;
            color: var(--primary);
            transition: all 0.4s ease;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 5px;
            font-family: 'Poppins', sans-serif;
        }
        
        .stat-label {
            color: var(--gray);
            font-size: 1.1rem;
        }
        
        /* Testimonials */
        .testimonials {
            padding: 80px 0;
            position: relative;
        }
        
        .testimonial-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .testimonial-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.4s ease;
            position: relative;
            border: 1px solid rgba(91, 103, 255, 0.1);
        }
        
        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(91, 103, 255, 0.1);
        }
        
        .testimonial-text {
            font-style: italic;
            color: var(--text-secondary);
            margin-bottom: 25px;
            position: relative;
            padding: 0 20px;
            line-height: 1.8;
        }
        
        .testimonial-text:before,
        .testimonial-text:after {
            content: '"';
            font-size: 50px;
            color: var(--primary);
            opacity: 0.2;
            position: absolute;
            line-height: 1;
        }
        
        .testimonial-text:before {
            top: -10px;
            left: 0;
        }
        
        .testimonial-text:after {
            bottom: -30px;
            right: 0;
        }
        
        .testimonial-author {
            display: flex;
            align-items: center;
        }
        
        .author-avatar {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            background: var(--gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            margin-right: 15px;
            font-size: 1.5rem;
            transition: transform 0.3s ease;
        }
        
        .testimonial-card:hover .author-avatar {
            transform: rotate(15deg) scale(1.1);
        }
        
        .author-details h4 {
            font-size: 1.2rem;
            margin-bottom: 5px;
            font-family: 'Poppins', sans-serif;
        }
        
        .author-details p {
            color: var(--gray);
            font-size: 0.95rem;
        }
        
        /* Floating animation for elements */
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0% { transform: translate(0, 0px); }
            50% { transform: translate(0, 15px); }
            100% { transform: translate(0, -0px); }
        }
        
        /* Footer */
        footer {
            background: var(--dark);
            color: white;
            padding: 80px 0 40px;
            margin-top: 80px;
            position: relative;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
            position: relative;
            z-index: 2;
        }
        
        .footer-column h3 {
            font-size: 1.4rem;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
            font-family: 'Poppins', sans-serif;
        }
        
        .footer-column h3:after {
            content: '';
            position: absolute;
            width: 40px;
            height: 2px;
            background: var(--primary);
            bottom: 0;
            left: 0;
            animation: linePulse 2s infinite;
        }
        
        .footer-column p {
            margin-bottom: 20px;
            opacity: 0.8;
            line-height: 1.8;
        }
        
        .footer-links {
            list-style: none;
        }
        
        .footer-links li {
            margin-bottom: 15px;
        }
        
        .footer-links a {
            color: white;
            opacity: 0.8;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }
        
        .footer-links a i {
            margin-right: 10px;
            font-size: 14px;
            transition: transform 0.3s ease;
        }
        
        .footer-links a:hover {
            opacity: 1;
            color: var(--primary-light);
            transform: translateX(5px);
        }
        
        .footer-links a:hover i {
            transform: translateX(3px);
        }
        
        .copyright {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            opacity: 0.7;
            font-size: 0.9rem;
            position: relative;
            z-index: 2;
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }
        
        .animate.animated {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Particle animation */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }
        
        .particle {
            position: absolute;
            background: var(--primary);
            border-radius: 50%;
            opacity: 0.3;
            animation: floatParticle 15s infinite linear;
        }
        
        @keyframes floatParticle {
            0% {
                transform: translateY(0) translateX(0) rotate(0deg);
                opacity: 0.3;
            }
            100% {
                transform: translateY(-100vh) translateX(100vw) rotate(360deg);
                opacity: 0;
            }
        }
        
        /* Responsive Design */
        @media (max-width: 992px) {
            .hero h2 {
                font-size: 2.5rem;
            }
            
            .section-title h2 {
                font-size: 2.2rem;
            }
        }
        
        @media (max-width: 768px) {
            .hero {
                padding: 80px 0 50px;
            }
            
            .hero h2 {
                font-size: 2.2rem;
            }
            
            .hero p {
                font-size: 1.1rem;
            }
            
            .action-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                max-width: 300px;
            }
            
            .features, .testimonials, .stats {
                padding: 60px 0;
            }
        }
        
        @media (max-width: 576px) {
            .header-content {
                flex-direction: column;
                text-align: center;
            }
            
            .logo {
                margin-bottom: 15px;
                justify-content: center;
            }
            
            .hero h2 {
                font-size: 1.8rem;
            }
            
            .section-title h2 {
                font-size: 1.8rem;
            }
            
            .feature-card, .stat-card, .testimonial-card {
                padding: 20px;
            }
            
            .stat-number {
                font-size: 2rem;
            }
            
            footer {
                padding: 60px 0 30px;
            }
        }
    </style>
</head>
<body>
    <!-- Animated Background Elements -->
    <div class="bg-animation">
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
    </div>
    
    <!-- Particle Animation -->
    <div class="particles" id="particles"></div>

    <header>
        <div class="container">
            <div class="header-content">
                <a href="#" class="logo">
                    <div class="logo-img">
                        <i class="fas fa-brain"></i>
                    </div>
                    <div class="logo-text">
                        <h1>MindSync</h1>
                        <span>WELLNESS PORTAL</span>
                    </div>
                </a>
            </div>
        </div>
    </header>

    <main class="container">
        <section class="hero">
            <div class="hero-content">
                <h2>Sync Your Mind, Find Your Balance</h2>
                <p>Connect with professional counselors confidentially, track your emotional wellbeing, and discover resources to support your mental health journey.</p>
                
                <div class="action-buttons">
                    <a href="register.php" class="btn btn-primary"><i class="fas fa-user-plus"></i> Student Register</a>
                    <a href="login.php" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i> Student Login</a>
                    <a href="counselor_login.php" class="btn btn-secondary"><i class="fas fa-headset"></i> Counselor Login</a>
                </div>
            </div>
        </section>

        <section class="features">
            <div class="section-title">
                <h2>How We Can Help</h2>
            </div>
            
            <div class="features-grid">
                <div class="feature-card animate">
                    <div class="feature-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3>Confidential Chat</h3>
                    <p>Chat privately with certified counselors without revealing your identity.</p>
                </div>
                
                <div class="feature-card animate">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Mood Tracking</h3>
                    <p>Monitor your emotional wellbeing with our easy-to-use mood tracking tools.</p>
                </div>
                
                <div class="feature-card animate">
                    <div class="feature-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <h3>Resources</h3>
                    <p>Access a library of articles and resources to support your mental health journey.</p>
                </div>
                
                <div class="feature-card animate">
                    <div class="feature-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h3>Scheduling</h3>
                    <p>Book appointments with counselors at times that work for your schedule.</p>
                </div>
            </div>
        </section>

        <section class="stats">
            <div class="section-title">
                <h2>Our Impact</h2>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card animate">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number"><?php echo $total_students; ?></div>
                    <div class="stat-label">Registered Students</div>
                </div>
                
                <div class="stat-card animate">
                    <div class="stat-icon">
                        <i class="fas fa-comment-dots"></i>
                    </div>
                    <div class="stat-number"><?php echo $total_chats; ?></div>
                    <div class="stat-label">Messages Sent</div>
                </div>
                
                <div class="stat-card animate">
                    <div class="stat-icon">
                        <i class="fas fa-globe"></i>
                    </div>
                    <div class="stat-number"><?php echo $total_visitors; ?></div>
                    <div class="stat-label">Total Visitors</div>
                </div>
                
                <div class="stat-card animate">
                    <div class="stat-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="stat-number"><?php echo $today_visitors; ?></div>
                    <div class="stat-label">Today's Visitors</div>
                </div>
            </div>
        </section>

        <section class="testimonials">
            <div class="section-title">
                <h2>What Students Say</h2>
            </div>
            
            <div class="testimonial-grid">
                <div class="testimonial-card animate">
                    <div class="testimonial-text">
                        The anonymous chatting feature helped me open up about issues I wasn't comfortable discussing face-to-face. Thank you for this service.
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">A</div>
                        <div class="author-details">
                            <h4>Anonymous Student</h4>
                            <p>Psychology Major</p>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card animate">
                    <div class="testimonial-text">
                        The mood tracking feature has helped me recognize patterns in my emotional health and take proactive steps.
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">M</div>
                        <div class="author-details">
                            <h4>Michael</h4>
                            <p>Engineering Student</p>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card animate">
                    <div class="testimonial-text">
                        As an international student, having access to counseling in my own language through this portal has been invaluable.
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">S</div>
                        <div class="author-details">
                            <h4>Sophia</h4>
                            <p>International Student</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>MindSync Wellness</h3>
                    <p>Providing accessible mental health support and counseling services to students in a safe, confidential environment.</p>
                </div>
                
                <div class="footer-column">
                    <h3>Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="register.php"><i class="fas fa-arrow-right"></i> Student Registration</a></li>
                        <li><a href="login.php"><i class="fas fa-arrow-right"></i> Student Login</a></li>
                        <li><a href="counselor_login.php"><i class="fas fa-arrow-right"></i> Counselor Login</a></li>
                        <li><a href="#"><i class="fas fa-arrow-right"></i> Privacy Policy</a></li>
                        <li><a href="#"><i class="fas fa-arrow-right"></i> Terms of Service</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3>Contact Us</h3>
                    <ul class="footer-links">
                        <li><a href="mailto:support@mindsync.edu"><i class="fas fa-envelope"></i> support@mindsync.edu</a></li>
                        <li><a href="tel:5551234357"><i class="fas fa-phone"></i> (555) 123-HELP</a></li>
                        <li><a href="#"><i class="fas fa-map-marker-alt"></i> Student Services Building, Room 305</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="copyright">
                <p>&copy; 2023 MindSync Wellness Portal. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Animation on scroll
        document.addEventListener('DOMContentLoaded', function() {
            const animatedElements = document.querySelectorAll('.animate');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.classList.add('animated');
                        }, 100);
                    }
                });
            }, {
                threshold: 0.1
            });
            
            animatedElements.forEach(element => {
                observer.observe(element);
            });
            
            // Create particles
            createParticles();
            
            // Add subtle hover effects to all cards
            const cards = document.querySelectorAll('.feature-card, .stat-card, .testimonial-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = this.style.transform + ' scale(1.02)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = this.style.transform.replace(' scale(1.02)', '');
                });
            });
        });
        
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const numberOfParticles = 30;
            
            for (let i = 0; i < numberOfParticles; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                
                // Random size between 5 and 15px
                const size = Math.random() * 10 + 5;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                
                // Random position
                particle.style.left = `${Math.random() * 100}%`;
                particle.style.top = `${Math.random() * 100}%`;
                
                // Random animation duration between 10 and 30s
                const duration = Math.random() * 20 + 10;
                particle.style.animationDuration = `${duration}s`;
                
                // Random delay
                particle.style.animationDelay = `${Math.random() * 5}s`;
                
                // Random color from our palette
                const colors = ['#5b67ff', '#6cdec0', '#ff7d7d', '#e0e3ff'];
                const randomColor = colors[Math.floor(Math.random() * colors.length)];
                particle.style.background = randomColor;
                
                particlesContainer.appendChild(particle);
            }
        }
    </script>
</body>
</html>