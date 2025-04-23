<?php
session_start();
require_once 'includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'student') {
    header("Location: student_login.php");
    exit();
}

// Get student details and visit history
$student = getStudentDetails($_SESSION['student_id']);
if (!$student) {
    die('Error: Student not found');
}

$visit_history = getStudentVisitHistory($student['id']);

// Group visits by date
$grouped_visits = [];
foreach ($visit_history as $visit) {
    $date = date('Y-m-d', strtotime($visit['visit_time']));
    if (!isset($grouped_visits[$date])) {
        $grouped_visits[$date] = [];
    }
    $grouped_visits[$date][] = $visit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visit History - DYCI Tour System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root {
            --primary-color: #003366;
            --secondary-color: #0056b3;
            --accent-color: #e3f2fd;
            --text-color: #333;
            --light-gray: #f8f9fa;
            --timeline-color: #dee2e6;
        }

        body {
            background-color: var(--light-gray);
            color: var(--text-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            padding: 0.5rem 0;
            background: var(--primary-color);
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            margin-bottom: 30px;
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 1.5rem;
        }

        .btn-profile {
            background-color: rgba(255, 255, 255, 0.1);
            border: 2px solid white;
            color: white;
            transition: all 0.3s ease;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
        }

        .btn-profile:hover {
            background-color: white;
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        .timeline {
            position: relative;
            padding: 2rem;
        }

        .date-header {
            background: var(--accent-color);
            color: var(--primary-color);
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            display: inline-block;
            margin-bottom: 1.5rem;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .visit-group {
            position: relative;
            padding-left: 3rem;
            margin-bottom: 2rem;
            opacity: 0;
            transform: translateX(-20px);
            animation: slideIn 0.5s ease forwards;
        }

        .visit-group::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--timeline-color);
        }

        .visit-item {
            background: white;
            padding: 1.2rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            position: relative;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .visit-item:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .visit-item::before {
            content: '';
            position: absolute;
            left: -2.2rem;
            top: 50%;
            width: 12px;
            height: 12px;
            background: var(--primary-color);
            border-radius: 50%;
            transform: translateY(-50%);
            box-shadow: 0 0 0 4px var(--accent-color);
            transition: all 0.3s ease;
        }

        .visit-item:hover::before {
            transform: translateY(-50%) scale(1.2);
            box-shadow: 0 0 0 6px var(--accent-color), 0 0 20px rgba(0,0,0,0.2);
        }

        .visit-time {
            color: var(--secondary-color);
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .visit-location {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 0.5rem;
        }

        .visit-details {
            color: #666;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
        }

        .visit-details i {
            margin-right: 0.5rem;
            color: var(--primary-color);
        }

        .no-visits {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 12px;
        }

        .no-visits i {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .no-visits p {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
            perspective: 1000px;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transform-style: preserve-3d;
        }

        .stat-card:hover {
            transform: rotateY(10deg) rotateX(5deg);
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            transform: translateX(-100%);
            transition: 0.5s;
        }

        .stat-card:hover::after {
            transform: translateX(100%);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }

        .visit-item.expanded {
            background: var(--accent-color);
        }

        .visit-details-expanded {
            display: none;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(0,0,0,0.1);
        }

        .visit-item.expanded .visit-details-expanded {
            display: block;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .filter-container {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 2rem;
            perspective: 1000px;
        }

        .filter-btn {
            transform-style: preserve-3d;
            transition: all 0.3s ease;
        }

        .filter-btn:hover {
            transform: translateZ(20px);
        }

        .filter-btn.active {
            background: var(--primary-color);
            color: white;
            transform: translateZ(30px);
        }

        .search-container {
            position: relative;
            margin-bottom: 2rem;
        }

        .search-input {
            width: 100%;
            padding: 1rem 1.5rem;
            border: none;
            border-radius: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            padding-left: 3rem;
        }

        .search-input:focus {
            outline: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .stat-card:hover .stat-number {
            animation: pulse 1s infinite;
        }

        .stat-card .stat-icon {
            font-size: 2rem;
            color: var(--primary-color);
            opacity: 0.2;
            position: absolute;
            right: 1rem;
            bottom: 1rem;
            transition: all 0.3s ease;
        }

        .stat-card:hover .stat-icon {
            opacity: 0.8;
            transform: scale(1.2) rotate(15deg);
        }

        .visit-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s ease;
        }

        .visit-item:hover .visit-actions {
            opacity: 1;
            transform: translateY(0);
        }

        .action-btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 20px;
            background: var(--primary-color);
            color: white;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .action-btn i {
            font-size: 1rem;
        }

        .progress-bar {
            position: fixed;
            top: 0;
            left: 0;
            width: 0;
            height: 3px;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            z-index: 1001;
            transition: width 0.3s ease;
        }

        .back-to-top {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 40px;
            height: 40px;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .back-to-top.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .back-to-top:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        @keyframes slideIn {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @media (max-width: 768px) {
            .timeline {
                padding: 1rem;
            }

            .visit-group {
                padding-left: 2rem;
            }

            .visit-item::before {
                left: -1.7rem;
            }

            .stats-container {
                perspective: none;
            }
            
            .stat-card:hover {
                transform: none;
            }
            
            .filter-container {
                perspective: none;
            }
            
            .filter-btn:hover {
                transform: none;
            }
        }
    </style>
</head>
<body>
    <div class="progress-bar"></div>
    <nav class="navbar navbar-dark mb-4">
        <div class="container">
            <span class="navbar-brand">
                <i class="fas fa-history mr-2"></i>
                Visit History
            </span>
            <div>
                <a href="profile.php?ref=visitors" class="btn btn-profile mr-2">
                    <i class="fas fa-user"></i> Profile
                </a>
                <a href="dashboard.php" class="btn btn-outline-light">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <?php if (!empty($visit_history)): ?>
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" placeholder="Search visits by room or building..." id="searchVisits">
            </div>

            <div class="filter-container">
                <button class="filter-btn active" data-filter="all">All Visits</button>
                <button class="filter-btn" data-filter="today">Today</button>
                <button class="filter-btn" data-filter="week">This Week</button>
                <button class="filter-btn" data-filter="month">This Month</button>
            </div>

            <!-- Stats Section -->
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-number"><?php echo count($visit_history); ?></div>
                    <div class="stat-label">Total Visits</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo count(array_unique(array_column($visit_history, 'room_id'))); ?></div>
                    <div class="stat-label">Unique Rooms</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo count($grouped_visits); ?></div>
                    <div class="stat-label">Days Active</div>
                </div>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-clock mr-2"></i>
                    Your Visit Timeline
                </h4>
            </div>
            <div class="timeline">
                <?php if (!empty($grouped_visits)): ?>
                    <?php foreach ($grouped_visits as $date => $visits): ?>
                        <div class="date-header">
                            <i class="fas fa-calendar-day mr-2"></i>
                            <?php echo date('l, F j, Y', strtotime($date)); ?>
                        </div>
                        <div class="visit-group">
                            <?php foreach ($visits as $visit): ?>
                                <div class="visit-item" data-date="<?php echo $date; ?>">
                                    <div class="visit-time">
                                        <i class="far fa-clock mr-1"></i>
                                        <?php echo date('g:i A', strtotime($visit['visit_time'])); ?>
                                    </div>
                                    <div class="visit-location">
                                        <?php echo htmlspecialchars($visit['room_name']); ?>
                                    </div>
                                    <div class="visit-details">
                                        <i class="fas fa-building"></i>
                                        <?php echo htmlspecialchars($visit['building_name']); ?> - 
                                        Floor <?php echo htmlspecialchars($visit['floor']); ?>
                                    </div>
                                    <div class="visit-details-expanded">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Duration:</strong> 30 minutes</p>
                                                <p><strong>Purpose:</strong> Campus Tour</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Room Type:</strong> <?php echo htmlspecialchars($visit['room_type'] ?? 'Standard'); ?></p>
                                                <p><strong>Floor Level:</strong> <?php echo htmlspecialchars($visit['floor']); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-visits">
                        <i class="fas fa-walking d-block mb-3"></i>
                        <p>No visit history found yet.</p>
                        <a href="dashboard.php" class="btn btn-primary">
                            <i class="fas fa-compass mr-2"></i>
                            Start Exploring
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="back-to-top">
            <i class="fas fa-arrow-up"></i>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Visit item click handler
            document.querySelectorAll('.visit-item').forEach(item => {
                item.addEventListener('click', function() {
                    this.classList.toggle('expanded');
                });
            });

            // Search functionality
            const searchInput = document.getElementById('searchVisits');
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                document.querySelectorAll('.visit-item').forEach(item => {
                    const text = item.textContent.toLowerCase();
                    item.style.display = text.includes(searchTerm) ? 'block' : 'none';
                });
            });

            // Filter buttons
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    // Remove active class from all buttons
                    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    const filter = this.dataset.filter;
                    const today = new Date().toISOString().split('T')[0];
                    const items = document.querySelectorAll('.visit-item');

                    items.forEach(item => {
                        const date = new Date(item.dataset.date);
                        const itemDate = item.dataset.date;
                        
                        switch(filter) {
                            case 'today':
                                item.style.display = itemDate === today ? 'block' : 'none';
                                break;
                            case 'week':
                                const weekAgo = new Date();
                                weekAgo.setDate(weekAgo.getDate() - 7);
                                item.style.display = date >= weekAgo ? 'block' : 'none';
                                break;
                            case 'month':
                                const monthAgo = new Date();
                                monthAgo.setMonth(monthAgo.getMonth() - 1);
                                item.style.display = date >= monthAgo ? 'block' : 'none';
                                break;
                            default:
                                item.style.display = 'block';
                        }
                    });
                });
            });

            // Animate stats on scroll
            const stats = document.querySelectorAll('.stat-number');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const target = entry.target;
                        const finalValue = parseInt(target.textContent);
                        animateValue(target, 0, finalValue, 1500);
                        observer.unobserve(target);
                    }
                });
            });

            stats.forEach(stat => observer.observe(stat));

            function animateValue(obj, start, end, duration) {
                let startTimestamp = null;
                const step = (timestamp) => {
                    if (!startTimestamp) startTimestamp = timestamp;
                    const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                    obj.innerHTML = Math.floor(progress * (end - start) + start);
                    if (progress < 1) {
                        window.requestAnimationFrame(step);
                    }
                };
                window.requestAnimationFrame(step);
            }

            // Scroll Progress Bar
            const progressBar = document.querySelector('.progress-bar');
            window.addEventListener('scroll', () => {
                const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
                const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                const scrolled = (winScroll / height) * 100;
                progressBar.style.width = scrolled + '%';
            });

            // Navbar Scroll Effect
            const navbar = document.querySelector('.navbar');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });

            // Back to Top Button
            const backToTop = document.querySelector('.back-to-top');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 300) {
                    backToTop.classList.add('visible');
                } else {
                    backToTop.classList.remove('visible');
                }
            });

            backToTop.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });

            // Add icons to stat cards
            const statIcons = ['fa-chart-line', 'fa-door-open', 'fa-calendar-alt'];
            document.querySelectorAll('.stat-card').forEach((card, index) => {
                const icon = document.createElement('i');
                icon.className = `fas ${statIcons[index]} stat-icon`;
                card.appendChild(icon);
            });

            // Add action buttons to visit items
            document.querySelectorAll('.visit-item').forEach(item => {
                const actions = document.createElement('div');
                actions.className = 'visit-actions';
                actions.innerHTML = `
                    <button class="action-btn view-details">
                        <i class="fas fa-info-circle"></i>
                        View Details
                    </button>
                    <button class="action-btn share-visit">
                        <i class="fas fa-share-alt"></i>
                        Share
                    </button>
                `;
                item.appendChild(actions);

                // Add click handlers for action buttons
                const viewDetails = actions.querySelector('.view-details');
                const shareVisit = actions.querySelector('.share-visit');

                viewDetails.addEventListener('click', (e) => {
                    e.stopPropagation();
                    // Add your view details logic here
                    alert('Viewing details...');
                });

                shareVisit.addEventListener('click', (e) => {
                    e.stopPropagation();
                    // Add your share logic here
                    alert('Sharing visit...');
                });
            });

            // Add stagger effect to visit items
            document.querySelectorAll('.visit-group').forEach((group, groupIndex) => {
                group.style.animationDelay = `${groupIndex * 0.1}s`;
            });
        });
    </script>
</body>
</html> 