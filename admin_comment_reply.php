<?php
session_start();
require_once './includes_ db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "You must be an admin to access this page.";
    header("Location: index.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "Invalid comment ID.";
    header("Location: admin_comments.php");
    exit();
}

$comment_id = (int)$_GET['id'];

// Fetch comment
$stmt = $pdo->prepare("SELECT c.id, c.comment_text, c.reply_text, u.name as user_name, r.room_name
    FROM comments c
    JOIN users u ON c.user_id = u.id
    JOIN rooms r ON c.room_id = r.id
    WHERE c.id = ?");
$stmt->execute([$comment_id]);
$comment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$comment) {
    $_SESSION['error'] = "Comment not found.";
    header("Location: admin_comments.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reply = trim($_POST['reply_text']);
    $updateStmt = $pdo->prepare("UPDATE comments SET reply_text = ? WHERE id = ?");
    $updateStmt->execute([$reply, $comment_id]);
    $_SESSION['success'] = "Reply added successfully.";
    header("Location: admin_comments.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reply to Comment</title>
<link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h1>Reply to Comment</h1>
    <div class="card p-3">
        <p><strong>User:</strong> <?= htmlspecialchars($comment['user_name']) ?></p>
        <p><strong>Room:</strong> <?= htmlspecialchars($comment['room_name']) ?></p>
        <p><strong>Comment:</strong> <?= htmlspecialchars($comment['comment_text']) ?></p>

        <form method="post">
            <div class="mb-3">
                <label>Reply:</label>
                <textarea name="reply_text" class="form-control" rows="4"><?= htmlspecialchars($comment['reply_text']) ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Reply</button>
            <a href="admin_comments.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
</body>
</html>
