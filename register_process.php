<?php
session_start();
include_once './includes_ db.php';

try {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $fName = htmlspecialchars(trim($_POST['fName']));
        $lName = htmlspecialchars(trim($_POST['lName']));
        $email = htmlspecialchars(trim($_POST['email']));
        $password = $_POST['password'];

        if (empty($fName) || empty($lName) || empty($email) || empty($password)) {
            $_SESSION['error'] = "All fields are required.";
            header("Location: register.php");
            exit();
        }

        $fullName = trim($fName . ' ' . $lName);

        if (!preg_match("/^[a-zA-Z]+@uob\.edu\.bh$/", $email)) {
            $_SESSION['error'] = "Please use a valid UOB email address (example: wahmed@uob.edu.bh)";
            header("Location: register.php");
            exit();
        }

        // Check if email already exists
        $stmt = $pdo->prepare("SELECT email FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = "Email already registered.";
            header("Location: register.php");
            exit();
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert with default role 'user'
        $sql = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, 'user')";
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
