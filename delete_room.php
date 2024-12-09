<?php
session_start();
// تحقق من صلاحية الأدمن. (يفترض وجود آلية لذلك)

include_once './includes_ db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM rooms WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: admin_rooms.php");
exit;
