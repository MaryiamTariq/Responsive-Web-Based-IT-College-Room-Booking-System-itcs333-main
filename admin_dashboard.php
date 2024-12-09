<?php
session_start();

// التحقق من صلاحية الدخول كأدمن
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "You must be an admin to access this page.";
    header("Location: index.php");
    exit();
}

// يمكن إضافة أي استدعاء لملف الاتصال بقاعدة البيانات إذا لزم الأمر 
include_once './includes_ db.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | IT College Room Booking</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">IT College Room Booking - Admin</a>
            <div class="d-flex">
                <span class="navbar-text me-3">
                    <?php echo "Welcome, " . htmlspecialchars($_SESSION['username']); ?>
                </span>
                <a href="logout.php" class="btn btn-outline-light">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="mb-4">Admin Dashboard</h1>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <div class="row g-4">
            <!-- إدارة الغرف -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">Room Management</div>
                    <div class="card-body">
                        <p>Manage available rooms in the IT College, add new rooms or update existing ones.</p>
                        <a href="admin_rooms.php" class="btn btn-primary">Go to Room Management</a>
                    </div>
                </div>
            </div>

            <!-- إدارة جدول الحجوزات -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-info text-white">Room Schedule Management</div>
                    <div class="card-body">
                        <p>View and manage all room bookings. Approve or reject booking requests.</p>
                        <a href="admin_room_schedule.php" class="btn btn-info">View Bookings</a>
                    </div>
                </div>
            </div>
            
            <!-- قسم للتقارير و الاحصائيات (مثال) -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-success text-white">Reports & Analytics</div>
                    <div class="card-body">
                        <p>View usage statistics, popular rooms, and other analytics related to the booking system.</p>
                        <a href="admin_reports.php" class="btn btn-success">View Reports</a>
                    </div>
                </div>
            </div>
            
            <!-- قسم لإدارة التعليقات (مثال) -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-warning text-white">Comments Management</div>
                    <div class="card-body">
                        <p>Manage user feedback on rooms. Respond to their comments and queries.</p>
                        <a href="admin_comments.php" class="btn btn-warning">Manage Comments</a>
                    </div>
                </div>
            </div>
            
            <!-- يمكن إضافة المزيد من الأقسام عند الحاجة -->
        </div>
    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
