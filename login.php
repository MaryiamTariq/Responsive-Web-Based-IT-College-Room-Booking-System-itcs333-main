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
