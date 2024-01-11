<?php
if (isset($_POST['login'])) {
    // Establish a connection to the database
    $conn = new mysqli("localhost", "root", "jabez127", "calendar-final-activity");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get input from the login form
    $username = $_POST['username'];
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
        $userLoggedIn = true;

        // Redirect to dashboard or home page
        header("Location: index.php");
        exit();
    } else {
        // Invalid credentials, show an error message or redirect to the login page
        $loginError = "Invalid username or password.";
    }

    // Close the database connection
    $conn->close();
} else
?>
