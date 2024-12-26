<?php
// Database credentials
$servername = "localhost:3306";
$username = "root";
$password = "admin";  // Change if your MySQL root password is different
$dbname = "webApp";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user inputs from the login form
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Prepare SQL query
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die("Error preparing SQL query: " . $conn->error);
    }

    // Bind parameters and execute the query
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($pass, $row['password'])) {
            // Redirect to success page if login is successful
            header("Location: success.php");
            exit();
        } else {
            // Redirect to error page if password is incorrect
            header("Location: error.php");
            exit();
        }
    } else {
        // Redirect to error page if username is not found
        header("Location: error.php");
        exit();
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
