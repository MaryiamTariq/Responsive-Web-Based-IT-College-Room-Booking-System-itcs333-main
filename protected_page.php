<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must log in to access this page.";
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Protected Page</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h1>Protected Page</h1>
    <p>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>. You have successfully logged in.</p>
    <a href="logout.php" class="btn btn-danger">Logout</a>
</div>
</body>
</html>
