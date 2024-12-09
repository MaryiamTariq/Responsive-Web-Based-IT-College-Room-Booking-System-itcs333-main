<!-- index.php -->
<?php
include './includes_ db.php'; 
session_start();
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
<body>
    <div class="container text-center">
        <h1>Welcome to IT College Room Booking</h1>
        <?php if (isset($_SESSION['username'])): ?>
            <p>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?></p>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="admin_dashboard.php" class="btn btn-warning mt-3">Admin Dashboard</a>
            <?php endif; ?>
            <a href="booking.php" class="btn btn-primary mt-3">Book a Room</a>
            <a href="profile.php" class="btn btn-secondary mt-3">View Profile</a>
            <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
        <?php else: ?>
            <a href="login.php" class="btn btn-primary mt-3">User Login</a>
            <a href="register.php" class="btn btn-success mt-3">Register</a>
            <a href="admin_login.php" class="btn btn-dark mt-3">Admin Login</a>
        <?php endif; ?>
    </div>
</body>
</html>


<!-- register.php -->
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register New Account</title>
</head>
<body>
    <h2>Register</h2>
    <hr>
    <?php if (isset($_SESSION['error'])): ?>
        <div style="color:red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <div style="color:green;"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <form action="register_process.php" method="POST">
        <label for="fName">First Name</label><br>
        <input type="text" name="fName" id="fName" required><br><br>

        <label for="lName">Last Name</label><br>
        <input type="text" name="lName" id="lName" required><br><br>

        <label for="email">UoB Email</label><br>
        <input type="email" name="email" id="email" placeholder="example@uob.edu.bh" required><br><br>

        <label for="password">Password</label><br>
        <input type="password" name="password" id="password" required><br><br>

        <button type="submit">Register</button>
    </form>

    <p><br>Already have an account? <a href="login.php">Login here</a></p>
</body>
</html>


<!-- register_process.php -->
<?php
session_start();

// تأكد من مسار ملف الاتصال بقاعدة البيانات
include_once './includes_ db.php';

try {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // استلام القيم من النموذج
        $fName = htmlspecialchars(trim($_POST['fName']));
        $lName = htmlspecialchars(trim($_POST['lName']));
        $email = htmlspecialchars(trim($_POST['email']));
        $password = $_POST['password'];

        // تحقق من عدم ترك الحقول فارغة
        if (empty($fName) || empty($lName) || empty($email) || empty($password)) {
            $_SESSION['error'] = "All fields are required.";
            header("Location: register.php");
            exit();
        }

        // دمج الاسم الأول والأخير في حقل واحد
        $fullName = trim($fName . ' ' . $lName);

        // التحقق من بريد الجامعة
        // مثال على التحقق: يجب أن ينتهي بـ @uob.edu.bh ويبدأ بحروف فقط
        if (!preg_match("/^[a-zA-Z]+@uob\.edu\.bh$/", $email)) {
            $_SESSION['error'] = "Please use a valid UOB email address (example: wahmed@uob.edu.bh)";
            header("Location: register.php");
            exit();
        }

        // التحقق من وجود البريد مسبقاً
        $stmt = $pdo->prepare("SELECT email FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = "Email already registered.";
            header("Location: register.php");
            exit();
        }

        // تشفير كلمة المرور
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // إدخال البيانات في قاعدة البيانات
        $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':name', $fullName, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Registration successful! You can now login.";
            header("Location: login.php");
            exit();
        } else {
            $_SESSION['error'] = "Registration failed. Please try again.";
            header("Location: register.php");
            exit();
        }
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Database error: " . $e->getMessage();
    header("Location: register.php");
    exit();
}

?>


<!-- login.php -->
 <!-- login.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>    
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="form-container">
        <h2>Login</h2>
        <hr>
        <?php session_start(); ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div style="color:red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['success'])): ?>
            <div style="color:green;"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <form action="login_process.php" method="POST">
            <label for="username">Email</label>
            <input type="email" placeholder="Enter UOB Email" name="username" id="username" required><br><br>

            <label for="password">Password</label>
            <input type="password" placeholder="Enter Password" name="password" id="password" required><br><br>

            <button type="submit" class="registerbtn">Login</button>
        </form>
        <footer>
            <p><br>Don't have an account? <a href="register.php">Register here</a></p>
        </footer>
    </div>
</body>
</html>


<!-- login_process.php -->
<?php
session_start();

// تأكد من مسار ملف db.php
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

        // التحقق من وجود المستخدم عن طريق البريد الإلكتروني
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        // جلب بيانات المستخدم
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // تسجيل الدخول ناجح
            $_SESSION['user_id'] = $user['id']; 
            $_SESSION['username'] = $user['name']; // سنخزن الاسم في السيشن
            $_SESSION['success'] = "Login successful! Welcome, " . htmlspecialchars($user['name']) . ".";

            // توجيه المستخدم إلى صفحة محمية
            header("Location: protected_page.php");
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
?>



<!-- logout.php -->
<?php
session_start();
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

header("Location: index.php"); // Redirect to login page
exit();
?>



<!-- profile.php -->
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


<!-- protected_page.php -->
<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must log in to access this page.";
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Protected Page</title>
</head>
<body>
    <h1>Welcome to the Protected Page!</h1>
    <p>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>. You have successfully logged in.</p>
    <a href="logout.php">Logout</a>
</body>
</html>
