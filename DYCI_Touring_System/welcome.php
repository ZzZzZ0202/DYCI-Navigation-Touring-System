<?php
session_start();
require_once 'includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'student') {
    header("Location: student_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - DYCI Tour System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: #4834d4;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .welcome-container {
            max-width: 900px;
            padding: 2rem;
            text-align: center;
            color: white;
            position: relative;
            z-index: 1;
        }

        .typing-text {
            font-size: 2.2rem;
            margin-bottom: 2rem;
            min-height: 80px;
            display: inline-block;
            font-weight: 600;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
            position: relative;
        }

        .typing-text::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 3px;
            background: #ffd32a;
            animation: underline 0.5s ease forwards;
            animation-delay: 3.5s;
        }

        @keyframes underline {
            to { width: 100%; }
        }

        .main-text {
            font-size: 1.3rem;
            line-height: 1.8;
            opacity: 0;
            transform: translateY(20px);
            margin-bottom: 3rem;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
            padding: 2rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }

        .back-button {
            position: fixed;
            bottom: 2rem;
            left: 2rem;
            background-color: #ffd32a;
            color: #333;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(20px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .back-button:hover {
            background-color: #ffc800;
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            text-decoration: none;
            color: #333;
        }

        .back-button:hover i {
            transform: translateX(-5px);
        }

        .back-button i {
            transition: transform 0.3s ease;
        }

        /* Enhanced Animations */
        .typing {
            overflow: hidden;
            white-space: nowrap;
            border-right: 3px solid white;
            animation: 
                typing 2.5s steps(40, end),
                blink-caret 0.75s step-end infinite;
            margin: 0 auto;
        }

        @keyframes typing {
            from { width: 0 }
            to { width: 100% }
        }

        @keyframes blink-caret {
            from, to { border-color: transparent }
            50% { border-color: #ffd32a }
        }

        .fade-in {
            animation: fadeIn 1.5s ease forwards;
            animation-delay: 2.5s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
            background: linear-gradient(45deg, #4834d4, #686de0);
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            pointer-events: none;
            animation: float 3s infinite ease-in-out;
        }

        @keyframes float {
            0% {
                transform: translateY(0) translateX(0);
            }
            25% {
                transform: translateY(-15px) translateX(15px);
            }
            50% {
                transform: translateY(-25px) translateX(0);
            }
            75% {
                transform: translateY(-15px) translateX(-15px);
            }
            100% {
                transform: translateY(0) translateX(0);
            }
        }

        .glow {
            position: absolute;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 50% 50%, rgba(255,255,255,0.1) 0%, transparent 60%);
            animation: pulse 4s infinite ease-in-out;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.2); opacity: 0.8; }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .typing-text {
                font-size: 1.8rem;
            }
            .main-text {
                font-size: 1.1rem;
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="particles" id="particles">
        <div class="glow"></div>
    </div>
    
    <div class="welcome-container">
        <div class="typing-text typing">
            Welcome to DYCI Tour System
        </div>
        <div class="main-text fade-in">
            is designed to provide an interactive and informative experience, showcasing the rich history, 
            state-of-the-art facilities, and vibrant campus life of Dr. Yanga's Colleges, Inc. (DYCI). 
            Our goal is to give you an insider's view of the campus, highlighting key areas that play 
            a vital role in shaping the academic and extracurricular experiences of our students.
        </div>
    </div>

    <a href="dashboard.php" class="back-button fade-in">
        <i class="fas fa-arrow-left mr-2"></i>Back
    </a>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const particlesContainer = document.getElementById('particles');
            const numberOfParticles = 75; // Increased number of particles

            for (let i = 0; i < numberOfParticles; i++) {
                createParticle();
            }

            function createParticle() {
                const particle = document.createElement('div');
                particle.className = 'particle';
                
                // Enhanced random size between 2px and 8px
                const size = Math.random() * 6 + 2;
                particle.style.width = size + 'px';
                particle.style.height = size + 'px';
                
                // Random position
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                
                // More varied animation duration and delay
                const duration = Math.random() * 3 + 2;
                const delay = Math.random() * 3;
                particle.style.animation = `float ${duration}s infinite ease-in-out ${delay}s`;
                
                // Enhanced opacity variation
                particle.style.opacity = Math.random() * 0.6 + 0.2;
                
                // Add subtle glow effect
                particle.style.boxShadow = `0 0 ${size * 2}px rgba(255,255,255,0.8)`;
                
                particlesContainer.appendChild(particle);
            }

            // Add mouse interaction with particles
            document.addEventListener('mousemove', function(e) {
                const mouseX = e.clientX;
                const mouseY = e.clientY;
                
                document.querySelectorAll('.particle').forEach(particle => {
                    const rect = particle.getBoundingClientRect();
                    const distance = Math.sqrt(
                        Math.pow(mouseX - (rect.left + rect.width/2), 2) +
                        Math.pow(mouseY - (rect.top + rect.height/2), 2)
                    );
                    
                    if (distance < 100) {
                        const angle = Math.atan2(
                            mouseY - (rect.top + rect.height/2),
                            mouseX - (rect.left + rect.width/2)
                        );
                        const push = (100 - distance) / 5;
                        particle.style.transform = `translate(${-Math.cos(angle) * push}px, ${-Math.sin(angle) * push}px)`;
                    }
                });
            });
        });
    </script>
</body>
</html> 