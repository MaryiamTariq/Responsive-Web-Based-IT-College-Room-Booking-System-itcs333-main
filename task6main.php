<!DOCTYPE html>
<html lang="en">
<head> <!-- general page information: charset, content rendering meta tag, links to the google icons & css stylesheets -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="task6cssall.css">
    <title>Reporting & Analytics Dashboard</title> <!-- adding the title for the page -->
</head>

<body>
    <div class="grid-container">
        <nav class="header"> <!-- header (navigation bar) -->
            <a href="homepageprj.php"><span class="material-icons-outlined">home</span></a>
            <div class="header-left">
                <span class="material-icons-outlined">menu_book</span>
                <span class="material-icons-outlined">bar_chart</span>
                <span class="material-icons-outlined">account_circle</span>
            </div>
            <div class="header-right">
                <span class="material-icons-outlined">logout</span>
            </div>
        </nav>

        <main class="main-container"> <!-- main section: displays total users, total bookigs, and bar&line charts -->
            <div class="main-title">
                <span style="display: inline-block; font-weight: bold; width:100%">
                    <?php date_default_timezone_set('Asia/Bahrain');
                        $currentdate=new DateTime(); //get the date then modify its format
                        $formattedcurrentdate = date_format($currentdate,"d/m/y h:i:s"); ?>
                    <h2>Welcome to the room usage dashboard.</h2>
                    <h3> Showing bookings active on <?php echo ' ' . $formattedcurrentdate . ' ...'; ?> </h3>
                </span>
            </div>

            <div class="main-cards">
                <?php
                    $pdo = new PDO("mysql:host=127.0.0.1;port=3306;dbname=cs333project", 'root', ''); //pdo connection
                    $result = $pdo->query("SELECT name , starts_at , ends_at , booking_status FROM room_bookings JOIN rooms ON room_bookings.room_id = rooms.id WHERE booking_status='booked' AND NOW() BETWEEN room_bookings.starts_at AND room_bookings.ends_at"); //query
                    if ($result->rowCount()==0) { //if statement: if there aren't any bookings display "no active bookings"
                        echo '<div class="card">
                                <div class="card-inner">
                                <p style="text-align: center;">No active bookings right now.</p>
                                </div>
                            </div>';
                    }
                    else { //if there are active bookings display the room name & booking time
                        foreach($result as $row) {
                            $bookingdate = new DateTime($row['starts_at']);
                            $enddate = new DateTime($row['ends_at']);
                            echo '<div class="card">
                                    <div class="card-inner">
                                    <p class="text-primary">' . $row['name'] . '</p>
                                    </div>
                                    <span class="text-primary font-weight-bold">Booked from ' . $bookingdate->format('d/m/Y h:i A') . ' until ' . $enddate->format('d/m/Y h:i A') . '</span>
                                  </div>';
                        }
                    }
                ?>
            </div>

            <br>
            <span style="display: inline-block; font-weight: bold; width:100%"><h3>Showing Booking Count & Popularity Trends ...</h3></span><br>
            <div class="main-cards">

<!--total users query-->

                <?php
                    $pdo = new PDO("mysql:host=127.0.0.1;port=3306;dbname=cs333project", 'root', ''); //pdo connection
                    $result = $pdo->query("SELECT COUNT(*) FROM users"); //query
                    $totalusers = $result->fetchColumn(); //fetch the result
                ?>

                <div class="card">
                    <div class="card-inner"> <!--display the total no. of users-->
                        <p class="text-primary">TOTAL REGISTERED USERS</p>
                        <span class="material-icons-outlined text-white">person</span>
                    </div>
                    <span class="text-primary font-weight-bold"><?php echo $totalusers; ?></span>
                </div>

<!--total bookings query-->

                <?php
                    $pdo = new PDO("mysql:host=127.0.0.1;port=3306;dbname=cs333project", 'root', ''); //pdo connection
                    $result = $pdo->query("SELECT COUNT(*) FROM room_bookings WHERE booking_status ='booked'"); //query
                $totalbookings = $result->fetchColumn(); //fetch the result
                ?>

                <div class="card">
                    <div class="card-inner"> <!--display the total no. of bookings-->
                        <p class="text-primary">TOTAL BOOKINGS MADE</p>
                        <span class="material-icons-outlined text-white">book</span>
                    </div>
                    <span class="text-primary font-weight-bold"> <?php echo $totalbookings; ?> </span>
                </div>
            </div>

            <div class="charts"> <!-- adding the line/bar charts-->
                <div class="charts-card">
                    <p class="chart-title">Room Booking Count</p> <!--bar chart-->
                    <div id="bar-chart"></div>
                </div>
                <div class="charts-card">
                    <p class="chart-title">Bookings Trends by Month</p> <!--line chart-->
                    <div id="line-chart"></div>
                </div>
            </div>
        </main>
    </div>

<!-- ApexCharts link -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/4.0.0/apexcharts.min.js"></script>
<!-- JS content link -->
    <?php include('task6js.php'); ?>
</body>
</html>