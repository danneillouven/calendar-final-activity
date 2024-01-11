<?php
// Establish a connection to the database
$conn = new mysqli("localhost", "root", "jabez127", "calendar-final-activity");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the event ID from the AJAX request
$eventId = $_GET['eventId'];

// Query to fetch event details based on $eventId
$query = "SELECT events.*, users.username AS creator_username FROM events INNER JOIN users ON events.user_id = users.id WHERE events.id = $eventId";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    // Fetch event details and creator information as an associative array
    $eventData = $result->fetch_assoc();

    // Encode the data as JSON and send it back to the client
    echo json_encode($eventData);
} else {
    // Event not found
    echo "Event not found";
}

// Close the database connection
$conn->close();
?>