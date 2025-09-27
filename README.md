# MindSync
Overview
--------
The Counselor Dashboard is a lightweight web-based platform built using PHP, MySQL, Inline CSS,
and JavaScript that allows counselors to log in securely and interact with verified students through a
private one-to-one chat interface. The system provides an easy-to-use dashboard for counselors,
displays a list of verified students, and enables real-time text-based chat with automatic message
fetching every 2 seconds.
This project is designed for educational institutions, counseling centers, or online mentoring
platforms where one-to-one interaction between a counselor and a student is required. The interface
is styled with a clean blue-and-white theme, responsive chat design, and animated floating particles
in the background to make the user experience more engaging.
Key Features
------------
1. Secure Authentication
 - Counselors must log in using valid credentials.
 - Unauthorized users are redirected to the login page.
 - Sessions are maintained to prevent unauthorized access.
2. Student Management
 - The system fetches only verified students from the database.
 - Counselors can see a dynamic list of students on the dashboard.
 - Each student is listed with a quick "Chat button".
3. One-to-One Chat
 - Counselors can select a student and open a chat window.
 - Messages are stored in a MySQL database.
 - Real-time communication is achieved using AJAX polling every 2 seconds.
 - Chat automatically scrolls to the latest message.
4. UI & Design
 - Clean card-based container with a white background.
 - Blue buttons and links with hover animations.
 - Glowing floating particles animated in the background for visual appeal.
 - A floating back button (bottom-right corner) for quick navigation.
5. Logout Functionality
 - Counselors can log out securely, ending the session.
 - Logout button ensures data protection.
6. Modern Experience
 - Responsive layout for desktop and laptop users.
 - Smooth animations for buttons and links.
 - Minimal distractions, optimized for productivity.
Tech Stack
----------
Frontend:
- HTML5 for structure
- Inline CSS for styling
- Vanilla JavaScript for interactivity and AJAX fetch
Backend:
- PHP (for session management, message sending, and fetching data)
- MySQL (for storing users, verification, and chat messages)
Database Tables:
- counselors: Stores counselor credentials (id, name, email, password)
- students: Stores student info with verified=1 for approved ones
- messages: Stores chat logs with sender_id, receiver_id, timestamp, and content
Installation & Setup
-------------------
1. Install Requirements
 - Install XAMPP / WAMP / LAMP depending on your OS.
 - Ensure Apache and MySQL services are running.
2. Database Setup
 - Open phpMyAdmin.
 - Create a new database (e.g., counseling_db).
 - Run the provided SQL commands to create tables.
 - Add sample counselor and student data.
3. Configure Project
 - Place all .php files inside htdocs/counseling/ (XAMPP) or /var/www/html/counseling/ (LAMP).
 - Edit db.php to update your database credentials.
4. Run Project
 - Open your browser and go to: http://localhost/counseling/counselor_login.php
 - Log in using your counselor credentials.
 - Access the dashboard and start chatting with students.
Usage Flow
----------
1. Counselor Login
 - Counselor logs in via counselor_login.php.
 - Session starts and counselor is redirected to counselor_dashboard.php.
2. View Student List
 - Dashboard shows only verified students.
 - Each student has a Chat button to start a session.
3. Chat Window
 - Messages load automatically every 2 seconds.
 - Counselors can send new messages instantly.
 - Chat scrolls to the latest message automatically.
4. Logout
 - Counselor clicks logout to end the session.
 - Redirected back to login page.
User Interface
--------------
- Color Theme: White background + Blue buttons
- Floating Particles: 105 glowing particles rise upward randomly, creating an ambient effect
- Chat Box: Scrollable container with inset shadows
- Buttons: Rounded edges, glowing hover effect, smooth scaling animation
- Back Button: Floating circular button at bottom-right
Security Notes
--------------
- Passwords should be stored using password_hash() instead of plain text.
- SQL queries should use prepared statements to avoid injection attacks.
- Session timeouts should be configured for inactive users.
- Only verified students are displayed to counselors.

Conclusion
----------
The Counselor Dashboard with Student Chat System is a practical, lightweight, and visually
appealing platform for one-to-one communication between counselors and students. Its simple
PHP-MySQL backend, modern UI design, and interactive features make it easy to deploy in
real-world educational environments.
