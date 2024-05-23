<?php
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

// Register user
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = sanitize_input($_POST["username"]);
  $email = sanitize_input($_POST["email"]);
  $password = password_hash(sanitize_input($_POST["password"]), PASSWORD_DEFAULT);
  $role = "user"; // Assuming default role is "user"
  $registration_date = date("Y-m-d"); // Current date

  $sql = "INSERT INTO users (username, email, password, role, registration_date) VALUES (?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssss", $username, $email, $password, $role, $registration_date);

  if ($stmt->execute()) {
    echo "User registered successfully.";
  } else {
    echo "Error: " . $stmt->error;
  }

  // Close statement
  $stmt->close();
}

// Close connection
$conn->close();
?>
