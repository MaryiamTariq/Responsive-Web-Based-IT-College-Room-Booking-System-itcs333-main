<?php
session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to access this page.";
    header("Location: index.php");
    exit();
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'User';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- style & google icons stylsheet links -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome!</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="homepagestyles.css">
</head>
<body>
    <div class="grid-container">
        <nav class="header">
            <!--header (navbar)-->
            <a href="homepageprj.php"><span class="material-icons-outlined">home</span></a>
            <div class="header-left">
                <!-- تصفح الغرف -->
                <a href="room_browsing.php"><span class="material-icons-outlined">menu_book</span></a>
                <!-- الملف الشخصي -->
                <a href="profile.php"><span class="material-icons-outlined">account_circle</span></a>
                <!-- التعليقات (اختياري، حسب وجود الصفحة)
                     يمكنك إضافة صفحة خاصة بالآراء والتعليقات -->
                <a href="comment_page.php"><span class="material-icons-outlined">feedback</span></a>
            </div>
            <div class="header-right">
                <!-- تسجيل الخروج -->
                <a href="logout.php"><span class="material-icons-outlined">logout</span></a>
            </div>
        </nav>

        <div class="main-container">
            <!--main content: buttons and welcome message-->
            <div class="hello text-center">
               <img src="UOB-Logo-Transparant.png" alt="UOB Logo" width="222" height="257">
               <h1>Welcome, <?php echo htmlspecialchars($username); ?>, to the UOB Room Booking System!</h1>
               
               <!-- زر تصفح الغرف -->
               <a href="room_browsing.php"><button>Browse Rooms</button></a>
               
               <!-- زر عرض الحجوزات الخاصة بالمستخدم -->
               <a href="userbookingsdash.php"><button>View Bookings</button></a>
               
               <!-- زر لحجز غرفة جديدة -->
               <a href="booking.php"><button>Book a Room</button></a>
               
               <!-- زر تعديل الملف الشخصي -->
               <a href="profile.php"><button>View/Edit Profile</button></a>
               
               <!-- زر للتعليقات (اختياري) -->
               <a href="comment_page.php"><button>Leave a Comment</button></a>
            </div>
        </div>
    </div>
</body>
</html>
