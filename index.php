<?php
session_start();
$userLoggedIn = isset($_SESSION['user_id']);
$conn = new mysqli("localhost", "root", "jabez127", "calendar-final-activity");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['login'])) {
    // Establish a connection to the database
    $loginconn = new mysqli("localhost", "root", "jabez127", "calendar-final-activity");

    // Check connection
    if ($loginconn->connect_error) {
        die("Connection failed: " . $loginconn->connect_error);
    }

    // Get input from the login form
    $username = $_POST['username'];
    $name = $username;
    $password = $_POST['password'];

    // Hash the password (assuming it is stored hashed in the database)
    // $hashedPassword = hash('sha256', $password);

    // Query to check if the user exists
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // User exists, set session variable and $userLoggedIn to true
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $userLoggedIn = true;
        // Redirect to dashboard or home page
        header("Location: index.php");
        exit();
    } else {
        // Invalid credentials, show an error message or redirect to the login page
        $loginError = "Invalid username or password.";
    }

    // Close the database connection
    $loginconn->close();
}
if (isset($_POST['register'])) {
    // Establish a connection to the database
    $conn = new mysqli("localhost", "root", "jabez127", "calendar-final-activity");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get input from the registration form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash the password (for security)
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if the username is already taken
    $checkQuery = "SELECT * FROM users WHERE username = '$username'";
    $checkResult = $conn->query($checkQuery);

    if ($checkResult->num_rows > 0) {
        // Username already exists, show an error message or redirect to registration page
        echo "Username already taken. Please choose another.";
    } else {
        // Insert new user into the database
        $insertQuery = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
        if ($conn->query($insertQuery) === TRUE) {
            // Registration successful, redirect to login page or home page
            header("Location: index.php");
            exit();
        } else {
            // Registration failed, show an error message or redirect to registration page
            echo "Error: " . $conn->error;
        }
    }

    // Close the database connection
    $loginconn->close();
}
if (isset($_POST['logout'])) {
    // Logout logic: unset session variables and destroy the session
    session_unset();
    session_destroy();
    $userLoggedIn = false;

    // Redirect to the login page or any other page
    header("Location: index.php");
    exit();
}

if (isset($_POST['addEvent'])) {
    $conn = new mysqli("localhost", "root", "jabez127", "calendar-final-activity");
    // Get input from the add event form
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $location = $_POST['location'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    // Insert new event into the database
    $insertEventQuery = "INSERT INTO events (user_id, title, description, category, location, date, time) VALUES ('$_SESSION[user_id]', '$title', '$description', '$category', '$location', '$date', '$time')";
    if ($conn->query($insertEventQuery) === TRUE) {
        // Event added successfully
        header("Location: index.php");
        exit();
    } else {
        // Event addition failed, show an error message or handle accordingly
        echo "Error: " . $conn->error;
    }

    if (isset($_POST['event_id'])) {
        $eventId = $_POST['event_id'];

        // Perform a query to fetch event details based on $eventId
        $query = "SELECT * FROM events WHERE id = $eventId";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $event = $result->fetch_assoc();

            // Display the event details in HTML format
            echo "<p>Title: {$event['title']}</p>";
            echo "<p>Description: {$event['description']}</p>";
            echo "<p>Category: {$event['category']}</p>";
            echo "<p>Location: {$event['location']}</p>";
            echo "<p>Date: {$event['date']}</p>";
            echo "<p>Time: {$event['time']}</p>";

            // You can also fetch and display participants' details, if needed
        } else {
            echo "Event not found";
        }
    } else {
        echo "Invalid request";
    }
}

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

<script>
    // JavaScript to display an alert if loginError is not empty
    <?php if (!empty($loginError)) : ?>
        alert("<?php echo $loginError; ?>");
    <?php endif; ?>
</script>

