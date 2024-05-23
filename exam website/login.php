<?php
session_start(); // Start session

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "online_debt_managment_course_platform"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize user inputs
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Authenticate user login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitize_input($_POST["username"]);
    $password = sanitize_input($_POST["password"]);

    $sql = "SELECT * FROM users WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            // Password is correct, redirect to home page
            $_SESSION["username"] = $username;
            header("Location: home.html");
            exit();
        } else {
            // Password is incorrect
            echo '<script>alert("Invalid username or password"); window.location.href = "login.html";</script>';
        }
    } else {
        // Username not found
        echo '<script>alert("Invalid username or password"); window.location.href = "login.html";</script>';
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
