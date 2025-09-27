<!DOCTYPE html>
<html>
<head>
<title>Mental Health Resources</title>
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    background: #ffffff;
    min-height: 100vh;
    overflow-x: hidden;
    position: relative;
}

/* Animated Background Elements */
.bg-decoration {
    position: fixed;
    pointer-events: none;
    z-index: 1;
}

.floating-shape {
    position: absolute;
    border-radius: 50%;
    background: linear-gradient(45deg, #f0f9ff, #e0f2fe);
    opacity: 0.6;
    animation: floatAround 20s ease-in-out infinite;
}

.shape-1 {
    width: 200px;
    height: 200px;
    top: 10%;
    left: 5%;
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    animation-delay: 0s;
}

.shape-2 {
    width: 150px;
    height: 150px;
    top: 60%;
    right: 10%;
    background: linear-gradient(135deg, #f0fdf4, #dcfce7);
    animation-delay: -5s;
}

.shape-3 {
    width: 120px;
    height: 120px;
    bottom: 20%;
    left: 15%;
    background: linear-gradient(135deg, #fef7ff, #fae8ff);
    animation-delay: -10s;
}

.shape-4 {
    width: 180px;
    height: 180px;
    top: 30%;
    right: 30%;
    background: linear-gradient(135deg, #fff7ed, #fed7aa);
    animation-delay: -15s;
}

@keyframes floatAround {
    0%, 100% { 
        transform: translateX(0) translateY(0) scale(1);
        border-radius: 50%;
    }
    25% { 
        transform: translateX(30px) translateY(-40px) scale(1.1);
        border-radius: 60% 40% 60% 40%;
    }
    50% { 
        transform: translateX(-20px) translateY(-80px) scale(0.9);
        border-radius: 40% 60% 40% 60%;
    }
    75% { 
        transform: translateX(-40px) translateY(-20px) scale(1.05);
        border-radius: 55% 45% 55% 45%;
    }
}

/* Geometric Lines */
.geometric-line {
    position: fixed;
    height: 2px;
    background: linear-gradient(90deg, transparent, #e5e7eb, transparent);
    animation: slideAcross 15s linear infinite;
    pointer-events: none;
    z-index: 1;
}

.line-1 {
    top: 20%;
    width: 300px;
    animation-delay: 0s;
}

.line-2 {
    top: 70%;
    width: 200px;
    animation-delay: -7s;
}

@keyframes slideAcross {
    0% { transform: translateX(-100vw); opacity: 0; }
    50% { opacity: 1; }
    100% { transform: translateX(100vw); opacity: 0; }
}

/* Container */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
    position: relative;
    z-index: 10;
}

/* Header */
.header {
    text-align: center;
    margin-bottom: 4rem;
    position: relative;
}

.header-content {
    animation: fadeInScale 1.2s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.header h1 {
    font-size: 3.8rem;
    font-weight: 800;
    background: linear-gradient(135deg, #1e40af, #3b82f6, #06b6d4);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 1rem;
    letter-spacing: -0.02em;
    position: relative;
}

.header h1::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 4px;
    background: linear-gradient(90deg, #3b82f6, #06b6d4);
    border-radius: 2px;
    animation: expandLine 1s ease-out 0.5s both;
}

.header p {
    font-size: 1.3rem;
    color: #64748b;
    font-weight: 400;
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

/* Subtitle Animation */
.subtitle {
    opacity: 0;
    animation: slideUp 0.8s ease-out 0.3s both;
}

/* Cards Grid */
.cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2.5rem;
    margin-bottom: 3rem;
}

/* Card Styles */
.card {
    background: #ffffff;
    border-radius: 24px;
    padding: 2.5rem;
    position: relative;
    overflow: hidden;
    box-shadow: 
        0 4px 6px -1px rgba(0, 0, 0, 0.1),
        0 2px 4px -1px rgba(0, 0, 0, 0.06);
    border: 1px solid #f1f5f9;
    transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
    animation: cardSlideIn 0.8s ease-out both;
    transform-origin: center bottom;
}

.card:nth-child(1) { animation-delay: 0.1s; }
.card:nth-child(2) { animation-delay: 0.2s; }
.card:nth-child(3) { animation-delay: 0.3s; }
.card:nth-child(4) { animation-delay: 0.4s; }

.card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    transition: left 0.6s ease-in-out;
}

.card:hover {
    transform: translateY(-15px) rotateX(5deg);
    box-shadow: 
        0 25px 50px -12px rgba(0, 0, 0, 0.25),
        0 0 0 1px rgba(59, 130, 246, 0.1);
}

.card:hover::before {
    left: 100%;
}

/* Card Background Patterns */
.card::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, transparent 40%, rgba(59, 130, 246, 0.05) 41%, rgba(59, 130, 246, 0.05) 42%, transparent 43%);
    animation: rotate 20s linear infinite;
    pointer-events: none;
}

/* Card Icons */
.card-icon {
    width: 80px;
    height: 80px;
    margin-bottom: 2rem;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.2rem;
    position: relative;
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.card:hover .card-icon {
    transform: scale(1.15) rotate(10deg);
    animation: bounceIcon 0.6s ease-out;
}

.mindfulness .card-icon {
    background: linear-gradient(135deg, #dbeafe, #93c5fd);
    box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
}

.therapy .card-icon {
    background: linear-gradient(135deg, #fce7f3, #f9a8d4);
    box-shadow: 0 10px 20px rgba(236, 72, 153, 0.3);
}

.articles .card-icon {
    background: linear-gradient(135deg, #ecfdf5, #86efac);
    box-shadow: 0 10px 20px rgba(34, 197, 94, 0.3);
}

.helplines .card-icon {
    background: linear-gradient(135deg, #fff7ed, #fed7aa);
    box-shadow: 0 10px 20px rgba(251, 146, 60, 0.3);
    animation: pulse 2s infinite;
}

.card h3 {
    font-size: 1.6rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 1rem;
    letter-spacing: -0.01em;
    transition: color 0.3s ease;
}

.card:hover h3 {
    color: #3b82f6;
}

.card p {
    color: #64748b;
    line-height: 1.7;
    margin-bottom: 2rem;
    font-size: 1rem;
    transition: color 0.3s ease;
}

.card:hover p {
    color: #475569;
}

.card ul {
    list-style: none;
    color: #475569;
}

.card li {
    padding: 0.8rem 0;
    border-bottom: 1px solid #f1f5f9;
    transition: all 0.3s ease;
    position: relative;
    padding-left: 1rem;
}

.card li::before {
    content: '→';
    position: absolute;
    left: 0;
    color: #3b82f6;
    font-weight: bold;
    transition: transform 0.3s ease;
}

.card li:hover {
    padding-left: 2rem;
    color: #1e293b;
    background: #f8fafc;
    margin: 0 -1rem;
    padding-right: 1rem;
    border-radius: 8px;
}

.card li:hover::before {
    transform: translateX(5px);
}

.card li:last-child {
    border-bottom: none;
}

.cta-button {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    text-decoration: none;
    padding: 1rem 2rem;
    border-radius: 50px;
    font-weight: 600;
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
    position: relative;
    overflow: hidden;
}

.cta-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.6s ease;
}

.cta-button:hover {
    transform: translateY(-2px) scale(1.05);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.6);
}

.cta-button:hover::before {
    left: 100%;
}

/* Navigation */
.navigation {
    text-align: center;
    margin-top: 4rem;
    animation: fadeInUp 1s ease-out 0.8s both;
}

.nav-links {
    display: flex;
    justify-content: center;
    gap: 2rem;
    flex-wrap: wrap;
}

.nav-link {
    color: #64748b;
    text-decoration: none;
    font-weight: 500;
    padding: 1rem 2rem;
    border-radius: 50px;
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    position: relative;
    overflow: hidden;
}

.nav-link::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    border-radius: 50%;
    transition: all 0.4s ease;
    transform: translate(-50%, -50%);
    z-index: 0;
}

.nav-link:hover::before {
    width: 300px;
    height: 300px;
}

.nav-link:hover {
    color: white;
    border-color: #3b82f6;
    transform: translateY(-3px);
    z-index: 1;
}

.nav-link span {
    position: relative;
    z-index: 2;
}

/* Back Button */
.back-btn {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 1.8rem;
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    z-index: 1000;
    animation: bounceIn 1s ease-out 1.2s both;
}

.back-btn:hover {
    transform: translateY(-8px) scale(1.1) rotate(5deg);
    box-shadow: 0 15px 35px rgba(59, 130, 246, 0.6);
}

/* Animations */
@keyframes fadeInScale {
    0% {
        opacity: 0;
        transform: scale(0.9) translateY(20px);
    }
    100% {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

@keyframes slideUp {
    0% {
        opacity: 0;
        transform: translateY(30px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes expandLine {
    0% {
        width: 0;
        opacity: 0;
    }
    100% {
        width: 80px;
        opacity: 1;
    }
}

@keyframes cardSlideIn {
    0% {
        opacity: 0;
        transform: translateY(50px) rotateX(-10deg);
    }
    100% {
        opacity: 1;
        transform: translateY(0) rotateX(0);
    }
}

@keyframes bounceIcon {
    0%, 20%, 50%, 80%, 100% {
        transform: scale(1.15) rotate(10deg);
    }
    40% {
        transform: scale(1.25) rotate(15deg);
    }
    60% {
        transform: scale(1.2) rotate(12deg);
    }
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

@keyframes pulse {
    0%, 100% { 
        transform: scale(1);
        box-shadow: 0 10px 20px rgba(251, 146, 60, 0.3);
    }
    50% { 
        transform: scale(1.05);
        box-shadow: 0 15px 30px rgba(251, 146, 60, 0.5);
    }
}

@keyframes fadeInUp {
    0% {
        opacity: 0;
        transform: translateY(40px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes bounceIn {
    0% {
        opacity: 0;
        transform: scale(0.3) rotate(-180deg);
    }
    50% {
        opacity: 1;
        transform: scale(1.05) rotate(-90deg);
    }
    70% {
        transform: scale(0.9) rotate(-45deg);
    }
    100% {
        opacity: 1;
        transform: scale(1) rotate(0);
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        padding: 1rem;
    }
    
    .header h1 {
        font-size: 2.8rem;
    }
    
    .cards-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .card {
        padding: 2rem;
    }
    
    .nav-links {
        flex-direction: column;
        gap: 1rem;
    }
    
    .floating-shape {
        opacity: 0.3;
    }
}

/* Text highlight animation */
@keyframes highlight {
    0% { background-position: -100% 0; }
    100% { background-position: 100% 0; }
}

.highlight-text {
    background: linear-gradient(90deg, transparent, #fef3c7, transparent);
    background-size: 200% 100%;
    animation: highlight 2s ease-in-out;
}

/* Loading dots */
.loading-dots {
    display: inline-block;
}

.loading-dots::after {
    content: '';
    animation: dots 2s linear infinite;
}

@keyframes dots {
    0%, 20% { content: '.'; }
    40% { content: '..'; }
    60% { content: '...'; }
    90%, 100% { content: ''; }
}
</style>
</head>
<body>
<!-- Background Decorations -->
<div class="bg-decoration">
    <div class="floating-shape shape-1"></div>
    <div class="floating-shape shape-2"></div>
    <div class="floating-shape shape-3"></div>
    <div class="floating-shape shape-4"></div>
</div>

<div class="geometric-line line-1"></div>
<div class="geometric-line line-2"></div>

<div class="container">
    <div class="header">
        <div class="header-content">
            <h1>Mental Health Resources</h1>
            <p class="subtitle">Your wellbeing journey starts here. Find support, guidance, and tools for better mental health.</p>
        </div>
    </div>
    
    <div class="cards-grid">
        <div class="card mindfulness">
            <div class="card-icon">🧘‍♀️</div>
            <h3>Mindfulness & Meditation</h3>
            <p>Discover daily mindfulness practices, guided meditations, and breathing exercises designed to reduce stress and promote inner peace.</p>
            <a href="https://www.mindful.org/how-to-meditate/" target="_blank" class="cta-button">
                Start Practice →
            </a>
        </div>
        
        <div class="card therapy">
            <div class="card-icon">💬</div>
            <h3>Professional Therapy</h3>
            <p>Connect with licensed therapists and counselors for personalized support through secure online therapy sessions.</p>
            <a href="https://www.betterhelp.com/" target="_blank" class="cta-button">
                Find Therapist →
            </a>
        </div>
        
        <div class="card articles">
            <div class="card-icon">📚</div>
            <h3>Educational Resources</h3>
            <p>Explore comprehensive articles and guides covering anxiety, depression, stress management, and mental wellness strategies.</p>
            <a href="https://www.mentalhealth.org.uk/publications/all" target="_blank" class="cta-button">
                Read Articles →
            </a>
        </div>
        
        <div class="card helplines">
            <div class="card-icon">📞</div>
            <h3>Crisis Support</h3>
            <p>24/7 helplines for immediate support during mental health crises. Help is always available.</p>
            <ul>
                <li>🇮🇳 India: 1800-599-0019 (Vandrevala Foundation)</li>
                <li>🇺🇸 USA: 988 (Suicide & Crisis Lifeline)</li>
                <li>🇬🇧 UK: 116 123 (Samaritans)</li>
            </ul>
        </div>
    </div>
    
    <div class="navigation">
        <div class="nav-links">
            <a href="dashboard.php" class="nav-link"><span>📊 Dashboard</span></a>
            <a href="logout.php" class="nav-link"><span>🚪 Logout</span></a>
        </div>
    </div>
</div>

<a href="javascript:history.back()" class="back-btn" title="Go Back">←</a>
</body>
</html>