<body>
    <header>
        <nav>
            <ul>
                <li>
                    <img src="assets/icons8-calendar-48.png" alt="calendar-logo" class="calendar-logo">
                    <?php
                    if (isset($_SESSION['username'])) {
                        $name = $_SESSION['username'];
                        echo '<h1 class="calendar-name">' . $name . "'s Events</h1>";
                    } else {
                        echo '<h1 class="calendar-name">Calendar</h1>';
                    }
                    ?>
                </li>
                <li>
                    <?php
                    if ($userLoggedIn) {
                        // Show user profile picture if logged in
                        // echo '
                            // <img src="assets/icons8-admin-settings-male-48.png" alt="user-profile-picture" class="user-profile-picture">';
                        echo '
                            <form method="post">
                                <button type="submit" name="logout">Logout</button>
                            </form>
                        ';
                    } else {
                        // Show login form if not logged in
                        echo '
                            <form action="index.php" method="post">
                                <input type="text" name="username" placeholder="Username" required>
                                <input type="password" name="password" placeholder="Password" required>
                                <button type="submit" name="login">Login</button>
                                <button onclick="openRegistrationModal()">Register</button>
                            </form>
                            ';
                    }
                    ?>
                </li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="calendar-container">
            <div class="month-header">
                <div>
                    <img src="assets/chevron-left.svg" alt="prevoius" class="prev-month">
                    <h1 class="current-month">January</h1>
                    <img src="assets/chevron-right.svg" alt="next" class="next-month">
                </div>
                <p class="current-year"></p>
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

            </div>
        </div>
    </main>
    <aside>
        <div class="top-sidebar">
            <h2>Events</h2>
            <img src="assets/plus.svg" alt="plus-button" class="add_event" onclick="openAddEventForm()">
        </div>
        <?php
        // Assuming $conn is your database connection

        if ($userLoggedIn) {
            // Get events created by the user
            $userId = $_SESSION['user_id'];
            $createdEventsQuery = "SELECT * FROM events WHERE user_id = $userId";
            $createdEventsResult = $conn->query($createdEventsQuery);

            // Display events created by the user
            while ($createdEvent = $createdEventsResult->fetch_assoc()) {
                echo '
                <div class="user-events" data-event-id="' . $createdEvent['id'] . '" onclick="openEventDetailsModal(' . $createdEvent['id'] . ')">
                <p>' . $createdEvent['title'] . '</p>
                <p>' . $createdEvent['date'] . ' | ' . $createdEvent['time'] . '</p>
            </div>
        ';
            }

            // Get events where the user is a participant
            $participantEventsQuery = "SELECT events.* FROM events JOIN participants ON events.id = participants.event_id WHERE participants.user_id = $userId";
            $participantEventsResult = $conn->query($participantEventsQuery);

            // Display events where the user is a participant
            while ($participantEvent = $participantEventsResult->fetch_assoc()) {
                echo '
                <div class="user-events" data-event-id="' . $participantEvent['id'] . '" onclick="openEventDetailsModal(' . $participantEvent['id'] . ')">
                <p>' . $participantEvent['title'] . '</p>
                <p>' . $participantEvent['date'] . ' | ' . $participantEvent['time'] . '</p>
            </div>
        ';
            }
        } else {
            // Show message or alternative content if not logged in
            echo '
        </div>
        <div class="user-events">
            <p>Please log in to view and add events.</p>
        </div>
    ';
        }
        ?>

    </aside>
    <div id="registrationModal" class="modal">
        <h2>Register</h2>
        <form action="index.php" method="post">
            <label for="username">Username:</label>
            <input type="text" name="username" required>

            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <button type="submit" name="register">Register</button>
        </form>
        <button onclick="closeRegistrationModal()">Close</button>
    </div>

    <div id="eventDetailsModal" class="event-details-modal">
        <h2>Event Details</h2>
        <div id="eventDetailsContent">
            <!-- Event details will be displayed here -->
        </div>
        <button onclick="closeEventDetailsModal()">Close</button>
    </div>

    <?php
    if ($userLoggedIn) {
        // Show "Add" button if logged in
        echo '
            <div id="addEventForm" class="add_event_form">
            <h2>Add Event</h2>
        <form action="index.php" method="post">
            <label for="title">Title:</label>
            <input type="text" name="title" required>

            <label for="description">Description:</label>
            <textarea name="description" rows="4" required></textarea>

            <label for="category">Category:</label>
            <input type="text" name="category">

            <label for="location">Location:</label>
            <input type="text" name="location">

            <label for="date">Date:</label>
            <input type="date" name="date">

            <label for="time">Time:</label>
            <input type="time" name="time">

            <button type="submit" name="addEvent">Add Event</button>
        </form>
        <button onclick="closeAddEventForm()">Close</button>
        </div>
            ';
    }
    ?>
    <script src="script.js"></script>
</body>

</html>