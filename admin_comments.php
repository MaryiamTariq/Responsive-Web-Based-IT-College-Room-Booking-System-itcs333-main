<?php
session_start();
require_once './includes_ db.php';

// Check admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "You must be an admin to access this page.";
    header("Location: index.php");
    exit();
}

// Fetch comments
$stmt = $pdo->query("
    SELECT c.id, c.user_id, c.room_id, c.comment_text, c.reply_text, c.created_at, u.name as user_name, r.room_name
    FROM comments c
    JOIN users u ON c.user_id = u.id
    JOIN rooms r ON c.room_id = r.id
    ORDER BY c.created_at DESC
");
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Comments Management</title>
<link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h1>Comments Management</h1>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']);?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']);?></div>
    <?php endif; ?>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Room</th>
                <th>Comment</th>
                <th>Reply</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($comments as $c): ?>
            <tr>
                <td><?= $c['id'] ?></td>
                <td><?= htmlspecialchars($c['user_name']) ?></td>
                <td><?= htmlspecialchars($c['room_name']) ?></td>
                <td><?= htmlspecialchars($c['comment_text']) ?></td>
                <td><?= htmlspecialchars($c['reply_text']) ?></td>
                <td><?= $c['created_at'] ?></td>
                <td>
                    <a href="admin_comment_reply.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-primary">Reply</a>
                    <a href="admin_comment_delete.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this comment?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <a href="admin_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>
</body>
</html>
