<?php
session_start();
require_once './includes_ db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to access your profile.";
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    // Handle file upload
    if (!empty($_FILES['profile_picture']['name'])) {
        $target_dir = "uploads/";
        // Make sure the uploads directory exists
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_name = basename($_FILES['profile_picture']['name']);
        $target_file = $target_dir . $file_name;
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file);

        $query = $pdo->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
        $query->execute([$target_file, $user_id]);
    }

    // Update user details
    $query = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
    $query->execute([$name, $email, $user_id]);

    $_SESSION['success'] = "Profile updated successfully.";
    header("Location: profile.php");
    exit();
}

// Fetch user data
$query = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$query->execute([$user_id]);
$user = $query->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h1>Your Profile</h1>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="mt-4">
        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']); ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Profile Picture:</label>
            <input type="file" name="profile_picture" class="form-control">
            <?php if ($user['profile_picture']): ?>
                <img src="<?= htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture" width="100" class="mt-2">
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary">Update Profile</button>
        <a href="index.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>
