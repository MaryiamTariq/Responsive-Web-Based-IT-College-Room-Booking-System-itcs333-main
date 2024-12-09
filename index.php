<?php
session_start();
include_once './includes_ db.php'; 

// التحقق من دور المستخدم إذا كان مسجّلاً للدخول
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        // توجيه الأدمن إلى لوحة التحكم
        header("Location: admin_dashboard.php");
        exit();
    } elseif ($_SESSION['role'] === 'user') {
        // توجيه المستخدم العادي إلى الصفحة الرئيسية الخاصة به
        header("Location: homepageprj.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT College Room Booking</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light">
    <div class="container text-center mt-5">
        <h1>Welcome to IT College Room Booking</h1>
        <!-- إذا وصلنا إلى هنا، يعني أن المستخدم غير مسجل الدخول -->
        <a href="login.php" class="btn btn-primary mt-3">User Login</a>
        <a href="register.php" class="btn btn-success mt-3">Register</a>
        <a href="admin_login.php" class="btn btn-dark mt-3">Admin Login</a>
    </div>
</body>
</html>
