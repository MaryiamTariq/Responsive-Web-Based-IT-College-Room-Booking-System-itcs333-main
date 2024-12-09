<?php
session_start();
// Check admin privileges (assuming there's a mechanism for that)

include_once './includes_ db.php';

$stmt = $pdo->query("SELECT * FROM rooms");
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1>Room Management</h1>
    <a href="admin_room_form.php" class="btn btn-primary mb-3">Add New Room</a>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Room ID</th>
                <th>Room Name</th>
                <th>Capacity</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rooms as $room): ?>
            <tr>
                <td><?php echo $room['id']; ?></td>
                <td><?php echo htmlspecialchars($room['room_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo $room['capacity']; ?></td>
                <td><?php echo $room['status']; ?></td>
                <td>
                    <a href="admin_room_form.php?id=<?php echo $room['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="delete_room.php?id=<?php echo $room['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this room?');">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="admin_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>

</body>
</html>
