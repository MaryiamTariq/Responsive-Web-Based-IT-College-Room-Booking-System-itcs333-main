<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register New Account</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Register</h2>
    <hr>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <form action="register_process.php" method="POST">
        <div class="mb-3">
            <label for="fName" class="form-label">First Name</label>
            <input type="text" name="fName" id="fName" required class="form-control">
        </div>
        <div class="mb-3">
            <label for="lName" class="form-label">Last Name</label>
            <input type="text" name="lName" id="lName" required class="form-control">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">UoB Email</label>
            <input type="email" name="email" id="email" placeholder="example@uob.edu.bh" required class="form-control">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" required class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Register</button>
    </form>

    <p class="mt-3">Already have an account? <a href="login.php">Login here</a></p>
</div>
</body>
</html>
