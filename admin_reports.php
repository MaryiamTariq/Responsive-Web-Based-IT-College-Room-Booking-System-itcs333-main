<?php
session_start();
require_once './includes_ db.php';

// Check admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "You must be an admin to access this page.";
    header("Location: index.php");
    exit();
}

// Total number of bookings
$stmt = $pdo->query("SELECT COUNT(*) as total_bookings FROM bookings");
$totalBookings = $stmt->fetchColumn();

// Most booked rooms
$mostBookedStmt = $pdo->query("
    SELECT r.room_name, COUNT(b.id) as booking_count
    FROM bookings b
    JOIN rooms r ON b.room_id = r.id
    GROUP BY r.id, r.room_name
    ORDER BY booking_count DESC
    LIMIT 5
");
$mostBookedRooms = $mostBookedStmt->fetchAll(PDO::FETCH_ASSOC);

// Number of available vs unavailable rooms
$roomStatusStmt = $pdo->query("
    SELECT status, COUNT(*) as count_status
    FROM rooms
    GROUP BY status
");
$roomStatuses = $roomStatusStmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Reports</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h1>Admin Reports</h1>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="mt-4">
        <h3>Total Bookings</h3>
        <p><?= $totalBookings ?></p>
    </div>

    <div class="mt-4">
        <h3>Most Booked Rooms (Top 5)</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Room Name</th>
                    <th>Booking Count</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mostBookedRooms as $room): ?>
                    <tr>
                        <td><?= htmlspecialchars($room['room_name']) ?></td>
                        <td><?= $room['booking_count'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <h3>Room Status Count</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Number of Rooms</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($roomStatuses as $status): ?>
                    <tr>
                        <td><?= $status['status'] ?></td>
                        <td><?= $status['count_status'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <a href="admin_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>
</body>
</html>
