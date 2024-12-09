<?php
session_start();
require_once './includes_ db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid Room ID.');
}

$id = (int)$_GET['id'];

// Assuming we have columns equipment and available_times in rooms
$query = "SELECT room_name, capacity, 'Projector, Whiteboard' as equipment, '9:00 AM - 5:00 PM' as available_times FROM rooms WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$id]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$room) {
    die('Room not found.');
}

// Simple background selection
$backgroundClass = 'Room-A';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color:  #ebb9bf;
            font-family: 'Arial', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }
        header {
            background: #c4eff5;
            color: #333;
            text-align: center;
            padding: 15px;
            border-radius: 10px;
            width: 50%;
            margin-top: 30px;
            margin-bottom: 30px;
        }
        h1 {
            font-size: 2.5rem;
            color: #d9235f;
        }
        .room-info {
            background-color:  #d7f5c4;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }
        .list-group-item {
            background-color: #f9f9f9;
            border: none;
            padding: 10px;
            font-size: 1.1rem;
        }
        .list-group-item strong {
            color: #4b0082;
        }
        .btn {
            background-color: #af8b58;
            color: white;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 1rem;
        }
        .btn:hover {
            background-color: #ff3385;
        }
        footer {
            background: #83e965;
            color: #3e3e3e;
            text-align: center;
            padding: 10px;
            width: 100%;
            margin-top: auto;
        }
    </style>
</head>
<body>
<img src="ms.png" alt="class activities 1" width="100" height="100" >
<header>
    <h1><?= htmlspecialchars($room['room_name']) ?> Details</h1>
</header>

<div class="room-info">
    <ul class="list-group">
        <li class="list-group-item"><strong>Capacity:</strong> <?= htmlspecialchars($room['capacity']) ?> people</li>
        <li class="list-group-item"><strong>Equipment:</strong> <?= htmlspecialchars($room['equipment']) ?></li>
        <li class="list-group-item"><strong>Available Times:</strong> <?= htmlspecialchars($room['available_times']) ?></li>
    </ul>
    <a href="room_browsing.php" class="btn mt-3">Back to Room Browsing</a>
</div>

<footer>
    <p>&copy; 2024 Room Booking System. All rights reserved.</p>
</footer>
</body>
</html>
