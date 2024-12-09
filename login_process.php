<?php
session_start();

include_once './includes_ db.php';

try {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $email = htmlspecialchars(trim($_POST['username'])); // في النموذج السابق كان اسم الحقل username، نفترض أنه أصبح email
        $password = $_POST['password'];

        // التحقق من عدم ترك الحقول فارغة
        if (empty($email) || empty($password)) {
            $_SESSION['error'] = "All fields are required.";
            header("Location: login.php");
            exit();
        }

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // تسجيل الدخول ناجح
            $_SESSION['user_id'] = $user['id']; 
            $_SESSION['username'] = $user['name']; // سنخزن الاسم في السيشن
            $_SESSION['role'] = "user";
            $_SESSION['success'] = "Login successful! Welcome, " . htmlspecialchars($user['name']) . ".";

            // توجيه المستخدم إلى صفحة محمية
            header("Location: homepageprj.php");
            exit();
        } else {
            // بيانات خاطئة
            $_SESSION['error'] = "Invalid email or password.";
            header("Location: login.php");
            exit();
        }
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Database error: " . $e->getMessage();
    header("Location: login.php");
    exit();
}
