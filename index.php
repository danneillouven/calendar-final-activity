<?php
$today = new DateTime();
$date = new DateTime();
$firstDayIndex = (int)$date->format('N') - 1;
$lastDayIndex = (int)$date->format('t');
$prevLastDay = (int)(new DateTime($date->format('Y-m-d')))->sub(new DateInterval('P1M'))->format('t');
$nextDays = 7 - (($firstDayIndex + $lastDayIndex) % 7);

$months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

$currentMonth = $months[(int)$date->format('m') - 1];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="assets/icons8-calendar-48.png" type="image/x-icon" />
    <link rel="stylesheet" href="styles.css" />
    <title>Calendar</title>
</head>

<body>
    <header>
        <nav>
            <ul>
                <li>
                    <img src="assets/icons8-calendar-48.png" alt="calendar-logo" class="calendar-logo">
                    <h1 class="calendar-name">Calendar</h1>
                </li>
                <li>
                    <img src="assets/icons8-admin-settings-male-48.png" alt="user-profile-picture" class="user-profile-picture">
                </li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="calendar-container">
            <div class="month-header">
                <div>
                    <img src="assets/chevron-left.svg" alt="prevoius" class="prev-month">
                    <h1 class="current-month"><?php echo $currentMonth; ?></h1>
                    <img src="assets/chevron-right.svg" alt="next" class="next-month">
                </div>
                <p class="current-year"><?php echo $date->format('Y'); ?></p>
            </div>
            <div class="weekday">
                <h2>S</h2>
            </div>
            <div class="weekday">
                <h2>M</h2>
            </div>
            <div class="weekday">
                <h2>T</h2>
            </div>
            <div class="weekday">
                <h2>W</h2>
            </div>
            <div class="weekday">
                <h2>T</h2>
            </div>
            <div class="weekday">
                <h2>F</h2>
            </div>
            <div class="weekday">
                <h2>S</h2>
            </div>
            <div class="date">
                <?php
                for ($i = $firstDayIndex; $i > 0; $i--) {
                    echo '<div>' . ($prevLastDay - $i + 1) . '</div>';
                }

                for ($i = 1; $i <= $lastDayIndex; $i++) {
                    if ($i === $today->format('j') && (int)$date->format('m') === (int)$today->format('m')) {
                        echo '<div class="today">' . $i . '</div>';
                    } else {
                        echo '<div>' . $i . '</div>';
                    }
                }

                for ($i = 1; $i <= $nextDays; $i++) {
                    echo '<div>' . $i . '</div>';
                }
                ?>
            </div>
        </div>
    </main>
    <aside>
        <div class="top-sidebar">
            <h2>Events</h2>
            <img src="assets/plus.svg" alt="plus-button">
        </div>
        <div class="user-events">
            <p>Meeting</p>
            <p>January 1, 2024 | 12:30PM - 1:00PM</p>

        </div>
    </aside>
    <script src="script.js"></script>
</body>

</html>