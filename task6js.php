<html>
    <script> //js code for the charts
        <?php //bar chart php&js code
            $pdo = new PDO("mysql:host=127.0.0.1;port=3306;dbname=cs333project", 'root', ''); //pdo connection
      //query to find number of bookings for each room
            $result = $pdo->query(" 
                SELECT rooms.name, COUNT(room_bookings.booking_status) AS rcount 
                FROM rooms
                LEFT JOIN room_bookings ON room_bookings.room_id = rooms.id AND room_bookings.booking_status='booked'
                GROUP BY rooms.name 
                ORDER BY rooms.name");

            $roomnames = []; //array for room names
            $roomcount = []; //array for booking no.

      $roompop = $result->fetchAll(PDO::FETCH_ASSOC); //fetch query results and put them int the above arrays
      foreach ($roompop as $row) {
          $roomnames[] = $row['name'];
          $roomcount[] = $row['rcount'];
      }

      $roomnamesjson = json_encode($roomnames); //encode room names as json for js
      $roomcountjson = json_encode($roomcount); //encode no. of bookings as json for js
      ?>

      var roomNamesJS = <?php echo $roomnamesjson; ?>; //pass the encoded data
      var roomCountJS = <?php echo $roomcountjson; ?>; //pass the encoded data

      //ApexCharts bar chart code
      var barChartOptions = {
        series: [{
          data: roomCountJS
        }],
        chart: {
          type: 'bar',
          height: 350,
          toolbar: {
            show: false
          },
        },
        colors: ["#af8b58"],
        plotOptions: {
          bar: {
            distributed: true,
            borderRadius: 4,
            borderRadiusApplication: 'end',
            horizontal: false,
            columnWidth: '40%',
          }
        },
        dataLabels: {
          enabled: false
        },
        legend: {
          show: false
        },
        xaxis: {
          categories: roomNamesJS,
        },
        yaxis: {
          title: {
            text: "Number of Bookings"
          }
        },
      };

      //bar chart rendering
      var barChart = new ApexCharts(document.querySelector("#bar-chart"), barChartOptions);
      barChart.render();


        <?php //line chart php&js code
            $pdo = new PDO("mysql:host=127.0.0.1;port=3306;dbname=cs333project", 'root', ''); //pdo connection
      //query to find monthly number of bookings for each room
            $query = "SELECT rooms.name, 
               YEAR(room_bookings.starts_at) AS year, 
               MONTH(room_bookings.starts_at) AS month,
               COUNT(room_bookings.room_id) AS booking_count
               FROM rooms
               LEFT JOIN room_bookings ON rooms.id = room_bookings.room_id
               WHERE (room_bookings.starts_at BETWEEN '2024-01-01' AND '2024-12-31 23:59:59'
               AND booking_status = 'booked') OR room_bookings.starts_at IS NULL
               GROUP BY rooms.name, YEAR(room_bookings.starts_at), MONTH(room_bookings.starts_at)
               ORDER BY rooms.name, month;";
            $stmt = $pdo->query($query);

            $monthlyBookings = $stmt->fetchAll(PDO::FETCH_ASSOC); //fetch query results

            $roomNames = []; //array for room names
            $bookingData = []; //array for room data

            $months = array_fill(0, 12, 0); //array for months

            foreach ($monthlyBookings as $row) {
                $roomName = $row['name'];
                $monthIndex = $row['month'] ? $row['month'] - 1 : null;

                if (!isset($bookingData[$roomName])) { //initialize the room data array to 0
                    $bookingData[$roomName] = $months;
                }

                if ($monthIndex !== null) { //if there is no. of bookings for the month modify the initial value
                    $bookingData[$roomName][$monthIndex] = $row['booking_count'];
                }
            }

            $roomNames = array_keys($bookingData);
            $roomNamesJson = json_encode($roomNames); //encode room names as json for js
            $bookingDataJson = json_encode($bookingData); //encode bookings data as json for js
        ?>

        var roomNamesJS = <?php echo $roomNamesJson; ?>; //pass the encoded data
        var bookingDataJS = <?php echo $bookingDataJson; ?>; //pass the encoded data

    var seriesData = roomNamesJS.map(function(roomName) { //line chart series data
        return {
            name: roomName,
            data: bookingDataJS[roomName] //monthly booking data for this room
        };
    });

      //ApexCharts line chart code
    var lineChartOptions = {
    series: seriesData,
    chart: {
        height: 350,
        type: 'line',
        zoom: {
            enabled: false
        },
        toolbar: {
            show: false
        }
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        curve: 'smooth'
    },
    grid: {
        row: {
            colors: ['#f3f3f3', 'transparent'],
            opacity: 0.5
        }
    },
    xaxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
    },
    yaxis: {
        title: {
            text: "Number of Bookings"
        }
    },
};

//line chart  rendering
var lineChart = new ApexCharts(document.querySelector("#line-chart"), lineChartOptions);
lineChart.render();
    </script>
</html>