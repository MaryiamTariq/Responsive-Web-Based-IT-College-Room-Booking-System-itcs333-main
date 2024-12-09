<?php
session_start();
require_once './includes_ db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "You must be an admin to do this action.";
    header("Location: index.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "Invalid comment ID.";
    header("Location: admin_comments.php");
    exit();
}

$comment_id = (int)$_GET['id'];

$stmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
$stmt->execute([$comment_id]);

$_SESSION['success'] = "Comment deleted successfully.";
header("Location: admin_comments.php");
exit();
