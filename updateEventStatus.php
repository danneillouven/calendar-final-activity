<?php
session_start();
$conn = new mysqli("localhost", "root", "jabez127", "calendar-final-activity");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['eventId']) && isset($_GET['status'])) {
    $eventId = $_GET['eventId'];
    $status = $_GET['status'];
    
    // Perform the necessary update in the database
    $updateQuery = "UPDATE participants SET status = '$status' WHERE event_id = $eventId AND user_id = $_SESSION[user_id]";

    if ($conn->query($updateQuery) === TRUE) {
        echo "RSVP update successful!";
    } else {
        echo "Error updating RSVP: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}


// Close the database connection
$conn->close();
?>
