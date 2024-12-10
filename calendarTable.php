<?php
// Codes for CALENDER
function build_calendar($month, $year) {
    $mysqli = new mysqli('localhost', 'root', '', 'ihoms_inventory');
    $stmt = $mysqli->prepare("SELECT * FROM neurology_bookings WHERE MONTH(DATE) = ? AND YEAR(DATE) = ?");
    $stmt->bind_param('ss', $month, $year);
    $bookings = array();
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $bookings[] = $row['DATE'];
            }
            $stmt->close();
        }
    }

    $daysOfWeek = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
    $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
    $numberDays = date('t', $firstDayOfMonth);
    $dateComponents = getdate($firstDayOfMonth);
    $monthName = $dateComponents['month'];
    $dayOfWeek = $dateComponents['wday'];
    $datetoday = date('Y-m-d');

    // Get the last day of the previous month
    $prevMonth = $month - 1 > 0 ? $month - 1 : 12;
    $prevYear = $month - 1 > 0 ? $year : $year - 1;
    $prevMonthDays = date('t', mktime(0, 0, 0, $prevMonth, 1, $prevYear));
    
    $calendar = "<table class='table table-bordered'>";
    $calendar .= "<center><h2>$monthName $year</h2>";
    $calendar .= "<a class='btn btn-xs btn-success' href='?month=" . date('m', mktime(0, 0, 0, $month - 1, 1, $year)) . "&year=" . date('Y', mktime(0, 0, 0, $month - 1, 1, $year)) . "'>Previous Month</a> ";
    $calendar .= "<a class='btn btn-xs btn-danger' href='?month=" . date('m') . "&year=" . date('Y') . "'>Current Month</a> ";
    $calendar .= "<a class='btn btn-xs btn-primary' href='?month=" . date('m', mktime(0, 0, 0, $month + 1, 1, $year)) . "&year=" . date('Y', mktime(0, 0, 0, $month + 1, 1, $year)) . "'>Next Month</a></center><br>";

    $calendar .= "<tr>";
    foreach ($daysOfWeek as $day) {
        $calendar .= "<th class='header'>$day</th>";
    }

    $calendar .= "</tr><tr>";
    
    if ($dayOfWeek > 0) {
        // Display the last few days of the previous month with the "dimmed" style
        for ($k = $dayOfWeek - 1; $k >= 0; $k--) {
            $prevDay = $prevMonthDays - $k;
            $calendar .= "<td class='dimmed'>$prevDay</td>";
        }
    }

    $currentDay = 1;
    $month = str_pad($month, 2, "0", STR_PAD_LEFT);

    while ($currentDay <= $numberDays) {
        if ($dayOfWeek == 7) {
            $dayOfWeek = 0;
            $calendar .= "</tr><tr>";
        }

        $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
        $date = "$year-$month-$currentDayRel";
        $today = $date == date('Y-m-d') ? "today" : "";

        if ($date < date('Y-m-d')) {
            $calendar .= "<td><h4>$currentDay</h4> <button class='btn btn-danger btn-xs' disabled>N/A</button>";
        } elseif (in_array($date, $bookings)) {
            $calendar .= "<td class='$today'><h4>$currentDay</h4> <button class='btn btn-danger btn-xs'><span class='glyphicon glyphicon-lock'></span> Already Booked</button>";
        } else {
            $calendar .= "<td class='$today'><h4>$currentDay</h4> <a href='book.php?date=" . $date . "' class='btn btn-success btn-xs'><span class='glyphicon glyphicon-ok'></span> Book Now</a>";
        }

        $calendar .= "</td>";
        $currentDay++;
        $dayOfWeek++;
    }

    if ($dayOfWeek != 7) {
        $remainingDays = 7 - $dayOfWeek;
        for ($i = 1; $i <= $remainingDays; $i++) {
            $calendar .= "<td class='dimmed'>$i</td>";
        }
    }

    $calendar .= "</tr>";
    $calendar .= "</table>";
    echo $calendar;
}
?>

<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="css/style.css"> -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css"> -->
    <!-- <style>
       @media only screen and (max-width: 760px),
        (min-device-width: 802px) and (max-device-width: 1020px) {

            /* Force table to not be like tables anymore */
            table, thead, tbody, th, td, tr {
                display: block;

            }
            
            .empty {
                display: none;
            }

            /* Hide table headers (but not display: none;, for accessibility) */
            th {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }

            tr {
                border: 1px solid #ccc;
            }

            td {
                /* Behave  like a "row" */
                border: none;
                border-bottom: 1px solid #eee;
                position: relative;
                padding-left: 50%;
            }



            /*
		Label the data
		*/
            td:nth-of-type(1):before {
                content: "Sunday";
            }
            td:nth-of-type(2):before {
                content: "Monday";
            }
            td:nth-of-type(3):before {
                content: "Tuesday";
            }
            td:nth-of-type(4):before {
                content: "Wednesday";
            }
            td:nth-of-type(5):before {
                content: "Thursday";
            }
            td:nth-of-type(6):before {
                content: "Friday";
            }
            td:nth-of-type(7):before {
                content: "Saturday";
            }


        }

        /* Smartphones (portrait and landscape) ----------- */

        @media only screen and (min-device-width: 320px) and (max-device-width: 480px) {
            body {
                padding: 0;
                margin: 0;
            }
        }

        /* iPads (portrait and landscape) ----------- */

        @media only screen and (min-device-width: 802px) and (max-device-width: 1020px) {
            body {
                width: 495px;
            }
        }

        @media (min-width:641px) {
            table {
                table-layout: fixed;
            }
            td {
                width: 33%;
            }
        }
        
        .row{
            margin-top: 20px;
        }
        
        .today{
            background:#eee;
        }

        .dimmed {
            color: #a9a9a9; /* Gray color for dim effect */
            background-color: #f9f9f9; /* Light background */
        }
    </style> -->
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!-- <div class="alert alert-danger" style="background: #2ecc71;border:none;color:#fff"> -->
                    <!-- <h1>Calendar</h1> -->
                    <!-- </div> -->
                    <?php
                        $dateComponents = getdate();
                        if(isset($_GET['month']) && isset($_GET['year'])){
                            $month = $_GET['month'];
                            $year = $_GET['year'];
                        }else{
                            $month = $dateComponents['mon'];
                            $year = $dateComponents['year'];
                        }
                        echo build_calendar($month, $year);
                    ?>
                <!-- </div> -->
            </div>
        </div>
    </div>
</body>

<?php
?>