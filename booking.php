<?php
session_start();
include_once './includes_ db.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to book a room.";
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $room_id = isset($_POST['room_id']) ? (int)$_POST['room_id'] : 0;
    $date = trim($_POST['date']);
    $time = trim($_POST['time']);

    if (empty($room_id) || empty($date) || empty($time)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: booking.php");
        exit();
    }

    $stmt = $pdo->prepare("SELECT status FROM rooms WHERE id = :room_id LIMIT 1");
    $stmt->bindParam(':room_id', $room_id, PDO::PARAM_INT);
    $stmt->execute();
    $room = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$room || $room['status'] !== 'Available') {
        $_SESSION['error'] = "The selected room is not available.";
        header("Location: booking.php");
        exit();
    }

    // التحقق من عدم وجود حجز متعارض
    // في هذه الحالة نفترض أن لا يمكن حجز نفس الغرفة في نفس التاريخ والوقت من قبل مستخدم آخر
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE room_id = :room_id AND date = :date AND time = :time AND status IN ('Pending','Approved')");
    $stmt->bindParam(':room_id', $room_id, PDO::PARAM_INT);
    $stmt->bindParam(':date', $date, PDO::PARAM_STR);
    $stmt->bindParam(':time', $time, PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        // يوجد حجز سابق في نفس الموعد
        $_SESSION['error'] = "This room is already booked at the selected time.";
        header("Location: booking.php");
        exit();
    }

    // إدخال الحجز في قاعدة البيانات
    $insert = $pdo->prepare("INSERT INTO bookings (user_id, room_id, date, time, status) VALUES (?, ?, ?, ?, 'Pending')");
    $insert->execute([$user_id, $room_id, $date, $time]);

    $_SESSION['success'] = "Room booked successfully! Your booking is now pending approval.";
    header("Location: booking.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Room</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h1>Book a Room</h1>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <form method="POST" class="mt-4">
        <div class="mb-3">
            <label for="room_id" class="form-label">Room:</label>
            <select name="room_id" id="room_id" class="form-control" required>
                <!-- يمكنك جلب قائمة الغرف ديناميكياً من قاعدة البيانات -->
                <option value="1">Room A</option>
                <option value="2">Room B</option>
                <option value="3">Room C</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Date:</label>
            <input type="date" name="date" id="date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="time" class="form-label">Time:</label>
            <input type="time" name="time" id="time" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Book Now</button>
    </form>
    <a href="index.php" class="btn btn-secondary mt-3">Back</a>
</div>
</body>
</html>
