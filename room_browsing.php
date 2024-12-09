<?php
session_start();
require_once './includes_ db.php'; 

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$capacity = isset($_GET['capacity']) ? (int)$_GET['capacity'] : 0;

$query = "SELECT id, room_name, capacity FROM rooms WHERE 1=1";
$params = [];

if (!empty($search)) {
    $query .= " AND room_name LIKE :room_name";
    $params[':room_name'] = "%".$search."%";
}

if ($capacity > 0) {
    $query .= " AND capacity >= :capacity";
    $params[':capacity'] = $capacity;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Browsing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <style>
        .material-icons-outlined { 
            vertical-align:middle;
            line-height:1px;
            color:#af8b58;
        }
        .header {
            height:70px;
            background-color: #ffffff;
            display:flex;
            align-items:center;
            justify-content: space-between;
            padding: 0 30px 0 30px;
            box-shadow: 0 6px 7px -4px rgba(0,0,0,0.2);
        }
        body {
            font-family: 'Arial', sans-serif;
            background: #f5f5f5;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin-top: 50px;
        }
        h1 {
            margin-bottom: 30px;
            text-align: center;  
            margin-top: 50px;
        }
        .card {
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border: none;
            border-radius: 8px;
            overflow: hidden;
        }
        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .btn-custom {
            background-color: #af8b58;
            color: white;
            border-radius: 5px;
        }
        .btn-custom:hover {
            background-color: #45a049;
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        .alert {
            margin-top: 20px;
            background-color: #ffcc00;
            color: #333;
            border-radius: 5px;
        }
        @media (max-width: 600px) {
            h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
<nav class="header">
    <a href="homepageprj.php"><span class="material-icons-outlined">home</span></a>
    <div class="header-left">
        <span class="material-icons-outlined">menu_book</span>
        <span class="material-icons-outlined">bar_chart</span>
        <span class="material-icons-outlined">account_circle</span>
    </div>
    <div class="header-right">
        <a href="logout.php"><span class="material-icons-outlined">logout</span></a>
    </div>
</nav>
<div class="container">
    <h1>Available Rooms</h1>

    <form method="GET" class="search-form mb-4">
        <div class="row">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Search by room name" value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-md-4">
                <input type="number" name="capacity" class="form-control" placeholder="Min. Capacity" value="<?= htmlspecialchars($capacity) ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-custom w-100">Search</button>
            </div>
        </div>
    </form>

    <?php if (count($rooms) > 0): ?>
        <div class="row">
            <?php foreach ($rooms as $room): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <?php 
                        // Dummy image selection based on room_name
                        $imagePath = 'default_room.jpg';
                        switch ($room['room_name']) {
                            case 'Room A':
                                $imagePath = 'yy.jpg';
                                break;
                            case 'Room B':
                                $imagePath = 'oo.jpg';
                                break;
                            case 'Room C':
                                $imagePath = 'vv.jpg';
                                break;
                            case 'Room D':
                                $imagePath = 'TT.jpg';
                                break;
                            case 'Room E':
                                $imagePath = 'LL.jpg';
                                break;
                            case 'Room H':
                                $imagePath = 'UU.jpg';
                                break;
                        }
                        ?>
                        <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($room['room_name']) ?>" class="card-img-top">

                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($room['room_name']) ?></h5>
                            <p class="card-text">
                                <strong>Capacity:</strong> <?= htmlspecialchars($room['capacity']) ?><br>
                                <strong>Equipment:</strong> Projector, Whiteboard 
                            </p>
                            <a href="room_details.php?id=<?= $room['id'] ?>" class="btn btn-custom">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert">
            No rooms found based on your search criteria.
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<footer class="text-center mt-5">
    <p>&copy; 2024 Room Booking System. All rights reserved.</p>
</footer>
</body>
</html>
