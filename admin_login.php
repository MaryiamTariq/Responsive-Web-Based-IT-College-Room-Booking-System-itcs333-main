<?php
session_start();
require_once './includes_ db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // التحقق من عدم ترك الحقول فارغة
    if (empty($email) || empty($password)) {
        $error = "All fields are required.";
    } else {
        // جلب بيانات الأدمن
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND role = 'admin' LIMIT 1");
        $stmt->execute(['email' => $email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['password'])) {
            // نجاح تسجيل الدخول
            $_SESSION['user_id'] = $admin['id'];
            $_SESSION['username'] = $admin['name'];
            $_SESSION['role'] = $admin['role']; // تكون 'admin'
            $_SESSION['success'] = "Login successful! Welcome, Admin " . htmlspecialchars($admin['name']) . ".";

            header("Location: admin_dashboard.php");
            exit;
        } else {
            $error = "Invalid email or password!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <!-- استخدام Bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body class="bg-light d-flex align-items-center" style="height:100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card p-4">
                    <h3 class="text-center mb-4">Admin Login</h3>
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="email" class="form-label">UOB Email</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="admin@uob.edu.bh" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" id="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                    <a href="index.php" class="d-block text-center mt-3">Back to Home</a>
                </div>
            </div>
        </div>
    </div>

    <!-- سكربتات Bootstrap -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
