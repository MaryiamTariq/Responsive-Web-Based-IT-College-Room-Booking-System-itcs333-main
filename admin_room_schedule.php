<?php
session_start();

// Check admin privileges
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "You must be an admin to access this page.";
    header("Location: admin_login.php");
    exit();
}

include_once './includes_ db.php';

// If the form is submitted to update booking status
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = (int)$_POST['booking_id'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?");
    $stmt->execute([$status, $booking_id]);

    $_SESSION['success'] = "Booking status has been updated successfully.";
    header("Location: admin_room_schedule.php");
    exit();
}

// Fetch all bookings with related room and user info
$sql = "SELECT b.id as booking_id, b.date, b.time, b.status, r.room_name, u.name as user_name 
        FROM bookings b
        JOIN rooms r ON b.room_id = r.id
        JOIN users u ON b.user_id = u.id
        ORDER BY b.date, b.time";
$stmt = $pdo->query($sql);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Schedule Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1>Room Schedule Management</h1>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Room Name</th>
                <th>User Name</th>
                <th>Date</th>
                <th>Time</th>
                <th>Current Status</th>
                <th>Update Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($bookings as $b): ?>
            <tr>
                <td><?php echo $b['booking_id']; ?></td>
                <td><?php echo htmlspecialchars($b['room_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($b['user_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo $b['date']; ?></td>
                <td><?php echo $b['time']; ?></td>
                <td><?php echo $b['status']; ?></td>
                <td>
                    <form method="post" style="display:inline-block;">
                        <input type="hidden" name="booking_id" value="<?php echo $b['booking_id']; ?>">
                        <select name="status" class="form-select form-select-sm d-inline-block" style="width:auto;">
                            <option value="Pending" <?php echo $b['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="Approved" <?php echo $b['status'] == 'Approved' ? 'selected' : ''; ?>>Approved</option>
                            <option value="Rejected" <?php echo $b['status'] == 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="admin_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>

</body>
</html>
