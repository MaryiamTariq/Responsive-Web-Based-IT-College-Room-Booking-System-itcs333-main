<?php
session_start();
require_once './includes_ db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to view your bookings.";
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get current datetime
$currentDateTime = new DateTime();

// Upcoming bookings: status = Approved AND (date/time) > now
$upcomingQuery = "
SELECT r.room_name, b.date, b.time
FROM bookings b
JOIN rooms r ON b.room_id = r.id
WHERE b.user_id = :user_id 
AND b.status = 'Approved'
AND (STR_TO_DATE(CONCAT(b.date, ' ', b.time), '%Y-%m-%d %H:%i:%s') > NOW())
ORDER BY b.date, b.time ASC";

$upcomingStmt = $pdo->prepare($upcomingQuery);
$upcomingStmt->execute(['user_id' => $user_id]);
$upcomingBookings = $upcomingStmt->fetchAll(PDO::FETCH_ASSOC);

// Past bookings: status = Approved AND (date/time) < now
$pastQuery = "
SELECT r.room_name, b.date, b.time
FROM bookings b
JOIN rooms r ON b.room_id = r.id
WHERE b.user_id = :user_id
AND b.status = 'Approved'
AND (STR_TO_DATE(CONCAT(b.date, ' ', b.time), '%Y-%m-%d %H:%i:%s') < NOW())
ORDER BY b.date, b.time ASC";

$pastStmt = $pdo->prepare($pastQuery);
$pastStmt->execute(['user_id' => $user_id]);
$pastBookings = $pastStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<html>
<head>
    <title>Your Bookings</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: #f5f5f5;
            padding-bottom: 100px;
        }
        .header {
            height:70px;
            background-color: #ffffff;
            display:flex;
            align-items:center;
            justify-content: space-between;
            padding: 0 30px;
            box-shadow: 0 6px 7px -4px rgba(0,0,0,0.2);
        }
        .card {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            margin-top: 50px;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>
<nav class="header">
    <a href="homepageprj.php"><span class="material-icons-outlined">home</span></a>
    <div class="header-left">
        <span class="material-icons-outlined">menu_book</span>
        <!-- <a href="admin_reports.php"><span class="material-icons-outlined">bar_chart</span></a> -->
        <span class="material-icons-outlined">account_circle</span>
    </div>
    <div class="header-right">
        <a href="logout.php"><span class="material-icons-outlined">logout</span></a>
    </div>
</nav>

<div class="container">
    <h1>Your Upcoming Bookings and Booking History</h1>

    <h2 class="mt-5">Your Upcoming Bookings:</h2>
    <?php if (empty($upcomingBookings)): ?>
        <div class="card">
            No Upcoming Bookings found!
        </div>
    <?php else: ?>
        <?php foreach ($upcomingBookings as $booking): ?>
            <div class="card">
                <strong><?= htmlspecialchars($booking['room_name']) ?></strong><br>
                Time: <?= htmlspecialchars($booking['date']) ?> <?= htmlspecialchars($booking['time']) ?><br><br>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <h2 class="mt-5">Your Past Bookings:</h2>
    <?php if (empty($pastBookings)): ?>
        <div class="card">
            No Past Bookings found!
        </div>
    <?php else: ?>
        <?php foreach ($pastBookings as $booking): ?>
            <div class="card">
                <strong><?= htmlspecialchars($booking['room_name']) ?></strong><br>
                Time: <?= htmlspecialchars($booking['date']) ?> <?= htmlspecialchars($booking['time']) ?><br><br>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>
