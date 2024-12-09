<?php
session_start();
// Check admin privileges (assuming there's a mechanism for that)

include_once './includes_ db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$room = [
    'room_name' => '',
    'capacity' => '',
    'status' => 'Available'
];

if ($id > 0) {
    // Editing an existing room
    $stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = ?");
    $stmt->execute([$id]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$room) {
        // No room found
        header("Location: admin_rooms.php");
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_name = $_POST['room_name'];
    $capacity = (int)$_POST['capacity'];
    $status = $_POST['status'];

    if ($id > 0) {
        // Update existing room
        $stmt = $pdo->prepare("UPDATE rooms SET room_name = ?, capacity = ?, status = ? WHERE id = ?");
        $stmt->execute([$room_name, $capacity, $status, $id]);
    } else {
        // Add a new room
        $stmt = $pdo->prepare("INSERT INTO rooms (room_name, capacity, status) VALUES (?, ?, ?)");
        $stmt->execute([$room_name, $capacity, $status]);
    }

    header("Location: admin_rooms.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $id > 0 ? "Edit Room" : "Add New Room"; ?></title>
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1><?php echo $id > 0 ? "Edit Room" : "Add New Room"; ?></h1>
    <form method="post" class="mb-3">
        <div class="mb-3">
            <label class="form-label">Room Name</label>
            <input type="text" name="room_name" class="form-control" required value="<?php echo htmlspecialchars($room['room_name'], ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Capacity</label>
            <input type="number" name="capacity" class="form-control" required value="<?php echo htmlspecialchars($room['capacity'], ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
                <option value="Available" <?php echo $room['status'] === 'Available' ? 'selected' : ''; ?>>Available</option>
                <option value="Unavailable" <?php echo $room['status'] === 'Unavailable' ? 'selected' : ''; ?>>Unavailable</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Save</button>
        <a href="admin_rooms.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

</body>
</html>
